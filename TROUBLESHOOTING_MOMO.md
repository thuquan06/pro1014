# Hướng dẫn xử lý lỗi thanh toán MoMo

## Các lỗi thường gặp và cách khắc phục

### 1. Lỗi "ID hóa đơn không hợp lệ"

**Nguyên nhân:** 
- Không truyền được `hoadon_id` từ frontend
- JavaScript không gọi đúng API

**Cách khắc phục:**
- Mở Console trình duyệt (F12) và kiểm tra lỗi JavaScript
- Kiểm tra xem button có đúng `onclick` không
- Kiểm tra `hoadon_id` có giá trị không

### 2. Lỗi "Vui lòng cấu hình thông tin MoMo trong file env.php"

**Nguyên nhân:**
- Chưa cấu hình Partner Code, Access Key, Secret Key trong `commons/env.php`

**Cách khắc phục:**
1. Mở file `commons/env.php`
2. Tìm phần `MOMO_PAYMENT CONFIGURATION`
3. Điền đầy đủ:
   ```php
   define('MOMO_PARTNER_CODE', 'YOUR_PARTNER_CODE');
   define('MOMO_ACCESS_KEY', 'YOUR_ACCESS_KEY');
   define('MOMO_SECRET_KEY', 'YOUR_SECRET_KEY');
   ```
4. Lưu file và thử lại

### 3. Lỗi "Server trả về dữ liệu không hợp lệ"

**Nguyên nhân:**
- Server trả về HTML thay vì JSON (có thể do lỗi PHP)
- Có lỗi PHP trong quá trình xử lý

**Cách khắc phục:**
1. Mở Console trình duyệt (F12) → Tab Network
2. Tìm request đến `payment-create` hoặc `payment-qrcode`
3. Xem Response - nếu là HTML thì có lỗi PHP
4. Kiểm tra PHP error log:
   ```bash
   tail -f /Applications/MAMP/logs/php_error.log
   ```
5. Sửa lỗi PHP và thử lại

### 4. Lỗi "Không thể tạo yêu cầu thanh toán"

**Nguyên nhân:**
- MoMo API trả về lỗi
- Cấu hình MoMo không đúng
- Signature không hợp lệ

**Cách khắc phục:**
1. Kiểm tra log PHP để xem chi tiết lỗi từ MoMo
2. Kiểm tra lại Partner Code, Access Key, Secret Key
3. Kiểm tra BASE_URL có đúng không
4. Kiểm tra IPN URL và Return URL trong MoMo Dashboard

### 5. Lỗi khi nhấn nút nhưng không có phản hồi

**Nguyên nhân:**
- JavaScript bị lỗi
- Button không có event handler
- CORS hoặc network error

**Cách khắc phục:**
1. Mở Console trình duyệt (F12)
2. Kiểm tra có lỗi JavaScript không
3. Kiểm tra Tab Network xem có request được gửi không
4. Kiểm tra BASE_URL trong JavaScript có đúng không

## Cách kiểm tra từng bước

### Bước 1: Kiểm tra cấu hình

Mở file `commons/env.php` và đảm bảo có:

```php
define('MOMO_PRODUCTION', false); // hoặc true cho production
define('MOMO_PARTNER_CODE', 'YOUR_CODE');
define('MOMO_ACCESS_KEY', 'YOUR_KEY');
define('MOMO_SECRET_KEY', 'YOUR_SECRET');
```

### Bước 2: Kiểm tra Console trình duyệt

1. Mở trang booking-confirm
2. Nhấn F12 để mở Developer Tools
3. Vào tab Console
4. Nhấn nút "Thanh toán bằng MoMo"
5. Xem có lỗi nào không

### Bước 3: Kiểm tra Network requests

1. Trong Developer Tools, vào tab Network
2. Nhấn nút "Thanh toán bằng MoMo"
3. Tìm request đến `payment-create`
4. Click vào request đó
5. Xem:
   - **Request**: Kiểm tra `hoadon_id` có được gửi không
   - **Response**: Xem server trả về gì (JSON hay HTML)

### Bước 4: Kiểm tra PHP Error Log

```bash
# MAMP
tail -f /Applications/MAMP/logs/php_error.log

# Hoặc kiểm tra trong php.ini
# error_log = /path/to/error.log
```

Tìm các dòng có:
- `MoMo Payment Error`
- `Payment Create Error`
- `MoMo Callback`

### Bước 5: Test API trực tiếp

Mở trình duyệt và truy cập:
```
http://localhost:8888/pro1014/?act=payment-create&hoadon_id=1
```

Nếu thấy JSON response thì API hoạt động. Nếu thấy HTML hoặc lỗi thì có vấn đề với PHP.

## Debug checklist

- [ ] Đã cấu hình đầy đủ MoMo credentials trong `env.php`
- [ ] BASE_URL đúng với domain hiện tại
- [ ] Database đã có các cột thanh toán (chạy SQL migration)
- [ ] Không có lỗi JavaScript trong Console
- [ ] Network request được gửi thành công
- [ ] Server trả về JSON (không phải HTML)
- [ ] PHP error log không có lỗi nghiêm trọng
- [ ] MoMo Dashboard đã cấu hình IPN URL và Return URL

## Liên hệ hỗ trợ

Nếu vẫn gặp lỗi sau khi kiểm tra các bước trên:

1. Copy toàn bộ lỗi từ Console trình duyệt
2. Copy response từ Network tab
3. Copy các dòng liên quan từ PHP error log
4. Gửi cho team hỗ trợ kèm theo:
   - URL trang đang test
   - Hoadon ID đang test
   - Môi trường (sandbox/production)

## Lưu ý quan trọng

1. **Sandbox vs Production**: 
   - Sandbox: `MOMO_PRODUCTION = false` - dùng để test
   - Production: `MOMO_PRODUCTION = true` - môi trường thật

2. **BASE_URL**: Phải là URL công khai nếu test với MoMo thật (không dùng localhost)

3. **Secret Key**: Giữ bí mật, không commit vào Git

4. **IPN URL**: Phải là URL công khai, MoMo sẽ gửi POST request đến đây

