<?php
// Helper functions
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatPrice($price) {
    if (empty($price) || !is_numeric($price)) return '0';
    return number_format($price, 0, ',', '.');
}

function getImageUrl($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80';
    }
    if (preg_match('/^https?:\/\//', $imagePath)) {
        return $imagePath;
    }
    if (strpos($imagePath, BASE_URL) === false) {
        return BASE_URL . ltrim($imagePath, '/');
    }
    return $imagePath;
}

function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

// Tính tổng tiền
$giagoi = floatval($tour['giagoi'] ?? 0);
$giatreem = floatval($tour['giatreem'] ?? 0);
$giatrenho = floatval($tour['giatrenho'] ?? 0);
?>

<div class="max-w-[1440px] mx-auto px-10 py-12">
    <!-- Header -->
    <section class="text-center mb-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary">Đặt tour</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark">
            Vui lòng điền thông tin để hoàn tất đặt tour
        </p>
    </section>

    <!-- Error Message -->
    <?php if (isset($_SESSION['booking_error'])): ?>
        <div class="max-w-4xl mx-auto mb-6 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= e($_SESSION['booking_error']) ?>
        </div>
        <?php unset($_SESSION['booking_error']); ?>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="<?= BASE_URL ?>?act=booking-store" id="bookingForm" class="space-y-6">
                <input type="hidden" name="tour_id" value="<?= e($tour['id_goi']) ?>">
                
                <!-- Tour Info Card -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-map-marked-alt text-primary mr-2"></i>
                        Thông tin tour
                    </h2>
                    <div class="flex space-x-4">
                        <img 
                            alt="<?= e($tour['tengoi'] ?? '') ?>" 
                            class="w-32 h-32 object-cover rounded-lg" 
                            src="<?= e(getImageUrl($tour['hinhanh'] ?? '')) ?>"
                        />
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2"><?= e($tour['tengoi'] ?? '') ?></h3>
                            <p class="text-text-muted-light dark:text-text-muted-dark mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?= e($tour['vitri'] ?? '') ?>
                            </p>
                            <p class="text-text-muted-light dark:text-text-muted-dark">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <?= e($tour['songay'] ?? '') ?> ngày
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-user text-primary mr-2"></i>
                        Thông tin khách hàng
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="hoten" class="block text-sm font-semibold mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="hoten" 
                                name="hoten" 
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
                        <div>
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
                    </div>
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="phongdon" 
                                value="1"
                                class="mr-2 w-5 h-5"
                            />
                            <span>Yêu cầu phòng đơn</span>
                        </label>
                    </div>
                </div>

                <!-- Number of Guests -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-users text-primary mr-2"></i>
                        Số lượng khách
                    </h2>
                    <div class="grid md:grid-cols-3 gap-4">
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
                                Trẻ em (5-11 tuổi)
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
                                Trẻ nhỏ (2-4 tuổi)
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
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-calendar text-primary mr-2"></i>
                        Ngày đi / Ngày về
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="ngayvao" class="block text-sm font-semibold mb-2">
                                Ngày vào
                            </label>
                            <input 
                                type="date" 
                                id="ngayvao" 
                                name="ngayvao"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label for="ngayra" class="block text-sm font-semibold mb-2">
                                Ngày ra
                            </label>
                            <input 
                                type="date" 
                                id="ngayra" 
                                name="ngayra"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-primary mr-2"></i>
                        Ghi chú
                    </h2>
                    <textarea 
                        id="ghichu" 
                        name="ghichu" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent resize-vertical"
                        placeholder="Ghi chú thêm (dị ứng, yêu cầu đặc biệt...)"
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 px-6 rounded-lg transition-colors text-lg"
                >
                    <i class="fas fa-check-circle mr-2"></i>
                    Xác nhận đặt tour
                </button>
            </form>
        </div>

        <!-- Sidebar - Price Summary -->
        <div class="lg:col-span-1">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-2xl font-bold mb-4">Tổng tiền</h2>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between">
                        <span>Người lớn:</span>
                        <span id="price-nguoilon"><?= formatPrice($giagoi) ?> VNĐ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Trẻ em:</span>
                        <span id="price-treem">0 VNĐ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Trẻ nhỏ:</span>
                        <span id="price-trenho">0 VNĐ</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-300 dark:border-gray-600 pt-4 mb-4">
                    <div class="flex justify-between text-xl font-bold">
                        <span>Tổng cộng:</span>
                        <span id="total-price" class="text-primary"><?= formatPrice($giagoi) ?> VNĐ</span>
                    </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-4">
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                        <i class="fas fa-info-circle mr-2"></i>
                        Giá có thể thay đổi tùy theo lịch khởi hành. Vui lòng liên hệ để xác nhận giá chính xác.
                    </p>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Cần hỗ trợ?</h3>
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-2">
                        Hotline: <a href="tel:1900xxxx" class="text-primary font-semibold">1900 xxxx</a>
                    </p>
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                        Email: <a href="mailto:info@starvel.com" class="text-primary font-semibold">info@starvel.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const giagoi = <?= $giagoi ?>;
const giatreem = <?= $giatreem ?>;
const giatrenho = <?= $giatrenho ?>;

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function calculateTotal() {
    const nguoilon = parseInt(document.getElementById('nguoilon').value) || 0;
    const treem = parseInt(document.getElementById('treem').value) || 0;
    const trenho = parseInt(document.getElementById('trenho').value) || 0;
    
    const totalNguoilon = nguoilon * giagoi;
    const totalTreem = treem * giatreem;
    const totalTrenho = trenho * giatrenho;
    
    document.getElementById('price-nguoilon').textContent = formatPrice(totalNguoilon) + ' VNĐ';
    document.getElementById('price-treem').textContent = formatPrice(totalTreem) + ' VNĐ';
    document.getElementById('price-trenho').textContent = formatPrice(totalTrenho) + ' VNĐ';
    
    const total = totalNguoilon + totalTreem + totalTrenho;
    document.getElementById('total-price').textContent = formatPrice(total) + ' VNĐ';
}

// Initialize on page load
calculateTotal();
</script>

