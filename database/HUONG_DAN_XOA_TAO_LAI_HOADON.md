# 🔄 HƯỚNG DẪN XÓA VÀ TẠO LẠI BẢNG HOADON

## 📋 Mục đích:
Xóa bảng `hoadon` cũ và tạo lại với cấu trúc đúng để đảm bảo không bị lỗi AUTO_INCREMENT.

## ⚠️ LƯU Ý QUAN TRỌNG:
- **Tất cả dữ liệu trong bảng hoadon sẽ bị xóa**
- Nếu bạn có dữ liệu quan trọng, hãy backup trước!
- Sau khi tạo lại, bạn có thể thử đặt tour ngay

---

## 🚀 CÁCH THỰC HIỆN (Chọn 1 trong 2 cách):

### **Cách 1: Chạy SQL File trong phpMyAdmin (Khuyến nghị)**

1. **Mở phpMyAdmin**
   - URL: `http://localhost:8888/phpMyAdmin/` (MAMP)
   - Hoặc `http://localhost/phpmyadmin/` (XAMPP)

2. **Chọn database `starvel`**
   - Click vào database `starvel` ở sidebar bên trái

3. **Vào tab "SQL"**
   - Click tab "SQL" ở menu trên cùng

4. **Import file SQL**
   - Cách 1: Click "Choose File" → Chọn file `database/recreate_hoadon.sql` → Click "Go"
   - Cách 2: Mở file `database/recreate_hoadon.sql` → Copy toàn bộ nội dung → Paste vào ô SQL → Click "Go"

5. **Kiểm tra kết quả**
   - Nếu thành công, sẽ thấy thông báo "Table 'hoadon' has been created"
   - Click vào bảng `hoadon` để xem cấu trúc

---

### **Cách 2: Chạy từng lệnh SQL**

1. **Mở phpMyAdmin** → Chọn database `starvel` → Tab "SQL"

2. **Chạy lệnh xóa bảng:**
```sql
DROP TABLE IF EXISTS `hoadon`;
```

3. **Chạy lệnh tạo bảng (copy toàn bộ):**
```sql
CREATE TABLE `hoadon` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng hóa đơn/booking tour';
```

---

## ✅ KIỂM TRA SAU KHI TẠO:

### 1. Kiểm tra cấu trúc bảng:
Chạy lệnh này trong tab SQL:
```sql
DESCRIBE hoadon;
```

**Kết quả mong đợi:**
- Cột `id_hoadon` phải có:
  - **Type**: `int(11)`
  - **Null**: `NO`
  - **Key**: `PRI` (Primary Key)
  - **Extra**: `auto_increment` ← **QUAN TRỌNG!**

### 2. Test insert:
Chạy lệnh này để test:
```sql
INSERT INTO hoadon (email_nguoidung, nguoilon, trangthai) 
VALUES ('test@example.com', 1, 0);

SELECT * FROM hoadon WHERE email_nguoidung = 'test@example.com';
```

Nếu thấy bản ghi với `id_hoadon = 1`, nghĩa là thành công!

Xóa dữ liệu test:
```sql
DELETE FROM hoadon WHERE email_nguoidung = 'test@example.com';
```

---

## 🎯 SAU KHI HOÀN THÀNH:

1. ✅ Bảng `hoadon` đã được tạo lại với cấu trúc đúng
2. ✅ Cột `id_hoadon` có AUTO_INCREMENT
3. ✅ Tất cả các cột cần thiết đã có
4. ✅ **Bạn có thể thử đặt tour ngay bây giờ!**

---

## 🆘 NẾU GẶP LỖI:

### Lỗi: "Table 'hoadon' already exists"
→ Chạy lại lệnh `DROP TABLE IF EXISTS hoadon;` trước

### Lỗi: "Access denied"
→ Kiểm tra user MySQL có quyền CREATE TABLE và DROP TABLE

### Lỗi: "Unknown database 'starvel'"
→ Tạo database `starvel` trước:
```sql
CREATE DATABASE IF NOT EXISTS starvel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

**Sau khi hoàn thành, thử đặt tour và cho tôi biết kết quả!** 🎉


