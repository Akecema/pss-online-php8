# Running PSS_Online in Docker (PHP 8.4 + Apache + MySQL)

This app was migrated from PHP 5/7 to PHP 8. This guide covers running it
in Docker, either against the bundled MySQL container or against an
existing MySQL server you already have.

## Prerequisites

- Docker Desktop (or Docker Engine + Compose plugin) installed and running.

## Quick start (default: connects to MySQL already running on your PC)

1. Copy the env template and adjust if needed:

   cp .env.example .env

   By default `DB_HOST=host.docker.internal`, which reaches a MySQL server
   already running on your Windows machine (NOT "localhost" - inside a
   container that means the container itself, not your PC). Your local MySQL
   needs to accept non-local connections (bind address not locked to
   `127.0.0.1`) and a user grant like `'root'@'%'`.

2. Start the app (and phpMyAdmin, pointed at the same DB):

   docker compose up -d --build

3. Open the app: http://localhost:8090
   Open phpMyAdmin: http://localhost:8081  (user: root, password: from .env)

## Running alongside i-CHARM

Both this app and the i-CHARM project connect to `host.docker.internal`
(the same local MariaDB instance on your PC) with different database names
(`mrin_project_ipsb` here, `icharm` there), and use different Docker bridge
subnets (10.78.78.0/24 here, 10.77.77.0/24 there) - so both can run at the
same time with no changes needed. The only thing that would have collided is
the app port, which is why this one defaults to 8090 instead of i-CHARM's
8080.

## Using the bundled MySQL container instead

If you'd rather have a self-contained MySQL in a container (no dependency on
anything already running on your machine):

1. In `.env`, set `DB_HOST=db`.
2. If you have an existing database dump, put it in `docker/initdb/` (e.g.
   `docker/initdb/mrin_project_ipsb.sql`) - MySQL imports it automatically
   the first time the `db` container is created on an empty volume.
3. Start with the `bundled-db` profile enabled, so the `db` service actually
   starts (it's off by default):

   docker compose --profile bundled-db up -d --build

## FromPortal2 is mounted from the original location, not copied

FromPortal2 (~91,000 files of historical portal data, organized by
month/category) was left where it already was on disk rather than
duplicated into this folder - docker-compose.yml mounts it directly:

    ../FromPortal2:/var/www/html/FromPortal2

This assumes _PHP8_MIGRATED and the original FromPortal2 stay siblings
(i.e. both live directly under the original PSS_Online folder). If you move
this folder somewhere else, update that path in docker-compose.yml to point
at wherever FromPortal2 actually lives. The other 9 small upload/data
folders (BOM_upload, ToIPOS, set_upload, etc.) were copied in normally since
they're small.

## What was changed to make this container-friendly

- `include/config.php` and `PSSboard/include/config.php` now read
  DB_HOST / DB_USER / DB_PASSWORD / DB_NAME from environment variables
  (falling back to the original hardcoded values if unset), so the exact
  same code runs in Docker, on the original server, or anywhere else.
- `mysqli_report(MYSQLI_REPORT_OFF)` is set in config.php so mysqli behaves
  like the old mysql_* extension (returns false on error, checked via
  `or die(...)`) instead of throwing exceptions, which is PHP 8.1's new
  default and would otherwise crash every page that hits a DB error.

## Known limitations / things to verify yourself

- This container setup has **not been build-tested or run** in the
  environment that produced it (no Docker available there) - only the PHP
  code itself was verified (see MIGRATION_NOTES.md). Please run
  `docker compose up -d --build` and check the logs / browse the app before
  relying on this.
- The upload/data "drop folders" (BOM_upload, FromPortal, FromPortal2,
  FromPortal3, ToIPOS, set_upload, upload_master_MRIN, BOM_update,
  BOM_detail_update, BOM_detail_upload) were intentionally excluded from
  this migrated copy - they hold operational data, not code. If the app
  writes into these at runtime, mount them as volumes or recreate them
  as empty directories with the right permissions.
- PDF password-protection / AES-256 encryption (an optional TCPDF feature
  this app does not appear to call anywhere) was updated from mcrypt to
  OpenSSL but could not be runtime-tested here - flag it for a quick check
  if you ever turn that feature on.
