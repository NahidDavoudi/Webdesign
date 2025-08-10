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
<a href="#main" class="skip-to-content">رفتن به محتوای اصلی</a>

<header>
    <div class="container header-container">
        <div class="logo">
            <a href="#"><img src="./logo.png" alt="لوگوی آموزشگاه فامو"></a>
            <h1>فامو <span>آکادمی</span></h1>
        </div>
        <nav>
            <ul id="mainMenu">
                <li><a href="index.html">خانه</a></li>
                <li><a href="#" class="active">داشبورد</a></li>
                <li><a href="index.html#courses">دوره‌ها</a></li>
                <li><a href="index.html#contact">تماس با ما</a></li>
                <li><a href="#" id="logoutBtn">خروج</a></li>
            </ul>
        </nav>
        <button class="mobile-menu-btn" aria-label="منوی موبایل" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>
<main id="main" class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 32px;">
            <!-- Welcome Card -->
            <div class="card">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0;">خوش آمدید، <?php echo $fullName; ?>!</h2>
                        <p style="margin: 4px 0 0 0; color: var(--muted);">عضو آموزشگاه فامو</p>
                    </div>
                </div>
                <p>ثبت‌نام شما با موفقیت انجام شده است. به زودی با شما تماس گرفته خواهد شد.</p>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                    حساب کاربری شما فعال است
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h3 style="margin-top: 0;">دسترسی سریع</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a class="btn" href="index.html#courses" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-book"></i>
                        مشاهده دوره‌ها
                    </a>
                    <a class="btn btn-secondary" href="index.html#instructors" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                        مشاهده اساتید
                    </a>
                    <a class="btn" style="background: #0f766e; display: flex; align-items: center; gap: 8px;" href="index.html#contact">
                        <i class="fas fa-phone"></i>
                        تماس با ما
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card mt-4">
            <h3>مراحل بعدی</h3>
            <div class="grid grid-3">
                <div class="feature-card" style="text-align: center; padding: 24px;">
                    <div class="feature-icon" style="margin: 0 auto 16px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0;">تماس مشاوران</h4>
                    <p style="color: var(--muted); margin: 0; font-size: 0.9rem;">در کمترین زمان ممکن</p>
                </div>
                <div class="feature-card" style="text-align: center; padding: 24px;">
                    <div class="feature-icon" style="margin: 0 auto 16px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0;">تنظیم برنامه</h4>
                    <p style="color: var(--muted); margin: 0; font-size: 0.9rem;">برنامه‌ریزی تحصیلی شخصی</p>
                </div>
                <div class="feature-card" style="text-align: center; padding: 24px;">
                    <div class="feature-icon" style="margin: 0 auto 16px;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0;">شروع کلاس‌ها</h4>
                    <p style="color: var(--muted); margin: 0; font-size: 0.9rem;">آغاز مسیر موفقیت</p>
                </div>
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