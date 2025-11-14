<?php
/**
 * TEST CHI TIẾT TOUR
 * File: test_chitiet_tour.php
 * Mục đích: Kiểm tra Model TourChiTietModel
 */

// Include các file cần thiết
require_once 'commons/env.php';
require_once 'commons/function.php';
require_once 'models/BaseModel.php';
require_once 'models/TourChiTietModel.php';

// Khởi tạo model
$model = new TourChiTietModel();

// ======================================
// THAY ĐỔI ID TOUR Ở ĐÂY
// ======================================
$idGoi = 71;  // THAY BẰNG ID TOUR CỦA BẠN

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Chi Tiết Tour ID: <?= $idGoi ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 30px;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            font-size: 1.8em;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title::before {
            content: '';
            width: 5px;
            height: 30px;
            background: #667eea;
            border-radius: 5px;
        }
        
        .timeline {
            position: relative;
            padding-left: 40px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #667eea, #764ba2);
        }
        
        .day-card {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .day-card:hover {
            transform: translateX(10px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .day-card::before {
            content: '';
            position: absolute;
            left: -35px;
            top: 30px;
            width: 15px;
            height: 15px;
            background: #667eea;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 3px #667eea;
        }
        
        .day-title {
            font-size: 1.3em;
            color: #764ba2;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .day-description {
            color: #555;
            margin-bottom: 15px;
            text-align: justify;
        }
        
        .activities {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            margin: 15px 0;
        }
        
        .activities h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .activities pre {
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            color: #333;
            line-height: 1.8;
        }
        
        .info-row {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        
        .info-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .policy-list {
            list-style: none;
        }
        
        .policy-item {
            background: #f8f9ff;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }
        
        .policy-item:hover {
            background: #eef0ff;
            transform: translateX(5px);
        }
        
        .policy-item.cancel {
            border-left-color: #e74c3c;
        }
        
        .policy-item.change {
            border-left-color: #f39c12;
        }
        
        .policy-percent {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .tag {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.95em;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .tag:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .category-tag {
            color: white;
            font-weight: 500;
        }
        
        .hash-tag {
            background: #ecf0f1;
            color: #34495e;
            border: 2px solid #bdc3c7;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .alert-error {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        
        .alert-success {
            background: #d4edda;
            border-left-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 TEST CHI TIẾT TOUR</h1>
            <p>Kiểm tra dữ liệu cho Tour ID: <strong><?= $idGoi ?></strong></p>
        </div>
        
        <div class="content">
            <?php
            // Lấy thông tin tour
            try {
                $chitiet = $model->layChiTietDayDu($idGoi);
                
                // Đếm số lượng
                $soNgay = count($chitiet['lichtrinh']);
                $soAnh = count($chitiet['hinhanh']);
                $soChinhSach = count($chitiet['chinhsach']);
                $soLoai = count($chitiet['loaitour']);
                $soTags = count($chitiet['tags']);
            ?>
            
            <!-- THỐNG KÊ -->
            <div class="alert alert-success">
                <strong>✅ Kết nối database thành công!</strong><br>
                Đã tải dữ liệu chi tiết cho tour ID: <?= $idGoi ?>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $soNgay ?></div>
                    <div class="stat-label">Ngày lịch trình</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $soAnh ?></div>
                    <div class="stat-label">Hình ảnh</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $soChinhSach ?></div>
                    <div class="stat-label">Chính sách</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $soLoai + $soTags ?></div>
                    <div class="stat-label">Phân loại</div>
                </div>
            </div>
            
            <!-- LỊCH TRÌNH -->
            <div class="section">
                <h2 class="section-title">📅 Lịch trình chi tiết</h2>
                
                <?php if ($soNgay > 0): ?>
                    <div class="timeline">
                        <?php foreach ($chitiet['lichtrinh'] as $ngay): ?>
                            <div class="day-card">
                                <div class="day-title">
                                    <?= htmlspecialchars($ngay['tieude']) ?>
                                </div>
                                <div class="day-description">
                                    <?= nl2br(htmlspecialchars($ngay['mota'])) ?>
                                </div>
                                
                                <?php if (!empty($ngay['hoatdong'])): ?>
                                    <div class="activities">
                                        <h4>🎯 Hoạt động trong ngày:</h4>
                                        <pre><?= htmlspecialchars($ngay['hoatdong']) ?></pre>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="info-row">
                                    <?php if (!empty($ngay['buaan'])): ?>
                                        <div class="info-badge">
                                            🍽️ <?= htmlspecialchars($ngay['buaan']) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($ngay['noinghi'])): ?>
                                        <div class="info-badge">
                                            🏨 <?= htmlspecialchars($ngay['noinghi']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-message">
                        ⚠️ Chưa có lịch trình cho tour này. Hãy thêm dữ liệu bằng SQL!
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- CHÍNH SÁCH -->
            <div class="section">
                <h2 class="section-title">📋 Chính sách hủy & đổi</h2>
                
                <?php if ($soChinhSach > 0): ?>
                    <ul class="policy-list">
                        <?php foreach ($chitiet['chinhsach'] as $cs): ?>
                            <li class="policy-item <?= $cs['loai_chinhsach'] == 'huy' ? 'cancel' : 'change' ?>">
                                <?php if ($cs['phantram_hoantien'] > 0): ?>
                                    <span class="policy-percent">
                                        Hoàn <?= number_format($cs['phantram_hoantien'], 0) ?>%
                                    </span>
                                <?php endif; ?>
                                <?= htmlspecialchars($cs['noidung']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-message">
                        ⚠️ Chưa có chính sách cho tour này.
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- LOẠI TOUR & TAGS -->
            <div class="section">
                <h2 class="section-title">🏷️ Phân loại & Tags</h2>
                
                <?php if ($soLoai > 0): ?>
                    <h3 style="color: #667eea; margin-bottom: 15px;">Loại tour:</h3>
                    <div class="tags-container">
                        <?php foreach ($chitiet['loaitour'] as $loai): ?>
                            <div class="tag category-tag" style="background: <?= htmlspecialchars($loai['mau_sac']) ?>;">
                                <span><?= htmlspecialchars($loai['icon'] ?? '📍') ?></span>
                                <span><?= htmlspecialchars($loai['ten_loai']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($soTags > 0): ?>
                    <h3 style="color: #667eea; margin: 25px 0 15px;">Tags:</h3>
                    <div class="tags-container">
                        <?php foreach ($chitiet['tags'] as $tag): ?>
                            <div class="tag hash-tag">
                                #<?= htmlspecialchars($tag['ten_tag']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($soLoai == 0 && $soTags == 0): ?>
                    <div class="empty-message">
                        ⚠️ Chưa có loại tour và tags. Hãy gán bằng SQL!
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- HÌNH ẢNH -->
            <div class="section">
                <h2 class="section-title">📸 Hình ảnh</h2>
                
                <?php if ($soAnh > 0): ?>
                    <div class="alert">
                        <strong>ℹ️ Có <?= $soAnh ?> hình ảnh trong gallery</strong><br>
                        <small>Hiển thị danh sách ảnh (chưa render HTML img tag):</small>
                    </div>
                    <ul>
                        <?php foreach ($chitiet['hinhanh'] as $anh): ?>
                            <li>
                                <strong><?= htmlspecialchars($anh['duongdan_anh']) ?></strong>
                                <?php if ($anh['anh_daodien'] == 1): ?>
                                    <span style="color: #e74c3c; font-weight: bold;"> ⭐ Ảnh đại diện</span>
                                <?php endif; ?>
                                <?php if (!empty($anh['mota_anh'])): ?>
                                    <br><small><?= htmlspecialchars($anh['mota_anh']) ?></small>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-message">
                        ⚠️ Chưa có hình ảnh cho tour này.
                    </div>
                <?php endif; ?>
            </div>
            
            <?php
            } catch (Exception $e) {
                echo '<div class="alert alert-error">';
                echo '<strong>❌ LỖI:</strong><br>';
                echo htmlspecialchars($e->getMessage());
                echo '</div>';
            }
            ?>
            
            <!-- DEBUG INFO -->
            <div class="section" style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                <h3 style="color: #6c757d;">🔧 Debug Info</h3>
                <ul style="color: #6c757d; line-height: 2;">
                    <li><strong>File test:</strong> <?= __FILE__ ?></li>
                    <li><strong>Tour ID:</strong> <?= $idGoi ?></li>
                    <li><strong>Thời gian:</strong> <?= date('Y-m-d H:i:s') ?></li>
                    <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
                </ul>
                
                <p style="margin-top: 20px; color: #6c757d;">
                    <strong>💡 Hướng dẫn:</strong><br>
                    - Để thay đổi tour, sửa biến <code>$idGoi</code> ở dòng 17<br>
                    - Để thêm dữ liệu, chạy file SQL trong phpMyAdmin<br>
                    - Để xem raw data, thêm <code>print_r($chitiet);</code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>