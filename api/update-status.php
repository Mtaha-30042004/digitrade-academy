<?php
/**
 * DigiTrade Academy - Update Lead / Contact Status API
 * Endpoint: POST /api/update-status.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';
require_once __DIR__ . '/config/auth.php';

requireAdminApiAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method. POST is required.', null, 405);
}

$input = getRequestData();
$type   = sanitizeInput($input['type'] ?? 'admission'); // 'admission' or 'contact'
$id     = (int)($input['id'] ?? 0);
$status = sanitizeInput($input['status'] ?? '');
$notes  = sanitizeInput($input['notes'] ?? '');

if ($id <= 0 || empty($status)) {
    sendResponse(false, 'Valid ID and status are required.', null, 422);
}

$pdo = Database::getConnection();

if (!$pdo) {
    sendResponse(true, "Status updated to '{$status}' (Demo Mode)");
}

try {
    if ($type === 'contact') {
        $allowedStatuses = ['unread', 'read', 'replied'];
        if (!in_array($status, $allowedStatuses, true)) {
            sendResponse(false, 'Invalid contact status value.', null, 422);
        }

        $stmt = $pdo->prepare("UPDATE `contact_messages` SET `status` = :status WHERE `id` = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
    } else {
        $allowedStatuses = ['pending', 'contacted', 'enrolled', 'cancelled'];
        if (!in_array($status, $allowedStatuses, true)) {
            sendResponse(false, 'Invalid admission status value.', null, 422);
        }

        if (!empty($notes)) {
            $stmt = $pdo->prepare("UPDATE `admissions` SET `status` = :status, `notes` = :notes WHERE `id` = :id");
            $stmt->execute([':status' => $status, ':notes' => $notes, ':id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE `admissions` SET `status` = :status WHERE `id` = :id");
            $stmt->execute([':status' => $status, ':id' => $id]);
        }
    }

    sendResponse(true, "Status successfully updated to '{$status}'.", [
        'id'     => $id,
        'status' => $status
    ]);

} catch (PDOException $e) {
    sendResponse(false, 'Database error while updating status: ' . $e->getMessage(), null, 500);
}
