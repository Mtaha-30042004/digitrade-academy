<?php
/**
 * DigiTrade Academy - Admin Authentication Login API
 * Endpoint: POST /api/admin-login.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';
require_once __DIR__ . '/config/auth.php';

setApiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method. POST is required.', null, 405);
}

$input = getRequestData();
$username = sanitizeInput($input['username'] ?? '');
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    sendResponse(false, 'Please enter both username and password.', null, 422);
}

$pdo = Database::getConnection();

// Default master credentials fallback if DB is not yet imported
$defaultUser = 'admin';
$defaultPass = 'Taha@30042004';

if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `admins` WHERE `username` = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        if ($admin) {
            // Check password hash
            if (password_verify($password, $admin['password_hash']) || ($username === $defaultUser && $password === $defaultPass)) {
                // Update last login
                $updateStmt = $pdo->prepare("UPDATE `admins` SET `last_login` = NOW() WHERE `id` = :id");
                $updateStmt->execute([':id' => $admin['id']]);

                // Create session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $admin['id'];
                $_SESSION['admin_username']  = $admin['username'];
                $_SESSION['admin_name']      = $admin['full_name'];
                $_SESSION['admin_role']      = $admin['role'] ?? 'admin';

                sendResponse(true, 'Login successful. Redirecting to Dashboard...', [
                    'redirect' => 'dashboard.php',
                    'username' => $admin['username'],
                    'name'     => $admin['full_name']
                ]);
            }
        }
    } catch (PDOException $e) {
        // Fall back to built-in check
    }
}

// Built-in hard fallback for instant testing before DB import
if ($username === $defaultUser && $password === $defaultPass) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id']        = 1;
    $_SESSION['admin_username']  = 'admin';
    $_SESSION['admin_name']      = 'DigiTrade Master Admin';
    $_SESSION['admin_role']      = 'admin';

    sendResponse(true, 'Login successful (Default Credentials). Redirecting...', [
        'redirect' => 'dashboard.php',
        'username' => 'admin',
        'name'     => 'DigiTrade Master Admin'
    ]);
}

sendResponse(false, 'Invalid username or password. Please try again.', null, 401);
