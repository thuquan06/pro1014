<?php
/**
 * Script tự động thêm cột phuong_tien vào bảng lich_khoi_hanh
 * Chạy file này một lần để cập nhật database
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

try {
    $conn = connectDB();
    
    echo "=== Kiểm tra và thêm cột phuong_tien vào bảng lich_khoi_hanh ===\n\n";
    
    // Kiểm tra xem cột đã tồn tại chưa
    $checkColumn = $conn->query("SHOW COLUMNS FROM lich_khoi_hanh LIKE 'phuong_tien'");
    
    if ($checkColumn->rowCount() > 0) {
        echo "✓ Cột phuong_tien đã tồn tại trong bảng lich_khoi_hanh\n";
        echo "  Không cần thực hiện thay đổi.\n";
    } else {
        echo "→ Đang thêm cột phuong_tien...\n";
        
        // Thêm cột
        $sql = "ALTER TABLE `lich_khoi_hanh` 
                ADD COLUMN `phuong_tien` VARCHAR(255) NULL DEFAULT NULL 
                COMMENT 'Phương tiện di chuyển (xe khách, máy bay, tàu hỏa, v.v.)' 
                AFTER `so_cho_con_trong`";
        
        $conn->exec($sql);
        
        echo "✓ Đã thêm cột phuong_tien thành công!\n\n";
        
        // Kiểm tra lại
        $verify = $conn->query("SHOW COLUMNS FROM lich_khoi_hanh LIKE 'phuong_tien'");
        if ($verify->rowCount() > 0) {
            echo "✓ Xác nhận: Cột phuong_tien đã được tạo thành công.\n";
        }
    }
    
    echo "\n=== Hoàn tất ===\n";
    
} catch (PDOException $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

