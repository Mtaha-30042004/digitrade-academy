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
$mentorName = 'Muhammad Taha (Digital Marketing & Marketplace)';
$mentorNumber = '923405201175';

if ($mentorChoice === 'mentor2' || stripos($mentorChoice, 'Safiullah') !== false || stripos($selectedCourse, 'Forex') !== false) {
    $mentorName = 'Muhammad Safiullah (Forex & Trading)';
    $mentorNumber = '923327292282';
}

if ($mentorChoice === 'mentor2' || stripos($mentorChoice, 'Safiullah') !== false || stripos($mentorChoice, 'Trading') !== false) {
    $mentorName = 'Muhammad Safiullah (Forex & Trading)';
    $mentorNumber = '923327292282';
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

$pdo = Database::getConnection();

if (!$pdo) {
    sendResponse(false, 'Database connection failed. Please check MySQL settings.', null, 500);
    exit();
}

try {
    // 1. Get actual existing columns in admissions table
    $colStmt = $pdo->query("SHOW COLUMNS FROM `admissions`");
    $existingCols = $colStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

    // 2. Map available data to existing columns
    $dataToInsert = [];
    
    if (in_array('full_name', $existingCols, true)) $dataToInsert['full_name'] = $fullName;
    if (in_array('student_name', $existingCols, true)) $dataToInsert['student_name'] = $fullName;
    if (in_array('name', $existingCols, true)) $dataToInsert['name'] = $fullName;
    
    if (in_array('whatsapp', $existingCols, true)) $dataToInsert['whatsapp'] = $whatsappNumber;
    if (in_array('phone', $existingCols, true)) $dataToInsert['phone'] = $whatsappNumber;
    
    if (in_array('email', $existingCols, true)) $dataToInsert['email'] = $email;
    if (in_array('city', $existingCols, true)) $dataToInsert['city'] = $city;
    
    if (in_array('course', $existingCols, true)) $dataToInsert['course'] = $selectedCourse;
    if (in_array('course_name', $existingCols, true)) $dataToInsert['course_name'] = $selectedCourse;
    
    if (in_array('experience_level', $existingCols, true)) $dataToInsert['experience_level'] = $experienceLevel;
    if (in_array('experience', $existingCols, true)) $dataToInsert['experience'] = $experienceLevel;
    
    if (in_array('mentor_choice', $existingCols, true)) $dataToInsert['mentor_choice'] = $mentorName;
    if (in_array('assigned_mentor', $existingCols, true)) $dataToInsert['assigned_mentor'] = $mentorName;
    if (in_array('mentor', $existingCols, true)) $dataToInsert['mentor'] = $mentorName;
    if (in_array('mentor_number', $existingCols, true)) $dataToInsert['mentor_number'] = $mentorNumber;
    
    if (in_array('message', $existingCols, true)) $dataToInsert['message'] = $userMessage;
    if (in_array('notes', $existingCols, true)) $dataToInsert['notes'] = $userMessage;
    
    if (in_array('status', $existingCols, true)) $dataToInsert['status'] = 'pending';
    if (in_array('ip_address', $existingCols, true)) $dataToInsert['ip_address'] = $ipAddress;
    if (in_array('user_agent', $existingCols, true)) $dataToInsert['user_agent'] = $userAgent;

    if (empty($dataToInsert)) {
        sendResponse(false, 'No valid table columns matched in admissions table.', null, 500);
    }

    $colNames = '`' . implode('`, `', array_keys($dataToInsert)) . '`';
    $placeholders = ':' . implode(', :', array_keys($dataToInsert));

    $sql = "INSERT INTO `admissions` ({$colNames}) VALUES ({$placeholders})";
    $stmt = $pdo->prepare($sql);
    
    $params = [];
    foreach ($dataToInsert as $k => $v) {
        $params[':' . $k] = $v;
    }

    $stmt->execute($params);
    $leadId = (int)$pdo->lastInsertId();

    sendResponse(true, 'Admission application recorded successfully!', [
        'lead_id'       => $leadId,
        'mentor_name'   => $mentorName,
        'mentor_number' => $mentorNumber
    ], 201);

} catch (PDOException $e) {
    sendResponse(false, 'Database error occurred while recording admission: ' . $e->getMessage(), null, 500);
}