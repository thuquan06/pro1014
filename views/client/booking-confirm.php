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

function getStatusText($status) {
    switch($status) {
        case 0: return 'Chờ xác nhận';
        case 1: return 'Đã xác nhận';
        case 2: return 'Hoàn thành';
        default: return 'Không xác định';
    }
}

function getStatusColor($status) {
    switch($status) {
        case 0: return 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200';
        case 1: return 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200';
        case 2: return 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200';
        default: return 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200';
    }
}
?>

<div class="max-w-[1440px] mx-auto px-4 md:px-10 py-12">
    <!-- Success Message -->
    <div class="max-w-3xl mx-auto text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900 mb-4">
            <i class="fas fa-check-circle text-4xl text-green-600 dark:text-green-400"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary">Đặt tour thành công!</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark">
            Cảm ơn bạn đã đặt tour với chúng tôi. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
        </p>
    </div>

    <!-- Booking Details -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Thông tin đặt tour</h2>
                    <p class="text-text-muted-light dark:text-text-muted-dark">
                        Mã đơn hàng: <span class="font-bold text-primary">#<?= e($hoadon['id_hoadon']) ?></span>
                    </p>
                </div>
                <div>
                    <span class="px-4 py-2 rounded-lg <?= getStatusColor($hoadon['trangthai'] ?? 0) ?> font-semibold">
                        <?= getStatusText($hoadon['trangthai'] ?? 0) ?>
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Tour Information -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Thông tin tour</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Tên tour:</p>
                            <p class="font-semibold"><?= e($tour['tengoi'] ?? 'N/A') ?></p>
                        </div>
                        <?php if (!empty($tour['vitri'])): ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Địa điểm:</p>
                            <p class="font-semibold"><?= e($tour['vitri']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($hoadon['ngayvao'])): ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Ngày khởi hành:</p>
                            <p class="font-semibold"><?= date('d/m/Y', strtotime($hoadon['ngayvao'])) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($hoadon['ngayra'])): ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Ngày kết thúc:</p>
                            <p class="font-semibold"><?= date('d/m/Y', strtotime($hoadon['ngayra'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Information -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Thông tin khách hàng</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Email:</p>
                            <p class="font-semibold"><?= e($hoadon['email_nguoidung'] ?? 'N/A') ?></p>
                        </div>
                        <?php if (!empty($hoadon['ghichu'])): 
                            $notes = explode("\n", $hoadon['ghichu']);
                            foreach ($notes as $note):
                                if (strpos($note, 'Tên khách hàng:') !== false):
                                    $name = str_replace('Tên khách hàng:', '', $note);
                        ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Họ tên:</p>
                            <p class="font-semibold"><?= e(trim($name)) ?></p>
                        </div>
                        <?php 
                                elseif (strpos($note, 'Số điện thoại:') !== false):
                                    $phone = str_replace('Số điện thoại:', '', $note);
                        ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Số điện thoại:</p>
                            <p class="font-semibold"><?= e(trim($phone)) ?></p>
                        </div>
                        <?php 
                                endif;
                            endforeach;
                        endif; ?>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Ngày đặt:</p>
                            <p class="font-semibold"><?= date('d/m/Y H:i', strtotime($hoadon['ngaydat'] ?? 'now')) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Number of People -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-xl font-bold mb-4">Số lượng người</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Người lớn:</p>
                        <p class="text-lg font-semibold"><?= e($hoadon['nguoilon'] ?? 0) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Trẻ em:</p>
                        <p class="text-lg font-semibold"><?= e($hoadon['treem'] ?? 0) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Trẻ nhỏ:</p>
                        <p class="text-lg font-semibold"><?= e($hoadon['trenho'] ?? 0) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Em bé:</p>
                        <p class="text-lg font-semibold"><?= e($hoadon['embe'] ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-xl font-bold mb-4">Tổng tiền</h3>
                <div class="space-y-2 mb-4">
                    <?php if (!empty($hoadon['nguoilon']) && $hoadon['nguoilon'] > 0): ?>
                    <div class="flex justify-between">
                        <span>Người lớn (<?= e($hoadon['nguoilon']) ?> người):</span>
                        <span class="font-semibold">
                            <?= formatPriceUSD(($tour['giagoi'] ?? 0) * $hoadon['nguoilon']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($hoadon['treem']) && $hoadon['treem'] > 0): ?>
                    <div class="flex justify-between">
                        <span>Trẻ em (<?= e($hoadon['treem']) ?> người):</span>
                        <span class="font-semibold">
                            <?= formatPriceUSD(($tour['giatreem'] ?? 0) * $hoadon['treem']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($hoadon['trenho']) && $hoadon['trenho'] > 0): ?>
                    <div class="flex justify-between">
                        <span>Trẻ nhỏ (<?= e($hoadon['trenho']) ?> người):</span>
                        <span class="font-semibold">
                            <?= formatPriceUSD(($tour['giatrenho'] ?? 0) * $hoadon['trenho']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold">Tổng cộng:</span>
                        <span class="text-3xl font-bold text-primary">
                            <?= formatPriceUSD($total) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Confirmation Notice -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6 mb-6 border-l-4 border-green-500">
            <div class="flex items-start">
                <i class="fas fa-envelope-circle-check text-green-600 dark:text-green-400 text-2xl mr-3 mt-1"></i>
                <div>
                    <h3 class="text-xl font-bold mb-2 text-green-800 dark:text-green-200">Email xác nhận đã được gửi!</h3>
                    <p class="text-green-700 dark:text-green-300 mb-2">
                        Chúng tôi đã gửi email xác nhận đến địa chỉ <strong><?= e($hoadon['email_nguoidung'] ?? '') ?></strong>.
                        Vui lòng kiểm tra hộp thư đến (và cả thư mục Spam) để xem chi tiết đơn đặt tour của bạn.
                    </p>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nếu không nhận được email trong vòng vài phút, vui lòng liên hệ với chúng tôi qua hotline.
                    </p>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">Hướng dẫn tiếp theo</h3>
            <ul class="space-y-2 text-text-muted-light dark:text-text-muted-dark">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Nhân viên của chúng tôi sẽ liên hệ với bạn trong vòng <strong>24 giờ</strong> để xác nhận thông tin và hướng dẫn thanh toán.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Vui lòng kiểm tra email thường xuyên để nhận thông tin cập nhật về tour của bạn.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email hoặc hotline.</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="<?= BASE_URL ?>?act=home" 
               class="flex-1 bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg text-center transition-colors">
                <i class="fas fa-home mr-2"></i>
                Về trang chủ
            </a>
            <a href="<?= BASE_URL ?>?act=tours" 
               class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-text-light dark:text-text-dark font-bold py-3 rounded-lg text-center transition-colors">
                <i class="fas fa-list mr-2"></i>
                Xem thêm tour
            </a>
        </div>
    </div>
</div>

