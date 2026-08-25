<?php
/**
 * DigiTrade Academy - Standard JSON Response & Request Helper
 */

declare(strict_types=1);

/**
 * Set Standard JSON and CORS Headers
 */
function setApiHeaders(): void {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

/**
 * Send Standard JSON Response
 */
function sendResponse(bool $success, string $message, $data = null, int $statusCode = 200): void {
    setApiHeaders();
    http_response_code($statusCode);

    $response = [
        'success'   => $success,
        'message'   => $message,
        'timestamp' => date('c')
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

/**
 * Get Clean Input Data from JSON Body or POST
 */
function getRequestData(): array {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : [];
    }

    return $_POST;
}

/**
 * Sanitize string input
 */
function sanitizeInput(?string $value): string {
    if ($value === null) return '';
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}
