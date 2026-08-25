<?php
/**
 * DigiTrade Academy - Admin Session & Authentication Guard
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    // Secure session cookies
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

/**
 * Check if the user is authenticated as an admin
 */
function isAuthenticatedAdmin(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !empty($_SESSION['admin_id']);
}

/**
 * Require admin authentication for JSON APIs
 */
function requireAdminApiAuth(): void {
    if (!isAuthenticatedAdmin()) {
        require_once __DIR__ . '/response.php';
        sendResponse(false, 'Unauthorized. Please log in as an administrator.', null, 401);
    }
}

/**
 * Require admin authentication for Admin HTML/PHP pages
 */
function requireAdminPageAuth(): void {
    if (!isAuthenticatedAdmin()) {
        header('Location: login.php?error=auth_required');
        exit();
    }
}
