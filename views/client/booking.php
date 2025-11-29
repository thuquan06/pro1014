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
    <nav class="mb-8 text-sm flex items-center space-x-2">
        <a href="<?= BASE_URL ?>?act=home" class="text-primary hover:text-primary/80 transition-colors">Trang chủ</a>
        <i class="fas fa-chevron-right text-xs text-text-muted-light dark:text-text-muted-dark"></i>
        <a href="<?= BASE_URL ?>?act=tours" class="text-primary hover:text-primary/80 transition-colors">Tour</a>
        <i class="fas fa-chevron-right text-xs text-text-muted-light dark:text-text-muted-dark"></i>
        <a href="<?= BASE_URL ?>?act=tour-detail&id=<?= $tour['id_goi'] ?>" class="text-primary hover:text-primary/80 transition-colors"><?= e($tour['tengoi']) ?></a>
        <i class="fas fa-chevron-right text-xs text-text-muted-light dark:text-text-muted-dark"></i>
        <span class="text-text-muted-light dark:text-text-muted-dark font-medium">Đặt tour</span>
    </nav>

    <!-- Header -->
    <section class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-full mb-4">
            <i class="fas fa-calendar-check text-2xl text-primary"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-3 text-primary">Đặt tour</h1>
        <p class="text-lg text-text-muted-light dark:text-text-muted-dark max-w-2xl mx-auto">
            <?= e($tour['tengoi']) ?>
        </p>
    </section>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg p-8 md:p-10 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center mb-8 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-list text-primary"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-text-light dark:text-text-dark">Thông tin đặt tour</h2>
                </div>
                
                <form method="POST" action="<?= BASE_URL ?>?act=booking-submit" id="bookingForm" class="space-y-8">
                    <input type="hidden" name="tour_id" value="<?= e($tour['id_goi']) ?>">
                    
                    <!-- Lịch khởi hành -->
                    <?php if (!empty($departurePlans)): ?>
                    <div>
                        <label for="departure_id" class="flex items-center text-sm font-semibold mb-3 text-text-light dark:text-text-dark">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>
                            Chọn lịch khởi hành <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select 
                                id="departure_id" 
                                name="departure_id"
                                required
                                class="w-full px-4 py-3 pl-10 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer"
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
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Thông tin khách hàng -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="flex items-center text-xl font-bold mb-6 text-text-light dark:text-text-dark">
                            <i class="fas fa-user-circle text-primary mr-2"></i>
                            Thông tin khách hàng
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        required
                                        class="w-full px-4 py-3 pl-10 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                        placeholder="Nhập họ và tên"
                                    />
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        required
                                        class="w-full px-4 py-3 pl-10 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                        placeholder="your.email@example.com"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone"
                                    required
                                    class="w-full px-4 py-3 pl-10 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                    placeholder="0123 456 789"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Số lượng người -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="flex items-center text-xl font-bold mb-6 text-text-light dark:text-text-dark">
                            <i class="fas fa-users text-primary mr-2"></i>
                            Số lượng người
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="nguoilon" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Người lớn <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="nguoilon" 
                                    name="nguoilon" 
                                    min="1"
                                    value="1"
                                    required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center font-semibold"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="treem" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Trẻ em (2-11 tuổi)
                                </label>
                                <input 
                                    type="number" 
                                    id="treem" 
                                    name="treem" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center font-semibold"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="trenho" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Trẻ nhỏ (dưới 2 tuổi)
                                </label>
                                <input 
                                    type="number" 
                                    id="trenho" 
                                    name="trenho" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center font-semibold"
                                    onchange="calculateTotal()"
                                />
                            </div>
                            <div>
                                <label for="embe" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Em bé (miễn phí)
                                </label>
                                <input 
                                    type="number" 
                                    id="embe" 
                                    name="embe" 
                                    min="0"
                                    value="0"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center font-semibold"
                                    onchange="calculateTotal()"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Phòng -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="flex items-center text-xl font-bold mb-6 text-text-light dark:text-text-dark">
                            <i class="fas fa-bed text-primary mr-2"></i>
                            Thông tin phòng
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="sophong" class="block text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                                    Số phòng
                                </label>
                                <input 
                                    type="number" 
                                    id="sophong" 
                                    name="sophong" 
                                    min="1"
                                    value="1"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center font-semibold"
                                />
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center cursor-pointer group">
                                    <input 
                                        type="checkbox" 
                                        id="phongdon" 
                                        name="phongdon" 
                                        value="1"
                                        class="w-5 h-5 text-primary border-gray-300 dark:border-gray-600 rounded focus:ring-primary cursor-pointer"
                                    />
                                    <span class="ml-2 text-sm text-text-light dark:text-text-dark group-hover:text-primary transition-colors">Phòng đơn (nếu cần)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <label for="ghichu" class="flex items-center text-sm font-semibold mb-2 text-text-light dark:text-text-dark">
                            <i class="fas fa-sticky-note text-primary mr-2"></i>
                            Ghi chú thêm
                        </label>
                        <textarea 
                            id="ghichu" 
                            name="ghichu" 
                            rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none"
                            placeholder="Dị ứng, yêu cầu đặc biệt, v.v..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <button 
                            type="submit"
                            class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 px-6 rounded-lg transition-all text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
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
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-info-circle text-primary text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Thông tin tour</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-text-muted-light dark:text-text-muted-dark mb-1">Tên tour:</p>
                            <p class="font-semibold text-text-light dark:text-text-dark"><?= e($tour['tengoi']) ?></p>
                        </div>
                        <?php if (!empty($tour['vitri'])): ?>
                        <div>
                            <p class="text-xs text-text-muted-light dark:text-text-muted-dark mb-1">Địa điểm:</p>
                            <p class="font-semibold text-text-light dark:text-text-dark">
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                                <?= e($tour['vitri']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['songay'])): ?>
                        <div>
                            <p class="text-xs text-text-muted-light dark:text-text-muted-dark mb-1">Thời gian:</p>
                            <p class="font-semibold text-text-light dark:text-text-dark">
                                <i class="fas fa-clock text-primary mr-1"></i>
                                <?= e($tour['songay']) ?> ngày <?= e($tour['songay'] - 1) ?> đêm
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="bg-gradient-to-br from-primary to-primary/80 rounded-xl p-6 shadow-xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Tổng tiền</h3>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                <span class="text-white/90">Người lớn:</span>
                                <span class="font-bold text-white" id="price-adult">
                                    <?= formatPriceUSD($tour['giagoi'] ?? 0) ?>
                                </span>
                            </div>
                            <?php if (!empty($tour['giatreem'])): ?>
                            <div class="flex justify-between items-center bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                <span class="text-white/90">Trẻ em:</span>
                                <span class="font-bold text-white" id="price-child">
                                    <?= formatPriceUSD($tour['giatreem']) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($tour['giatrenho'])): ?>
                            <div class="flex justify-between items-center bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                <span class="text-white/90">Trẻ nhỏ:</span>
                                <span class="font-bold text-white" id="price-baby">
                                    <?= formatPriceUSD($tour['giatrenho']) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="border-t border-white/20 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-white">Tổng cộng:</span>
                                <span class="text-3xl font-bold text-white" id="total-price">
                                    <?= formatPriceUSD($tour['giagoi'] ?? 0) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-headset text-primary text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Hỗ trợ</h3>
                    </div>
                    <div class="space-y-3">
                        <a href="tel:1900xxxx" class="flex items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-primary/10 transition-colors group">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-colors">
                                <i class="fas fa-phone text-primary group-hover:text-white"></i>
                            </div>
                            <span class="text-primary font-semibold group-hover:text-primary/80">1900 xxxx</span>
                        </a>
                        <a href="mailto:info@starvel.com" class="flex items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-primary/10 transition-colors group">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-colors">
                                <i class="fas fa-envelope text-primary group-hover:text-white"></i>
                            </div>
                            <span class="text-primary font-semibold group-hover:text-primary/80">info@starvel.com</span>
                        </a>
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
    
    const totalElement = document.getElementById('total-price');
    if (totalElement) {
        totalElement.textContent = formatPriceUSD(total);
        // Subtle animation
        totalElement.style.transform = 'scale(1.05)';
        setTimeout(() => {
            totalElement.style.transform = 'scale(1)';
        }, 200);
    }
}

// Form submit loading state
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    submitBtn.disabled = true;
});
</script>
