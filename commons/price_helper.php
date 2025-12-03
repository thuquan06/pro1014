<?php
/**
 * Price Helper - Tính toán giá tour với khuyến mãi
 * Created: 2024-12-03
 */

/**
 * Tính giá sau khuyến mãi
 *
 * @param float $originalPrice Giá gốc
 * @param array $tour Thông tin tour (chứa khuyenmai, khuyenmai_phantram, dates)
 * @return float Giá sau khuyến mãi
 */
function calculatePromotionPrice($originalPrice, $tour) {
    // Kiểm tra có khuyến mãi không
    if (empty($tour['khuyenmai']) || $tour['khuyenmai'] != 1) {
        return $originalPrice;
    }

    // Kiểm tra phần trăm khuyến mãi
    $discount = isset($tour['khuyenmai_phantram']) ? (int)$tour['khuyenmai_phantram'] : 0;
    if ($discount <= 0 || $discount > 100) {
        return $originalPrice;
    }

    // Kiểm tra thời gian khuyến mãi còn hiệu lực không
    if (!isPromotionActive($tour)) {
        return $originalPrice;
    }

    // Tính giá sau khuyến mãi
    $discountAmount = $originalPrice * ($discount / 100);
    $finalPrice = $originalPrice - $discountAmount;

    return $finalPrice;
}

/**
 * Kiểm tra khuyến mãi còn hiệu lực không
 *
 * @param array $tour Thông tin tour
 * @return bool True nếu còn hiệu lực
 */
function isPromotionActive($tour) {
    // Không có khuyến mãi
    if (empty($tour['khuyenmai']) || $tour['khuyenmai'] != 1) {
        return false;
    }

    // Không có ngày bắt đầu/kết thúc
    if (empty($tour['khuyenmai_tungay']) || empty($tour['khuyenmai_denngay'])) {
        return false;
    }

    $today = date('Y-m-d');
    $startDate = $tour['khuyenmai_tungay'];
    $endDate = $tour['khuyenmai_denngay'];

    // Kiểm tra ngày hiện tại có nằm trong khoảng khuyến mãi không
    return ($today >= $startDate && $today <= $endDate);
}

/**
 * Format giá tiền VNĐ
 *
 * @param float $price Giá
 * @return string Giá đã format (VD: 5,000,000 VNĐ)
 */
function formatPrice($price) {
    return number_format($price, 0, ',', ',') . ' VNĐ';
}

/**
 * Lấy giá hiển thị cho tour (có strikethrough giá cũ nếu có KM)
 *
 * @param float $originalPrice Giá gốc
 * @param array $tour Thông tin tour
 * @return string HTML hiển thị giá
 */
function displayTourPrice($originalPrice, $tour) {
    $finalPrice = calculatePromotionPrice($originalPrice, $tour);

    // Không có khuyến mãi hoặc giá không đổi
    if ($finalPrice == $originalPrice) {
        return '<span class="price">' . formatPrice($originalPrice) . '</span>';
    }

    // Có khuyến mãi - hiển thị giá cũ gạch ngang + giá mới
    $html = '<span class="price-old" style="text-decoration: line-through; color: #999; font-size: 14px;">'
          . formatPrice($originalPrice)
          . '</span> ';
    $html .= '<span class="price-new" style="color: #e74c3c; font-weight: bold; font-size: 18px;">'
           . formatPrice($finalPrice)
           . '</span>';

    return $html;
}

/**
 * Hiển thị badge khuyến mãi
 *
 * @param array $tour Thông tin tour
 * @return string HTML badge khuyến mãi
 */
function displayPromotionBadge($tour) {
    if (!isPromotionActive($tour)) {
        return '';
    }

    $discount = isset($tour['khuyenmai_phantram']) ? (int)$tour['khuyenmai_phantram'] : 0;
    if ($discount <= 0) {
        return '';
    }

    $html = '<span class="badge-promotion" style="'
          . 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); '
          . 'color: white; '
          . 'padding: 4px 12px; '
          . 'border-radius: 20px; '
          . 'font-size: 12px; '
          . 'font-weight: bold; '
          . 'display: inline-block; '
          . 'margin-bottom: 8px;'
          . '">';
    $html .= '🔥 Giảm ' . $discount . '%';
    $html .= '</span>';

    return $html;
}

/**
 * Hiển thị countdown thời gian còn lại của khuyến mãi
 *
 * @param array $tour Thông tin tour
 * @return string HTML countdown
 */
function displayPromotionCountdown($tour) {
    if (!isPromotionActive($tour)) {
        return '';
    }

    $today = new DateTime();
    $endDate = new DateTime($tour['khuyenmai_denngay']);
    $diff = $today->diff($endDate);
    $daysLeft = $diff->days;

    if ($daysLeft <= 0) {
        return '<span style="color: #e74c3c; font-size: 12px;">⏰ Sắp hết hạn!</span>';
    }

    $html = '<span style="color: #f39c12; font-size: 12px;">';
    $html .= '⏰ Còn ' . $daysLeft . ' ngày';
    $html .= '</span>';

    return $html;
}

/**
 * Tính tổng tiền đặt tour (dùng cho booking)
 *
 * @param array $tour Thông tin tour
 * @param int $nguoilon Số người lớn
 * @param int $treem Số trẻ em
 * @param int $trenho Số trẻ nhỏ
 * @return float Tổng tiền
 */
function calculateBookingTotal($tour, $nguoilon = 1, $treem = 0, $trenho = 0) {
    $giaNguoiLon = calculatePromotionPrice($tour['giagoi'], $tour);
    $giaTreEm = calculatePromotionPrice($tour['giatreem'], $tour);
    $giaTreNho = calculatePromotionPrice($tour['giatrenho'], $tour);

    $total = ($giaNguoiLon * $nguoilon)
           + ($giaTreEm * $treem)
           + ($giaTreNho * $trenho);

    return $total;
}
