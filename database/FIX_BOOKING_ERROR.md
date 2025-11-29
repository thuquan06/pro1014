# 🔧 HƯỚNG DẪN SỬA LỖI "Đặt tour thất bại"

## ❌ Lỗi hiện tại:
```
SQLSTATE[HY000]: General error: 1364 Field 'id_hoadon' doesn't have a default value
```

## 🔍 Nguyên nhân:
Bảng `hoadon` đã tồn tại nhưng cột `id_hoadon` **không có AUTO_INCREMENT**, khiến MySQL không thể tự động tạo ID mới khi insert.

## ✅ CÁCH SỬA (Chọn 1 trong 3 cách):

### **Cách 1: Sửa bằng phpMyAdmin (Khuyến nghị - Dễ nhất)**

1. Mở **phpMyAdmin** (thường là `http://localhost:8888/phpMyAdmin/` với MAMP)
2. Chọn database **`starvel`** ở sidebar bên trái
3. Click vào bảng **`hoadon`**
4. Vào tab **"Structure"** (Cấu trúc)
5. Tìm cột **`id_hoadon`** và click icon **"Change"** (biểu tượng bút chì)
6. Trong phần **"A_I"** (Auto Increment), tick vào checkbox để bật AUTO_INCREMENT
7. Click **"Save"** (Lưu)

**HOẶC** chạy SQL trực tiếp trong tab **"SQL"**:
```sql
ALTER TABLE `hoadon` 
MODIFY `id_hoadon` int(11) NOT NULL AUTO_INCREMENT;
```

### **Cách 2: Sửa bằng MySQL Command Line**

Mở Terminal và chạy:
```bash
mysql -u root -proot -h 127.0.0.1 -P 8889 starvel < database/fix_hoadon_id_autoincrement.sql
```

Hoặc kết nối MySQL và chạy:
```sql
USE starvel;
ALTER TABLE `hoadon` MODIFY `id_hoadon` int(11) NOT NULL AUTO_INCREMENT;
```

### **Cách 3: Sửa bằng file SQL**

1. Mở file `database/fix_hoadon_id_autoincrement.sql`
2. Copy toàn bộ nội dung
3. Mở phpMyAdmin → Chọn database `starvel` → Tab "SQL"
4. Paste và chạy

## 🧪 KIỂM TRA SAU KHI SỬA:

Chạy lệnh này trong phpMyAdmin (tab SQL) để kiểm tra:
```sql
DESCRIBE hoadon;
```

Kết quả mong đợi:
- Cột `id_hoadon` phải có **Extra = "auto_increment"**
- Cột `id_hoadon` phải có **Key = "PRI"** (Primary Key)

Hoặc chạy:
```sql
SHOW CREATE TABLE hoadon;
```

Phải thấy: `id_hoadon` int(11) NOT NULL AUTO_INCREMENT

## ✅ SAU KHI SỬA XONG:

1. **Thử đặt tour lại** trên website
2. Nếu vẫn lỗi, kiểm tra PHP error log:
   - MAMP Mac: `/Applications/MAMP/logs/php_error.log`
   - Hoặc xem trong terminal: `tail -f /Applications/MAMP/logs/php_error.log`

## 📝 LƯU Ý:

- Nếu bảng `hoadon` chưa tồn tại, chạy file `database/hoadon.sql` trước
- Nếu có dữ liệu trong bảng, việc sửa AUTO_INCREMENT sẽ không làm mất dữ liệu
- Đảm bảo MAMP/MySQL đang chạy trước khi sửa

## 🆘 NẾU VẪN LỖI:

Kiểm tra các điều sau:
1. ✅ MySQL/MAMP đã khởi động chưa?
2. ✅ Database `starvel` đã được tạo chưa?
3. ✅ Bảng `hoadon` đã tồn tại chưa?
4. ✅ Cột `id_hoadon` đã có AUTO_INCREMENT chưa? (chạy `DESCRIBE hoadon;` để kiểm tra)

---

**Sau khi sửa xong, thử đặt tour lại và cho tôi biết kết quả!** 🎉


