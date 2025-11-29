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
        <a href="<?= BASE_URL ?>?act=tour-detail&id=<?= $tour['id_goi'] ?>" class="text-primary hover:underline"><?= e($tour['tengoi']) ?></a>
        <span class="mx-2 text-text-muted-light dark:text-text-muted-dark">/</span>
        <span class="text-text-muted-light dark:text-text-muted-dark">Đặt tour</span>
    </nav>

    <!-- Header -->
    <section class="text-center mb-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary">Đặt tour</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark">
            <?= e($tour['tengoi']) ?>
        </p>
    </section>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold mb-6">Thông tin đặt tour</h2>
                <form method="POST" action="<?= BASE_URL ?>?act=booking-submit" id="bookingForm" class="space-y-6">
                    <input type="hidden" name="tour_id" value="<?= e($tour['id_goi']) ?>">
                    
                    <!-- Lịch khởi hành -->
                    <?php if (!empty($departurePlans)): ?>
                    <div>
                        <label for="departure_id" class="block text-sm font-semibold mb-2">
                            Chọn lịch khởi hành <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="departure_id" 
                            name="departure_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">-- Chọn lịch khởi hành --</option>
                            <?php foreach ($departurePlans as $plan): ?>
                                <option value="<?= $plan['id'] ?>" 
                                    <?= ($selectedDeparture && $selectedDeparture['id'] == $plan['id']) ? 'selected' : '' ?>
                                    data-date="<?= e($plan['ngay_khoi_hanh']) ?>"
                                    data-time="<?= e($plan['gio_khoi_hanh']) ?>"
                                    data-seats="<?= e($plan['so_cho_con_trong'] ?? 0) ?>"
                                >
                                    <?= date('d/m/Y', strtotime($plan['ngay_khoi_hanh'])) ?> 
                                    - <?= e($plan['gio_khoi_hanh']) ?> 
                                    (Còn <?= e($plan['so_cho_con_trong'] ?? 0) ?> chỗ)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Thông tin khách hàng -->
                    <div class="border-t pt-6">
                        <h3 class="text-xl font-bold mb-4">Thông tin khách hàng</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Nhập họ và tên"
                                />
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="your.email@example.com"
                                />
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-semibold mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0123 456 789"
                            />
                        </div>
                    </div>

                    <!-- Số lượng người -->
                    <div class="border-t pt-6">
                        <h3 class="text-xl font-bold mb-4">Số lượng người</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="nguoilon" class="block text-sm font-semibold mb-2">
                                    Người lớn <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="nguoilon" 
                                    name="nguoilon" 
                                    min="1"
                                    value="1"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="treem" class="block text-sm font-semibold mb-2">
                                    Trẻ em (2-11 tuổi)
                                </label>
                                <input 
                                    type="number" 
                                    id="treem" 
                                    name="treem" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="trenho" class="block text-sm font-semibold mb-2">
                                    Trẻ nhỏ (dưới 2 tuổi)
                                </label>
                                <input 
                                    type="number" 
                                    id="trenho" 
                                    name="trenho" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="embe" class="block text-sm font-semibold mb-2">
                                    Em bé (miễn phí)
                                </label>
                                <input 
                                    type="number" 
                                    id="embe" 
                                    name="embe" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                    onchange="calculateTotal()"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Phòng -->
                    <div class="border-t pt-6">
                        <h3 class="text-xl font-bold mb-4">Thông tin phòng</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="sophong" class="block text-sm font-semibold mb-2">
                                    Số phòng
                                </label>
                                <input 
                                    type="number" 
                                    id="sophong" 
                                    name="sophong" 
                                    min="1"
                                    value="1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                />
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        id="phongdon" 
                                        name="phongdon" 
                                        value="1"
                                        class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary"
                                    />
                                    <span class="ml-2 text-sm">Phòng đơn (nếu cần)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="border-t pt-6">
                        <label for="ghichu" class="block text-sm font-semibold mb-2">
                            Ghi chú thêm
                        </label>
                        <textarea 
                            id="ghichu" 
                            name="ghichu" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Dị ứng, yêu cầu đặc biệt, v.v..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t pt-6">
                        <button 
                            type="submit"
                            class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg transition-colors text-lg"
                        >
                            <i class="fas fa-calendar-check mr-2"></i>
                            Xác nhận đặt tour
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar: Tour Info & Price Summary -->
        <div class="lg:col-span-1">
            <div class="sticky top-4 space-y-6">
                <!-- Tour Summary -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h3 class="text-xl font-bold mb-4">Thông tin tour</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Tên tour:</p>
                            <p class="font-semibold"><?= e($tour['tengoi']) ?></p>
                        </div>
                        <?php if (!empty($tour['vitri'])): ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Địa điểm:</p>
                            <p class="font-semibold"><?= e($tour['vitri']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['songay'])): ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Thời gian:</p>
                            <p class="font-semibold"><?= e($tour['songay']) ?> ngày <?= e($tour['songay'] - 1) ?> đêm</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md border-2 border-primary">
                    <h3 class="text-xl font-bold mb-4">Tổng tiền</h3>
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span>Người lớn:</span>
                            <span class="font-semibold" id="price-adult">
                                <?= formatPriceUSD($tour['giagoi'] ?? 0) ?>
                            </span>
                        </div>
                        <?php if (!empty($tour['giatreem'])): ?>
                        <div class="flex justify-between">
                            <span>Trẻ em:</span>
                            <span class="font-semibold" id="price-child">
                                <?= formatPriceUSD($tour['giatreem']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['giatrenho'])): ?>
                        <div class="flex justify-between">
                            <span>Trẻ nhỏ:</span>
                            <span class="font-semibold" id="price-baby">
                                <?= formatPriceUSD($tour['giatrenho']) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Tổng cộng:</span>
                            <span class="text-2xl font-bold text-primary" id="total-price">
                                <?= formatPriceUSD($tour['giagoi'] ?? 0) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                    <h3 class="text-xl font-bold mb-4">Hỗ trợ</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-primary mr-3"></i>
                            <a href="tel:1900xxxx" class="text-primary hover:underline">1900 xxxx</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-primary mr-3"></i>
                            <a href="mailto:info@starvel.com" class="text-primary hover:underline">info@starvel.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Price data
const prices = {
    adult: <?= $tour['giagoi'] ?? 0 ?>,
    child: <?= $tour['giatreem'] ?? 0 ?>,
    baby: <?= $tour['giatrenho'] ?? 0 ?>
};

function formatPriceUSD(price) {
    if (!price || price === 0) return 'Liên hệ';
    const usdPrice = Math.round(price / 25000);
    return '$' + usdPrice.toLocaleString();
}

function calculateTotal() {
    const nguoilon = parseInt(document.getElementById('nguoilon').value) || 0;
    const treem = parseInt(document.getElementById('treem').value) || 0;
    const trenho = parseInt(document.getElementById('trenho').value) || 0;
    
    const total = (nguoilon * prices.adult) + (treem * prices.child) + (trenho * prices.baby);
    
    document.getElementById('total-price').textContent = formatPriceUSD(total);
}
</script>

