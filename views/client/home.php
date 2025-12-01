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

<div class="max-w-[1440px] mx-auto">

    <!-- FEATURED TOURS -->
    <div class="px-10 py-14 space-y-20">
        
        <section>
            <h2 class="text-3xl font-bold text-center mb-10 text-secondary">
                Tour Nổi Bật & Khuyến Mãi
            </h2>

            <div class="relative">
                <div class="flex space-x-6 overflow-x-auto pb-4 -mx-10 px-10 snap-x snap-mandatory scrollbar-hide">

                    <?php if (!empty($featuredTours)): ?>
                        <?php foreach (array_slice($featuredTours, 0, 6) as $tour): ?>

                            <?php
                            $tourImage = getImageUrl($tour['hinhanh'] ?? '', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500&auto=format&fit=crop&q=80');
                            $tourUrl = BASE_URL . '?act=tour-detail&id=' . $tour['id_goi'];
                            $hasPromo = !empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1;
                            $priceUSD = formatPriceUSD($tour['giagoi'] ?? 0);
                            ?>
                            
                            <div class="snap-start flex-shrink-0 w-80 bg-white rounded-xl shadow-lg overflow-hidden 
                                        border border-gray-100 transition transform hover:-translate-y-2 hover:shadow-2xl">
                                
                                <div class="relative">
                                    <img loading="lazy" alt="<?= e($tour['tengoi']) ?>" 
                                         class="w-full h-52 object-cover" src="<?= e($tourImage) ?>">

                                    <?php if ($hasPromo): ?>
                                        <span class="absolute top-2 right-2 bg-accent text-secondary text-xs font-bold px-2 py-1 rounded shadow">
                                            Giảm 10%
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="p-5">
                                    <h3 class="font-semibold text-lg text-secondary">
                                        <?= e($tour['tengoi']) ?>
                                    </h3>

                                    <p class="text-primary text-xl font-bold mt-2">
                                        <?= $priceUSD ?>
                                        <span class="block text-gray-500 text-sm font-normal mt-1">
                                            <?= formatPrice($tour['giagoi']) ?>
                                        </span>
                                    </p>

                                    <a href="<?= $tourUrl ?>" 
                                       class="mt-5 block bg-primary hover:bg-primary-light text-white py-2 rounded-lg 
                                              text-center font-semibold transition shadow">
                                        Đặt ngay
                                    </a>
                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-gray-500">Chưa có tour nổi bật</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- DOMESTIC & INTERNATIONAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-20">

            <!-- Domestic -->
            <section>
                <h2 class="text-3xl font-bold text-center mb-8 text-secondary">Tour Trong Nước</h2>
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
                        <a href="<?= BASE_URL ?>?act=tours&region=<?= urlencode($d[0]) ?>"
                           class="relative h-40 rounded-xl overflow-hidden shadow group">
                            
                            <img loading="lazy" class="w-full h-full object-cover scale-105 group-hover:scale-110 transition" 
                                 src="<?= $d[1] ?>" alt="<?= e($d[0]) ?>">
                            
                            <div class="absolute inset-0 bg-secondary/50 group-hover:bg-primary/50 transition"></div>

                            <h3 class="absolute inset-0 flex items-center justify-center text-white text-xl font-semibold 
                                       drop-shadow-lg">
                                <?= e($d[0]) ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- International -->
            <section>
                <h2 class="text-3xl font-bold text-center mb-8 text-secondary">Tour Quốc Tế</h2>
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
                        <a href="<?= BASE_URL ?>?act=tours&region=<?= urlencode($ic[0]) ?>"
                           class="relative h-40 rounded-xl overflow-hidden shadow group">
                            
                            <img loading="lazy" class="w-full h-full object-cover scale-105 group-hover:scale-110 transition" 
                                 src="<?= $ic[1] ?>" alt="<?= e($ic[0]) ?>">

                            <div class="absolute inset-0 bg-secondary/50 group-hover:bg-primary/50 transition"></div>

                            <h3 class="absolute inset-0 flex items-center justify-center text-white text-xl font-semibold">
                                <?= e($ic[0]) ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>
    </div>

    <!-- LOCATION + STATS + NEWSLETTER -->
    <div class="bg-[#F0FDFC] px-10 py-16 space-y-20 rounded-t-3xl">

        <!-- Locations -->
        <section>
            <h2 class="text-3xl font-bold text-center mb-10 text-secondary">
                Khám Phá Theo Địa Điểm
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                $locations = [
                    ['Hà Nội', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400'],
                    ['Hồ Chí Minh', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=400'],
                    ['Đà Nẵng', 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400'],
                    ['Hội An', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400'],
                    ['Nha Trang', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400'],
                    ['Đà Lạt', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=400'],
                    ['Huế', 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400'],
                    ['Phú Quốc', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400'],
                ];

                foreach ($locations as $loc):
                ?>
                    <a href="<?= BASE_URL ?>?act=tours&city=<?= urlencode($loc[0]) ?>"
                       class="text-center group cursor-pointer">
                        
                        <img loading="lazy"
                             class="rounded-xl mb-3 shadow-lg w-full h-40 object-cover group-hover:scale-105 transition"
                             src="<?= e($loc[1]) ?>" alt="<?= e($loc[0]) ?>">
                        
                        <p class="font-semibold text-secondary"><?= e($loc[0]) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>


        <!-- Stats -->
        <section>
            <h2 class="text-3xl font-bold text-center mb-10 text-secondary">
                Thống Kê Nhanh
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

                <div>
                    <span class="material-icons text-5xl text-primary mb-2">public</span>
                    <p class="text-3xl font-bold text-secondary"><?= number_format($totalTours ?? 0) ?>+</p>
                    <p class="text-gray-500">Tour Có Sẵn</p>
                </div>

                <div>
                    <span class="material-icons text-5xl text-primary mb-2">groups</span>
                    <p class="text-3xl font-bold text-secondary"><?= number_format($totalCustomers ?? 100000) ?>+</p>
                    <p class="text-gray-500">Khách Hàng Hài Lòng</p>
                </div>

                <div>
                    <div class="flex justify-center text-yellow-400 mb-2">
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star_half</span>
                    </div>
                    <p class="text-3xl font-bold text-secondary">4.9/5</p>
                    <p class="text-gray-500">Đánh Giá Trung Bình</p>
                </div>

            </div>
        </section>

        <!-- Newsletter -->
        <section class="bg-primary text-white rounded-2xl p-12 text-center shadow-xl">
            <h2 class="text-3xl font-bold">Đăng Ký Nhận Tin</h2>
            <p class="mt-2 text-white/90">Nhận thông tin tour mới và ưu đãi đặc biệt</p>

            <form class="mt-6 max-w-lg mx-auto flex">
                <input class="w-full rounded-l-xl px-4 py-3 text-secondary focus:ring-2 focus:ring-white outline-none"
                       placeholder="Nhập email của bạn" type="email" required>
                
                <button class="bg-accent hover:bg-yellow-400 text-secondary px-6 rounded-r-xl font-bold transition">
                    Đăng ký
                </button>
            </form>
        </section>

    </div>

</div>
