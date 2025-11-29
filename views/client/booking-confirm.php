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

        <!-- Payment Section -->
        <?php 
        $paymentStatus = $hoadon['trang_thai_thanh_toan'] ?? 0;
        $paymentMethod = $hoadon['phuong_thuc_thanh_toan'] ?? null;
        $showPayment = ($paymentStatus == 0 || $paymentStatus == 2); // Chưa thanh toán hoặc đang xử lý
        $autoCreatePayment = $autoCreatePayment ?? false; // Tự động tạo payment request
        $paymentRequired = $paymentRequired ?? false; // Yêu cầu thanh toán
        ?>
        
        <?php if ($showPayment && $total > 0): ?>
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 dark:from-pink-900/20 dark:to-purple-900/20 rounded-lg p-8 mb-6 border-2 border-pink-200 dark:border-pink-800">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold mb-2 text-primary">
                    <?= $paymentRequired ? 'Thanh toán để hoàn tất đặt tour' : 'Thanh toán đơn hàng' ?>
                </h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    <?php if ($paymentRequired): ?>
                        Vui lòng thanh toán để hoàn tất đặt tour. Đơn hàng của bạn sẽ được xác nhận sau khi thanh toán thành công.
                    <?php else: ?>
                        Thanh toán nhanh chóng và an toàn qua MoMo
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if ($paymentRequired && $paymentStatus == 0): ?>
            <div class="bg-yellow-100 dark:bg-yellow-900/30 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-bold text-yellow-800 dark:text-yellow-200">Thanh toán bắt buộc</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Vui lòng thanh toán để hoàn tất đặt tour. Đơn hàng sẽ được xác nhận sau khi thanh toán thành công.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payment Status Messages -->
            <?php if (isset($_GET['payment'])): ?>
                <?php if ($_GET['payment'] == 'thanh-toan-thanh-cong'): ?>
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl mr-3"></i>
                        <div>
                            <p class="font-bold text-green-800 dark:text-green-200">Thanh toán thành công!</p>
                            <p class="text-sm text-green-700 dark:text-green-300">Đơn hàng của bạn đã được thanh toán thành công.</p>
                        </div>
                    </div>
                </div>
                <?php elseif ($_GET['payment'] == 'thanh-toan-that-bai'): ?>
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl mr-3"></i>
                        <div>
                            <p class="font-bold text-red-800 dark:text-red-200">Thanh toán thất bại!</p>
                            <p class="text-sm text-red-700 dark:text-red-300">Vui lòng thử lại hoặc liên hệ hỗ trợ.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Payment Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Tổng tiền cần thanh toán:</span>
                    <span class="text-2xl font-bold text-primary"><?= formatPriceUSD($total) ?></span>
                </div>
                <?php if ($paymentStatus == 2): ?>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded mb-4">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <i class="fas fa-clock mr-2"></i>
                        Đang chờ thanh toán...
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- QR Code Display (if available) -->
            <div id="qr-code-container" class="hidden mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center">
                    <h4 class="text-lg font-bold mb-4">Quét mã QR để thanh toán</h4>
                    <div class="flex justify-center mb-4">
                        <img id="qr-code-image" src="" alt="QR Code" class="max-w-xs border-2 border-gray-200 dark:border-gray-700 rounded-lg">
                    </div>
                    <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                        Mở ứng dụng MoMo và quét mã QR để thanh toán
                    </p>
                </div>
            </div>

            <!-- Payment Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button id="btn-payment-momo" 
                        onclick="createMoMoPayment(<?= $hoadon['id_hoadon'] ?>)"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    Thanh toán bằng MoMo
                </button>
                <button id="btn-show-qr" 
                        onclick="showQRCode(<?= $hoadon['id_hoadon'] ?>)"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-text-light dark:text-text-dark font-bold py-4 px-6 rounded-lg transition-colors">
                    <i class="fas fa-qrcode mr-2"></i>
                    Xem mã QR
                </button>
            </div>

            <!-- Payment Instructions -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h4 class="font-bold mb-2">Hướng dẫn thanh toán:</h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-text-muted-light dark:text-text-muted-dark">
                    <li>Nhấn nút "Thanh toán bằng MoMo" hoặc "Xem mã QR"</li>
                    <li>Mở ứng dụng MoMo trên điện thoại</li>
                    <li>Quét mã QR hoặc nhấn vào link thanh toán</li>
                    <li>Xác nhận thanh toán trong ứng dụng MoMo</li>
                    <li>Hệ thống sẽ tự động cập nhật trạng thái thanh toán</li>
                </ol>
            </div>
        </div>
        <?php elseif ($paymentStatus == 1): ?>
        <!-- Payment Success -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6 mb-6 border-l-4 border-green-500">
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-2xl mr-3 mt-1"></i>
                <div>
                    <h3 class="text-xl font-bold mb-2 text-green-800 dark:text-green-200">Đã thanh toán thành công!</h3>
                    <p class="text-green-700 dark:text-green-300 mb-2">
                        Đơn hàng của bạn đã được thanh toán thành công qua <?= $paymentMethod == 'momo' ? 'MoMo' : 'phương thức khác' ?>.
                    </p>
                    <?php if (!empty($hoadon['ma_giao_dich_momo'])): ?>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-receipt mr-1"></i>
                        Mã giao dịch: <strong><?= e($hoadon['ma_giao_dich_momo']) ?></strong>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($hoadon['ngay_thanh_toan'])): ?>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-calendar mr-1"></i>
                        Ngày thanh toán: <?= date('d/m/Y H:i', strtotime($hoadon['ngay_thanh_toan'])) ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Email Confirmation Notice (chỉ hiển thị sau khi thanh toán thành công) -->
        <?php if ($paymentStatus == 1): ?>
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
        <?php endif; ?>

        <!-- Next Steps -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">Hướng dẫn tiếp theo</h3>
            <ul class="space-y-2 text-text-muted-light dark:text-text-muted-dark">
                <?php if ($paymentStatus == 1): ?>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Đơn hàng của bạn đã được thanh toán và xác nhận thành công!</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Nhân viên của chúng tôi sẽ liên hệ với bạn trong vòng <strong>24 giờ</strong> để xác nhận thông tin chi tiết.</span>
                </li>
                <?php else: ?>
                <li class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                    <span>Vui lòng hoàn tất thanh toán để xác nhận đơn hàng. Đơn hàng sẽ được xác nhận sau khi thanh toán thành công.</span>
                </li>
                <?php endif; ?>
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

<script>
// Tự động tạo payment request khi vào trang (nếu yêu cầu)
<?php if ($autoCreatePayment && $paymentStatus == 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    // Tự động tạo payment request sau 1 giây
    setTimeout(function() {
        createMoMoPayment(<?= $hoadon['id_hoadon'] ?>, true);
    }, 1000);
});
<?php endif; ?>

// Tạo yêu cầu thanh toán MoMo
function createMoMoPayment(hoadonId, autoCreate = false) {
    const btn = document.getElementById('btn-payment-momo');
    let originalText = '';
    
    if (btn) {
        originalText = btn.innerHTML;
        // Disable button và hiển thị loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    } else if (!autoCreate) {
        // Nếu không phải auto create và không có button, hiển thị loading ở nơi khác
        const paymentSection = document.querySelector('.bg-gradient-to-r.from-pink-50');
        if (paymentSection) {
            paymentSection.innerHTML = '<div class="text-center p-8"><i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i><p class="text-lg">Đang tạo yêu cầu thanh toán...</p></div>';
        }
    }
    
    // Gửi request đến server
    fetch('<?= BASE_URL ?>?act=payment-create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'hoadon_id=' + hoadonId
    })
    .then(response => {
        // Kiểm tra response status
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        // Kiểm tra content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Response is not JSON:', text);
                throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng kiểm tra cấu hình MoMo!');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Hiển thị QR code nếu có
            if (data.qrCodeUrl) {
                document.getElementById('qr-code-image').src = data.qrCodeUrl;
                document.getElementById('qr-code-container').classList.remove('hidden');
            }
            
            // Mở link thanh toán trong tab mới hoặc redirect
            if (data.payUrl) {
                // Mở trong tab mới
                window.open(data.payUrl, '_blank');
                
                // Hoặc redirect trực tiếp
                // window.location.href = data.payUrl;
            }
            
            // Hiển thị thông báo thành công
            alert('Đã tạo yêu cầu thanh toán thành công! Vui lòng hoàn tất thanh toán trong ứng dụng MoMo.');
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể tạo yêu cầu thanh toán. Vui lòng thử lại sau!'));
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'Đã có lỗi xảy ra. ';
        if (error.message) {
            errorMessage += error.message;
        } else {
            errorMessage += 'Vui lòng kiểm tra console để biết chi tiết hoặc liên hệ hỗ trợ!';
        }
        alert(errorMessage);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

// Hiển thị QR code
function showQRCode(hoadonId) {
    const container = document.getElementById('qr-code-container');
    const qrImage = document.getElementById('qr-code-image');
    
    // Hiển thị loading
    if (container) {
        container.classList.remove('hidden');
        if (qrImage) {
            qrImage.src = '';
            qrImage.alt = 'Đang tải...';
        }
    }
    
    // Nếu đã có QR code trong database, hiển thị
    fetch('<?= BASE_URL ?>?act=payment-qrcode&hoadon_id=' + hoadonId)
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Response is not JSON:', text);
                throw new Error('Server trả về dữ liệu không hợp lệ!');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.qrCodeUrl) {
            qrImage.src = data.qrCodeUrl;
            container.classList.remove('hidden');
        } else {
            // Nếu chưa có, tạo mới
            createMoMoPayment(hoadonId);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Hiển thị thông báo lỗi
        if (container) {
            container.innerHTML = '<div class="text-center p-6 text-red-600"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>Không thể tải mã QR. ' + (error.message || 'Vui lòng thử lại sau!') + '</p></div>';
        }
        // Nếu lỗi, thử tạo mới payment request
        console.log('Attempting to create new payment request...');
        createMoMoPayment(hoadonId);
    });
}

// Auto-check payment status nếu đang chờ thanh toán
<?php if ($paymentStatus == 2): ?>
// Kiểm tra trạng thái thanh toán mỗi 5 giây
let checkPaymentInterval = setInterval(function() {
    // Reload page sau 30 giây để cập nhật trạng thái
    setTimeout(function() {
        window.location.reload();
    }, 30000);
}, 5000);

// Clear interval sau 5 phút
setTimeout(function() {
    clearInterval(checkPaymentInterval);
}, 300000);
<?php endif; ?>
</script>

