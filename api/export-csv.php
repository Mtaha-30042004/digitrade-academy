<?php
/**
 * DigiTrade Academy - Export Admissions to Excel / CSV
 * Endpoint: GET /api/export-csv.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

requireAdminApiAuth();

$pdo = Database::getConnection();

$filename = 'DigiTrade_Admissions_Export_' . date('Y-m-d_His') . '.csv';

// Output CSV headers
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Microsoft Excel proper Urdu/Special character rendering
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Header Columns
fputcsv($output, [
    'Lead ID',
    'Full Name',
    'WhatsApp Number',
    'Email Address',
    'City',
    'Enrolled Course',
    'Experience Level',
    'Assigned Mentor',
    'Mentor Number',
    'Student Message / Goal',
    'Current Status',
    'Admin Notes',
    'Submission Date & Time'
]);

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, full_name, whatsapp, email, city, course, experience_level, mentor_choice, mentor_number, message, status, notes, created_at FROM `admissions` ORDER BY created_at DESC");
        
        while ($row = $stmt->fetch()) {
            fputcsv($output, [
                $row['id'],
                $row['full_name'],
                $row['whatsapp'],
                $row['email'] ?: 'N/A',
                $row['city'] ?: 'N/A',
                $row['course'],
                $row['experience_level'],
                $row['mentor_choice'],
                $row['mentor_number'],
                $row['message'] ?: 'None',
                strtoupper($row['status']),
                $row['notes'] ?: '',
                $row['created_at']
            ]);
        }
    } catch (PDOException $e) {
        fputcsv($output, ['Error generating export', $e->getMessage()]);
    }
} else {
    // Sample fallback rows for instant test download
    fputcsv($output, [
        '1', 'Hamza Tariq', '+92 300 1234567', 'hamza.tariq@gmail.com', 'Lahore',
        'Forex Trading Mastery', 'Intermediate', 'Muhammad Safiullah (Trading)', '923327292282',
        'SMC setups guidance', 'ENROLLED', '', date('Y-m-d H:i:s')
    ]);
}

fclose($output);
exit();
