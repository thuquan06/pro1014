<?php
/**
 * Script sửa bảng hoadon - Đảm bảo id_hoadon có AUTO_INCREMENT
 */

$basePath = dirname(__DIR__);
require_once $basePath . '/commons/env.php';
require_once $basePath . '/commons/function.php';

echo "=== Sửa bảng hoadon ===\n\n";

try {
    $conn = connectDB();
    
    // Kiểm tra cấu trúc bảng hiện tại
    echo "1. Kiểm tra cấu trúc bảng hiện tại...\n";
    $columns = $conn->query("SHOW COLUMNS FROM hoadon LIKE 'id_hoadon'")->fetch(PDO::FETCH_ASSOC);
    
    if ($columns) {
        echo "   Cột id_hoadon hiện tại:\n";
        echo "   - Type: " . $columns['Type'] . "\n";
        echo "   - Null: " . $columns['Null'] . "\n";
        echo "   - Key: " . $columns['Key'] . "\n";
        echo "   - Extra: " . $columns['Extra'] . "\n\n";
        
        // Kiểm tra xem có AUTO_INCREMENT chưa
        if (strpos($columns['Extra'], 'auto_increment') === false) {
            echo "2. Cột id_hoadon chưa có AUTO_INCREMENT. Đang sửa...\n";
            
            // Sửa cột id_hoadon để có AUTO_INCREMENT
            $sql = "ALTER TABLE `hoadon` 
                    MODIFY `id_hoadon` int(11) NOT NULL AUTO_INCREMENT";
            
            $conn->exec($sql);
            echo "   ✓ Đã thêm AUTO_INCREMENT cho id_hoadon\n\n";
        } else {
            echo "2. ✓ Cột id_hoadon đã có AUTO_INCREMENT\n\n";
        }
    } else {
        echo "   ✗ Không tìm thấy cột id_hoadon. Bảng có thể chưa tồn tại.\n";
        echo "   Vui lòng chạy create_hoadon_table.php trước.\n";
        exit(1);
    }
    
    // Kiểm tra PRIMARY KEY
    echo "3. Kiểm tra PRIMARY KEY...\n";
    $keys = $conn->query("SHOW KEYS FROM hoadon WHERE Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC);
    
    if (!$keys) {
        echo "   ✗ Chưa có PRIMARY KEY. Đang thêm...\n";
        $conn->exec("ALTER TABLE `hoadon` ADD PRIMARY KEY (`id_hoadon`)");
        echo "   ✓ Đã thêm PRIMARY KEY\n\n";
    } else {
        echo "   ✓ Đã có PRIMARY KEY\n\n";
    }
    
    // Kiểm tra các cột khác
    echo "4. Kiểm tra các cột cần thiết...\n";
    $allColumns = $conn->query("SHOW COLUMNS FROM hoadon")->fetchAll(PDO::FETCH_COLUMN);
    $requiredColumns = [
        'id_hoadon', 'id_goi', 'id_ks', 'email_nguoidung', 
        'nguoilon', 'treem', 'trenho', 'embe', 'phongdon', 
        'ngayvao', 'ngayra', 'sophong', 'ghichu', 'trangthai', 
        'huy', 'ngaydat', 'ngaycapnhat'
    ];
    
    $missingColumns = array_diff($requiredColumns, $allColumns);
    
    if (empty($missingColumns)) {
        echo "   ✓ Tất cả các cột cần thiết đã có\n\n";
    } else {
        echo "   ⚠ Thiếu các cột: " . implode(', ', $missingColumns) . "\n";
        echo "   Vui lòng chạy lại file hoadon.sql để cập nhật cấu trúc.\n\n";
    }
    
    // Test insert
    echo "5. Test insert (sẽ rollback)...\n";
    $conn->beginTransaction();
    try {
        $testSql = "INSERT INTO hoadon (email_nguoidung, nguoilon, trangthai) VALUES ('test@example.com', 1, 0)";
        $conn->exec($testSql);
        $testId = $conn->lastInsertId();
        $conn->rollBack();
        
        if ($testId) {
            echo "   ✓ Test insert thành công! ID được tạo: $testId\n";
        } else {
            echo "   ✗ Test insert thất bại - không tạo được ID\n";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "   ✗ Test insert thất bại: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Hoàn thành ===\n";
    echo "Bây giờ bạn có thể thử đặt tour lại.\n";
    
} catch (PDOException $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    echo "\nVui lòng kiểm tra:\n";
    echo "1. Database connection settings trong commons/env.php\n";
    echo "2. Database 'starvel' đã được tạo chưa?\n";
    echo "3. User có quyền ALTER TABLE không?\n";
    exit(1);
}


