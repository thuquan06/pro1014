# Hướng dẫn cập nhật bảng hoadon - Thêm trường lý do hủy

## Mô tả
Cập nhật này thêm trường `ly_do_huy` vào bảng `hoadon` để lưu lý do khi hủy đơn hàng.

## Các thay đổi

### 1. Database
- Thêm trường `ly_do_huy` (TEXT) vào bảng `hoadon`

### 2. Model (HoadonModel.php)
- Cập nhật method `cancelHoadon()` để nhận và lưu lý do hủy
- Thêm method `confirmHoadon()` để xác nhận hóa đơn
- Thêm method `completeHoadon()` để đánh dấu hoàn thành

### 3. Controller (HoadonController.php)
- Cập nhật method `cancel()` để yêu cầu lý do hủy
- Thêm method `confirm()` để xác nhận hóa đơn
- Thêm method `complete()` để hoàn thành hóa đơn

### 4. Views
- Cập nhật `views/admin/hoadon/detail.php`:
  - Thêm nút "Xác nhận" khi trạng thái = 0 (Chờ xác nhận)
  - Thêm nút "Hoàn thành" khi trạng thái = 1 (Đã xác nhận)
  - Cập nhật nút "Hủy" với modal form nhập lý do
  - Hiển thị lý do hủy nếu đơn đã bị hủy
  
- Cập nhật `views/admin/hoadon/list.php`:
  - Thêm nút "Xác nhận" cho đơn chờ xác nhận
  - Thêm nút "Hoàn thành" cho đơn đã xác nhận

### 5. Routes (index.php)
- Thêm route `hoadon-confirm` → `HoadonController::confirm()`
- Thêm route `hoadon-complete` → `HoadonController::complete()`

## Cách chạy migration

### Cách 1: Chạy trực tiếp trong phpMyAdmin
1. Mở phpMyAdmin
2. Chọn database `starvel`
3. Vào tab "SQL"
4. Copy và paste nội dung file `add_ly_do_huy_to_hoadon.sql`
5. Click "Go" để chạy

### Cách 2: Chạy qua command line
```bash
mysql -u root -p starvel < database/add_ly_do_huy_to_hoadon.sql
```

### Cách 3: Chạy qua PHP script
```php
// Tạo file run_migration.php trong thư mục database/
require_once '../commons/env.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USERNAME,
        DB_PASSWORD
    );
    
    $sql = file_get_contents(__DIR__ . '/add_ly_do_huy_to_hoadon.sql');
    $pdo->exec($sql);
    
    echo "Migration thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
```

## Kiểm tra kết quả

Sau khi chạy migration, kiểm tra bằng SQL:
```sql
DESCRIBE hoadon;
```

Bạn sẽ thấy trường `ly_do_huy` trong danh sách các cột.

## Cách sử dụng

### Xác nhận đơn hàng
- Vào trang chi tiết hóa đơn hoặc danh sách hóa đơn
- Click nút "Xác nhận" (màu xanh dương) khi đơn ở trạng thái "Chờ xác nhận"
- Đơn sẽ chuyển sang trạng thái "Đã xác nhận"

### Hoàn thành đơn hàng
- Vào trang chi tiết hóa đơn hoặc danh sách hóa đơn
- Click nút "Hoàn thành" (màu xanh lá) khi đơn ở trạng thái "Đã xác nhận"
- Đơn sẽ chuyển sang trạng thái "Hoàn thành"

### Hủy đơn hàng
- Vào trang chi tiết hóa đơn
- Click nút "Hủy hóa đơn" (màu đỏ)
- Nhập lý do hủy vào form modal
- Click "Xác nhận hủy"
- Đơn sẽ được đánh dấu là đã hủy và lý do sẽ được lưu lại

## Lưu ý
- Khi hủy đơn hàng, **bắt buộc** phải nhập lý do hủy
- Lý do hủy sẽ được hiển thị trong trang chi tiết hóa đơn
- Đơn đã hủy không thể xác nhận hoặc hoàn thành

