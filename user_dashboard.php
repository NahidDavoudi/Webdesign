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
            <a href="index.html" aria-label="بازگشت به صفحه اصلی">
                <img src="./logo.png" alt="لوگوی آموزشگاه فامو" width="48" height="48">
                <h1>فامو <span>آکادمی</span></h1>
            </a>
        </div>
        <nav aria-label="منوی کاربری">
            <ul id="mainMenu" role="menubar">
                <li role="none"><a href="index.html" role="menuitem">خانه</a></li>
                <li role="none"><a href="#" role="menuitem" aria-current="page">داشبورد</a></li>
                <li role="none"><a href="#" id="logoutBtn" role="menuitem">خروج</a></li>
            </ul>
        </nav>
        <button class="mobile-menu-btn" aria-label="باز کردن منوی موبایل" aria-expanded="false" aria-controls="mainMenu" id="mobileMenuBtn">
            <i class="fas fa-bars" aria-hidden="true"></i>
            <span class="sr-only">منو</span>
        </button>
    </div>
</header>
<main class="section">
    <div class="container">
        <div class="card">
            <h2>خوش آمدید، <?php echo $fullName; ?>!</h2>
            <p>ثبت‌نام شما با موفقیت انجام شده است. به زودی با شما تماس گرفته خواهد شد.</p>
            <div style="margin-top:var(--spacing-lg);">
                <a class="btn" href="index.html#courses" aria-label="مشاهده دوره‌های آموزشی">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                    مشاهده دوره‌ها
                </a>
                <a class="btn btn-secondary" href="index.html#contact" aria-label="تماس با آموزشگاه">
                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                    تماس با ما
                </a>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('mainMenu');
    
    if (mobileBtn && nav) {
        mobileBtn.addEventListener('click', () => {
            const isExpanded = mobileBtn.getAttribute('aria-expanded') === 'true';
            mobileBtn.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('show');
            
            const newLabel = isExpanded ? 'باز کردن منوی موبایل' : 'بستن منوی موبایل';
            mobileBtn.setAttribute('aria-label', newLabel);
        });
        
        document.querySelectorAll('nav ul li a').forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('show');
                mobileBtn.setAttribute('aria-expanded', 'false');
                mobileBtn.setAttribute('aria-label', 'باز کردن منوی موبایل');
            });
        });
    }

    // Logout functionality
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (confirm('آیا مطمئن هستید که می‌خواهید خارج شوید؟')) {
                try {
                    const response = await fetch('logout.php', { 
                        method: 'POST', 
                        credentials: 'same-origin' 
                    });
                    
                    if (response.ok) {
                        window.location.href = 'index.html';
                    } else {
                        alert('خطا در خروج از سیستم');
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                    alert('خطا در برقراری ارتباط با سرور');
                }
            }
        });
    }
});
</script>
</body>
</html>