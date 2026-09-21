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


$H = ['pss.example.com'];
check('origin: same host ok', origin_matches_host('https://pss.example.com', null, $H));
check('origin: other host rejected', !origin_matches_host('https://evil.com', null, $H));
check('origin: referer fallback ok', origin_matches_host(null, 'https://pss.example.com/x?y=1', $H));
check('origin: referer other host rejected', !origin_matches_host(null, 'https://evil.com/x', $H));
check('origin: none present allowed (non-browser)', origin_matches_host(null, null, $H));
check('origin: "null" origin rejected', !origin_matches_host('null', null, $H));
check('origin: lookalike host rejected', !origin_matches_host('https://pss.example.com.evil.com', null, $H));

check('upload name: plain xlsx kept', upload_safe_name('BOM list (1).xlsx', ['xls','xlsx']) === 'BOM list (1).xlsx');
check('upload name: traversal stripped', upload_safe_name('..\..\x/evil.xls', ['xls']) === 'evil.xls');
check('upload ext: lower-cased', upload_safe_ext('A.JPG', ['jpg']) === 'jpg');


$bc = password_hash('S3cret!pw', PASSWORD_DEFAULT);
check('password_matches: bcrypt ok', password_matches('S3cret!pw', $bc));
check('password_matches: bcrypt wrong', !password_matches('nope', $bc));
check('password_matches: legacy md5 ok', password_matches('S3cret!pw', md5('S3cret!pw')));
check('password_matches: legacy md5 wrong', !password_matches('nope', md5('S3cret!pw')));
check('bcrypt fits varchar(150) column', strlen($bc) <= 150);

ob_end_flush();
exit($fail === 0 ? 0 : 1);
