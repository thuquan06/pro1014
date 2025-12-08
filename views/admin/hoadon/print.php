<?php
/**
 * Xuất hóa đơn - Printable Invoice
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

function formatDateTime($datetime) {
    return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : '-';
}

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

$id = $hoadon['id_hoadon'] ?? '';
$ma_booking = $hoadon['ma_booking'] ?? '';
$email = $hoadon['email_nguoidung'] ?? '';
$ho_ten = $hoadon['ho_ten'] ?? '';
$so_dien_thoai = $hoadon['so_dien_thoai'] ?? '';
$dia_chi = $hoadon['dia_chi'] ?? '';
$ten_goi = $hoadon['ten_goi'] ?? 'N/A';
$nguoilon = $hoadon['nguoilon'] ?? 0;
$treem = $hoadon['treem'] ?? 0;
$trenho = $hoadon['trenho'] ?? 0;
$embe = $hoadon['embe'] ?? 0;
$ngayvao = $hoadon['ngayvao'] ?? '';
$ngayra = $hoadon['ngayra'] ?? '';
$ngaydat = $hoadon['ngaydat'] ?? '';
$ngay_thanh_toan = $hoadon['ngay_thanh_toan'] ?? '';
$tong_tien = $hoadon['tong_tien'] ?? ($total ?? 0);
$tien_dat_coc = $hoadon['tien_dat_coc'] ?? 0;
$ghi_chu = $hoadon['ghichu'] ?? '';

$giagoi = $hoadon['giagoi'] ?? $hoadon['gia_nguoi_lon'] ?? 0;
$giatreem = $hoadon['giatreem'] ?? $hoadon['gia_tre_em'] ?? 0;
$giatrenho = $hoadon['giatrenho'] ?? $hoadon['gia_tre_nho'] ?? 0;

$total_people = $nguoilon + $treem + $trenho;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #<?= $id ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            background: white;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .invoice-header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .invoice-header p {
            color: #666;
            font-size: 14px;
        }
        
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
        
        .info-box h3 {
            font-size: 14px;
            color: #1e40af;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .invoice-table th {
            background: #1e40af;
            color: white;
            font-weight: 600;
        }
        
        .invoice-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .invoice-total {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .total-row.final {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
            padding: 12px 0;
            margin-top: 10px;
        }
        
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .footer-box {
            text-align: center;
        }
        
        .footer-box h4 {
            margin-bottom: 40px;
            color: #333;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-actions {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            background: #1e40af;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #1e3a8a;
        }
        
        .btn-secondary {
            background: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button onclick="window.print()" class="btn">
            <i class="fas fa-print"></i> In hóa đơn
        </button>
        <a href="<?= BASE_URL ?>?act=hoadon-detail&id=<?= $id ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>HÓA ĐƠN</h1>
            <p>Mã hóa đơn: #<?= safe_html($id) ?></p>
            <?php if ($ma_booking): ?>
                <p>Mã booking: <?= safe_html($ma_booking) ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Thông tin khách hàng và công ty -->
        <div class="invoice-info">
            <div class="info-box">
                <h3>Thông tin khách hàng</h3>
                <div class="info-row">
                    <span class="info-label">Họ tên:</span>
                    <span class="info-value"><?= safe_html($ho_ten) ?></span>
                </div>
                <?php if ($email): ?>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= safe_html($email) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($so_dien_thoai): ?>
                <div class="info-row">
                    <span class="info-label">Số điện thoại:</span>
                    <span class="info-value"><?= safe_html($so_dien_thoai) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($dia_chi): ?>
                <div class="info-row">
                    <span class="info-label">Địa chỉ:</span>
                    <span class="info-value"><?= safe_html($dia_chi) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="info-box">
                <h3>Thông tin hóa đơn</h3>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value"><?= formatDateTime($ngaydat) ?></span>
                </div>
                <?php if ($ngay_thanh_toan): ?>
                <div class="info-row">
                    <span class="info-label">Ngày thanh toán:</span>
                    <span class="info-value"><?= formatDateTime($ngay_thanh_toan) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($ngayvao): ?>
                <div class="info-row">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value"><?= formatDate($ngayvao) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($ngayra): ?>
                <div class="info-row">
                    <span class="info-label">Ngày kết thúc:</span>
                    <span class="info-value"><?= formatDate($ngayra) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Chi tiết dịch vụ -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Dịch vụ</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1;
                $subtotal = 0;
                ?>
                
                <?php if ($nguoilon > 0 && $giagoi > 0): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td><?= safe_html($ten_goi) ?> - Người lớn</td>
                    <td class="text-center"><?= $nguoilon ?></td>
                    <td class="text-right"><?= formatPrice($giagoi) ?></td>
                    <td class="text-right"><?= formatPrice($nguoilon * $giagoi) ?></td>
                </tr>
                <?php $subtotal += $nguoilon * $giagoi; ?>
                <?php endif; ?>
                
                <?php if ($treem > 0 && $giatreem > 0): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td><?= safe_html($ten_goi) ?> - Trẻ em</td>
                    <td class="text-center"><?= $treem ?></td>
                    <td class="text-right"><?= formatPrice($giatreem) ?></td>
                    <td class="text-right"><?= formatPrice($treem * $giatreem) ?></td>
                </tr>
                <?php $subtotal += $treem * $giatreem; ?>
                <?php endif; ?>
                
                <?php if ($trenho > 0 && $giatrenho > 0): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td><?= safe_html($ten_goi) ?> - Trẻ nhỏ</td>
                    <td class="text-center"><?= $trenho ?></td>
                    <td class="text-right"><?= formatPrice($giatrenho) ?></td>
                    <td class="text-right"><?= formatPrice($trenho * $giatrenho) ?></td>
                </tr>
                <?php $subtotal += $trenho * $giatrenho; ?>
                <?php endif; ?>
                
                <?php if ($stt == 1): ?>
                <tr>
                    <td colspan="5" class="text-center">Không có chi tiết dịch vụ</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Tổng tiền -->
        <div class="invoice-total">
            <div class="total-row">
                <span>Tổng tiền dịch vụ:</span>
                <span><?= formatPrice($subtotal > 0 ? $subtotal : $tong_tien) ?></span>
            </div>
            <?php if ($tien_dat_coc > 0): ?>
            <div class="total-row">
                <span>Tiền đặt cọc:</span>
                <span><?= formatPrice($tien_dat_coc) ?></span>
            </div>
            <div class="total-row">
                <span>Còn lại:</span>
                <span><?= formatPrice(($tong_tien > 0 ? $tong_tien : $subtotal) - $tien_dat_coc) ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row final">
                <span>TỔNG CỘNG:</span>
                <span><?= formatPrice($tong_tien > 0 ? $tong_tien : $subtotal) ?></span>
            </div>
        </div>
        
        <!-- Ghi chú -->
        <?php if ($ghi_chu): ?>
        <div style="margin-top: 30px; padding: 15px; background: #f9fafb; border-radius: 8px;">
            <strong>Ghi chú:</strong>
            <p style="margin-top: 5px;"><?= nl2br(safe_html($ghi_chu)) ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-box">
                <h4>Người lập hóa đơn</h4>
                <div class="signature-line">
                    <p>(Ký, ghi rõ họ tên)</p>
                </div>
            </div>
            <div class="footer-box">
                <h4>Khách hàng</h4>
                <div class="signature-line">
                    <p>(Ký, ghi rõ họ tên)</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tự động in khi load trang (nếu có tham số print=1)
        <?php if (isset($_GET['print']) && $_GET['print'] == '1'): ?>
        window.onload = function() {
            window.print();
        };
        <?php endif; ?>
    </script>
</body>
</html>

