<?php
/**
 * DigiTrade Academy - Submit Admission Application API
 * Endpoint: POST /api/submit-admission.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';

setApiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method. POST is required.', null, 405);
}

$input = getRequestData();

// Extract & Sanitize fields
$fullName        = sanitizeInput($input['fullName'] ?? $input['full_name'] ?? '');
$whatsappNumber  = sanitizeInput($input['whatsappNumber'] ?? $input['whatsapp'] ?? '');
$email           = sanitizeInput($input['email'] ?? '');
$city            = sanitizeInput($input['city'] ?? '');
$selectedCourse  = sanitizeInput($input['selectedCourse'] ?? $input['course'] ?? '');
$experienceLevel = sanitizeInput($input['experienceLevel'] ?? $input['experience_level'] ?? 'Beginner');
$mentorChoice    = sanitizeInput($input['mentorChoice'] ?? $input['mentor_choice'] ?? 'mentor1');
$userMessage     = sanitizeInput($input['userMessage'] ?? $input['message'] ?? '');

// Validation
if (empty($fullName)) {
    sendResponse(false, 'Full name is required.', null, 422);
}
if (empty($whatsappNumber)) {
    sendResponse(false, 'WhatsApp number is required.', null, 422);
}
if (empty($selectedCourse)) {
    sendResponse(false, 'Selected course is required.', null, 422);
}

// Mentor resolution
$mentorName = 'Muhammad Taha (Digital Marketing)';
$mentorNumber = '923405201175';

if ($mentorChoice === 'mentor2' || stripos($mentorChoice, 'Safiullah') !== false || stripos($mentorChoice, 'Trading') !== false) {
    $mentorName = 'Muhammad Safiullah (Forex & Trading)';
    $mentorNumber = '923327292282';
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

$pdo = Database::getConnection();

if (!$pdo) {
    // Database is not connected yet, but we allow form submission to succeed for frontend WhatsApp flow
    sendResponse(true, 'Application received (Database offline - please configure MySQL schema).', [
        'lead_id'       => null,
        'mentor_name'   => $mentorName,
        'mentor_number' => $mentorNumber
    ]);
}

try {
    $sql = "INSERT INTO `admissions` 
            (`full_name`, `whatsapp`, `email`, `city`, `course`, `experience_level`, `mentor_choice`, `mentor_number`, `message`, `status`, `ip_address`, `user_agent`, `created_at`) 
            VALUES 
            (:full_name, :whatsapp, :email, :city, :course, :experience_level, :mentor_choice, :mentor_number, :message, 'pending', :ip_address, :user_agent, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name'        => $fullName,
        ':whatsapp'         => $whatsappNumber,
        ':email'            => $email,
        ':city'             => $city,
        ':course'           => $selectedCourse,
        ':experience_level' => $experienceLevel,
        ':mentor_choice'    => $mentorName,
        ':mentor_number'    => $mentorNumber,
        ':message'          => $userMessage,
        ':ip_address'       => $ipAddress,
        ':user_agent'       => $userAgent
    ]);

    $leadId = (int)$pdo->lastInsertId();

    sendResponse(true, 'Admission application recorded successfully!', [
        'lead_id'       => $leadId,
        'mentor_name'   => $mentorName,
        'mentor_number' => $mentorNumber
    ], 201);

} catch (PDOException $e) {
    sendResponse(false, 'Database error occurred while recording admission: ' . $e->getMessage(), null, 500);
}
