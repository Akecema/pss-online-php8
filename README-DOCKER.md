# Running PSS_Online in Docker (PHP 8.4 + Apache + MySQL)

This app was migrated from PHP 5/7 to PHP 8. This guide covers running it
in Docker, either against the bundled MySQL container or against an
existing MySQL server you already have.

## Prerequisites

- Docker Desktop (or Docker Engine + Compose plugin) installed and running.

## Quick start (bundled MySQL container)

1. Copy the env template and adjust if needed:

   cp .env.example .env

2. If you have an existing database dump, put it in `docker/initdb/` before
   first startup (e.g. `docker/initdb/mrin_project_ipsb.sql`) - MySQL will
   import it automatically the first time the `db` container is created.

3. Start everything:

   docker compose up -d --build

4. Open the app: http://localhost:8080
   Open phpMyAdmin: http://localhost:8081  (user: root, password: from .env)

## Using your own existing MySQL server instead of the bundled one

Set in `.env`:

    DB_HOST=host.docker.internal

(this is Docker Desktop's built-in DNS name for reaching services running on
your host machine). Then either remove the `db`/`phpmyadmin` services from
`docker-compose.yml` or just ignore them - the `app` container will connect
to your own MySQL server using the DB_HOST/DB_USER/DB_PASSWORD/DB_NAME you set.

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
