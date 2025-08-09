<?php
$host = 'localhost';
$db   = 'famoacad_mein';
$user = 'famoacad_davoudi';
$pass = 'XATjm6;]PCm3K*]z';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}
