# HƯỚNG DẪN SỬ DỤNG TÍNH NĂNG KHUYẾN MÃI CHI TIẾT

## 🎯 Tổng quan
Đã thêm tính năng khuyến mãi chi tiết cho tour bao gồm:
- Phần trăm giảm giá (0-100%)
- Ngày bắt đầu khuyến mãi
- Ngày kết thúc khuyến mãi
- Mô tả khuyến mãi

## 📋 BƯỚC 1: Chạy SQL để thêm cột mới vào database

### Cách 1: Sử dụng phpMyAdmin (Khuyến nghị)
1. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
2. Chọn database `starvel` bên trái
3. Click tab **SQL** ở trên
4. Mở file `database/add_promotion_fields.sql` và copy toàn bộ nội dung
5. Paste vào ô SQL query
6. Click nút **Go** để thực thi

### Cách 2: Sử dụng MySQL Workbench
1. Mở MySQL Workbench
2. Kết nối đến server localhost
3. Mở file `database/add_promotion_fields.sql`
4. Click icon ⚡ (Execute) để chạy

### Cách 3: Sử dụng command line (nếu có MySQL trong PATH)
```bash
cd c:\laragon\www\pro1014
mysql -u root starvel < database/add_promotion_fields.sql
```

## ✅ BƯỚC 2: Kiểm tra đã thêm thành công

Chạy query sau trong phpMyAdmin để kiểm tra:
```sql
DESCRIBE goidulich;
```

Bạn sẽ thấy các cột mới:
- `khuyenmai_phantram` (INT)
- `khuyenmai_tungay` (DATE)
- `khuyenmai_denngay` (DATE)
- `khuyenmai_mota` (VARCHAR 255)

## 🎨 BƯỚC 3: Test tính năng

### Tạo tour mới với khuyến mãi:
1. Đăng nhập Admin
2. Vào **Quản lý Tour** > **Tạo tour mới**
3. Trong phần "Cấu hình Tour":
   - Chọn **✅ Có khuyến mãi**
   - Nhập **Phần trăm giảm giá**: VD: 20 (giảm 20%)
   - Nhập **Mô tả**: VD: "Ưu đãi mùa hè"
   - Chọn **Ngày bắt đầu**: VD: 2024-12-01
   - Chọn **Ngày kết thúc**: VD: 2024-12-31
4. Điền các thông tin còn lại
5. Click **Tạo Tour**

### Kiểm tra:
- Vào danh sách tour xem tour vừa tạo
- Giá tour sẽ được tính với khuyến mãi (giảm theo %)
- Hiển thị badge khuyến mãi và thời gian còn lại

## 📊 Cách tính giá khuyến mãi:

```
Giá sau khuyến mãi = Giá gốc - (Giá gốc × Phần trăm / 100)

Ví dụ:
- Giá gốc: 5,000,000 VNĐ
- Khuyến mãi: 20%
- Giá sau KM: 5,000,000 - (5,000,000 × 20/100) = 4,000,000 VNĐ
```

## 🔧 Các file đã cập nhật:

1. **Database:**
   - `database/add_promotion_fields.sql` - SQL thêm cột mới

2. **Models:**
   - `models/TourModel.php` - Thêm logic lưu/cập nhật khuyến mãi

3. **Views:**
   - `views/admin/tours/create.php` - Form tạo tour với input khuyến mãi
   - *(Cần cập nhật: edit.php, detail.php)*

4. **Controllers:**
   - *(Không cần sửa - validation tự động)*

## 🚀 Tính năng sẽ triển khai tiếp:

1. ✅ Thêm trường database
2. ✅ Form tạo tour với khuyến mãi
3. ⏳ Form sửa tour (edit.php) - **TẠM THỜI CHƯA LÀM**
4. ⏳ Hiển thị badge khuyến mãi trên danh sách tour
5. ⏳ Tự động tính giá sau khuyến mãi
6. ⏳ Hiển thị thời gian còn lại của khuyến mãi
7. ⏳ Tự động hết hạn khuyến mãi

## ⚠️ Lưu ý quan trọng:

- **Chỉ hiển thị form khuyến mãi chi tiết khi chọn "Có khuyến mãi"**
- Phần trăm giảm giá từ 0-100%
- Ngày kết thúc phải sau ngày bắt đầu
- Nếu chọn "Không khuyến mãi", các giá trị khuyến mãi sẽ được set NULL/0

## 📞 Hỗ trợ:

Nếu gặp lỗi khi chạy SQL:
- Kiểm tra đã chọn đúng database `starvel`
- Kiểm tra MySQL/MariaDB đang chạy
- Xem log lỗi trong phpMyAdmin hoặc MySQL Workbench
