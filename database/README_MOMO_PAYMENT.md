# Hướng dẫn tích hợp thanh toán MoMo

## 1. Cài đặt Database

Chạy file SQL để thêm các cột thanh toán vào bảng `hoadon`:

```sql
-- Chạy file: database/add_payment_fields_to_hoadon.sql
```

Hoặc chạy trực tiếp:

```bash
mysql -u root -p starvel < database/add_payment_fields_to_hoadon.sql
```

## 2. Cấu hình MoMo Payment

### Bước 1: Đăng ký tài khoản MoMo Doanh Nghiệp

1. Truy cập: https://business.momo.vn/
2. Đăng ký tài khoản doanh nghiệp
3. Hoàn tất quá trình xác thực

### Bước 2: Lấy thông tin API

Sau khi đăng ký thành công, bạn sẽ nhận được:
- **Partner Code**: Mã đối tác
- **Access Key**: Khóa truy cập
- **Secret Key**: Khóa bí mật (quan trọng, giữ bí mật)

### Bước 3: Cấu hình trong hệ thống

Mở file `commons/env.php` và cập nhật các thông tin:

```php
// ==================== MOMO PAYMENT CONFIGURATION ====================
define('MOMO_PRODUCTION', false); // false cho sandbox, true cho production
define('MOMO_PARTNER_CODE', 'YOUR_PARTNER_CODE'); // Thay bằng Partner Code của bạn
define('MOMO_ACCESS_KEY', 'YOUR_ACCESS_KEY'); // Thay bằng Access Key của bạn
define('MOMO_SECRET_KEY', 'YOUR_SECRET_KEY'); // Thay bằng Secret Key của bạn
```

### Bước 4: Cấu hình IPN và Return URL trong MoMo Dashboard

Trong dashboard MoMo, cấu hình:

- **IPN URL (Callback URL)**: 
  ```
  http://yourdomain.com/pro1014/?act=payment-callback
  ```

- **Return URL**: 
  ```
  http://yourdomain.com/pro1014/?act=payment-return
  ```

## 3. Kiểm tra hoạt động

### Test với Sandbox

1. Đảm bảo `MOMO_PRODUCTION = false` trong `env.php`
2. Tạo một đơn hàng test
3. Thử thanh toán với số tiền nhỏ
4. Kiểm tra callback và return URL

### Chuyển sang Production

1. Đổi `MOMO_PRODUCTION = true` trong `env.php`
2. Cập nhật lại Partner Code, Access Key, Secret Key cho production
3. Cập nhật IPN URL và Return URL trong MoMo Dashboard
4. Test lại với số tiền thật

## 4. Các trạng thái thanh toán

- `0`: Chưa thanh toán
- `1`: Đã thanh toán thành công
- `2`: Đang xử lý (đã tạo yêu cầu thanh toán)

## 5. Các phương thức thanh toán hỗ trợ

- `momo`: Thanh toán qua MoMo Wallet
- `bank_transfer`: Chuyển khoản ngân hàng (chưa tích hợp)
- `cash`: Thanh toán tiền mặt (chưa tích hợp)

## 6. Xử lý lỗi

Nếu gặp lỗi, kiểm tra:

1. **Lỗi cấu hình**: Kiểm tra lại Partner Code, Access Key, Secret Key
2. **Lỗi callback**: Kiểm tra IPN URL có đúng không, server có thể nhận POST request không
3. **Lỗi signature**: Kiểm tra Secret Key có đúng không
4. **Lỗi database**: Kiểm tra các cột thanh toán đã được thêm vào bảng `hoadon` chưa

## 7. Log và Debug

Tất cả các lỗi được ghi vào PHP error log. Kiểm tra log để debug:

```bash
tail -f /path/to/php/error.log
```

Hoặc trong MAMP:
```
/Applications/MAMP/logs/php_error.log
```

## 8. Tài liệu tham khảo

- MoMo Developers: https://developers.momo.vn/
- MoMo Business: https://business.momo.vn/
- API Documentation: https://developers.momo.vn/v3/vi/docs/payment/overview

## 9. Lưu ý bảo mật

1. **KHÔNG** commit Secret Key vào Git
2. Sử dụng biến môi trường hoặc file config riêng cho production
3. Luôn sử dụng HTTPS cho production
4. Kiểm tra signature trong callback để đảm bảo request đến từ MoMo

## 10. Hỗ trợ

Nếu cần hỗ trợ, liên hệ:
- MoMo Support: support@momo.vn
- Hoặc kiểm tra tài liệu tại: https://developers.momo.vn/

