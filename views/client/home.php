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
    $usdPrice = round($price / 25000); // Convert VND to USD (approximate)
    return '$' . number_format($usdPrice, 0);
}

function getImageUrl($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?? BASE_URL . 'assets/images/default-tour.jpg';
    }
    if (preg_match('/^https?:\/\//', $imagePath)) {
        return $imagePath;
    }
    if (strpos($imagePath, BASE_URL) === false) {
        return BASE_URL . ltrim($imagePath, '/');
    }
    return $imagePath;
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<div class="max-w-[1440px] mx-auto">
    <!-- Featured Tours Section -->
    <div class="px-10 py-12 space-y-16">
        <section>
            <h2 class="text-3xl font-bold text-center mb-8">Tour Nổi Bật & Khuyến Mãi</h2>
            <div class="relative">
                <div class="flex space-x-6 overflow-x-auto pb-4 -mx-10 px-10 snap-x snap-mandatory">
                    <?php if (!empty($featuredTours)): ?>
                        <?php foreach (array_slice($featuredTours, 0, 6) as $tour): ?>
                            <?php
                            $tourImage = getImageUrl($tour['hinhanh'] ?? '', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&q=80');
                            $tourUrl = BASE_URL . '?act=tour-detail&id=' . $tour['id_goi'];
                            $hasPromo = !empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1;
                            $priceUSD = formatPriceUSD($tour['giagoi'] ?? 0);
                            ?>
                            <div class="snap-start flex-shrink-0 w-72 bg-surface-light dark:bg-surface-dark rounded-lg shadow-md overflow-hidden">
                                <div class="relative">
                                    <img alt="<?= e($tour['tengoi']) ?>" class="w-full h-48 object-cover" src="<?= e($tourImage) ?>"/>
                                    <?php if ($hasPromo): ?>
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Giảm 10%</span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg"><?= e($tour['tengoi']) ?></h3>
                                    <p class="text-lg font-bold text-primary mt-2"><?= $priceUSD ?></p>
                                    <a href="<?= $tourUrl ?>" class="mt-4 w-full bg-primary text-white font-bold py-2 rounded block text-center">Đặt ngay</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-text-muted-light dark:text-text-muted-dark">Chưa có tour nổi bật</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Domestic & International Tours -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            <section>
                <h2 class="text-3xl font-bold text-center mb-8">Tour Trong Nước</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Miền Bắc" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Miền Bắc</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Miền Trung" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Miền Trung</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Miền Nam" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Miền Nam</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Ven Biển" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Ven Biển</h3>
                        </div>
                    </div>
                </div>
            </section>
            
            <section>
                <h2 class="text-3xl font-bold text-center mb-8">Tour Quốc Tế</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Đông Nam Á" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Đông Nam Á</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Đông Á" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Đông Á</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Châu Âu" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Châu Âu</h3>
                        </div>
                    </div>
                    <div class="relative rounded-lg overflow-hidden h-40 group">
                        <img alt="Điểm Đến Khác" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=400&q=80"/>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <h3 class="text-white text-xl font-bold">Điểm Đến Khác</h3>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Explore by Location & Statistics -->
    <div class="bg-teal-50 dark:bg-teal-900/20 px-10 py-12 space-y-16">
        <!-- Explore by Location -->
        <section>
            <h2 class="text-3xl font-bold text-center mb-8">Khám Phá Theo Địa Điểm</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                $locations = [
                    ['name' => 'Hà Nội', 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=300&q=80'],
                    ['name' => 'Hồ Chí Minh', 'image' => 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=300&q=80'],
                    ['name' => 'Đà Nẵng', 'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=300&q=80'],
                    ['name' => 'Hội An', 'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&q=80'],
                    ['name' => 'Nha Trang', 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=300&q=80'],
                    ['name' => 'Đà Lạt', 'image' => 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=300&q=80'],
                    ['name' => 'Huế', 'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=300&q=80'],
                    ['name' => 'Phú Quốc', 'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&q=80'],
                ];
                foreach ($locations as $location):
                ?>
                    <div class="text-center group cursor-pointer">
                        <img alt="<?= e($location['name']) ?>" class="rounded-lg mb-2 shadow-md w-full h-40 object-cover" src="<?= e($location['image']) ?>"/>
                        <p class="font-semibold"><?= e($location['name']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Statistics -->
        <section>
            <h2 class="text-3xl font-bold text-center mb-8">Thống Kê Nhanh</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <span class="material-icons text-5xl text-primary mb-2">public</span>
                    <p class="text-3xl font-bold"><?= number_format($totalTours ?? 0) ?>+</p>
                    <p class="text-text-muted-light dark:text-text-muted-dark">Tour Có Sẵn</p>
                </div>
                <div>
                    <span class="material-icons text-5xl text-primary mb-2">groups</span>
                    <p class="text-3xl font-bold"><?= number_format($totalCustomers ?? 100000) ?>+</p>
                    <p class="text-text-muted-light dark:text-text-muted-dark">Khách Hàng Hài Lòng</p>
                </div>
                <div>
                    <div class="flex justify-center text-yellow-400 mb-2">
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star</span>
                        <span class="material-icons text-3xl">star_half</span>
                    </div>
                    <p class="text-3xl font-bold">4.9/5</p>
                    <p class="text-text-muted-light dark:text-text-muted-dark">Đánh Giá Trung Bình</p>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="bg-primary/90 text-white rounded-lg p-12 text-center">
            <h2 class="text-3xl font-bold">Đăng Ký Nhận Tin</h2>
            <p class="mt-2 opacity-90">Nhận thông tin tour mới và khuyến mãi</p>
            <form class="mt-6 max-w-lg mx-auto flex" action="<?= BASE_URL ?>?act=newsletter-subscribe" method="POST">
                <input class="w-full rounded-l-lg border-0 focus:ring-2 focus:ring-white text-text-light px-4 py-2" 
                       placeholder="Nhập địa chỉ email của bạn" type="email" name="email" required/>
                <button class="bg-primary text-white font-bold px-6 rounded-r-lg" type="submit">Đăng ký</button>
            </form>
        </section>
    </div>
</div>
