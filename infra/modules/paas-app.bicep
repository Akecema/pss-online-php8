// ============================================================================
// modules/paas-app.bicep — one PHP app (Container App) + its own Azure
// Database for MySQL Flexible Server, deployed into the SHARED Container
// Apps Environment / ACR / VNet / Key Vault created by ../shared/main.bicep.
//
// IDENTICAL COPY exists in all three project repos (icharm, Appraisal,
// PSS_Online) — that's deliberate, not drift: each project's GitHub Actions
// pipeline only has access to its own repo, so this module has to travel
// with each one. If you change this file, copy the change to the other two
// repos too (or promote it to a private Bicep registry module later once
// there's a 4th project and copy-drift risk becomes worth solving properly).
//
// Rationale for shared compute / isolated data: a bug or leaked credential
// in one project's DB should never reach another project's data, but
// compute (Container Apps Environment, ACR) is cheap and safe to share,
// especially at min-replicas 0. This module is also what closes the
// "app connects as root everywhere" gap flagged in the i-CHARM Azure PaaS
// Deployment Plan — the app's DB_USER is always the least-privilege
// appDbUser below, never the server admin login.
// ============================================================================

@description('Short project slug: icharm | appraisal | pssonline')
param projectName string

@description('Azure region — must match the shared infra region')
param location string = resourceGroup().location

@description('Name of the existing shared Container Apps Environment')
param containerAppsEnvName string

@description('Name of the existing shared Azure Container Registry')
param acrName string

@description('Existing delegated subnet resource ID for MySQL Flexible Server')
param mysqlSubnetId string

@description('Existing private DNS zone resource ID for MySQL Flexible Server private link')
param mysqlPrivateDnsZoneId string

@description('Existing shared Key Vault name')
param keyVaultName string

@description('Full image reference, e.g. myacr.azurecr.io/icharm:sha-abc1234 — supplied by GitHub Actions after build & push')
param containerImage string

@description('Internal port the app listens on inside the container (Apache = 80 for all three projects)')
param targetPort int = 80

@description('MySQL admin (server-level) login — used once at provisioning time to create the least-privilege app user, never used by the app itself afterward')
param mysqlAdminLogin string = '${projectName}admin'

@secure()
param mysqlAdminPassword string

@description('Least-privilege application DB user the app actually connects as')
param appDbUser string = '${projectName}_app'

@secure()
param appDbPassword string

@description('Database name to create on the Flexible Server')
param databaseName string

@description('Testing-tier default — bump before this goes past shared testing')
param mysqlSkuName string = 'Standard_B1ms'
param mysqlTier string = 'Burstable'
param mysqlVersion string = '8.0.21'

@description('App-specific non-secret env vars beyond DB_* — e.g. SSO_ASSET_URL for icharm')
param extraEnvVars array = []

@description('App-specific Key-Vault-backed secrets beyond db-password, as {name, keyVaultUrl} — identity is filled in automatically')
param extraSecretNames array = []

param minReplicas int = 0
param maxReplicas int = 2

resource containerAppsEnv 'Microsoft.App/managedEnvironments@2024-03-01' existing = {
  name: containerAppsEnvName
}

resource acr 'Microsoft.ContainerRegistry/registries@2023-07-01' existing = {
  name: acrName
}

resource keyVault 'Microsoft.KeyVault/vaults@2023-07-01' existing = {
  name: keyVaultName
}

resource uami 'Microsoft.ManagedIdentity/userAssignedIdentities@2023-01-31' = {
  name: 'id-${projectName}-app'
  location: location
}

resource acrPullRole 'Microsoft.Authorization/roleAssignments@2022-04-01' = {
  name: guid(acr.id, uami.id, 'AcrPull')
  scope: acr
  properties: {
    principalId: uami.properties.principalId
    principalType: 'ServicePrincipal'
    roleDefinitionId: subscriptionResourceId('Microsoft.Authorization/roleDefinitions', '7f951dda-4ed3-4680-a7ca-43fe172d538d') // AcrPull
  }
}

resource kvSecretsUserRole 'Microsoft.Authorization/roleAssignments@2022-04-01' = {
  name: guid(keyVault.id, uami.id, 'KeyVaultSecretsUser')
  scope: keyVault
  properties: {
    principalId: uami.properties.principalId
    principalType: 'ServicePrincipal'
    roleDefinitionId: subscriptionResourceId('Microsoft.Authorization/roleDefinitions', '4633458b-17de-408a-b874-0445c86b69e6') // Key Vault Secrets User
  }
}

resource dbPasswordSecret 'Microsoft.KeyVault/vaults/secrets@2023-07-01' = {
  parent: keyVault
  name: '${projectName}-db-password'
  properties: {
    value: appDbPassword
  }
}

resource mysql 'Microsoft.DBforMySQL/flexibleServers@2023-06-30' = {
  name: 'mysql-${projectName}-test'
  location: location
  sku: {
    name: mysqlSkuName
    tier: mysqlTier
  }
  properties: {
    version: mysqlVersion
    administratorLogin: mysqlAdminLogin
    administratorLoginPassword: mysqlAdminPassword
    network: {
      delegatedSubnetResourceId: mysqlSubnetId
      privateDnsZoneResourceId: mysqlPrivateDnsZoneId
      publicNetworkAccess: 'Disabled'
    }
    highAvailability: {
      mode: 'Disabled' // testing tier — revisit before promoting past shared testing
    }
    storage: {
      storageSizeGB: 20
      autoGrow: 'Enabled'
    }
    backup: {
      backupRetentionDays: 7
      geoRedundantBackup: 'Disabled'
    }
  }
}

resource mysqlRequireTls 'Microsoft.DBforMySQL/flexibleServers/configurations@2023-06-30' = {
  parent: mysql
  name: 'require_secure_transport'
  properties: {
    value: 'ON'
    source: 'user-override'
  }
}

resource mysqlDatabase 'Microsoft.DBforMySQL/flexibleServers/databases@2023-06-30' = {
  parent: mysql
  name: databaseName
  properties: {
    charset: 'utf8mb4'
    collation: 'utf8mb4_general_ci'
  }
}

// Least-privilege app DB user — Bicep has no native "CREATE USER" resource,
// so this runs via a one-shot Azure CLI deployment script. Idempotent
// (CREATE USER IF NOT EXISTS + re-GRANT is harmless to repeat on redeploy).
// This is what keeps the app off the admin/root login day to day.
//
// CAVEAT — NOT YET VALIDATED AGAINST A REAL SUBSCRIPTION: deploymentScripts
// containers run in a Microsoft-managed network by default, and mysql above
// has publicNetworkAccess: Disabled (private endpoint only). Those two facts
// may conflict — this script might not be able to reach the DB host at all
// as written. Before relying on this: either (a) confirm deploymentScripts'
// VNet integration (subnet delegation via `properties.storageAccountSettings`
// / container group networking) actually reaches a private-endpoint MySQL
// server in your subscription, or (b) fall back to running the CREATE
// USER/GRANT by hand once via Cloud Shell (VNet-peered) or `az mysql
// flexible-server execute` immediately after first deploy, then remove this
// resource. Flagged here rather than asserted as working — verify with
// `az deployment group create --what-if` before the first real run.
resource createAppDbUser 'Microsoft.Resources/deploymentScripts@2023-08-01' = {
  name: 'create-${projectName}-app-db-user'
  location: location
  kind: 'AzureCLI'
  properties: {
    azCliVersion: '2.60.0'
    retentionInterval: 'PT1H'
    timeout: 'PT10M'
    environmentVariables: [
      { name: 'MYSQL_HOST', value: '${mysql.name}.mysql.database.azure.com' }
      { name: 'MYSQL_ADMIN', value: mysqlAdminLogin }
      { name: 'MYSQL_ADMIN_PW', secureValue: mysqlAdminPassword }
      { name: 'APP_USER', value: appDbUser }
      { name: 'APP_PW', secureValue: appDbPassword }
      { name: 'DB_NAME', value: databaseName }
    ]
    scriptContent: '''
      apt-get update && apt-get install -y default-mysql-client >/dev/null 2>&1
      mysql -h "$MYSQL_HOST" -u "$MYSQL_ADMIN" -p"$MYSQL_ADMIN_PW" --ssl-mode=REQUIRED <<SQL
        CREATE USER IF NOT EXISTS '$APP_USER'@'%' IDENTIFIED BY '$APP_PW';
        ALTER USER '$APP_USER'@'%' IDENTIFIED BY '$APP_PW';
        GRANT SELECT, INSERT, UPDATE, DELETE ON \`$DB_NAME\`.* TO '$APP_USER'@'%';
        FLUSH PRIVILEGES;
      SQL
    '''
  }
  dependsOn: [
    mysqlDatabase
  ]
}

var baseEnv = [
  { name: 'DB_HOST', value: '${mysql.name}.mysql.database.azure.com' }
  { name: 'DB_USER', value: appDbUser }
  { name: 'DB_NAME', value: databaseName }
  { name: 'DB_PASSWORD', secretRef: 'db-password' }
  { name: 'APP_DEBUG', value: 'false' }
]

var extraSecretsFormatted = [for s in extraSecretNames: {
  name: s.name
  keyVaultUrl: s.keyVaultUrl
  identity: uami.id
}]

resource containerApp 'Microsoft.App/containerApps@2024-03-01' = {
  name: 'ca-${projectName}-test'
  location: location
  identity: {
    type: 'UserAssigned'
    userAssignedIdentities: {
      '${uami.id}': {}
    }
  }
  properties: {
    managedEnvironmentId: containerAppsEnv.id
    configuration: {
      ingress: {
        external: true
        targetPort: targetPort
        transport: 'auto'
      }
      registries: [
        {
          server: acr.properties.loginServer
          identity: uami.id
        }
      ]
      secrets: concat([
        {
          name: 'db-password'
          keyVaultUrl: dbPasswordSecret.properties.secretUri
          identity: uami.id
        }
      ], extraSecretsFormatted)
    }
    template: {
      containers: [
        {
          name: projectName
          image: containerImage
          env: concat(baseEnv, extraEnvVars)
          resources: {
            cpu: json('0.5')
            memory: '1Gi'
          }
        }
      ]
      scale: {
        minReplicas: minReplicas
        maxReplicas: maxReplicas
      }
    }
  }
  dependsOn: [
    acrPullRole
    kvSecretsUserRole
    createAppDbUser
  ]
}

output containerAppFqdn string = containerApp.properties.configuration.ingress.fqdn
output mysqlHost string = '${mysql.name}.mysql.database.azure.com'
output mysqlServerName string = mysql.name
output uamiPrincipalId string = uami.properties.principalId
