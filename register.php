<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

// اصلاح مسیر فایل config
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['detail' => 'Method not allowed']);
    exit;
}

// اصلاح نام فیلدها
$full_name = trim($_POST['full_name'] ?? '');
$phone     = trim($_POST['phone_number'] ?? '');

if ($full_name === '' || $phone === '') {
    http_response_code(400);
    echo json_encode(['detail' => 'نام و شماره تماس الزامی هستند']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone_number = :phone LIMIT 1");
    $stmt->execute([':phone' => $phone]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(400);
        echo json_encode(['detail' => 'این شماره قبلاً ثبت شده']);
        exit;
    }

    $insert = $pdo->prepare("INSERT INTO users (full_name, phone_number) VALUES (:name, :phone)");
    $insert->execute([':name' => $full_name, ':phone' => $phone]);

    http_response_code(201);
    echo json_encode([
        'message' => 'ثبت نام با موفقیت انجام شد',
        'user_id' => (int)$pdo->lastInsertId(),
        'next_step' => 'همکاران ما ظرف 24 ساعت با شما تماس خواهند گرفت'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['detail' => 'خطا در ثبت اطلاعات: ' . $e->getMessage()]);
}