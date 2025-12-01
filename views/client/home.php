<?php
// Helper functions
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}

function formatPrice($price) {
    if (empty($price) || !is_numeric($price)) return 'Liên hệ';
    return number_format($price, 0, ',', '.') . ' VNĐ';
}

function formatPriceUSD($price) {
    if (empty($price) || !is_numeric($price)) return 'Liên hệ';
    $usdPrice = round($price / 25000);
    return '$' . number_format($usdPrice, 0);
}

function getImageUrl($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?? BASE_URL . 'assets/images/default-tour.jpg';
    }
    if (preg_match('/^https?:\/\//', $imagePath)) return $imagePath;
    if (strpos($imagePath, BASE_URL) === false) return BASE_URL . ltrim($imagePath, '/');
    return $imagePath;
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<div class="max-w-[1440px] mx-auto px-4 py-10">

    <!-- Featured Tours -->
    <section class="text-center py-16">
        <h2 class="text-3xl font-bold text-secondary mb-8">Tour Nổi Bật & Khuyến Mãi</h2>

        <div class="flex space-x-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
            <?php if (!empty($featuredTours) && is_array($featuredTours)): ?>
                <?php foreach (array_slice($featuredTours, 0, 6) as $tour): ?>
                    <?php
                    $tourImage = getImageUrl($tour['hinhanh'] ?? '', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&auto=format&fit=crop&q=80');
                    $tourUrl = BASE_URL . 'tour/' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tour['tengoi']))) . '-' . $tour['id_goi'] . '.html';
                    $hasPromo = !empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1;
                    $priceUSD = formatPriceUSD($tour['giagoi'] ?? 0);
                    ?>

                    <div class="flex-shrink-0 w-80 bg-white rounded-xl shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl relative overflow-hidden">
                        <img loading="lazy" alt="<?= e($tour['tengoi']) ?>" class="w-full h-52 object-cover" src="<?= e($tourImage) ?>"
                             srcset="<?= e($tourImage) ?> 500w, <?= e($tourImage) ?> 1000w" sizes="(max-width: 600px) 500px, 1000px">
                        
                        <?php if ($hasPromo): ?>
                            <span class="absolute top-2 right-2 bg-accent text-white text-xs font-bold px-2 py-1 rounded shadow">Giảm 10%</span>
                        <?php endif; ?>

                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-secondary"><?= e($tour['tengoi']) ?></h3>
                            <p class="text-primary text-xl font-bold mt-2"><?= $priceUSD ?>
                                <span class="block text-gray-500 text-sm font-normal mt-1"><?= formatPrice($tour['giagoi']) ?></span>
                            </p>
                            <a href="<?= $tourUrl ?>" class="mt-5 inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold shadow-md hover:bg-primary-light transition">Đặt ngay</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500">Chưa có tour nổi bật</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Tour By Region -->
    <section class="py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <h2 class="text-3xl font-bold text-center text-secondary mb-8">Tour Trong Nước</h2>
                <div class="grid grid-cols-2 gap-6">
                    <?php 
                    $domestic = [
                        ["Miền Bắc", "https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&auto=format&fit=crop&q=80"],
                        ["Miền Trung", "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=500&auto=format&fit=crop&q=80"],
                        ["Miền Nam", "https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&auto=format&fit=crop&q=80"],
                        ["Ven Biển", "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&auto=format&fit=crop&q=80"],
                    ];
                    foreach ($domestic as $d):
                    ?>
                        <a href="<?= BASE_URL ?>?act=tours&region=<?= urlencode($d[0]) ?>" class="relative h-40 rounded-xl overflow-hidden shadow-lg group">
                            <img loading="lazy" class="w-full h-full object-cover transition-transform transform group-hover:scale-105" src="<?= $d[1] ?>" alt="<?= e($d[0]) ?>"
                                 srcset="<?= $d[1] ?> 500w, <?= $d[1] ?> 1000w" sizes="(max-width: 600px) 500px, 1000px">
                            <div class="absolute inset-0 bg-secondary/50 group-hover:bg-primary/50 transition-all"></div>
                            <h3 class="absolute inset-0 flex items-center justify-center text-white text-xl font-semibold drop-shadow-lg"><?= e($d[0]) ?></h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <h2 class="text-3xl font-bold text-center text-secondary mb-8">Tour Quốc Tế</h2>
                <div class="grid grid-cols-2 gap-6">
                    <?php 
                    $inter = [
                        ["Đông Nam Á", "https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=500&auto=format&fit=crop&q=80"],
                        ["Đông Á", "https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=500&auto=format&fit=crop&q=80"],
                        ["Châu Âu", "https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=500&auto=format&fit=crop&q=80"],
                        ["Điểm Đến Khác", "https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500&auto=format&fit=crop&q=80"],
                    ];
                    foreach ($inter as $ic):
                    ?>
                        <a href="<?= BASE_URL ?>?act=tours&region=<?= urlencode($ic[0]) ?>" class="relative h-40 rounded-xl overflow-hidden shadow-lg group">
                            <img loading="lazy" class="w-full h-full object-cover transition-transform transform group-hover:scale-105" src="<?= $ic[1] ?>" alt="<?= e($ic[0]) ?>"
                                 srcset="<?= $ic[1] ?> 500w, <?= $ic[1] ?> 1000w" sizes="(max-width: 600px) 500px, 1000px">
                            <div class="absolute inset-0 bg-secondary/50 group-hover:bg-primary/50 transition-all"></div>
                            <h3 class="absolute inset-0 flex items-center justify-center text-white text-xl font-semibold"><?= e($ic[0]) ?></h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="bg-gray-100 py-16">
        <h2 class="text-3xl font-bold text-center text-secondary mb-10">Thống Kê Nhanh</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <div>
                <p class="text-5xl font-bold text-primary"><?= number_format($totalTours) ?>+</p>
                <p class="text-gray-600">Tour Có Sẵn</p>
            </div>
            <div>
                <p class="text-5xl font-bold text-primary"><?= number_format($totalCustomers) ?>+</p>
                <p class="text-gray-600">Khách Hàng Hài Lòng</p>
            </div>
            <div>
                <p class="text-5xl font-bold text-primary"><?= number_format($totalTrips) ?>+</p>
                <p class="text-gray-600">Chuyến Đi Thành Công</p>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-primary text-white py-16 px-8 rounded-xl text-center shadow-xl">
        <h2 class="text-3xl font-bold">Đăng Ký Nhận Tin</h2>
        <p class="mt-4">Nhận thông tin tour mới và ưu đãi đặc biệt từ chúng tôi</p>
        <form method="POST" action="" class="mt-6 max-w-lg mx-auto flex justify-center gap-4">
            <input class="w-full rounded-xl px-4 py-3 text-secondary focus:ring-2 focus:ring-white outline-none" placeholder="Nhập email của bạn" type="email" name="email" required>
            <button class="bg-accent hover:bg-yellow-400 text-white px-6 rounded-xl font-bold">Đăng ký</button>
        </form>
    </section>
</div>

<?php
// Xử lý form đăng ký nhận tin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Lưu vào cơ sở dữ liệu hoặc gửi email
        echo "<script>alert('Cảm ơn bạn đã đăng ký!');</script>";
    } else {
        echo "<script>alert('Email không hợp lệ!');</script>";
    }
}
?>
