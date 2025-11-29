<?php
// Helper functions
// BASE_URL should already be defined from env.php
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

function buildQueryString($params) {
    $query = [];
    foreach ($params as $key => $value) {
        if (!empty($value)) {
            $query[$key] = $value;
        }
    }
    return !empty($query) ? '&' . http_build_query($query) : '';
}
?>

<div class="max-w-[1440px] mx-auto px-4 md:px-10 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Danh Sách Tour</h1>
        <p class="text-text-muted-light dark:text-text-muted-dark">
            Tìm thấy <strong><?= $totalTours ?></strong> tour
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4">Bộ Lọc</h2>
                
                <!-- Filter by Type -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Loại Tour</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="" 
                                   <?= empty($filters['type']) ? 'checked' : '' ?>
                                   onchange="updateFilter('type', this.value)"
                                   class="mr-2">
                            <span>Tất cả</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="domestic"
                                   <?= $filters['type'] === 'domestic' ? 'checked' : '' ?>
                                   onchange="updateFilter('type', this.value)"
                                   class="mr-2">
                            <span>Trong nước</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="international"
                                   <?= $filters['type'] === 'international' ? 'checked' : '' ?>
                                   onchange="updateFilter('type', this.value)"
                                   class="mr-2">
                            <span>Quốc tế</span>
                        </label>
                    </div>
                </div>

                <!-- Filter by Province -->
                <?php if (!empty($provinces)): ?>
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Tỉnh/Thành phố</h3>
                    <select onchange="updateFilter('province', this.value)" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark">
                        <option value="">Tất cả</option>
                        <?php foreach ($provinces as $provinceName): ?>
                            <option value="<?= e($provinceName) ?>" 
                                    <?= $filters['province'] === $provinceName ? 'selected' : '' ?>>
                                <?= e($provinceName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Filter by Promo -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Khuyến mãi</h3>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               <?= $filters['promo'] == '1' ? 'checked' : '' ?>
                               onchange="updateFilter('promo', this.checked ? '1' : '')"
                               class="mr-2">
                        <span>Chỉ tour khuyến mãi</span>
                    </label>
                </div>

                <!-- Reset Filters -->
                <button onclick="resetFilters()" 
                        class="w-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-text-light dark:text-text-dark font-semibold py-2 rounded-lg transition-colors">
                    Xóa bộ lọc
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Sort & View Options -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-text-muted-light dark:text-text-muted-dark">Sắp xếp:</span>
                    <select onchange="updateFilter('sort', this.value)"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark">
                        <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Giá thấp → cao</option>
                        <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Giá cao → thấp</option>
                    </select>
                </div>
            </div>

            <!-- Tours Grid -->
            <?php if (!empty($tours)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <?php foreach ($tours as $tour): ?>
                        <?php
                        $tourImage = getImageUrl($tour['hinhanh'] ?? '', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&q=80');
                        $tourUrl = BASE_URL . '?act=tour-detail&id=' . $tour['id_goi'];
                        $hasPromo = !empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1;
                        $priceUSD = formatPriceUSD($tour['giagoi'] ?? 0);
                        $priceVND = formatPrice($tour['giagoi'] ?? 0);
                        $days = $tour['songay'] ?? 'N/A';
                        $location = $tour['vitri'] ?? 'N/A';
                        $province = $tour['ten_tinh'] ?? '';
                        ?>
                        <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden group">
                            <div class="relative">
                                <img alt="<?= e($tour['tengoi']) ?>" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" 
                                     src="<?= e($tourImage) ?>"/>
                                <?php if ($hasPromo): ?>
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        Khuyến mãi
                                    </span>
                                <?php endif; ?>
                                <?php if ($province): ?>
                                    <span class="absolute top-2 left-2 bg-primary/90 text-white text-xs font-semibold px-2 py-1 rounded">
                                        <?= e($province) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-2 line-clamp-2 min-h-[3.5rem]">
                                    <?= e($tour['tengoi']) ?>
                                </h3>
                                <div class="flex items-center gap-2 text-sm text-text-muted-light dark:text-text-muted-dark mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= e($location) ?></span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-text-muted-light dark:text-text-muted-dark mb-3">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><?= $days ?> ngày</span>
                                    <?php if (!empty($tour['giodi'])): ?>
                                        <span class="mx-1">•</span>
                                        <i class="fas fa-clock"></i>
                                        <span><?= e($tour['giodi']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-baseline gap-2 mb-3">
                                    <p class="text-xl font-bold text-primary"><?= $priceUSD ?></p>
                                    <p class="text-xs text-text-muted-light dark:text-text-muted-dark"><?= $priceVND ?></p>
                                </div>
                                <a href="<?= $tourUrl ?>" 
                                   class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2 rounded block text-center transition-colors">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex justify-center items-center gap-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="?act=tours<?= buildQueryString(array_merge($filters, ['page' => $currentPage - 1])) ?>" 
                               class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-chevron-left"></i> Trước
                            </a>
                        <?php endif; ?>

                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="?act=tours<?= buildQueryString(array_merge($filters, ['page' => $i])) ?>" 
                               class="px-4 py-2 <?= $i == $currentPage ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?act=tours<?= buildQueryString(array_merge($filters, ['page' => $currentPage + 1])) ?>" 
                               class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                Sau <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-search text-5xl text-text-muted-light dark:text-text-muted-dark mb-4"></i>
                    <p class="text-xl font-semibold mb-2">Không tìm thấy tour nào</p>
                    <p class="text-text-muted-light dark:text-text-muted-dark mb-4">
                        Thử thay đổi bộ lọc để tìm thêm tour khác
                    </p>
                    <button onclick="resetFilters()" 
                            class="bg-primary hover:bg-primary/90 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                        Xóa bộ lọc
                    </button>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function updateFilter(key, value) {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    
    if (value === '' || value === null) {
        params.delete(key);
    } else {
        params.set(key, value);
    }
    
    // Reset to page 1 when filter changes
    params.delete('page');
    
    window.location.href = '?act=tours&' + params.toString();
}

function resetFilters() {
    window.location.href = '?act=tours';
}
</script>

