<?php
// Needs a reachable MySQL (DB_HOST/DB_USER/DB_PASSWORD/DB_NAME env vars); uses a TEMPORARY table only, so no data is touched.
// Run from the project root: php tests/test_db_prepared.php
if (getenv('DB_HOST') === false || getenv('DB_PASSWORD') === false) { echo "SKIP: no DB configured
"; exit(0); }
$_SERVER['REQUEST_METHOD'] = 'GET';
require __DIR__ . '/../include/config.php';
$f = 0; function ck($n,$ok){ global $f; echo ($ok?'PASS ':'FAIL ').$n."\n"; if(!$ok) $f++; }
mysqli_query($dbc, "CREATE TEMPORARY TABLE t_prep (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50), qty INT, d DATE NULL)");
$name = "O'Brien \ \"x\""; $qty = '5';
$q = "INSERT INTO t_prep (name, qty, d) VALUES ('".db_esc($dbc,$name)."','".db_esc($dbc,$qty)."', NOW())";
$old = mysqli_query($dbc, $q); $oldId = mysqli_insert_id($dbc);
$q2 = "INSERT INTO t_prep (name, qty, d) VALUES (?,?, NOW())"; $q2_args = [$name, $qty];
$new = db_query_bind($dbc, $q2, $q2_args); $newId = mysqli_insert_id($dbc);
ck('insert returns true', $new === true && $old === true);
ck('insert_id works after prepared stmt', $newId === $oldId + 1);
$rows = mysqli_fetch_all(mysqli_query($dbc, "SELECT name, qty FROM t_prep ORDER BY id"), MYSQLI_ASSOC);
ck('stored values identical old vs prepared (quotes/backslash)', $rows[0] === $rows[1] && $rows[1]['name'] === $name);
$u = db_query_bind($dbc, "UPDATE t_prep SET qty = ? WHERE name = ?", ['9', $name]);
ck('update ok + affected_rows == 2', $u === true && mysqli_affected_rows($dbc) === 2);
$r = db_query_bind($dbc, "SELECT * FROM t_prep WHERE name LIKE ?", ['%Brien%']);
ck('SELECT returns mysqli_result with LIKE %param%', $r instanceof mysqli_result && mysqli_num_rows($r) === 2);
$r = db_query_bind($dbc, "SELECT * FROM t_prep WHERE name = ?", ["' OR '1'='1"]);
ck('injection literal matches 0 rows', mysqli_num_rows($r) === 0);
$n = db_query_bind($dbc, "INSERT INTO t_prep (name, qty) VALUES (?, ?)", [null, 1]);
ck('null bound as empty string like the old code', mysqli_fetch_row(mysqli_query($dbc, "SELECT COUNT(*) FROM t_prep WHERE name=''"))[0] == 1);
$d = db_query_bind($dbc, "DELETE FROM t_prep WHERE qty = ?", ['9']);
ck('delete ok + affected_rows == 2', $d === true && mysqli_affected_rows($dbc) === 2);
$bad = db_query_bind($dbc, "INSERT INTO t_prep (name, qty) VALUES (?, ?)", ['x']);
ck('wrong arg count -> false, no fatal', $bad === false);
$bad2 = db_query_bind($dbc, "SELECT nosuch FROM t_prep WHERE 1 = ?", ['1']);
ck('bad SQL -> false (or die(...) path works)', $bad2 === false);
$e = mysqli_fetch_row(db_query_bind($dbc, "SELECT COUNT(*) FROM t_prep", []));
ck('empty-args SELECT works', $e[0] == 1);
exit($f ? 1 : 0);
