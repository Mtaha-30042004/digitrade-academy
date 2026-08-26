<?php
declare(strict_types=1);

/**
 * DigiTrade Academy - Database Configuration & PDO Handler
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);

class Database {
    private static ?PDO $conn = null;

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
                $host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'mysql.railway.internal';
                $db   = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'railway';
                $user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
                $pass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';
                $port = (string)(getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306');
            }

            if (strpos($host, '${{') !== false) {
                $host = 'mysql.railway.internal';
            }
            if (strpos($pass, '${{') !== false) {
                $pass = '';
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
