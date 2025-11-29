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

function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y H:i', $timestamp);
}

function getStatusText($status) {
    $statuses = [
        0 => ['text' => 'Chờ xác nhận', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'],
        1 => ['text' => 'Đã xác nhận', 'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'],
        2 => ['text' => 'Đã thanh toán', 'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
        3 => ['text' => 'Đã hủy', 'class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'],
    ];
    return $statuses[$status] ?? $statuses[0];
}

$statusInfo = getStatusText($hoadon['trangthai'] ?? 0);
?>

<div class="max-w-4xl mx-auto px-10 py-12">
    <!-- Success Message -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full mb-4">
            <i class="fas fa-check-circle text-5xl text-green-600 dark:text-green-400"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary">Đặt tour thành công!</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark">
            Cảm ơn bạn đã đặt tour với StarVel Travel
        </p>
    </div>

    <!-- Booking Info Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Thông tin đơn hàng</h2>
            <span class="px-4 py-2 rounded-lg <?= $statusInfo['class'] ?> font-semibold">
                <?= e($statusInfo['text']) ?>
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Mã đơn hàng</p>
                <p class="text-xl font-bold text-primary">#<?= e($hoadon['id_hoadon']) ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Ngày đặt</p>
                <p class="text-lg font-semibold"><?= formatDate($hoadon['ngaydat'] ?? '') ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Email</p>
                <p class="text-lg"><?= e($hoadon['email_nguoidung'] ?? '') ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Tour</p>
                <p class="text-lg font-semibold"><?= e($tour['tengoi'] ?? '') ?></p>
            </div>
        </div>
    </div>

    <!-- Guest Info -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8 mb-6">
        <h2 class="text-2xl font-bold mb-4">Thông tin khách</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Người lớn</p>
                <p class="text-lg font-semibold"><?= e($hoadon['nguoilon'] ?? 0) ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Trẻ em</p>
                <p class="text-lg font-semibold"><?= e($hoadon['treem'] ?? 0) ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Trẻ nhỏ</p>
                <p class="text-lg font-semibold"><?= e($hoadon['trenho'] ?? 0) ?></p>
            </div>
            <div>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark mb-1">Số phòng</p>
                <p class="text-lg font-semibold"><?= e($hoadon['sophong'] ?? 1) ?></p>
            </div>
        </div>
    </div>

    <!-- Price Summary -->
    <?php
    $giagoi = floatval($tour['giagoi'] ?? 0);
    $giatreem = floatval($tour['giatreem'] ?? 0);
    $giatrenho = floatval($tour['giatrenho'] ?? 0);
    $nguoilon = intval($hoadon['nguoilon'] ?? 0);
    $treem = intval($hoadon['treem'] ?? 0);
    $trenho = intval($hoadon['trenho'] ?? 0);
    $total = ($nguoilon * $giagoi) + ($treem * $giatreem) + ($trenho * $giatrenho);
    ?>
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8 mb-6">
        <h2 class="text-2xl font-bold mb-4">Tổng tiền</h2>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between">
                <span>Người lớn (<?= $nguoilon ?> người):</span>
                <span><?= formatPrice($nguoilon * $giagoi) ?> VNĐ</span>
            </div>
            <?php if ($treem > 0): ?>
            <div class="flex justify-between">
                <span>Trẻ em (<?= $treem ?> người):</span>
                <span><?= formatPrice($treem * $giatreem) ?> VNĐ</span>
            </div>
            <?php endif; ?>
            <?php if ($trenho > 0): ?>
            <div class="flex justify-between">
                <span>Trẻ nhỏ (<?= $trenho ?> người):</span>
                <span><?= formatPrice($trenho * $giatrenho) ?> VNĐ</span>
            </div>
            <?php endif; ?>
        </div>
        <div class="border-t border-gray-300 dark:border-gray-600 pt-4">
            <div class="flex justify-between text-xl font-bold">
                <span>Tổng cộng:</span>
                <span class="text-primary"><?= formatPrice($total) ?> VNĐ</span>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
            Hướng dẫn tiếp theo
        </h3>
        <ul class="space-y-2 text-text-muted-light dark:text-text-muted-dark">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                <span>Chúng tôi sẽ gửi email xác nhận đến địa chỉ email của bạn trong vòng 24 giờ.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                <span>Vui lòng kiểm tra email và làm theo hướng dẫn thanh toán.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                <span>Nếu có thắc mắc, vui lòng liên hệ hotline <strong>1900 xxxx</strong> hoặc email <strong>info@starvel.com</strong></span>
            </li>
        </ul>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-4 justify-center">
        <a 
            href="<?= BASE_URL ?>?act=home" 
            class="bg-primary hover:bg-primary/90 text-white font-semibold px-8 py-3 rounded-lg transition-colors"
        >
            <i class="fas fa-home mr-2"></i>
            Về trang chủ
        </a>
        <a 
            href="<?= BASE_URL ?>?act=tours" 
            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors"
        >
            <i class="fas fa-list mr-2"></i>
            Xem thêm tour
        </a>
        <a 
            href="<?= BASE_URL ?>?act=contact" 
            class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-text-light dark:text-text-dark font-semibold px-8 py-3 rounded-lg transition-colors"
        >
            <i class="fas fa-envelope mr-2"></i>
            Liên hệ
        </a>
    </div>
</div>

