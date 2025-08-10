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
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: login.html');
    exit;
}
$fullName = htmlspecialchars($_SESSION['full_name'] ?? 'ادمین', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>پنل مدیریت | آموزشگاه فامو</title>
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
                <li><a href="#" class="active">پنل مدیریت</a></li>
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
        <!-- Admin Header -->
        <div class="card">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; background: var(--danger); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h2 style="margin: 0;">سلام، <?php echo $fullName; ?>!</h2>
                    <p style="margin: 4px 0 0 0; color: var(--muted);">مدیر آموزشگاه فامو</p>
                </div>
            </div>
            <p>پنل مدیریت آموزشگاه - مدیریت کاربران و درخواست‌های ثبت‌نام</p>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-4 mt-4">
            <div class="card text-center">
                <div style="font-size: 2rem; color: var(--primary); margin-bottom: 8px;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 style="margin: 0; font-size: 2rem; color: var(--primary);">۲۳</h3>
                <p style="margin: 4px 0 0 0; color: var(--muted);">کاربر فعال</p>
            </div>
            <div class="card text-center">
                <div style="font-size: 2rem; color: var(--success); margin-bottom: 8px;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 style="margin: 0; font-size: 2rem; color: var(--success);">۵</h3>
                <p style="margin: 4px 0 0 0; color: var(--muted);">درخواست جدید</p>
            </div>
            <div class="card text-center">
                <div style="font-size: 2rem; color: var(--warning); margin-bottom: 8px;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="margin: 0; font-size: 2rem; color: var(--warning);">۳</h3>
                <p style="margin: 4px 0 0 0; color: var(--muted);">دوره فعال</p>
            </div>
            <div class="card text-center">
                <div style="font-size: 2rem; color: var(--danger); margin-bottom: 8px;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 style="margin: 0; font-size: 2rem; color: var(--danger);">۸</h3>
                <p style="margin: 4px 0 0 0; color: var(--muted);">استاد برجسته</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-2 mt-4">
            <div class="card">
                <h3 style="margin-top: 0;">مدیریت کاربران</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <button class="btn" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-eye"></i>
                        مشاهده کاربران جدید
                    </button>
                    <button class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-search"></i>
                        جستجوی کاربر
                    </button>
                    <button class="btn" style="background: var(--success); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-file-excel"></i>
                        خروجی اکسل
                    </button>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top: 0;">گزارش‌ها</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <button class="btn" style="background: var(--warning); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chart-bar"></i>
                        آمار ثبت‌نام‌ها
                    </button>
                    <button class="btn" style="background: #6366f1; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-alt"></i>
                        گزارش ماهانه
                    </button>
                    <button class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-print"></i>
                        چاپ گزارش
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card mt-4">
            <h3>فعالیت‌های اخیر</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--bg); border-radius: var(--radius);">
                    <div style="width: 40px; height: 40px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 0; font-weight: 600;">کاربر جدید ثبت‌نام کرد</p>
                        <p style="margin: 0; color: var(--muted); font-size: 0.9rem;">علی محمدی - ۲ دقیقه پیش</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--bg); border-radius: var(--radius);">
                    <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 0; font-weight: 600;">تماس با کاربر انجام شد</p>
                        <p style="margin: 0; color: var(--muted); font-size: 0.9rem;">فاطمه احمدی - ۱۵ دقیقه پیش</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--bg); border-radius: var(--radius);">
                    <div style="width: 40px; height: 40px; background: var(--warning); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 0; font-weight: 600;">اطلاعات دوره به‌روزرسانی شد</p>
                        <p style="margin: 0; color: var(--muted); font-size: 0.9rem;">دوره ریاضی - ۱ ساعت پیش</p>
                    </div>
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