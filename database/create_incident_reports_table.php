<?php
/**
 * Script tạo bảng bao_cao_su_co
 * Chạy script này một lần để tạo bảng trong database
 * Truy cập: http://localhost:8888/pro1014/database/create_incident_reports_table.php
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

header('Content-Type: text/html; charset=utf-8');

try {
    // Thử kết nối với socket của MAMP
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;
    
    // Thử kết nối với socket nếu là localhost
    if ($host === 'localhost') {
        // MAMP thường dùng socket tại /Applications/MAMP/tmp/mysql/mysql.sock
        $socketPath = '/Applications/MAMP/tmp/mysql/mysql.sock';
        if (file_exists($socketPath)) {
            $dsn = "mysql:unix_socket=$socketPath;dbname=$dbname;charset=utf8mb4";
        } else {
            // Fallback về TCP connection
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        }
    } else {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    }
    
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    echo "<h2>Đang tạo bảng bao_cao_su_co...</h2>";
    echo "<pre>";
    
    // Câu lệnh SQL tạo bảng
    $sql = "CREATE TABLE IF NOT EXISTS `bao_cao_su_co` (
      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID báo cáo',
      `id_phan_cong` int(11) DEFAULT NULL COMMENT 'ID phân công HDV (FK: phan_cong_hdv.id)',
      `loai_su_co` varchar(50) DEFAULT NULL COMMENT 'Loại sự cố',
      `mo_ta` text DEFAULT NULL COMMENT 'Mô tả chi tiết về sự cố',
      `cach_xu_ly` text DEFAULT NULL COMMENT 'Cách xử lý sự cố',
      `goi_y_xu_ly` text DEFAULT NULL COMMENT 'Gợi ý xử lý (JSON)',
      `muc_do` enum('thap','trung_binh','cao','nghiem_trong') DEFAULT 'thap' COMMENT 'Mức độ nghiêm trọng',
      `ngay_xay_ra` date DEFAULT NULL COMMENT 'Ngày xảy ra sự cố',
      `gio_xay_ra` time DEFAULT NULL COMMENT 'Giờ xảy ra sự cố',
      `vi_tri_gps` varchar(255) DEFAULT NULL COMMENT 'Vị trí GPS (lat,lng)',
      `hinh_anh` text DEFAULT NULL COMMENT 'Danh sách hình ảnh (JSON array)',
      `thong_tin_khach` text DEFAULT NULL COMMENT 'Thông tin khách hàng liên quan',
      `da_gui_bao_cao` tinyint(1) DEFAULT 0 COMMENT 'Đã gửi báo cáo (0=Chưa, 1=Đã gửi)',
      `ngay_gui_bao_cao` datetime DEFAULT NULL COMMENT 'Ngày giờ gửi báo cáo',
      `nguoi_nhan_bao_cao` varchar(255) DEFAULT NULL COMMENT 'Email người nhận báo cáo',
      `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
      `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
      PRIMARY KEY (`id`),
      KEY `idx_id_phan_cong` (`id_phan_cong`),
      KEY `idx_loai_su_co` (`loai_su_co`),
      KEY `idx_muc_do` (`muc_do`),
      KEY `idx_ngay_xay_ra` (`ngay_xay_ra`),
      KEY `idx_ngay_tao` (`ngay_tao`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý báo cáo sự cố của hướng dẫn viên'";
    
    try {
        $conn->exec($sql);
        echo "✓ Thành công! Bảng bao_cao_su_co đã được tạo.\n\n";
        
        // Kiểm tra xem bảng phan_cong_hdv có tồn tại không
        $checkTable = $conn->query("SHOW TABLES LIKE 'phan_cong_hdv'");
        if ($checkTable->rowCount() > 0) {
            // Thử tạo foreign key nếu bảng phan_cong_hdv tồn tại
            try {
                // Kiểm tra xem foreign key đã tồn tại chưa
                $checkFK = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.KEY_COLUMN_USAGE 
                                        WHERE TABLE_SCHEMA = DATABASE() 
                                        AND TABLE_NAME = 'bao_cao_su_co' 
                                        AND CONSTRAINT_NAME = 'fk_bao_cao_su_co_phan_cong'");
                $fkExists = $checkFK->fetch()['cnt'] > 0;
                
                if (!$fkExists) {
                    $fkSql = "ALTER TABLE `bao_cao_su_co` 
                              ADD CONSTRAINT `fk_bao_cao_su_co_phan_cong` 
                              FOREIGN KEY (`id_phan_cong`) 
                              REFERENCES `phan_cong_hdv` (`id`) 
                              ON DELETE SET NULL ON UPDATE CASCADE";
                    $conn->exec($fkSql);
                    echo "✓ Foreign key constraint đã được tạo.\n";
                } else {
                    echo "✓ Foreign key constraint đã tồn tại.\n";
                }
            } catch (PDOException $e) {
                // Foreign key có thể đã tồn tại hoặc có lỗi khác
                echo "⚠ Không thể tạo foreign key: " . $e->getMessage() . "\n";
                echo "  (Bảng vẫn hoạt động bình thường không có foreign key)\n";
            }
        } else {
            echo "⚠ Bảng phan_cong_hdv chưa tồn tại, bỏ qua foreign key.\n";
        }
        
        echo "\n========================================\n";
        echo "✓ Hoàn tất! Bảng đã được tạo thành công.\n";
        echo "Bạn có thể đóng trang này và sử dụng tính năng báo cáo sự cố.\n";
        echo "========================================\n";
        
    } catch (PDOException $e) {
        echo "✗ Lỗi tạo bảng: " . $e->getMessage() . "\n";
        echo "\nVui lòng kiểm tra:\n";
        echo "1. Database 'starvel' đã tồn tại chưa?\n";
        echo "2. User có quyền CREATE TABLE không?\n";
        echo "3. MySQL server đã được khởi động chưa?\n";
    }
    
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<pre>";
    echo "✗ Lỗi kết nối database: " . htmlspecialchars($e->getMessage()) . "\n\n";
    echo "Vui lòng kiểm tra:\n";
    echo "1. MAMP đã được khởi động chưa?\n";
    echo "2. MySQL server đang chạy không?\n";
    echo "3. Database 'starvel' đã được tạo chưa?\n";
    echo "4. Thông tin kết nối trong commons/env.php có đúng không?\n";
    echo "</pre>";
}

