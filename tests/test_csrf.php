<?php
// Plain-PHP assertion harness: php tests/test_csrf.php
declare(strict_types=1);
$fail = 0;
function check(string $name, bool $ok): void { global $fail; echo ($ok ? 'PASS ' : 'FAIL ') . $name . "\n"; if (!$ok) { $fail++; } }
putenv('CSRF_SECRET=unit-test-secret');
require_once dirname(__DIR__) . '/include/csrf.php';

$tok = csrf_token_for_session('sess1');
check('token is 64 hex chars', preg_match('/^[0-9a-f]{64}$/', $tok) === 1);
check('token differs per session', $tok !== csrf_token_for_session('sess2'));
check('no session id gives no token', csrf_token_for_session('') === '');
check('matching token accepted', csrf_token_matches('sess1', $tok));
check('other session token, empty, null, array rejected',
    !csrf_token_matches('sess2', $tok) && !csrf_token_matches('sess1', '') && !csrf_token_matches('sess1', null) && !csrf_token_matches('sess1', [$tok]));

$in = csrf_inject_forms('<form method="post" action="a.php"><b>x</b></form>', $tok, 'localhost');
check('POST form gets hidden token', str_contains($in, "action=\"a.php\"><input type=hidden name=csrf_token value=$tok>"));
check('METHOD=POST (unquoted, upper) gets token', str_contains(csrf_inject_forms('<FORM METHOD=POST>', $tok), 'csrf_token'));
check('GET form untouched', !str_contains(csrf_inject_forms('<form method="get">', $tok), 'csrf_token'));
check('form without method untouched', !str_contains(csrf_inject_forms('<form action="s.php">', $tok), 'csrf_token'));
check('form posting to another site untouched',
    !str_contains(csrf_inject_forms('<form method="post" action="https://evil.example/x">', $tok, 'localhost'), 'csrf_token'));
check('form posting to same host gets token',
    str_contains(csrf_inject_forms('<form method="post" action="http://localhost/x">', $tok, 'localhost'), 'csrf_token'));
$js = '<script>var s = "<form method=post>";</script><form method=post>';
check('form text inside <script> untouched, real form gets one token',
    substr_count(csrf_inject_forms($js, $tok), 'csrf_token') === 1 && str_starts_with(csrf_inject_forms($js, $tok), '<script>var s = "<form method=post>";</script>'));
check('two forms both get tokens', substr_count(csrf_inject_forms('<form method=post></form><form method=post></form>', $tok), 'csrf_token') === 2);

$page = csrf_inject_script('<html><head><title>t</title></head><body></body></html>', $tok);
check('client script goes before </head>', str_contains($page, '</script></head>') && str_contains($page, 'X-CSRF-Token'));
check('fragment gets no script', csrf_inject_script('<div>frag</div>', $tok) === '<div>frag</div>');
check('client script has no unescaped token break', substr_count(csrf_client_script($tok), $tok) === 1);

exit($fail === 0 ? 0 : 1);