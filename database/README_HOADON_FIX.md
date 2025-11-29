# Hướng dẫn sửa lỗi "Đặt tour thất bại"

## Nguyên nhân có thể gây lỗi:

1. **Bảng `hoadon` chưa tồn tại trong database**
2. **Bảng `hoadon` thiếu các cột cần thiết**
3. **Lỗi kết nối database**
4. **Lỗi constraint hoặc foreign key**

## Cách kiểm tra và sửa:

### Bước 1: Kiểm tra bảng hoadon có tồn tại không

Chạy script kiểm tra:
```bash
php database/create_hoadon_table.php
```

Hoặc kiểm tra trực tiếp trong MySQL:
```sql
SHOW TABLES LIKE 'hoadon';
```

### Bước 2: Tạo bảng hoadon nếu chưa có

**Cách 1: Chạy script PHP (Khuyến nghị)**
```bash
php database/create_hoadon_table.php
```

**Cách 2: Import file SQL**
```bash
mysql -u root -p starvel < database/hoadon.sql
```

Hoặc trong phpMyAdmin:
1. Chọn database `starvel`
2. Vào tab "Import"
3. Chọn file `database/hoadon.sql`
4. Click "Go"

**Cách 3: Chạy SQL trực tiếp**
Mở file `database/hoadon.sql` và copy toàn bộ SQL, sau đó chạy trong MySQL client hoặc phpMyAdmin.

### Bước 3: Kiểm tra error log

Sau khi đã tạo bảng, thử đặt tour lại và kiểm tra PHP error log:

**Trên MAMP:**
- Mac: `/Applications/MAMP/logs/php_error.log`
- Windows: `C:\MAMP\logs\php_error.log`

**Hoặc kiểm tra trong code:**
- Mở file `controllers/ProductController.php` và `models/HoadonModel.php`
- Các lỗi sẽ được ghi vào error_log với thông tin chi tiết

### Bước 4: Kiểm tra cấu hình database

Kiểm tra file `commons/env.php`:
- `DB_HOST`: Thường là `localhost`
- `DB_PORT`: Với MAMP thường là `8889`
- `DB_NAME`: Phải là `starvel`
- `DB_USERNAME`: Thường là `root`
- `DB_PASSWORD`: Thường là `root` (MAMP)

### Bước 5: Kiểm tra các cột bắt buộc

Bảng `hoadon` phải có các cột sau:
- `id_hoadon` (AUTO_INCREMENT, PRIMARY KEY)
- `id_goi` (int, nullable)
- `id_ks` (int, nullable)
- `email_nguoidung` (varchar(255), NOT NULL)
- `nguoilon` (int, default 1)
- `treem` (int, default 0)
- `trenho` (int, default 0)
- `embe` (int, default 0)
- `phongdon` (tinyint, default 0)
- `ngayvao` (date, nullable)
- `ngayra` (date, nullable)
- `sophong` (int, default 1)
- `ghichu` (text, nullable)
- `trangthai` (tinyint, default 0)
- `huy` (tinyint, default 0)
- `ngaydat` (datetime, default CURRENT_TIMESTAMP)
- `ngaycapnhat` (datetime, nullable)

## Debug thêm:

Nếu vẫn còn lỗi, kiểm tra:

1. **Xem error log chi tiết:**
   - Mở PHP error log
   - Tìm các dòng có "Lỗi createHoadon" hoặc "Booking failed"
   - Copy thông tin lỗi để debug

2. **Test kết nối database:**
   ```php
   <?php
   require_once 'commons/env.php';
   require_once 'commons/function.php';
   try {
       $conn = connectDB();
       echo "Kết nối database thành công!";
   } catch (Exception $e) {
       echo "Lỗi: " . $e->getMessage();
   }
   ```

3. **Test insert trực tiếp:**
   ```sql
   INSERT INTO hoadon (email_nguoidung, nguoilon, trangthai) 
   VALUES ('test@example.com', 1, 0);
   ```

## Liên hệ hỗ trợ:

Nếu vẫn không giải quyết được, vui lòng cung cấp:
1. Thông tin lỗi từ PHP error log
2. Kết quả chạy `create_hoadon_table.php`
3. Cấu hình database trong `commons/env.php` (ẩn password)


