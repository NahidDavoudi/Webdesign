<?php
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
if (empty($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$fullName = htmlspecialchars($_SESSION['full_name'] ?? 'کاربر', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>پنل کاربری | آموزشگاه فامو</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="./assets/style.css" />
</head>
<body>
<header>
    <div class="container header-container">
        <div class="logo">
            <a href="#"><img src="./logo.png" alt="لوگوی آموزشگاه فامو"></a>
            <h1>فامو <span>آکادمی</span></h1>
        </div>
        <nav>
            <ul id="mainMenu">
                <li><a href="index.html">خانه</a></li>
                <li><a href="#">داشبورد</a></li>
                <li><a href="#" id="logoutBtn">خروج</a></li>
            </ul>
        </nav>
        <button class="mobile-menu-btn" aria-label="منوی موبایل" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>
<main class="section">
    <div class="container">
        <div class="card">
            <h2>خوش آمدید، <?php echo $fullName; ?>!</h2>
            <p>ثبت‌نام شما با موفقیت انجام شده است. به زودی با شما تماس گرفته خواهد شد.</p>
            <div style="margin-top:12px;">
                <a class="btn" href="index.html#courses">مشاهده دوره‌ها</a>
                <a class="btn btn-secondary" href="index.html#contact">تماس با ما</a>
            </div>
        </div>
    </div>
</main>
<script>
const mobileBtn = document.getElementById('mobileMenuBtn');
const nav = document.getElementById('mainMenu');
mobileBtn?.addEventListener('click', () => nav.classList.toggle('show'));
document.querySelectorAll('nav ul li a').forEach(link => link.addEventListener('click', () => nav.classList.remove('show')));

document.getElementById('logoutBtn').addEventListener('click', async (e) => {
    e.preventDefault();
    try {
        await fetch('logout.php', { method: 'POST', credentials: 'same-origin' });
        window.location.href = 'index.html';
    } catch {}
});
</script>
</body>
</html>