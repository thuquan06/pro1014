<?php
/**
 * IncidentReportEmailHelper - Gửi email báo cáo sự cố tự động
 */
require_once __DIR__ . '/EmailHelper.php';

class IncidentReportEmailHelper
{
    /**
     * Gửi email báo cáo sự cố cho công ty/điều phối viên
     */
    public static function sendIncidentReport($incident, $guide, $tour, $assignment)
    {
        require_once __DIR__ . '/../models/IncidentSuggestionHelper.php';
        
        $emailHelper = new EmailHelper();
        
        // Lấy email người nhận (có thể lấy từ config hoặc database)
        $recipientEmail = self::getRecipientEmail($incident['muc_do']);
        
        // Tạo nội dung email
        $subject = self::getEmailSubject($incident, $tour);
        $body = self::buildEmailBody($incident, $guide, $tour, $assignment);
        
        // Gửi email
        $result = $emailHelper->send($recipientEmail, $subject, $body);
        
        if ($result) {
            // Đánh dấu đã gửi trong database
            require_once __DIR__ . '/../models/IncidentReportModel.php';
            $incidentModel = new IncidentReportModel();
            $incidentModel->markAsSent($incident['id'], $recipientEmail);
        }
        
        return $result;
    }
    
    /**
     * Lấy email người nhận dựa trên mức độ
     */
    private static function getRecipientEmail($mucDo)
    {
        // Có thể lấy từ config hoặc database
        // Tạm thời dùng email từ env hoặc mặc định
        $defaultEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'admin@starvel.com');
        
        // Nếu mức độ cao/nghiêm trọng, gửi thêm cho điều phối viên trực
        if (in_array($mucDo, ['cao', 'nghiem_trong'])) {
            // Có thể thêm email điều phối viên trực
            return $defaultEmail;
        }
        
        return $defaultEmail;
    }
    
    /**
     * Tạo tiêu đề email
     */
    private static function getEmailSubject($incident, $tour)
    {
        $severityLabels = [
            'thap' => '[THẤP]',
            'trung_binh' => '[TRUNG BÌNH]',
            'cao' => '[CAO - KHẨN]',
            'nghiem_trong' => '[NGHIÊM TRỌNG - KHẨN CẤP]'
        ];
        
        $severity = $severityLabels[$incident['muc_do']] ?? '[THẤP]';
        $tourName = $tour['tengoi'] ?? 'Tour';
        $date = date('d/m/Y', strtotime($incident['ngay_xay_ra']));
        
        return "{$severity} Báo cáo sự cố - {$tourName} - {$date}";
    }
    
    /**
     * Tạo nội dung email HTML
     */
    private static function buildEmailBody($incident, $guide, $tour, $assignment)
    {
        require_once __DIR__ . '/../models/IncidentReportModel.php';
        $incidentModel = new IncidentReportModel();
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
        $typeLabel = $incidentTypes[$incident['loai_su_co']] ?? 'Khác';
        $severity = $severityLevels[$incident['muc_do']] ?? ['label' => 'Thấp', 'color' => '#10b981'];
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #ef4444; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; color: #6b7280; font-size: 12px; text-transform: uppercase; }
        .value { margin-top: 5px; color: #1f2937; }
        .severity-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-weight: bold; color: white; background: ' . $severity['color'] . '; }
        .suggestion-box { background: #dbeafe; padding: 15px; border-left: 4px solid #3b82f6; margin-top: 15px; border-radius: 4px; }
        .suggestion-box ol { margin: 10px 0; padding-left: 20px; }
        .footer { background: #f3f4f6; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">⚠️ BÁO CÁO SỰ CỐ</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Tour: ' . htmlspecialchars($tour['tengoi'] ?? 'N/A') . '</p>
        </div>
        
        <div class="content">
            <div class="section">
                <div class="label">Thông tin HDV</div>
                <div class="value">' . htmlspecialchars($guide['ho_ten'] ?? 'N/A') . ' (' . htmlspecialchars($guide['email'] ?? '') . ')</div>
            </div>
            
            <div class="section">
                <div class="label">Loại sự cố</div>
                <div class="value">' . htmlspecialchars($typeLabel) . '</div>
            </div>
            
            <div class="section">
                <div class="label">Mức độ nghiêm trọng</div>
                <div class="value"><span class="severity-badge">' . htmlspecialchars($severity['label']) . '</span></div>
            </div>
            
            <div class="section">
                <div class="label">Ngày giờ xảy ra</div>
                <div class="value">' . date('d/m/Y', strtotime($incident['ngay_xay_ra'])) . 
                    ($incident['gio_xay_ra'] ? ' lúc ' . date('H:i', strtotime($incident['gio_xay_ra'])) : '') . '</div>
            </div>';
        
        if (!empty($incident['vi_tri_gps'])) {
            $html .= '<div class="section">
                <div class="label">Vị trí</div>
                <div class="value">' . htmlspecialchars($incident['vi_tri_gps']) . '</div>
            </div>';
        }
        
        if (!empty($incident['thong_tin_khach'])) {
            $html .= '<div class="section">
                <div class="label">Thông tin khách liên quan</div>
                <div class="value">' . nl2br(htmlspecialchars($incident['thong_tin_khach'])) . '</div>
            </div>';
        }
        
        $html .= '<div class="section">
                <div class="label">Mô tả sự cố</div>
                <div class="value">' . nl2br(htmlspecialchars($incident['mo_ta'] ?? '')) . '</div>
            </div>
            
            <div class="section">
                <div class="label">Cách xử lý</div>
                <div class="value">' . nl2br(htmlspecialchars($incident['cach_xu_ly'] ?? '')) . '</div>
            </div>';
        
        // Hiển thị gợi ý xử lý nếu có
        if (!empty($incident['goi_y_xu_ly'])) {
            $suggestion = json_decode($incident['goi_y_xu_ly'], true);
            if ($suggestion) {
                $html .= '<div class="suggestion-box">
                    <strong>💡 Gợi ý xử lý từ hệ thống:</strong>
                    <h4>' . htmlspecialchars($suggestion['title'] ?? '') . '</h4>
                    <ol>';
                foreach ($suggestion['steps'] ?? [] as $step) {
                    $html .= '<li>' . htmlspecialchars($step) . '</li>';
                }
                $html .= '</ol>
                    <p><strong>Liên hệ:</strong> ' . htmlspecialchars($suggestion['contact'] ?? '') . '</p>
                </div>';
            }
        }
        
        // Hiển thị hình ảnh nếu có
        if (!empty($incident['hinh_anh'])) {
            $images = is_array($incident['hinh_anh']) ? $incident['hinh_anh'] : json_decode($incident['hinh_anh'], true);
            if ($images && count($images) > 0) {
                $html .= '<div class="section">
                    <div class="label">Hình ảnh đính kèm</div>
                    <div class="value">';
                foreach ($images as $image) {
                    $imageUrl = BASE_URL . $image;
                    $html .= '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" style="max-width: 200px; margin: 5px; border: 1px solid #ddd; border-radius: 4px;"></a>';
                }
                $html .= '</div></div>';
            }
        }
        
        $html .= '</div>
        
        <div class="footer">
            <p>Báo cáo được tạo tự động từ hệ thống StarVel Travel</p>
            <p>Thời gian: ' . date('d/m/Y H:i:s') . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
}

