<?php
// Plain-PHP assertion harness (IQIMS rule 14): php tests/test_helpers.php
declare(strict_types=1);
ob_start();
session_save_path(sys_get_temp_dir());
session_start();
require __DIR__ . '/../include/helpers.php';
require __DIR__ . '/../include/auth.php';

$fail = 0;
function check(string $name, bool $ok): void
{
    global $fail;
    echo ($ok ? 'PASS ' : 'FAIL ') . $name . "\n";
    if (!$ok) { $fail++; }
}

check('h escapes tags and quotes', h('<a href="x">\'&') === '&lt;a href=&quot;x&quot;&gt;&#039;&amp;');
check('h handles null/int', h(null) === '' && h(5) === '5');
check('role: logged out -> login', role_gate_decision(null, 2) === 'login');
check('role: match -> ok', role_gate_decision(2, 2) === 'ok');
check('role: mismatch -> forbidden', role_gate_decision(3, 2) === 'forbidden');
check('12 folders map to unique levels 1..12',
    count(array_unique(ROLE_LEVEL_BY_FOLDER)) === 12 && min(ROLE_LEVEL_BY_FOLDER) === 1 && max(ROLE_LEVEL_BY_FOLDER) === 12);

$_SESSION['csrf_token'] = 'abc123';
check('csrf accepts matching token', csrf_valid('abc123'));
check('csrf rejects wrong/missing/non-string', !csrf_valid('zzz') && !csrf_valid(null) && !csrf_valid(['abc123']));
check('csrf_field contains escaped token', str_contains(csrf_field(), 'value="abc123"'));

ob_end_flush();
exit($fail === 0 ? 0 : 1);
