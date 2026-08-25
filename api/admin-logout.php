<?php
/**
 * DigiTrade Academy - Admin Logout API
 * Endpoint: GET/POST /api/admin-logout.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/response.php';
require_once __DIR__ . '/config/auth.php';

// Unset all session variables
$_SESSION = [];

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

if (isset($_GET['redirect'])) {
    header('Location: ../admin/login.php?logged_out=1');
    exit();
}

sendResponse(true, 'Logged out successfully.', [
    'redirect' => 'login.php'
]);
