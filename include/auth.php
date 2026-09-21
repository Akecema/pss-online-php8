<?php
/**
 * include/auth.php
 * Session gate + per-folder role check (IQIMS rules 3 and 4).
 *
 * user_detail.level_id decides which folder a user belongs to; ckies-aut_scd.php
 * redirects each level to exactly one folder, and no folder links into another
 * one, so a request for a different folder's page is never legitimate.
 */

declare(strict_types=1);

const ROLE_LEVEL_BY_FOLDER = [
    'admin'          => 1,
    'prod'           => 2,
    'prod_super'     => 3,
    'ppc'            => 4,
    'ppc_super'      => 5,
    'supply'         => 6,
    'supply_super'   => 7,
    'planning'       => 8,
    'planning_super' => 9,
    'qqc'            => 10,
    'qqc_super'      => 11,
    'ppc_store'      => 12,
];

/** level_id of the logged-in user, or null when not logged in / unknown. */
function current_level_id(mysqli $dbc): ?int
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['username'])) {
        return null;
    }
    $stmt = mysqli_prepare($dbc, 'SELECT level_id FROM user_detail WHERE username = ?');
    if ($stmt === false) {
        return null;
    }
    $username = (string) $_SESSION['username'];
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    return $row === null ? null : (int) $row['level_id'];
}

/**
 * Pure decision used by require_role(): 'login' (not signed in),
 * 'forbidden' (wrong role) or 'ok'.
 */
function role_gate_decision(?int $userLevel, int $requiredLevel): string
{
    if ($userLevel === null) {
        return 'login';
    }
    return $userLevel === $requiredLevel ? 'ok' : 'forbidden';
}

/**
 * Call at the top of every protected page, right after config.php.
 * Same not-logged-in behaviour as before (redirect to ../index.php); a
 * logged-in user of a different role now gets 403.
 */
function require_role(mysqli $dbc, int $requiredLevel): void
{
    switch (role_gate_decision(current_level_id($dbc), $requiredLevel)) {
        case 'login':
            header('Location: ../index.php');
            exit();
        case 'forbidden':
            http_response_code(403);
            exit('Forbidden: your account cannot access this section.');
    }
}
