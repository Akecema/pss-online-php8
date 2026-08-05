Drop a .sql dump here (e.g. mrin_project_ipsb.sql) and it will be imported
automatically the FIRST time the "db" container is created (MySQL only runs
files in this folder on an empty data volume - it will NOT re-import on
every restart).

If you already have a `db` volume from a previous run and want to re-import,
either remove the volume first:
    docker compose down -v
    docker compose up -d
or import manually:
    docker compose exec -T db mysql -uroot -p"$MYSQL_ROOT_PASSWORD" mrin_project_ipsb < your_dump.sql
