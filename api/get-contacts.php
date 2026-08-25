<?php
/**
 * DigiTrade Academy - Get Contact Messages API
 * Endpoint: GET /api/get-contacts.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';
require_once __DIR__ . '/config/auth.php';

requireAdminApiAuth();

$pdo = Database::getConnection();

if (!$pdo) {
    sendResponse(true, 'Database offline (Sample Data)', [
        'messages' => [
            [
                'id'         => 1,
                'name'       => 'Ali Raza',
                'email'      => 'aliraza@test.com',
                'phone'      => '+92 300 7654321',
                'subject'    => 'Fee Discount Inquiry',
                'message'    => 'Can I pay in two installments for the Forex Trading Mastery course?',
                'status'     => 'unread',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
            ]
        ]
    ]);
}

try {
    $stmt = $pdo->query("SELECT * FROM `contact_messages` ORDER BY created_at DESC LIMIT 100");
    $messages = $stmt->fetchAll();

    sendResponse(true, 'Contact messages fetched successfully', [
        'messages' => $messages
    ]);
} catch (PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage(), null, 500);
}
