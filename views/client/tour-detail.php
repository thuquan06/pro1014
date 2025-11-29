<?php
// Helper functions
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8888/test23/');
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

<div class="max-w-[1440px] mx-auto px-4 md:px-10 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <a href="<?= BASE_URL ?>?act=home" class="text-primary hover:underline">Trang chủ</a>
        <span class="mx-2 text-text-muted-light dark:text-text-muted-dark">/</span>
        <a href="<?= BASE_URL ?>?act=tours" class="text-primary hover:underline">Tour</a>
        <span class="mx-2 text-text-muted-light dark:text-text-muted-dark">/</span>
        <span class="text-text-muted-light dark:text-text-muted-dark"><?= e($tour['tengoi']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tour Image & Gallery -->
            <div>
                <?php
                $mainImage = getImageUrl($tour['hinhanh'] ?? '', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80');
                ?>
                <img src="<?= e($mainImage) ?>" 
                     alt="<?= e($tour['tengoi']) ?>" 
                     class="w-full h-96 object-cover rounded-lg mb-4"/>
                
                <?php if (!empty($gallery) && count($gallery) > 0): ?>
                    <div class="grid grid-cols-4 gap-2">
                        <?php foreach (array_slice($gallery, 0, 4) as $img): ?>
                            <img src="<?= e(getImageUrl($img['duongdan_anh'] ?? '')) ?>" 
                                 alt="<?= e($img['mota_anh'] ?? '') ?>" 
                                 class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="openLightbox('<?= e(getImageUrl($img['duongdan_anh'] ?? '')) ?>')"/>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tour Title & Basic Info -->
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-4"><?= e($tour['tengoi']) ?></h1>
                
                <div class="flex flex-wrap gap-4 mb-6 text-sm">
                    <?php if (!empty($tour['ten_tinh'])): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <span><?= e($tour['ten_tinh']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($tour['songay'])): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <span><?= $tour['songay'] ?> ngày</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($tour['giodi'])): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-primary"></i>
                            <span><?= e($tour['giodi']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($tour['phuongtien'])): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-car text-primary"></i>
                            <span><?= e($tour['phuongtien']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($tour['vitri'])): ?>
                    <p class="text-text-muted-light dark:text-text-muted-dark mb-4">
                        <i class="fas fa-location-dot"></i> <?= e($tour['vitri']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Tour Description -->
            <?php if (!empty($tour['chitietgoi'])): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Giới thiệu tour</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <?= $tour['chitietgoi'] ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Itinerary -->
            <?php if (!empty($itinerary)): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-6">Lịch trình tour</h2>
                    <div class="space-y-6">
                        <?php foreach ($itinerary as $day): ?>
                            <div class="border-l-4 border-primary pl-4 pb-6 last:pb-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="bg-primary text-white font-bold px-3 py-1 rounded">Ngày <?= $day['ngay_thu'] ?></span>
                                    <?php if (!empty($day['tieude'])): ?>
                                        <h3 class="text-xl font-semibold"><?= e($day['tieude']) ?></h3>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($day['mota'])): ?>
                                    <p class="text-text-muted-light dark:text-text-muted-dark mb-3"><?= nl2br(e($day['mota'])) ?></p>
                                <?php endif; ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <?php if (!empty($day['diemden'])): ?>
                                        <div>
                                            <strong class="text-primary">Điểm đến:</strong> <?= e($day['diemden']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day['thoiluong'])): ?>
                                        <div>
                                            <strong class="text-primary">Thời lượng:</strong> <?= e($day['thoiluong']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day['hoatdong'])): ?>
                                        <div>
                                            <strong class="text-primary">Hoạt động:</strong> <?= e($day['hoatdong']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day['buaan'])): ?>
                                        <div>
                                            <strong class="text-primary">Bữa ăn:</strong> <?= e($day['buaan']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day['noinghi'])): ?>
                                        <div>
                                            <strong class="text-primary">Nơi nghỉ:</strong> <?= e($day['noinghi']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($day['ghichu_hdv'])): ?>
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <strong class="text-yellow-800 dark:text-yellow-200">Ghi chú HDV:</strong>
                                        <p class="text-yellow-700 dark:text-yellow-300"><?= nl2br(e($day['ghichu_hdv'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Program Details -->
            <?php if (!empty($tour['chuongtrinh'])): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Chương trình tour</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <?= $tour['chuongtrinh'] ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Notes -->
            <?php if (!empty($tour['luuy'])): ?>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-6 shadow-md border-l-4 border-yellow-500">
                    <h2 class="text-2xl font-bold mb-4 text-yellow-800 dark:text-yellow-200">
                        <i class="fas fa-exclamation-triangle"></i> Lưu ý
                    </h2>
                    <div class="prose dark:prose-invert max-w-none text-yellow-700 dark:text-yellow-300">
                        <?= $tour['luuy'] ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="sticky top-4 space-y-6">
                <!-- Price Card -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md border-2 border-primary">
                    <h3 class="text-xl font-bold mb-4">Giá tour</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span>Người lớn:</span>
                            <span class="text-xl font-bold text-primary">
                                <?= formatPriceUSD($tour['giagoi'] ?? 0) ?>
                            </span>
                        </div>
                        <?php if (!empty($tour['giatreem'])): ?>
                            <div class="flex justify-between items-center">
                                <span>Trẻ em:</span>
                                <span class="text-lg font-semibold">
                                    <?= formatPriceUSD($tour['giatreem']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['giatrenho'])): ?>
                            <div class="flex justify-between items-center">
                                <span>Trẻ nhỏ:</span>
                                <span class="text-lg font-semibold">
                                    <?= formatPriceUSD($tour['giatrenho']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1): ?>
                        <div class="bg-red-500 text-white text-center py-2 rounded mb-4 font-bold">
                            <i class="fas fa-fire"></i> Đang khuyến mãi
                        </div>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>?act=booking&tour_id=<?= $tour['id_goi'] ?>" 
                       class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg block text-center transition-colors mb-4">
                        <i class="fas fa-calendar-check"></i> Đặt tour ngay
                    </a>

                    <div class="text-center text-sm text-text-muted-light dark:text-text-muted-dark">
                        <i class="fas fa-phone"></i> Hotline: <a href="tel:1900xxxx" class="text-primary">1900 xxxx</a>
                    </div>
                </div>

                <!-- Tour Info Card -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h3 class="text-xl font-bold mb-4">Thông tin tour</h3>
                    <div class="space-y-3 text-sm">
                        <?php if (!empty($tour['mato'])): ?>
                            <div class="flex justify-between">
                                <span class="text-text-muted-light dark:text-text-muted-dark">Mã tour:</span>
                                <span class="font-semibold"><?= e($tour['mato']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['noixuatphat'])): ?>
                            <div class="flex justify-between">
                                <span class="text-text-muted-light dark:text-text-muted-dark">Nơi xuất phát:</span>
                                <span class="font-semibold"><?= e($tour['noixuatphat']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['quocgia'])): ?>
                            <div class="flex justify-between">
                                <span class="text-text-muted-light dark:text-text-muted-dark">Quốc gia:</span>
                                <span class="font-semibold"><?= e($tour['quocgia']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['socho'])): ?>
                            <div class="flex justify-between">
                                <span class="text-text-muted-light dark:text-text-muted-dark">Số chỗ:</span>
                                <span class="font-semibold"><?= $tour['socho'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Departure Plans -->
                <?php if (!empty($departurePlans)): ?>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                        <h3 class="text-xl font-bold mb-4">Lịch khởi hành</h3>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <?php foreach ($departurePlans as $plan): ?>
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                    <div class="font-semibold mb-1">
                                        <?= date('d/m/Y', strtotime($plan['ngay_khoi_hanh'])) ?>
                                    </div>
                                    <?php if (!empty($plan['gio_khoi_hanh'])): ?>
                                        <div class="text-sm text-text-muted-light dark:text-text-muted-dark">
                                            <i class="fas fa-clock"></i> <?= e($plan['gio_khoi_hanh']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($plan['so_cho_con_lai'])): ?>
                                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            Còn <?= $plan['so_cho_con_lai'] ?> chỗ
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full object-contain"/>
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
}
</script>

