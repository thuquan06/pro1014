<?php
// config.php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/'); // Thay bằng domain thật nếu deploy
}

// Demo dữ liệu
$title = "Chào mừng đến với TravelGo!";
$thoiTiet = "Hà Nội: 28°C, Nắng nhẹ";
$featuredTours = [
    ['id'=>1,'tengoi'=>'Tour Hà Nội - Hạ Long','giagoi'=>3500000,'hinhanh'=>'','khuyenmai'=>1],
    ['id'=>2,'tengoi'=>'Tour Đà Nẵng - Hội An','giagoi'=>4500000,'hinhanh'=>'','khuyenmai'=>0],
    ['id'=>3,'tengoi'=>'Tour TP.HCM - Vũng Tàu','giagoi'=>2500000,'hinhanh'=>'','khuyenmai'=>1],
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #0ea5e9; /* xanh hiện đại */
            --secondary: #f43f5e; /* hồng nổi bật */
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Header -->
<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
        <a href="<?= BASE_URL ?>" class="text-2xl font-bold text-primary">TravelGo</a>
        <nav class="space-x-6 hidden md:flex">
            <a href="<?= BASE_URL ?>" class="hover:text-primary">Trang chủ</a>
            <a href="<?= BASE_URL ?>?act=tour" class="hover:text-primary">Tour</a>
            <a href="<?= BASE_URL ?>?act=contact" class="hover:text-primary">Liên hệ</a>
        </nav>
        <button class="md:hidden text-3xl text-primary">☰</button>
    </div>
</header>

<!-- Hero Section -->
<section class="relative bg-primary text-white h-96 flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold"><?= $title ?></h1>
        <p class="mt-4 text-xl"><?= $thoiTiet ?></p>
        <a href="<?= BASE_URL ?>?act=tour" class="mt-6 inline-block bg-secondary px-6 py-3 rounded-lg font-semibold hover:bg-red-600">Xem Tour</a>
    </div>
</section>

<!-- Featured Tours -->
<section class="max-w-7xl mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-center mb-10">Tour Nổi Bật</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach($featuredTours as $tour): ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <img src="<?= $tour['hinhanh'] ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80' ?>" alt="<?= $tour['tengoi'] ?>" class="object-cover h-full w-full">
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold"><?= $tour['tengoi'] ?></h3>
                    <p class="text-primary font-semibold mt-2"><?= number_format($tour['giagoi'],0,',','.') ?> VNĐ</p>
                    <?php if($tour['khuyenmai']): ?>
                        <span class="inline-block bg-secondary text-white text-xs font-bold px-2 py-1 mt-2 rounded">Khuyến mãi</span>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>?act=tour-detail&id=<?= $tour['id'] ?>" class="mt-4 block bg-primary text-white text-center py-2 rounded hover:bg-blue-700">Đặt ngay</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h3 class="font-bold text-lg">TravelGo</h3>
            <p>Chuyên cung cấp tour du lịch trong nước và quốc tế. Trải nghiệm hiện đại, giá cả hợp lý.</p>
        </div>
        <div>
            <h3 class="font-bold text-lg">Liên hệ</h3>
            <p>Hotline: 1900xxxx</p>
            <p>Email: info@travelgo.com</p>
        </div>
        <div>
            <h3 class="font-bold text-lg">Theo dõi chúng tôi</h3>
            <div class="flex space-x-4 mt-2">
                <a href="#" class="hover:text-primary">Facebook</a>
                <a href="#" class="hover:text-primary">Instagram</a>
                <a href="#" class="hover:text-primary">YouTube</a>
            </div>
        </div>
    </div>
    <div class="text-center py-4 bg-gray-800">&copy; <?= date('Y') ?> TravelGo. All rights reserved.</div>
</footer>

</body>
</html>
