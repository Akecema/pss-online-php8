-- material_request: index on id_scan.
--
-- Why: many report/list pages loop over scan rows and run
--   SELECT * FROM material_request WHERE status_request = 'Y' AND id_scan = ? ORDER BY id_req
-- once per row. With no index on id_scan every one of those is a full scan of ~84k rows
-- (about 38 ms each), so pages with tens of thousands of rows took minutes.
-- With the index the same lookup is a single-row ref (about 1.6 ms) and the pages run in seconds.
--
-- Safe to re-run; InnoDB builds it in place without locking writers (about 0.1 s here).
-- Applied to mrin_project_ipsb on 2026-09-25.

SET @have := (SELECT COUNT(*) FROM information_schema.STATISTICS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'material_request' AND INDEX_NAME = 'idx_id_scan');
SET @ddl := IF(@have = 0,
               'ALTER TABLE material_request ADD INDEX idx_id_scan (id_scan), ALGORITHM=INPLACE, LOCK=NONE',
               'SELECT ''idx_id_scan already exists''');
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;