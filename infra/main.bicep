// Deploy PSS Online's Container App + MySQL Flexible Server into the shared
// infra created by icharm's infra/shared/main.bicep (deployed once, shared
// across all four projects).
//
// READY. The two blockers that previously gated this (git recovery, login-flow
// SQL injection) are both cleared as of 2026-08-17 — see [[Pss ipsb Version
// Control Setup]] and [[Pss ipsb Security Findings]]. Nothing left but a human
// with real Azure/Entra ID credentials running steps 1-10 of the runbook
// ([[Pss ipsb GitHub CLI Deployment Runbook]]).

param containerImage string

@secure()
param mysqlAdminPassword string

@secure()
param appDbPassword string

param containerAppsEnvName string = 'cae-ingress-paas-test'
param acrName string = 'acringresspaastest'
param keyVaultName string = 'kv-ingress-paas-test'
param mysqlSubnetId string
param mysqlPrivateDnsZoneId string

module app './modules/paas-app.bicep' = {
  name: 'pssonline-app'
  params: {
    projectName: 'pssonline'
    containerAppsEnvName: containerAppsEnvName
    acrName: acrName
    keyVaultName: keyVaultName
    mysqlSubnetId: mysqlSubnetId
    mysqlPrivateDnsZoneId: mysqlPrivateDnsZoneId
    containerImage: containerImage
    targetPort: 80
    mysqlAdminPassword: mysqlAdminPassword
    appDbPassword: appDbPassword
    databaseName: 'mrin_project_ipsb' // matches include/config.php's DB_NAME default
  }
}

output appUrl string = app.outputs.containerAppFqdn
