<?php
/**
 * Script tạo bảng hoadon nếu chưa tồn tại
 * Chạy file này một lần để đảm bảo bảng hoadon đã được tạo
 */

// Get the correct path based on where script is run from
$basePath = dirname(__DIR__);
require_once $basePath . '/commons/env.php';
require_once $basePath . '/commons/function.php';

echo "=== Kiểm tra và tạo bảng hoadon ===\n\n";

try {
    $conn = connectDB();
    
    // Kiểm tra xem bảng đã tồn tại chưa
    $checkTable = $conn->query("SHOW TABLES LIKE 'hoadon'");
    if ($checkTable->rowCount() > 0) {
        echo "✓ Bảng 'hoadon' đã tồn tại.\n";
        
        // Kiểm tra các cột quan trọng
        $columns = $conn->query("SHOW COLUMNS FROM hoadon")->fetchAll(PDO::FETCH_COLUMN);
        $requiredColumns = ['id_hoadon', 'id_goi', 'email_nguoidung', 'nguoilon', 'treem', 'trenho', 'embe', 'phongdon', 'ngayvao', 'ngayra', 'sophong', 'ghichu', 'trangthai', 'ngaydat'];
        
        $missingColumns = array_diff($requiredColumns, $columns);
        
        if (empty($missingColumns)) {
            echo "✓ Tất cả các cột cần thiết đã có.\n";
        } else {
            echo "⚠ Cảnh báo: Thiếu các cột sau: " . implode(', ', $missingColumns) . "\n";
            echo "Vui lòng chạy lại file hoadon.sql để cập nhật cấu trúc bảng.\n";
        }
    } else {
        echo "✗ Bảng 'hoadon' chưa tồn tại. Đang tạo bảng...\n";
        
        // Đọc và thực thi file SQL
        $sqlFile = __DIR__ . '/hoadon.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $conn->exec($sql);
            echo "✓ Đã tạo bảng 'hoadon' thành công!\n";
        } else {
            echo "✗ Không tìm thấy file hoadon.sql\n";
            echo "Đang tạo bảng trực tiếp...\n";
            
            // Tạo bảng trực tiếp
            $createTableSQL = "CREATE TABLE IF NOT EXISTS `hoadon` (
              `id_hoadon` int(11) NOT NULL AUTO_INCREMENT,
              `id_goi` int(11) DEFAULT NULL COMMENT 'ID gói du lịch',
              `id_ks` int(11) DEFAULT NULL COMMENT 'ID khách sạn',
              `email_nguoidung` varchar(255) NOT NULL COMMENT 'Email khách hàng',
              `nguoilon` int(11) DEFAULT 1 COMMENT 'Số người lớn',
              `treem` int(11) DEFAULT 0 COMMENT 'Số trẻ em',
              `trenho` int(11) DEFAULT 0 COMMENT 'Số trẻ nhỏ',
              `embe` int(11) DEFAULT 0 COMMENT 'Số em bé',
              `phongdon` tinyint(1) DEFAULT 0 COMMENT 'Có phòng đơn không',
              `ngayvao` date DEFAULT NULL COMMENT 'Ngày vào/khởi hành',
              `ngayra` date DEFAULT NULL COMMENT 'Ngày ra/kết thúc',
              `sophong` int(11) DEFAULT 1 COMMENT 'Số phòng',
              `ghichu` text DEFAULT NULL COMMENT 'Ghi chú',
              `trangthai` tinyint(1) DEFAULT 0 COMMENT '0: Chờ xác nhận, 1: Đã xác nhận, 2: Hoàn thành',
              `huy` tinyint(1) DEFAULT 0 COMMENT 'Đã hủy',
              `ngaydat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày đặt',
              `ngaycapnhat` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
              PRIMARY KEY (`id_hoadon`),
              KEY `idx_id_goi` (`id_goi`),
              KEY `idx_email` (`email_nguoidung`),
              KEY `idx_trangthai` (`trangthai`),
              KEY `idx_ngaydat` (`ngaydat`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng hóa đơn/booking tour'";
            
            $conn->exec($createTableSQL);
            echo "✓ Đã tạo bảng 'hoadon' thành công!\n";
        }
    }
    
    echo "\n=== Hoàn thành ===\n";
    
} catch (PDOException $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    echo "\nVui lòng kiểm tra:\n";
    echo "1. Database connection settings trong commons/env.php\n";
    echo "2. Database 'starvel' đã được tạo chưa?\n";
    echo "3. User có quyền CREATE TABLE không?\n";
    exit(1);
}

