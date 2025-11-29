# Hướng dẫn cấu hình MoMo Payment - Bước từng bước

## Bước 1: Đăng ký tài khoản MoMo Doanh Nghiệp

1. Truy cập: **https://business.momo.vn/**
2. Click vào **"Đăng ký ngay"** hoặc **"Đăng nhập"** nếu đã có tài khoản
3. Điền đầy đủ thông tin doanh nghiệp:
   - Tên doanh nghiệp
   - Mã số thuế
   - Địa chỉ
   - Số điện thoại
   - Email
   - Và các thông tin khác theo yêu cầu
4. Hoàn tất quá trình xác thực và chờ MoMo phê duyệt

## Bước 2: Lấy thông tin API từ MoMo Dashboard

Sau khi đăng ký thành công và được phê duyệt:

1. Đăng nhập vào **MoMo Dashboard**: https://business.momo.vn/
2. Vào phần **"Tích hợp"** hoặc **"API"** hoặc **"Developer"**
3. Bạn sẽ thấy các thông tin sau:
   - **Partner Code**: Mã đối tác (ví dụ: `MOMOXXXX20201010`)
   - **Access Key**: Khóa truy cập (chuỗi dài)
   - **Secret Key**: Khóa bí mật (chuỗi dài, **QUAN TRỌNG - GIỮ BÍ MẬT**)

## Bước 3: Cấu hình trong hệ thống

### Mở file `commons/env.php`

Tìm phần cấu hình MoMo (khoảng dòng 30-40) và cập nhật:

```php
// ==================== MOMO PAYMENT CONFIGURATION ====================
// Cấu hình MoMo Payment API
// Đăng ký tại: https://business.momo.vn/
define('MOMO_PRODUCTION', false); // false cho sandbox/test, true cho production
define('MOMO_PARTNER_CODE', 'YOUR_PARTNER_CODE_HERE'); // Thay bằng Partner Code của bạn
define('MOMO_ACCESS_KEY', 'YOUR_ACCESS_KEY_HERE'); // Thay bằng Access Key của bạn
define('MOMO_SECRET_KEY', 'YOUR_SECRET_KEY_HERE'); // Thay bằng Secret Key của bạn
```

### Ví dụ cấu hình:

```php
define('MOMO_PRODUCTION', false);
define('MOMO_PARTNER_CODE', 'MOMOXXXX20201010');
define('MOMO_ACCESS_KEY', 'abc123def456ghi789jkl012mno345pqr678stu901vwx234yz');
define('MOMO_SECRET_KEY', 'secret123456789abcdefghijklmnopqrstuvwxyz123456789');
```

## Bước 4: Cấu hình IPN URL và Return URL trong MoMo Dashboard

1. Vào **MoMo Dashboard** → **Cài đặt** → **Webhook/IPN**
2. Cấu hình các URL sau:

### IPN URL (Callback URL):
```
http://yourdomain.com/pro1014/?act=payment-callback
```

**Lưu ý:** 
- Thay `yourdomain.com` bằng domain thật của bạn
- Nếu test local, bạn có thể dùng ngrok để tạo public URL:
  ```bash
  ngrok http 8888
  ```
  Sau đó dùng URL ngrok: `https://xxxxx.ngrok.io/pro1014/?act=payment-callback`

### Return URL:
```
http://yourdomain.com/pro1014/?act=payment-return
```

## Bước 5: Test với Sandbox

1. Đảm bảo `MOMO_PRODUCTION = false` trong `env.php`
2. Tạo một đơn hàng test
3. Thử thanh toán với số tiền nhỏ (ví dụ: 10,000 VNĐ)
4. Kiểm tra callback và return URL

## Bước 6: Chuyển sang Production

Khi đã test thành công:

1. Đổi `MOMO_PRODUCTION = true` trong `env.php`
2. Cập nhật lại Partner Code, Access Key, Secret Key cho production (nếu khác với sandbox)
3. Cập nhật IPN URL và Return URL trong MoMo Dashboard với domain production
4. Test lại với số tiền thật nhỏ

## Lưu ý quan trọng

### ⚠️ Bảo mật:
- **KHÔNG** commit Secret Key vào Git
- Sử dụng file `.env` riêng cho production (nếu có)
- Secret Key phải được giữ bí mật tuyệt đối

### ⚠️ URL:
- IPN URL phải là URL công khai (không dùng localhost)
- MoMo sẽ gửi POST request đến IPN URL
- Return URL là nơi khách hàng quay lại sau khi thanh toán

### ⚠️ Testing:
- Luôn test với sandbox trước
- Kiểm tra log để debug
- Test với số tiền nhỏ trước khi dùng thật

## Troubleshooting

### Lỗi "Missing configuration"
- Kiểm tra lại các constant trong `env.php`
- Đảm bảo không có khoảng trắng thừa
- Đảm bảo các giá trị không rỗng

### Lỗi "Invalid signature"
- Kiểm tra Secret Key có đúng không
- Kiểm tra URL encoding trong signature
- Kiểm tra thứ tự các tham số

### Callback không hoạt động
- Kiểm tra IPN URL có đúng không
- Kiểm tra server có thể nhận POST request không
- Kiểm tra firewall có chặn không
- Kiểm tra PHP error log

## Hỗ trợ

- **MoMo Support**: support@momo.vn
- **Tài liệu**: https://developers.momo.vn/
- **Dashboard**: https://business.momo.vn/

## Checklist

- [ ] Đã đăng ký tài khoản MoMo Doanh Nghiệp
- [ ] Đã lấy được Partner Code, Access Key, Secret Key
- [ ] Đã cấu hình trong `commons/env.php`
- [ ] Đã cấu hình IPN URL trong MoMo Dashboard
- [ ] Đã cấu hình Return URL trong MoMo Dashboard
- [ ] Đã test với sandbox
- [ ] Đã kiểm tra callback hoạt động
- [ ] Đã chuyển sang production (nếu cần)

