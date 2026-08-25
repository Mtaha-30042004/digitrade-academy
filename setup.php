<?php
/**
 * DigiTrade Academy — Railway Live Database Installer
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Railway Direct Credentials
$host = 'mysql.railway.internal';
$port = '3306';
$db   = 'railway';
$user = 'root';
$pass = 'bVeXdjJsISFeAQluwzOAzrHrPLJZpkNdJ';

$logs = [];
$success = false;

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 10
    ]);
    $logs[] = ["status" => "ok", "msg" => "Connected to Railway MySQL Database successfully!"];

    // 1. Table: admissions
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admissions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(100) NOT NULL DEFAULT '',
        `student_name` VARCHAR(100) DEFAULT '',
        `name` VARCHAR(100) DEFAULT '',
        `phone` VARCHAR(30) NOT NULL DEFAULT '',
        `whatsapp` VARCHAR(30) NOT NULL DEFAULT '',
        `email` VARCHAR(100) DEFAULT NULL,
        `city` VARCHAR(80) DEFAULT NULL,
        `course` VARCHAR(150) NOT NULL DEFAULT 'XAU/USD Gold Trading',
        `course_name` VARCHAR(150) NOT NULL DEFAULT 'XAU/USD Gold Trading',
        `mentor_choice` VARCHAR(100) DEFAULT 'Muhammad Safiullah',
        `assigned_mentor` VARCHAR(100) DEFAULT 'Muhammad Safiullah',
        `mentor` VARCHAR(100) DEFAULT 'Muhammad Safiullah',
        `mentor_number` VARCHAR(50) DEFAULT '923327292282',
        `batch` VARCHAR(100) DEFAULT 'Evening Batch (8:00 PM - 10:00 PM)',
        `batch_timing` VARCHAR(100) DEFAULT 'Evening Batch (8:00 PM - 10:00 PM)',
        `experience` VARCHAR(80) DEFAULT 'Beginner',
        `experience_level` VARCHAR(80) DEFAULT 'Beginner',
        `notes` TEXT DEFAULT NULL,
        `message` TEXT DEFAULT NULL,
        `status` ENUM('pending', 'contacted', 'enrolled', 'cancelled') DEFAULT 'pending',
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `user_agent` VARCHAR(255) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_status` (`status`),
        INDEX `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $logs[] = ["status" => "ok", "msg" => "Table 'admissions' created."];

    // 2. Table: contact_messages
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `phone` VARCHAR(30) NOT NULL,
        `whatsapp` VARCHAR(30) DEFAULT NULL,
        `email` VARCHAR(100) DEFAULT NULL,
        `course` VARCHAR(150) DEFAULT 'General Inquiry',
        `course_interest` VARCHAR(150) DEFAULT 'General Inquiry',
        `message` TEXT NOT NULL,
        `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $logs[] = ["status" => "ok", "msg" => "Table 'contact_messages' created."];

    // 3. Table: admins
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password_hash` VARCHAR(255) NOT NULL,
        `full_name` VARCHAR(100) DEFAULT 'DigiTrade Master Admin',
        `role` ENUM('superadmin', 'admin', 'moderator') DEFAULT 'admin',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $adminHash = '$2y$10$wN9aWkJhE0f5vUaJbA3tNuB.ZgP4b2fLzIu7xG4tV6aQ9xY5lq7m2';
    $stmt = $pdo->prepare("INSERT INTO `admins` (`username`, `email`, `password_hash`, `full_name`, `role`)
        VALUES ('admin', 'admin@digitradeacademy.com', :hash, 'DigiTrade Master Admin', 'superadmin')
        ON DUPLICATE KEY UPDATE `password_hash` = :hash");
    $stmt->execute([':hash' => $adminHash]);
    $logs[] = ["status" => "ok", "msg" => "Admin Account Ready (admin / Admin@DigiTrade2026)."];

    // 4. Sample Lead
    $stmtCheck = $pdo->query("SELECT COUNT(*) FROM `admissions`");
    if ($stmtCheck->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `admissions` 
            (`full_name`, `student_name`, `name`, `phone`, `whatsapp`, `email`, `city`, `course`, `course_name`, `mentor_choice`, `assigned_mentor`, `mentor`, `batch`, `batch_timing`, `experience`, `experience_level`, `notes`, `status`)
            VALUES 
            ('Muhammad Hamza', 'Muhammad Hamza', 'Muhammad Hamza', '03327292282', '03327292282', 'hamza@gmail.com', 'Lahore', 'XAU/USD Gold Trading Course', 'XAU/USD Gold Trading Course', 'Muhammad Safiullah', 'Muhammad Safiullah', 'Muhammad Safiullah', 'Evening Batch', 'Evening Batch', 'Beginner', 'Beginner', 'Ready to join Batch 14', 'pending');");
        $logs[] = ["status" => "ok", "msg" => "Sample student lead added."];
    }

    $success = true;
} catch (Exception $e) {
    $logs[] = ["status" => "error", "msg" => "Setup Error: " . $e->getMessage()];
    $success = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DigiTrade Database Setup</title>
<style>
body { background: #120306; color: #FFF; font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
.card { background: #1F070B; border: 1px solid #D4AF37; padding: 30px; border-radius: 12px; max-width: 520px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.log { text-align: left; background: #0A0203; padding: 15px; border-radius: 8px; font-size: 13px; color: #86EFAC; margin: 20px 0; border: 1px solid rgba(212,175,55,0.2); }
.log div { margin-bottom: 6px; }
.log .err { color: #FCA5A5; }
.btn { background: #D4AF37; color: #120306; padding: 12px 24px; font-weight: bold; border-radius: 6px; text-decoration: none; display: inline-block; transition: 0.2s; }
.btn:hover { background: #E6C866; }
</style>
</head>
<body>
<div class="card">
<h2 style="color: #D4AF37; margin-top: 0;">DigiTrade Academy Database Setup</h2>
<div class="log">
<?php foreach ($logs as $l): ?>
<div class="<?= $l['status'] === 'ok' ? 'ok' : 'err' ?>"><?= ($l['status'] === 'ok' ? '✔ ' : '✖ ') . htmlspecialchars($l['msg']) ?></div>
<?php endforeach; ?>
</div>
<?php if ($success): ?>
<p style="color:#A7F3D0; font-size: 14px;">Database & Tables are 100% Ready!</p>
<a href="dashboard.php" class="btn">Open Admin Dashboard →</a>
<?php endif; ?>
</div>
</body>
</html>