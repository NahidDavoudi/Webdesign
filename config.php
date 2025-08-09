<?php
$host = getenv('DB_HOST') ?: '';
$db   = getenv('DB_NAME') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';
$dsn  = "mysql:host={$host};dbname={$db};charset=utf8mb4";

if ($host === '' || $db === '' || $user === '' || $pass === '') {
    http_response_code(500);
    die("Database configuration is missing. Please set DB_HOST, DB_NAME, DB_USER, and DB_PASS environment variables.");
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die("خطا در اتصال به دیتابیس.");
}
