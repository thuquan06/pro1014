<?php
/**
 * BookingEmailHelper - Gửi email xác nhận đặt tour tự động
 */
require_once __DIR__ . '/EmailHelper.php';

class BookingEmailHelper
{
    /**
     * Gửi email xác nhận đặt tour cho khách hàng
     * 
     * @param array $hoadon Thông tin hóa đơn
     * @param array $tour Thông tin tour
     * @param array $departure Thông tin lịch khởi hành (optional)
     * @return bool
     */
    public static function sendBookingConfirmation($hoadon, $tour, $departure = null)
    {
        $emailHelper = new EmailHelper();
        
        // Lấy email khách hàng
        $customerEmail = $hoadon['email_nguoidung'] ?? null;
        
        if (empty($customerEmail)) {
            error_log("BookingEmailHelper: Không có email khách hàng để gửi xác nhận");
            return false;
        }
        
        // Tạo tiêu đề và nội dung email
        $subject = self::getEmailSubject($hoadon, $tour);
        $body = self::buildEmailBody($hoadon, $tour, $departure);
        
        // Gửi email
        $result = $emailHelper->send($customerEmail, $subject, $body);
        
        if ($result) {
            error_log("BookingEmailHelper: Email xác nhận đã được gửi đến {$customerEmail} cho hóa đơn #{$hoadon['id_hoadon']}");
        } else {
            error_log("BookingEmailHelper: Không thể gửi email xác nhận đến {$customerEmail} cho hóa đơn #{$hoadon['id_hoadon']}");
        }
        
        return $result;
    }
    
    /**
     * Tạo tiêu đề email
     */
    private static function getEmailSubject($hoadon, $tour)
    {
        $tourName = $tour['tengoi'] ?? 'Tour';
        $bookingId = $hoadon['id_hoadon'] ?? '';
        return "Xác nhận đặt tour - {$tourName} - Mã đơn: #{$bookingId}";
    }
    
    /**
     * Tạo nội dung email HTML
     */
    private static function buildEmailBody($hoadon, $tour, $departure = null)
    {
        // Lấy thông tin khách hàng từ ghi chú
        $customerName = '';
        $customerPhone = '';
        if (!empty($hoadon['ghichu'])) {
            $notes = explode("\n", $hoadon['ghichu']);
            foreach ($notes as $note) {
                if (strpos($note, 'Tên khách hàng:') !== false) {
                    $customerName = trim(str_replace('Tên khách hàng:', '', $note));
                } elseif (strpos($note, 'Số điện thoại:') !== false) {
                    $customerPhone = trim(str_replace('Số điện thoại:', '', $note));
                }
            }
        }
        
        // Tính tổng tiền
        $total = 0;
        $total += ($hoadon['nguoilon'] ?? 0) * ($tour['giagoi'] ?? 0);
        $total += ($hoadon['treem'] ?? 0) * ($tour['giatreem'] ?? 0);
        $total += ($hoadon['trenho'] ?? 0) * ($tour['giatrenho'] ?? 0);
        
        // Format giá
        $formatPrice = function($price) {
            if (empty($price) || !is_numeric($price)) return 'Liên hệ';
            return number_format($price, 0, ',', '.') . ' VNĐ';
        };
        
        $formatPriceUSD = function($price) {
            if (empty($price) || !is_numeric($price)) return 'Liên hệ';
            $usdPrice = round($price / 25000);
            return '$' . number_format($usdPrice, 0);
        };
        
        // Trạng thái
        $statusText = self::getStatusText($hoadon['trangthai'] ?? 0);
        $statusColor = self::getStatusColor($hoadon['trangthai'] ?? 0);
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container { 
            max-width: 600px; 
            margin: 0 auto; 
            background-color: #ffffff;
        }
        .email-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 30px 20px; 
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-content { 
            padding: 30px 20px; 
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon i {
            font-size: 64px;
            color: #10b981;
        }
        .booking-id {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
        }
        .booking-id strong {
            font-size: 20px;
            color: #667eea;
        }
        .section { 
            margin-bottom: 25px; 
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title { 
            font-size: 18px; 
            font-weight: bold; 
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .info-label { 
            color: #6b7280; 
            font-size: 14px;
        }
        .info-value { 
            color: #1f2937; 
            font-weight: 600;
            text-align: right;
        }
        .status-badge { 
            display: inline-block; 
            padding: 8px 16px; 
            border-radius: 20px; 
            font-weight: bold; 
            color: white;
            font-size: 14px;
        }
        .price-summary {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .price-total {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-total-label {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        .price-total-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .next-steps {
            background: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            margin-top: 25px;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #1e40af;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
            color: #1e3a8a;
        }
        .email-footer { 
            background: #f3f4f6; 
            padding: 20px; 
            text-align: center; 
            font-size: 12px; 
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .contact-info {
            background: #fff7ed;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #f59e0b;
        }
        .contact-info p {
            margin: 5px 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✅ Xác nhận đặt tour thành công!</h1>
        </div>
        
        <div class="email-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="booking-id">
                <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">Mã đơn hàng</p>
                <strong>#' . htmlspecialchars($hoadon['id_hoadon'] ?? 'N/A') . '</strong>
            </div>
            
            <p style="font-size: 16px; color: #1f2937; margin-bottom: 25px;">
                Xin chào <strong>' . htmlspecialchars($customerName ?: 'Quý khách') . '</strong>,
            </p>
            
            <p style="font-size: 15px; color: #4b5563; line-height: 1.8;">
                Cảm ơn bạn đã đặt tour với <strong>StarVel Travel</strong>! Chúng tôi đã nhận được yêu cầu đặt tour của bạn và đang xử lý.
                Thông tin chi tiết về đơn đặt tour của bạn như sau:
            </p>
            
            <!-- Thông tin Tour -->
            <div class="section">
                <div class="section-title">📋 Thông tin Tour</div>
                <div class="info-row">
                    <span class="info-label">Tên tour:</span>
                    <span class="info-value">' . htmlspecialchars($tour['tengoi'] ?? 'N/A') . '</span>
                </div>';
        
        if (!empty($tour['vitri'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Địa điểm:</span>
                    <span class="info-value">' . htmlspecialchars($tour['vitri']) . '</span>
                </div>';
        }
        
        if (!empty($hoadon['ngayvao'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value">' . date('d/m/Y', strtotime($hoadon['ngayvao'])) . '</span>
                </div>';
        }
        
        if (!empty($hoadon['ngayra'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Ngày kết thúc:</span>
                    <span class="info-value">' . date('d/m/Y', strtotime($hoadon['ngayra'])) . '</span>
                </div>';
        }
        
        if ($departure && !empty($departure['gio_khoi_hanh'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Giờ khởi hành:</span>
                    <span class="info-value">' . date('H:i', strtotime($departure['gio_khoi_hanh'])) . '</span>
                </div>';
        }
        
        if ($departure && !empty($departure['diem_tap_trung'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Điểm tập trung:</span>
                    <span class="info-value">' . htmlspecialchars($departure['diem_tap_trung']) . '</span>
                </div>';
        }
        
        $html .= '<div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span class="info-value">
                        <span class="status-badge" style="background-color: ' . $statusColor . ';">' . htmlspecialchars($statusText) . '</span>
                    </span>
                </div>
            </div>
            
            <!-- Thông tin Khách hàng -->
            <div class="section">
                <div class="section-title">👤 Thông tin Khách hàng</div>';
        
        if ($customerName) {
            $html .= '<div class="info-row">
                    <span class="info-label">Họ tên:</span>
                    <span class="info-value">' . htmlspecialchars($customerName) . '</span>
                </div>';
        }
        
        $html .= '<div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">' . htmlspecialchars($hoadon['email_nguoidung'] ?? 'N/A') . '</span>
                </div>';
        
        if ($customerPhone) {
            $html .= '<div class="info-row">
                    <span class="info-label">Số điện thoại:</span>
                    <span class="info-value">' . htmlspecialchars($customerPhone) . '</span>
                </div>';
        }
        
        $html .= '<div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">' . date('d/m/Y H:i', strtotime($hoadon['ngaydat'] ?? 'now')) . '</span>
                </div>
            </div>
            
            <!-- Số lượng người -->
            <div class="section">
                <div class="section-title">👥 Số lượng người</div>
                <div class="info-row">
                    <span class="info-label">Người lớn:</span>
                    <span class="info-value">' . ($hoadon['nguoilon'] ?? 0) . ' người</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trẻ em:</span>
                    <span class="info-value">' . ($hoadon['treem'] ?? 0) . ' người</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trẻ nhỏ:</span>
                    <span class="info-value">' . ($hoadon['trenho'] ?? 0) . ' người</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Em bé:</span>
                    <span class="info-value">' . ($hoadon['embe'] ?? 0) . ' người</span>
                </div>';
        
        if (!empty($hoadon['sophong'])) {
            $html .= '<div class="info-row">
                    <span class="info-label">Số phòng:</span>
                    <span class="info-value">' . ($hoadon['sophong']) . ' phòng</span>
                </div>';
        }
        
        $html .= '</div>
            
            <!-- Tổng tiền -->
            <div class="section">
                <div class="section-title">💰 Tổng tiền</div>
                <div class="price-summary">';
        
        if (!empty($hoadon['nguoilon']) && $hoadon['nguoilon'] > 0) {
            $adultPrice = ($tour['giagoi'] ?? 0) * $hoadon['nguoilon'];
            $html .= '<div class="info-row">
                        <span class="info-label">Người lớn (' . ($hoadon['nguoilon']) . ' người):</span>
                        <span class="info-value">' . $formatPriceUSD($adultPrice) . '</span>
                    </div>';
        }
        
        if (!empty($hoadon['treem']) && $hoadon['treem'] > 0) {
            $childPrice = ($tour['giatreem'] ?? 0) * $hoadon['treem'];
            $html .= '<div class="info-row">
                        <span class="info-label">Trẻ em (' . ($hoadon['treem']) . ' người):</span>
                        <span class="info-value">' . $formatPriceUSD($childPrice) . '</span>
                    </div>';
        }
        
        if (!empty($hoadon['trenho']) && $hoadon['trenho'] > 0) {
            $babyPrice = ($tour['giatrenho'] ?? 0) * $hoadon['trenho'];
            $html .= '<div class="info-row">
                        <span class="info-label">Trẻ nhỏ (' . ($hoadon['trenho']) . ' người):</span>
                        <span class="info-value">' . $formatPriceUSD($babyPrice) . '</span>
                    </div>';
        }
        
        $html .= '<div class="price-total">
                        <span class="price-total-label">Tổng cộng:</span>
                        <span class="price-total-value">' . $formatPriceUSD($total) . '</span>
                    </div>
                </div>
            </div>';
        
        // Ghi chú nếu có
        if (!empty($hoadon['ghichu'])) {
            $notes = explode("\n", $hoadon['ghichu']);
            $userNotes = array_filter($notes, function($note) {
                return strpos($note, 'Tên khách hàng:') === false && 
                       strpos($note, 'Số điện thoại:') === false && 
                       !empty(trim($note));
            });
            
            if (!empty($userNotes)) {
                $html .= '<div class="section">
                    <div class="section-title">📝 Ghi chú</div>
                    <p style="color: #4b5563; white-space: pre-wrap;">' . nl2br(htmlspecialchars(implode("\n", $userNotes))) . '</p>
                </div>';
            }
        }
        
        $html .= '<div class="next-steps">
                <h3 style="margin-top: 0; color: #1e40af;">📌 Hướng dẫn tiếp theo</h3>
                <ul style="margin: 10px 0; padding-left: 20px; color: #1e3a8a;">
                    <li>Nhân viên của chúng tôi sẽ liên hệ với bạn trong vòng <strong>24 giờ</strong> để xác nhận thông tin và hướng dẫn thanh toán.</li>
                    <li>Vui lòng kiểm tra email thường xuyên để nhận thông tin cập nhật về tour của bạn.</li>
                    <li>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email hoặc hotline.</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <p style="margin: 0 0 5px 0;"><strong>📞 Liên hệ hỗ trợ:</strong></p>
                <p style="margin: 5px 0;">Email: ' . htmlspecialchars(defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'support@starvel.com') . '</p>
                <p style="margin: 5px 0;">Hotline: 1900-xxxx (8:00 - 20:00 hàng ngày)</p>
            </div>
        </div>
        
        <div class="email-footer">
            <p><strong>StarVel Travel</strong></p>
            <p>© ' . date('Y') . ' StarVel. All rights reserved.</p>
            <p style="margin-top: 10px;">
                <a href="' . (defined('BASE_URL') ? BASE_URL : 'http://localhost/pro1014/') . '">Truy cập website</a> | 
                <a href="' . (defined('BASE_URL') ? BASE_URL : 'http://localhost/pro1014/') . '?act=tours">Xem thêm tour</a>
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #9ca3af;">
                Email này được gửi tự động, vui lòng không reply trực tiếp.
            </p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Lấy text trạng thái
     */
    private static function getStatusText($status)
    {
        switch($status) {
            case 0: return 'Chờ xác nhận';
            case 1: return 'Đã xác nhận';
            case 2: return 'Hoàn thành';
            default: return 'Không xác định';
        }
    }
    
    /**
     * Lấy màu trạng thái
     */
    private static function getStatusColor($status)
    {
        switch($status) {
            case 0: return '#f59e0b'; // Vàng
            case 1: return '#3b82f6'; // Xanh dương
            case 2: return '#10b981'; // Xanh lá
            default: return '#6b7280'; // Xám
        }
    }
}

