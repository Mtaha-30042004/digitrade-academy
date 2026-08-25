<?php
declare(strict_types=1);

/**
 * DigiTrade Academy - Database Configuration & PDO Handler
 * Configured for Railway Live Production & Localhost
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);

class Database {
    private static ?PDO $conn = null;

    /**
     * Get Singleton PDO Connection
     */
    public static function getConnection(): ?PDO {
        if (self::$conn === null) {
            $isLocal = in_array($_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'], true);

            if ($isLocal) {
                $host = '127.0.0.1';
                $db   = 'digitrade_db';
                $user = 'root';
                $pass = '';
                $port = '3306';
            } else {
                // Railway Direct Credentials
                $host = 'mysql.railway.internal';
                $db   = 'railway';
                $user = 'root';
                $pass = 'bVeXdjJsISFeAQluwzOAzrHrPLJZpkNdJ';
                $port = '3306';
            }

            $charset = 'utf8mb4';
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset
            ];

            try {
                self::$conn = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                return null;
            }
        }

        return self::$conn;
    }
}