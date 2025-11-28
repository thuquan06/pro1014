<?php
// Đảm bảo BASE_URL được định nghĩa
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StarVel Travel - Du lịch trong nước & quốc tế' ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $pageDescription ?? 'StarVel Travel - Chuyên cung cấp các tour du lịch trong nước và quốc tế chất lượng cao với giá cả hợp lý' ?>">
    <meta name="keywords" content="<?= $pageKeywords ?? 'du lịch, tour, du lịch Việt Nam, tour quốc tế, StarVel' ?>">
    <meta property="og:title" content="<?= $pageTitle ?? 'StarVel Travel - Du lịch trong nước & quốc tế' ?>">
    <meta property="og:description" content="<?= $pageDescription ?? 'StarVel Travel - Chuyên cung cấp các tour du lịch trong nước và quốc tế chất lượng cao' ?>">
    <meta property="og:image" content="<?= $pageImage ?? BASE_URL . 'assets/images/og-image.jpg' ?>">
    <meta property="og:url" content="<?= $pageUrl ?? BASE_URL ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              primary: "#14697F",
              "background-light": "#F8FEFD",
              "background-dark": "#121A19",
              "surface-light": "#FFFFFF",
              "surface-dark": "#1F2928",
              "text-light": "#101010",
              "text-dark": "#E0E0E0",
              "text-muted-light": "#6B7280",
              "text-muted-dark": "#9CA3AF",
            },
            fontFamily: {
              display: ["Roboto", "sans-serif"],
            },
            borderRadius: {
              DEFAULT: "0.75rem",
            },
          },
        },
      };
    </script>

    <!-- Client CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/client-style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #10b981;
            --danger: #ef4444;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg-light: #f9fafb;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Custom page styles */
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <!-- Header Top Bar -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-container">
                <div class="header-contact">
                    <a href="tel:1900xxxx">
                        <i class="fas fa-phone"></i>
                        <span>1900 xxxx</span>
                    </a>
                    <a href="mailto:info@starvel.com">
                        <i class="fas fa-envelope"></i>
                        <span>info@starvel.com</span>
                    </a>
                </div>
                <div class="header-contact">
                    <a href="<?= BASE_URL ?>?act=order-tracking">
                        <i class="fas fa-search"></i>
                        <span>Tra cứu đơn hàng</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Main -->
    <header class="header">
        <div class="header-main">
            <div class="container">
                <div class="header-container">
                    <a href="<?= BASE_URL ?>?act=home" class="logo">
                        <i class="fas fa-plane-departure"></i>
                        <span>StarVel</span>
                    </a>

                    <!-- Search Bar -->
                    <div class="header-search">
                        <form onsubmit="handleSearch(event)">
                            <input type="text" placeholder="Tìm kiếm tour, điểm đến...">
                            <button type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <nav>
                        <ul class="nav-menu" id="navMenu">
                            <li><a href="<?= BASE_URL ?>?act=home">Trang chủ</a></li>
                            <li><a href="<?= BASE_URL ?>?act=tours">Tour</a></li>
                            <li><a href="<?= BASE_URL ?>?act=about">Giới thiệu</a></li>
                            <li><a href="<?= BASE_URL ?>?act=blog">Tin tức</a></li>
                            <li><a href="<?= BASE_URL ?>?act=contact">Liên hệ</a></li>
                            <li><a href="<?= BASE_URL ?>?act=tours" class="btn-book">Đặt tour</a></li>
                        </ul>
                    </nav>

                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Breadcrumb (if exists) -->
    <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-container">
                <a href="<?= BASE_URL ?>?act=home">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
                <?php foreach ($breadcrumb as $item): ?>
                    <i class="fas fa-chevron-right"></i>
                    <?php if (isset($item['url'])): ?>
                        <a href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                    <?php else: ?>
                        <span><?= $item['title'] ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Banner/Hero Section (only on homepage) -->
    <?php if (isset($showBanner) && $showBanner): ?>
    <section class="relative h-[500px] text-white">
        <img alt="Thuyền truyền thống trên vùng nước yên bình vào lúc hoàng hôn với những ngọn núi đá vôi phía sau" 
             class="w-full h-full object-cover" 
             src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1920&q=80"/>
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-5xl px-4">
            <div class="bg-surface-light/80 dark:bg-surface-dark/80 backdrop-blur-sm p-4 rounded-lg shadow-lg flex items-center space-x-4">
                <div class="flex-grow flex items-center bg-surface-light dark:bg-surface-dark rounded border border-gray-300 dark:border-gray-600 px-3">
                    <span class="material-icons text-text-muted-light dark:text-text-muted-dark">search</span>
                    <input class="w-full bg-transparent border-0 focus:ring-0 text-text-light dark:text-text-dark placeholder-text-muted-light dark:placeholder-text-muted-dark" 
                           placeholder="Bạn muốn đi đâu?" type="text"/>
                </div>
                <div class="flex items-center bg-surface-light dark:bg-surface-dark rounded border border-gray-300 dark:border-gray-600 px-3 py-2">
                    <span class="material-icons text-text-muted-light dark:text-text-muted-dark">calendar_today</span>
                    <span class="ml-2 text-text-light dark:text-text-dark whitespace-nowrap">Ngày đi</span>
                </div>
                <div class="flex items-center bg-surface-light dark:bg-surface-dark rounded border border-gray-300 dark:border-gray-600 px-3 py-2">
                    <span class="material-icons text-text-muted-light dark:text-text-muted-dark">calendar_today</span>
                    <span class="ml-2 text-text-light dark:text-text-dark whitespace-nowrap">Ngày về</span>
                </div>
                <div class="flex items-center bg-surface-light dark:bg-surface-dark rounded border border-gray-300 dark:border-gray-600 px-3 py-2">
                    <span class="material-icons text-text-muted-light dark:text-text-muted-dark">group</span>
                    <span class="ml-2 text-text-light dark:text-text-dark">Số khách</span>
                    <span class="material-icons text-text-muted-light dark:text-text-muted-dark">arrow_drop_down</span>
                </div>
                <button class="bg-primary text-white font-bold py-2 px-6 rounded whitespace-nowrap">Tìm kiếm Tour</button>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Hotline Sticky -->
    <div class="hotline-sticky">
        <a href="tel:1900xxxx" class="hotline-btn" title="Hotline">
            <i class="fas fa-phone-alt"></i>
        </a>
        <a href="https://zalo.me/1900xxxx" target="_blank" class="hotline-btn zalo" title="Chat Zalo">
            <i class="fab fa-facebook-messenger"></i>
        </a>
        <a href="https://m.me/starvel" target="_blank" class="hotline-btn messenger" title="Chat Facebook">
            <i class="fab fa-facebook-messenger"></i>
        </a>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>
                        <i class="fas fa-plane-departure"></i>
                        StarVel Travel
                    </h3>
                    <p>Chuyên cung cấp các tour du lịch trong nước và quốc tế chất lượng cao với giá cả hợp lý. Hơn 10 năm kinh nghiệm phục vụ khách hàng.</p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Danh mục</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>?act=tours&type=domestic">Tour trong nước</a></li>
                        <li><a href="<?= BASE_URL ?>?act=tours&type=international">Tour quốc tế</a></li>
                        <li><a href="<?= BASE_URL ?>?act=tours&promo=1">Tour khuyến mãi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Hỗ trợ</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>?act=contact">Liên hệ</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Chính sách hủy tour</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Liên hệ</h3>
                    <ul>
                        <li>
                            <i class="fas fa-phone"></i>
                            <strong>Hotline:</strong> <a href="tel:1900xxxx" style="color: #f59e0b;">1900 xxxx</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <strong>Email:</strong> <a href="mailto:info@starvel.com">info@starvel.com</a>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <strong>Địa chỉ:</strong> 123 Đường ABC, Quận X, Hà Nội
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <strong>Giờ làm việc:</strong> 8:00 - 20:00 (T2-CN)
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> StarVel Travel. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Client JavaScript -->
    <script>
        // Define BASE_URL for JavaScript
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>assets/js/client-main.js"></script>

    <!-- Page-specific scripts -->
    <?php if (isset($pageScripts)): ?>
        <?= $pageScripts ?>
    <?php endif; ?>
</body>
</html>
