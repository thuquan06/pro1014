<?php
/**
 * Script tự động kiểm tra và tạo/sửa bảng lich_khoi_hanh với đầy đủ các cột
 * Chạy file này một lần để đảm bảo bảng có đầy đủ cấu trúc
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

try {
    $conn = connectDB();
    
    echo "=== Kiểm tra và sửa bảng lich_khoi_hanh ===\n\n";
    
    // Kiểm tra xem bảng có tồn tại không
    $tableExists = $conn->query("SHOW TABLES LIKE 'lich_khoi_hanh'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "→ Bảng lich_khoi_hanh chưa tồn tại. Đang tạo bảng mới...\n";
        
        $createTableSQL = "CREATE TABLE `lich_khoi_hanh` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour',
            `ngay_khoi_hanh` DATE NULL DEFAULT NULL COMMENT 'Ngày khởi hành',
            `gio_khoi_hanh` TIME NULL DEFAULT NULL COMMENT 'Giờ khởi hành',
            `gio_tap_trung` TIME NULL DEFAULT NULL COMMENT 'Giờ tập trung',
            `diem_tap_trung` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Điểm tập trung',
            `so_cho_con_trong` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống',
            `phuong_tien` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Phương tiện di chuyển',
            `uu_dai_giam_gia` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Ưu đãi giảm giá (%)',
            `ghi_chu_van_hanh` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú vận hành',
            `trang_thai` TINYINT(1) DEFAULT 1 COMMENT 'Trạng thái: 0=Đóng, 1=Mở bán, 2=Hết chỗ',
            `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
            `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
            PRIMARY KEY (`id`),
            KEY `idx_id_tour` (`id_tour`),
            KEY `idx_ngay_khoi_hanh` (`ngay_khoi_hanh`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lịch khởi hành'";
        
        $conn->exec($createTableSQL);
        echo "✓ Đã tạo bảng lich_khoi_hanh thành công!\n\n";
    } else {
        echo "✓ Bảng lich_khoi_hanh đã tồn tại.\n";
        echo "→ Đang kiểm tra cột id...\n";
        
        // Kiểm tra cột id có đúng không
        $idColumnInfo = $conn->query("SHOW COLUMNS FROM lich_khoi_hanh WHERE Field = 'id'")->fetch(PDO::FETCH_ASSOC);
        if ($idColumnInfo) {
            $isAutoIncrement = strpos($idColumnInfo['Extra'], 'auto_increment') !== false;
            $isPrimary = $idColumnInfo['Key'] === 'PRI';
            
            echo "  Cột id - Auto Increment: " . ($isAutoIncrement ? "✓" : "✗") . "\n";
            echo "  Cột id - Primary Key: " . ($isPrimary ? "✓" : "✗") . "\n";
            
            if (!$isAutoIncrement || !$isPrimary) {
                echo "→ Đang sửa cột id thành AUTO_INCREMENT và PRIMARY KEY...\n";
                try {
                    // Kiểm tra xem có PRIMARY KEY nào khác không
                    $primaryKeys = $conn->query("SHOW KEYS FROM lich_khoi_hanh WHERE Key_name = 'PRIMARY'")->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($primaryKeys) || (count($primaryKeys) == 1 && $primaryKeys[0]['Column_name'] === 'id')) {
                        // Sửa cột id thành AUTO_INCREMENT và PRIMARY KEY
                        $conn->exec("ALTER TABLE `lich_khoi_hanh` 
                            MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT,
                            ADD PRIMARY KEY (`id`)");
                        echo "  ✓ Đã sửa cột id thành AUTO_INCREMENT và PRIMARY KEY\n";
                    } else {
                        // Nếu đã có PRIMARY KEY khác, chỉ sửa AUTO_INCREMENT
                        $conn->exec("ALTER TABLE `lich_khoi_hanh` 
                            MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");
                        echo "  ✓ Đã sửa cột id thành AUTO_INCREMENT\n";
                    }
                } catch (PDOException $e) {
                    echo "  ✗ Lỗi sửa cột id: " . $e->getMessage() . "\n";
                }
            } else {
                echo "  ✓ Cột id đã đúng cấu hình\n";
            }
        } else {
            echo "→ Cột id chưa tồn tại. Đang thêm cột id...\n";
            try {
                $conn->exec("ALTER TABLE `lich_khoi_hanh` 
                    ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
                echo "  ✓ Đã thêm cột id làm PRIMARY KEY và AUTO_INCREMENT\n";
            } catch (PDOException $e) {
                echo "  ✗ Lỗi thêm cột id: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n→ Đang kiểm tra các cột khác...\n\n";
    }
    
    // Lấy danh sách các cột hiện có
    $existingColumns = $conn->query("SHOW COLUMNS FROM lich_khoi_hanh")->fetchAll(PDO::FETCH_COLUMN);
    $existingColumnsLower = array_map('strtolower', $existingColumns);
    
    echo "Các cột hiện có: " . implode(', ', $existingColumns) . "\n\n";
    
    // Danh sách các cột cần thiết
    $requiredColumns = [
        'id_tour' => "`id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour'",
        'ngay_khoi_hanh' => "`ngay_khoi_hanh` DATE NULL DEFAULT NULL COMMENT 'Ngày khởi hành'",
        'gio_khoi_hanh' => "`gio_khoi_hanh` TIME NULL DEFAULT NULL COMMENT 'Giờ khởi hành'",
        'gio_tap_trung' => "`gio_tap_trung` TIME NULL DEFAULT NULL COMMENT 'Giờ tập trung'",
        'diem_tap_trung' => "`diem_tap_trung` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Điểm tập trung'",
        'so_cho_con_trong' => "`so_cho_con_trong` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống'",
        'phuong_tien' => "`phuong_tien` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Phương tiện di chuyển'",
        'uu_dai_giam_gia' => "`uu_dai_giam_gia` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Ưu đãi giảm giá (%)'",
        'ghi_chu_van_hanh' => "`ghi_chu_van_hanh` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú vận hành'",
        'trang_thai' => "`trang_thai` TINYINT(1) DEFAULT 1 COMMENT 'Trạng thái: 0=Đóng, 1=Mở bán, 2=Hết chỗ'",
        'ngay_tao' => "`ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo'",
        'ngay_cap_nhat' => "`ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật'"
    ];
    
    $addedCount = 0;
    $previousColumn = 'id'; // Bắt đầu sau cột id
    
    foreach ($requiredColumns as $columnName => $columnDef) {
        if (!in_array(strtolower($columnName), $existingColumnsLower)) {
            echo "→ Đang thêm cột: {$columnName}...\n";
            try {
                $sql = "ALTER TABLE `lich_khoi_hanh` ADD COLUMN {$columnDef} AFTER `{$previousColumn}`";
                $conn->exec($sql);
                echo "  ✓ Đã thêm cột {$columnName}\n";
                $addedCount++;
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                    echo "  ⚠ Cột {$columnName} đã tồn tại (có thể do đã được thêm trước đó)\n";
                } else {
                    echo "  ✗ Lỗi thêm cột {$columnName}: " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "  ✓ Cột {$columnName} đã tồn tại\n";
        }
        $previousColumn = $columnName;
    }
    
    if ($addedCount > 0) {
        echo "\n✓ Đã thêm {$addedCount} cột mới vào bảng lich_khoi_hanh\n";
    } else {
        echo "\n✓ Tất cả các cột đã tồn tại. Không cần thay đổi.\n";
    }
    
    // Kiểm tra lại cấu trúc
    echo "\n=== Cấu trúc bảng sau khi kiểm tra ===\n";
    $columns = $conn->query("SHOW COLUMNS FROM lich_khoi_hanh")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo sprintf("  - %s (%s)\n", $col['Field'], $col['Type']);
    }
    
    echo "\n=== Hoàn tất ===\n";
    
} catch (PDOException $e) {
    echo "✗ Lỗi PDO: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

