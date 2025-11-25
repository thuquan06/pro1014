# 🚀 QUICK START - CẤU HÌNH EMAIL TRONG 5 PHÚT

## 📝 CHECKLIST NHANH

- [ ] 1. Thêm cột Email vào database
- [ ] 2. Cập nhật email cho admin
- [ ] 3. Tạo Gmail App Password
- [ ] 4. Cấu hình SMTP trong env.php
- [ ] 5. Test gửi email

---

## ⚡ THỰC HIỆN NHANH

### 1️⃣ Database (1 phút)

Mở phpMyAdmin → Chọn database `starvel` → Tab SQL → Chạy:

```sql
-- Thêm cột Email
ALTER TABLE `admin` 
ADD COLUMN `Email` VARCHAR(255) NULL DEFAULT NULL 
AFTER `Password`;

-- Cập nhật email cho admin
UPDATE `admin` 
SET `Email` = 'your-email@gmail.com' 
WHERE `UserName` = 'admin';
```

*(Thay `your-email@gmail.com` bằng email thật)*

---

### 2️⃣ Gmail App Password (2 phút)

1. **Truy cập**: https://myaccount.google.com/apppasswords
2. **Chọn**:
   - App: **Mail**
   - Device: **Other (Custom name)**
   - Tên: **StarVel**
3. **Click**: Generate
4. **Copy** mật khẩu 16 ký tự (ví dụ: `abcd efgh ijkl mnop`)

---

### 3️⃣ Cấu hình SMTP (1 phút)

Mở file: `commons/env.php`

Tìm và sửa các dòng sau:

```php
define('SMTP_USERNAME', 'your-email@gmail.com');     // ← Email Gmail của bạn
define('SMTP_PASSWORD', 'abcdefghijklmnop');         // ← App Password (bỏ dấu cách)
define('SMTP_FROM_EMAIL', 'your-email@gmail.com');  // ← Email người gửi
```

---

### 4️⃣ Test (1 phút)

1. Vào: `http://localhost/pro1014/?act=forgot-password`
2. Nhập:
   - Username: `admin`
   - Email: Email bạn vừa cập nhật trong database
3. Click "Gửi link reset"
4. Kiểm tra email (cả thư mục Spam)

---

## ✅ HOÀN THÀNH!

Nếu nhận được email → **Thành công!** 🎉

Nếu không nhận được → Xem phần Troubleshooting trong `HUONG_DAN_CAU_HINH_EMAIL.md`

---

## 📚 TÀI LIỆU THAM KHẢO

- **Hướng dẫn chi tiết**: `HUONG_DAN_CAU_HINH_EMAIL.md`
- **Hướng dẫn nhanh**: `HUONG_DAN_NHANH.md`
- **File mẫu cấu hình**: `commons/env.example.php`



