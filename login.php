<?php
header("Content-Type: application/json; charset=utf-8");

$allowed_origin = getenv('CORS_ALLOW_ORIGIN') ?: '';
if ($allowed_origin !== '') {
    header("Access-Control-Allow-Origin: {$allowed_origin}");
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'famoacademy.ir',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/config.php';

function getCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'csrf') {
        echo json_encode(['csrf_token' => getCsrfToken()]);
        exit;
    }
    http_response_code(405);
    echo json_encode(['detail' => 'Method not allowed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['detail' => 'Method not allowed']);
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', (string)$csrf)) {
    http_response_code(403);
    echo json_encode(['detail' => 'درخواست نامعتبر']);
    exit;
}

$phone = isset($_POST['phone_number']) ? trim((string)$_POST['phone_number']) : '';
$phone = preg_replace('/[^0-9+]/', '', $phone);

if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['detail' => 'فرمت شماره تماس معتبر نیست']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, full_name, phone_number FROM users WHERE phone_number = :phone LIMIT 1');
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(401);
        echo json_encode(['detail' => 'حسابی با این شماره یافت نشد']);
        exit;
    }

    // Determine admin by environment list of phone numbers
    $adminPhones = array_filter(array_map('trim', explode(',', getenv('ADMIN_PHONES') ?: '')));
    $isAdmin = in_array($user['phone_number'], $adminPhones, true);

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['full_name'] = (string)$user['full_name'];
    $_SESSION['is_admin'] = $isAdmin;

    echo json_encode([
        'message' => 'ورود با موفقیت انجام شد',
        'user_id' => (int)$user['id'],
        'full_name' => (string)$user['full_name'],
        'is_admin' => $isAdmin,
        'redirect' => $isAdmin ? 'admin_dashboard.php' : 'user_dashboard.php'
    ]);
} catch (PDOException $e) {
    error_log('Database error in login.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['detail' => 'خطای سرور در ورود']);
}
