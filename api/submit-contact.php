<?php
/**
 * DigiTrade Academy - Submit Contact Message API
 * Endpoint: POST /api/submit-contact.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';

setApiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method. POST is required.', null, 405);
}

$input = getRequestData();

$name    = sanitizeInput($input['name'] ?? $input['fullName'] ?? '');
$email   = sanitizeInput($input['email'] ?? '');
$phone   = sanitizeInput($input['phone'] ?? $input['whatsappNumber'] ?? '');
$subject = sanitizeInput($input['subject'] ?? 'General Inquiry');
$message = sanitizeInput($input['message'] ?? $input['userMessage'] ?? '');

if (empty($name)) {
    sendResponse(false, 'Name is required.', null, 422);
}
if (empty($message)) {
    sendResponse(false, 'Message is required.', null, 422);
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$pdo = Database::getConnection();

if (!$pdo) {
    sendResponse(true, 'Message received (Database offline mode).', null);
}

try {
    $sql = "INSERT INTO `contact_messages` (`name`, `email`, `phone`, `subject`, `message`, `status`, `ip_address`, `created_at`) 
            VALUES (:name, :email, :phone, :subject, :message, 'unread', :ip_address, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name'       => $name,
        ':email'      => $email,
        ':phone'      => $phone,
        ':subject'    => $subject,
        ':message'    => $message,
        ':ip_address' => $ipAddress
    ]);

    sendResponse(true, 'Your message has been sent successfully! Our team will contact you soon.', [
        'message_id' => (int)$pdo->lastInsertId()
    ], 201);

} catch (PDOException $e) {
    sendResponse(false, 'Database error occurred: ' . $e->getMessage(), null, 500);
}
