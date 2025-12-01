<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}

$featuredTours = $featuredTours ?? [
    ['id'=>1,'name'=>'Hà Nội - Hạ Long','price'=>2500000,'image'=>'https://images.unsplash.com/photo-1601758123927-9aaefbdb2e57?w=400','duration'=>'3 ngày 2 đêm','rating'=>4.5],
    ['id'=>2,'name'=>'Đà Nẵng - Hội An','price'=>3000000,'image'=>'https://images.unsplash.com/photo-1582878925120-071f598d1d1e?w=400','duration'=>'4 ngày 3 đêm','rating'=>4.8],
    ['id'=>3,'name'=>'Sài Gòn - Cần Thơ','price'=>2800000,'image'=>'https://images.unsplash.com/photo-1552346154-1e29f9f02d5f?w=400','duration'=>'2 ngày 1 đêm','rating'=>4.2],
    ['id'=>4,'name'=>'Nha Trang - Đà Lạt','price'=>3500000,'image'=>'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400','duration'=>'5 ngày 4 đêm','rating'=>4.9],
];

$locations = $locations ?? [
    ['name'=>'Hà Nội','image'=>'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=300&q=80'],
    ['name'=>'Đà Nẵng','image'=>'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=300&q=80'],
    ['name'=>'Hồ Chí Minh','image'=>'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=300&q=80'],
    ['name'=>'Nha Trang','image'=>'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=300&q=80'],
];

$totalTours = $totalTours ?? 120;
$totalCustomers = $totalCustomers ?? 80000;
$totalTrips = $totalTrips ?? 250;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'StarVel Travel' ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<!-- Splide Carousel -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>

<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: '#34D399',
        secondary: '#2563EB',
        accent: '#F59E0B',
        bgLight: '#F1F5F9',
        dark: '#1F2937',
      },
      animation: {
        'fade-in': 'fadeIn 1s ease-in-out',
        'scale-up': 'scaleUp 0.3s ease-out',
        'slide-up': 'slideUp 0.7s ease-out',
        'slide-in': 'slideIn 0.5s ease-out',
        'pulse': 'pulse 1.5s infinite',
      },
      keyframes: {
        fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
        scaleUp: { '0%': { transform: 'scale(0.95)' }, '100%': { transform: 'scale(1)' } },
        slideUp: { '0%': { opacity: 0, transform: 'translateY(20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
        slideIn: { '0%': { opacity: 0, transform: 'translateX(-100%)' }, '100%': { opacity: 1, transform: 'translateX(0)' } },
        pulse: { '0%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' }, '100%': { transform: 'scale(1)' } },
      },
    },
  },
};
</script>

<style>
body {
    font-family: 'Poppins', sans-serif;
}
.card-hover:hover .overlay {
    opacity: 1;
    transform: translateY(0);
}
.tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}
.card-hover:hover .tooltip {
    opacity: 1;
}
.counter {
    font-weight: 700;
    font-size: 2rem;
}
</style>
</head>
<body class="bg-bgLight">

<!-- Header -->
<header class="fixed top-0 w-full z-50 bg-white/60 backdrop-blur-md shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
        <a href="<?= BASE_URL ?>?act=home" class="text-2xl font-extrabold text-primary flex items-center gap-2">
            <i class="fas fa-compass"></i> StarVel
        </a>
        <nav class="hidden md:flex gap-6 font-semibold text-gray-700">
            <a href="<?= BASE_URL ?>?act=home" class="hover:text-primary transition">Trang chủ</a>
            <a href="<?= BASE_URL ?>?act=tours" class="hover:text-primary transition">Tour</a>
            <a href="<?= BASE_URL ?>?act=about" class="hover:text-primary transition">Giới thiệu</a>
            <a href="<?= BASE_URL ?>?act=blog" class="hover:text-primary transition">Tin tức</a>
            <a href="<?= BASE_URL ?>?act=contact" class="hover:text-primary transition">Liên hệ</a>
        </nav>
        <button class="md:hidden text-2xl" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div id="mobileMenu" class="hidden md:hidden bg-white shadow-lg flex flex-col px-6 py-4 space-y-2">
        <a href="<?= BASE_URL ?>?act=home">Trang chủ</a>
        <a href="<?= BASE_URL ?>?act=tours">Tour</a>
        <a href="<?= BASE_URL ?>?act=about">Giới thiệu</a>
        <a href="<?= BASE_URL ?>?act=blog">Tin tức</a>
        <a href="<?= BASE_URL ?>?act=contact">Liên hệ</a>
    </div>
</header>

<!-- Hero Section -->
<section class="relative h-screen bg-gradient-to-r from-primary to-accent flex items-center justify-center text-center text-white overflow-hidden">
    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920" class="absolute w-full h-full object-cover opacity-40">
    <div class="relative z-10 max-w-3xl px-6 py-4">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 animate-fade-in">Khám Phá Thế Giới Cùng StarVel</h1>
        <p class="mb-6 text-lg md:text-2xl animate-fade-in">Tour trong nước & quốc tế, trải nghiệm đáng nhớ</p>
        <a href="<?= BASE_URL ?>?act=tours" class="bg-secondary text-white px-8 py-4 rounded-full font-bold shadow-lg hover:scale-105 transition transform">Đặt tour ngay</a>
    </div>
</section>

<!-- Featured Tours Section -->
<section class="max-w-7xl mx-auto py-20 px-6">
    <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Tour Nổi Bật</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach($featuredTours as $tour): ?>
        <div class="relative rounded-xl overflow-hidden shadow-lg card-hover transform transition hover:scale-105">
            <img src="<?= $tour['image'] ?>" alt="<?= $tour['name'] ?>" class="w-full h-60 object-cover">
            <div class="absolute inset-0 bg-black/40 overlay opacity-0 transition duration-300 flex flex-col justify-center items-center text-white p-4">
                <h3 class="font-bold text-xl mb-2"><?= $tour['name'] ?></h3>
                <p class="mb-1 font-semibold"><?= number_format($tour['price']) ?> VNĐ</p>
                <p class="mb-2">Thời gian: <?= $tour['duration'] ?></p>
                <div class="mb-2">
                    <?php for($i=1;$i<=5;$i++): ?>
                    <i class="fa-star <?= $i <= floor($tour['rating']) ? 'fas text-yellow-400' : 'far text-yellow-300' ?>"></i>
                    <?php endfor; ?>
                </div>
                <a href="<?= BASE_URL.'?act=tour-detail&id='.$tour['id'] ?>" class="bg-accent px-6 py-2 rounded-full font-bold hover:scale-105 transition transform">Xem chi tiết</a>
            </div>
            <div class="tooltip"><?= $tour['duration'] ?> - Rating: <?= $tour['rating'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Locations Section -->
<section class="py-20 bg-gradient-to-r from-accent to-primary text-white">
    <h2 class="text-4xl font-bold text-center mb-12">Khám Phá Địa Điểm</h2>
    <div class="max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        <?php foreach($locations as $loc): ?>
        <div class="relative rounded-xl overflow-hidden shadow-lg hover:scale-105 transform transition animate-slide-up card-hover">
            <img src="<?= $loc['image'] ?>" alt="<?= $loc['name'] ?>" class="w-full h-40 object-cover">
            <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                <h3 class="text-white font-bold text-lg"><?= $loc['name'] ?></h3>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Statistics -->
<section class="py-20 text-center">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="counter" data-count="<?= $totalTours ?>">0</p>
            <p class="mt-2 font-semibold">Tour đã thực hiện</p>
        </div>
        <div>
            <p class="counter" data-count="<?= $totalCustomers ?>">0</p>
            <p class="mt-2 font-semibold">Khách hàng</p>
        </div>
        <div>
            <p class="counter" data-count="<?= $totalTrips ?>">0</p>
            <p class="mt-2 font-semibold">Chuyến đi thành công</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
        <div>
            <h3 class="text-white font-bold mb-4 text-lg">StarVel Travel</h3>
            <p>Khám phá thế giới với trải nghiệm du lịch tuyệt vời cùng chúng tôi.</p>
        </div>
        <div>
            <h3 class="text-white font-bold mb-4 text-lg">Liên hệ</h3>
            <p>Email: info@starvel.com</p>
            <p>Hotline: 1900 xxxx</p>
        </div>
        <div>
            <h3 class="text-white font-bold mb-4 text-lg">Mạng xã hội</h3>
            <div class="flex gap-4">
                <a href="#" class="hover:text-accent"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-accent"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-accent"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>

<script>
// Initialize Carousel
document.addEventListener('DOMContentLoaded', function () {
  new Splide('#tour-carousel', {
    type   : 'loop',
    perPage: 3,
    autoplay:true,
    gap: '1rem',
    breakpoints: {
      1024: { perPage: 2 },
      640: { perPage: 1 }
    }
  }).mount();

  // Counter Animation
  const counters = document.querySelectorAll('.counter');
  counters.forEach(counter => {
    let updateCount = () => {
      const target = +counter.getAttribute('data-count');
      const count = +counter.innerText;
      const increment = target / 200;
      if(count < target){
        counter.innerText = Math.ceil(count + increment);
        setTimeout(updateCount, 10);
      } else {
        counter.innerText = target;
      }
    }
    updateCount();
  });
});
</script>
</body>
</html>
