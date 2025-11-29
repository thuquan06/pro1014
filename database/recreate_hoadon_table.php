<?php
/**
 * Script xóa và tạo lại bảng hoadon với cấu trúc đúng
 * Đảm bảo không bị lỗi AUTO_INCREMENT
 */

$basePath = dirname(__DIR__);
require_once $basePath . '/commons/env.php';
require_once $basePath . '/commons/function.php';

echo "=== Xóa và tạo lại bảng hoadon ===\n\n";

try {
    $conn = connectDB();
    
    // Bước 1: Kiểm tra bảng có tồn tại không
    echo "1. Kiểm tra bảng hoadon...\n";
    $checkTable = $conn->query("SHOW TABLES LIKE 'hoadon'");
    
    if ($checkTable->rowCount() > 0) {
        echo "   ✓ Bảng hoadon đã tồn tại\n";
        
        // Đếm số bản ghi
        $count = $conn->query("SELECT COUNT(*) as total FROM hoadon")->fetch(PDO::FETCH_ASSOC);
        $totalRecords = $count['total'] ?? 0;
        
        if ($totalRecords > 0) {
            echo "   ⚠ Cảnh báo: Bảng có $totalRecords bản ghi!\n";
            echo "   Bạn có chắc muốn xóa tất cả dữ liệu? (y/n): ";
            // Trong script tự động, chúng ta sẽ xóa luôn
            echo "y (tự động)\n";
        }
        
        // Bước 2: Xóa bảng
        echo "\n2. Đang xóa bảng hoadon cũ...\n";
        $conn->exec("DROP TABLE IF EXISTS `hoadon`");
        echo "   ✓ Đã xóa bảng hoadon\n";
    } else {
        echo "   ℹ Bảng hoadon chưa tồn tại\n";
    }
    
    // Bước 3: Tạo lại bảng với cấu trúc đúng
    echo "\n3. Đang tạo lại bảng hoadon với cấu trúc đúng...\n";
    
    $createTableSQL = "CREATE TABLE `hoadon` (
      `id_hoadon` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID hóa đơn',
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
    echo "   ✓ Đã tạo bảng hoadon thành công!\n";
    
    // Bước 4: Kiểm tra cấu trúc
    echo "\n4. Kiểm tra cấu trúc bảng...\n";
    $columns = $conn->query("SHOW COLUMNS FROM hoadon")->fetchAll(PDO::FETCH_ASSOC);
    
    $idColumn = null;
    foreach ($columns as $col) {
        if ($col['Field'] === 'id_hoadon') {
            $idColumn = $col;
            break;
        }
    }
    
    if ($idColumn) {
        echo "   Cột id_hoadon:\n";
        echo "   - Type: " . $idColumn['Type'] . "\n";
        echo "   - Null: " . $idColumn['Null'] . "\n";
        echo "   - Key: " . $idColumn['Key'] . "\n";
        echo "   - Extra: " . $idColumn['Extra'] . "\n";
        
        if (strpos($idColumn['Extra'], 'auto_increment') !== false) {
            echo "   ✓ AUTO_INCREMENT đã được thiết lập đúng!\n";
        } else {
            echo "   ✗ Lỗi: AUTO_INCREMENT chưa được thiết lập!\n";
        }
        
        if ($idColumn['Key'] === 'PRI') {
            echo "   ✓ PRIMARY KEY đã được thiết lập đúng!\n";
        } else {
            echo "   ✗ Lỗi: PRIMARY KEY chưa được thiết lập!\n";
        }
    }
    
    // Bước 5: Test insert
    echo "\n5. Test insert dữ liệu mẫu...\n";
    $conn->beginTransaction();
    try {
        $testSql = "INSERT INTO hoadon (email_nguoidung, nguoilon, trangthai) VALUES ('test@example.com', 1, 0)";
        $conn->exec($testSql);
        $testId = $conn->lastInsertId();
        $conn->rollBack();
        
        if ($testId && $testId > 0) {
            echo "   ✓ Test insert thành công! ID được tạo: $testId\n";
            echo "   ✓ Bảng hoadon đã sẵn sàng sử dụng!\n";
        } else {
            echo "   ✗ Test insert thất bại - không tạo được ID\n";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "   ✗ Test insert thất bại: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n=== Hoàn thành ===\n";
    echo "✓ Bảng hoadon đã được tạo lại thành công!\n";
    echo "✓ Bạn có thể thử đặt tour ngay bây giờ.\n";
    
} catch (PDOException $e) {
    echo "\n✗ Lỗi PDO: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "\nVui lòng kiểm tra:\n";
    echo "1. MySQL/MAMP đã được khởi động chưa?\n";
    echo "2. Database 'starvel' đã được tạo chưa?\n";
    echo "3. User có quyền CREATE TABLE và DROP TABLE không?\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}


