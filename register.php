<?php
header("Content-Type: application/json; charset=utf-8");

$allowed_origin = getenv('CORS_ALLOW_ORIGIN') ?: '';
if ($allowed_origin !== '') {
    header("Access-Control-Allow-Origin: {$allowed_origin}");
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['detail' => 'Method not allowed']);
    exit;
}

$full_name = isset($_POST['full_name']) ? trim((string)$_POST['full_name']) : '';
$phone     = isset($_POST['phone_number']) ? trim((string)$_POST['phone_number']) : '';

$full_name = preg_replace('/\s+/u', ' ', $full_name);
$phone     = preg_replace('/[^0-9+]/', '', $phone);

if ($full_name === '' || $phone === '') {
    http_response_code(400);
    echo json_encode(['detail' => 'نام و شماره تماس الزامی هستند']);
    exit;
}

if (mb_strlen($full_name) < 3 || mb_strlen($full_name) > 100) {
    http_response_code(400);
    echo json_encode(['detail' => 'طول نام معتبر نیست']);
    exit;
}

if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['detail' => 'فرمت شماره تماس معتبر نیست']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone_number = :phone LIMIT 1");
    $stmt->execute([':phone' => $phone]);

    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['detail' => 'این شماره قبلاً ثبت شده']);
        exit;
    }

    $insert = $pdo->prepare("INSERT INTO users (full_name, phone_number) VALUES (:name, :phone)");
    $insert->execute([':name' => $full_name, ':phone' => $phone]);

    $userId = (int)$pdo->lastInsertId();

    // Auto-login after registration
    $_SESSION['user_id'] = $userId;
    $_SESSION['full_name'] = $full_name;

    http_response_code(201);
    echo json_encode([
        'message' => 'ثبت نام با موفقیت انجام شد',
        'user_id' => $userId,
        'redirect' => 'user_dashboard.php'
    ]);

} catch (PDOException $e) {
    error_log('Database error in register.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['detail' => 'خطا در ثبت اطلاعات']);
}