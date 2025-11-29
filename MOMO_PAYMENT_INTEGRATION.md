# Tích hợp thanh toán MoMo - Tóm tắt

## ✅ Đã hoàn thành

### 1. Database
- ✅ File SQL: `database/add_payment_fields_to_hoadon.sql`
- ✅ Thêm các cột thanh toán vào bảng `hoadon`:
  - `phuong_thuc_thanh_toan`: Phương thức thanh toán
  - `trang_thai_thanh_toan`: Trạng thái thanh toán (0: Chưa thanh toán, 1: Đã thanh toán, 2: Đang xử lý)
  - `ma_giao_dich_momo`: Mã giao dịch từ MoMo
  - `so_tien_thanh_toan`: Số tiền thanh toán
  - `ngay_thanh_toan`: Ngày thanh toán
  - `qr_code_url`: URL mã QR thanh toán
  - `payment_link`: Link thanh toán MoMo

### 2. Backend
- ✅ `commons/MoMoPaymentHelper.php`: Helper class xử lý API MoMo
  - Tạo yêu cầu thanh toán
  - Xác thực callback từ MoMo
  - Xử lý signature và bảo mật

- ✅ `controllers/PaymentController.php`: Controller xử lý thanh toán
  - `createPayment()`: Tạo yêu cầu thanh toán MoMo
  - `handleCallback()`: Xử lý callback/IPN từ MoMo
  - `handleReturn()`: Xử lý return URL sau khi thanh toán
  - `getQRCode()`: Lấy mã QR thanh toán

- ✅ `models/HoadonModel.php`: Thêm method `updatePaymentInfo()`

### 3. Frontend
- ✅ `views/client/booking-confirm.php`: Cập nhật trang xác nhận đặt tour
  - Thêm section thanh toán MoMo
  - Hiển thị nút thanh toán và mã QR
  - Hiển thị trạng thái thanh toán
  - JavaScript xử lý thanh toán

### 4. Routes
- ✅ `index.php`: Thêm các routes thanh toán
  - `payment-create`: Tạo yêu cầu thanh toán
  - `payment-callback`: Callback từ MoMo (IPN)
  - `payment-return`: Return URL sau thanh toán
  - `payment-qrcode`: Lấy mã QR

### 5. Configuration
- ✅ `commons/env.php`: Thêm cấu hình MoMo Payment
  - `MOMO_PRODUCTION`: Môi trường (true/false)
  - `MOMO_PARTNER_CODE`: Partner Code từ MoMo
  - `MOMO_ACCESS_KEY`: Access Key từ MoMo
  - `MOMO_SECRET_KEY`: Secret Key từ MoMo

### 6. Documentation
- ✅ `database/README_MOMO_PAYMENT.md`: Hướng dẫn chi tiết cấu hình và sử dụng

## 🚀 Cách sử dụng

### Bước 1: Cài đặt Database
```bash
mysql -u root -p starvel < database/add_payment_fields_to_hoadon.sql
```

### Bước 2: Cấu hình MoMo
1. Đăng ký tài khoản tại: https://business.momo.vn/
2. Lấy Partner Code, Access Key, Secret Key
3. Cập nhật trong `commons/env.php`

### Bước 3: Test
1. Tạo một đơn hàng test
2. Vào trang xác nhận đặt tour
3. Nhấn nút "Thanh toán bằng MoMo"
4. Hoàn tất thanh toán trong ứng dụng MoMo

## 📋 Luồng thanh toán

1. **Khách hàng đặt tour** → Tạo hóa đơn (`hoadon`)
2. **Vào trang xác nhận** → Hiển thị nút thanh toán MoMo
3. **Nhấn nút thanh toán** → Gọi API MoMo tạo payment request
4. **MoMo trả về** → Link thanh toán và mã QR
5. **Khách hàng thanh toán** → Trong ứng dụng MoMo
6. **MoMo gửi callback** → Cập nhật trạng thái thanh toán
7. **Khách hàng quay lại** → Hiển thị kết quả thanh toán

## 🔒 Bảo mật

- ✅ Xác thực signature trong callback
- ✅ Kiểm tra partnerCode
- ✅ Validate dữ liệu đầu vào
- ✅ Ghi log để debug

## 📝 Lưu ý

1. **Sandbox vs Production**: 
   - Sandbox: `MOMO_PRODUCTION = false` (để test)
   - Production: `MOMO_PRODUCTION = true` (môi trường thật)

2. **Callback URL**: Phải là URL công khai, MoMo sẽ gửi POST request đến đây

3. **Return URL**: URL khách hàng quay lại sau khi thanh toán

4. **Secret Key**: Giữ bí mật, không commit vào Git

## 🐛 Troubleshooting

### Lỗi "Missing configuration"
- Kiểm tra lại các constant trong `env.php`
- Đảm bảo đã khai báo đầy đủ Partner Code, Access Key, Secret Key

### Lỗi "Invalid signature"
- Kiểm tra Secret Key có đúng không
- Kiểm tra URL encoding trong signature

### Callback không hoạt động
- Kiểm tra IPN URL trong MoMo Dashboard
- Kiểm tra server có thể nhận POST request không
- Kiểm tra PHP error log

### QR Code không hiển thị
- Kiểm tra `qr_code_url` có được trả về từ MoMo không
- Kiểm tra JavaScript console có lỗi không

## 📞 Hỗ trợ

- MoMo Support: support@momo.vn
- Tài liệu: https://developers.momo.vn/
- File hướng dẫn: `database/README_MOMO_PAYMENT.md`

