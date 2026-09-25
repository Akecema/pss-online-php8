<?php

/**
 * ckies-aut_frst.php
 * Part of: Core / entry-point script
 * Filename suggests: ckies aut frst
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, failed_login.
 * Includes: rst-mail.php, index_admin.php, backjob_clean.php, index_super.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
// --- "Remember me" auto-login: REMOVED ---
// This used to sign a visitor in when the ID_my_site / Key_my_site cookies held a username and that
// account's stored password hash. The app stopped issuing those cookies long ago (see
// ckies-aut_scd.php), so the only thing left that could send them was an attacker: with a username
// and a hash (legacy MD5 accounts still exist) the two cookies were enough to log in without the
// password. Any leftover cookies are simply expired.
if (isset($_COOKIE['ID_my_site']) || isset($_COOKIE['Key_my_site'])) {
    $expired = ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax'];
    setcookie('ID_my_site', '', $expired);
    setcookie('Key_my_site', '', $expired);
}?>
