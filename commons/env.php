<?php 

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL'       , 'http://localhost/pro1014/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'starvel');  // Tên database

define('PATH_ROOT'    , __DIR__ . '/../');

// ==================== EMAIL CONFIGURATION ====================
// Cấu hình SMTP để gửi email
define('SMTP_HOST', 'smtp.gmail.com');        // SMTP server (Gmail: smtp.gmail.com)
define('SMTP_PORT', 587);                      // SMTP port (Gmail: 587 cho TLS, 465 cho SSL)
define('SMTP_USERNAME', 'nguyenthuquan99@gmail.com'); // Email đăng nhập SMTP
define('SMTP_PASSWORD', 'twckruqncljhedch');    // Mật khẩu ứng dụng (App Password)
define('SMTP_FROM_EMAIL', 'nguyenthuquan99@gmail.com'); // Email người gửi
define('SMTP_FROM_NAME', 'StarVel Admin');        // Tên người gửi
define('SMTP_SECURE', 'tls');                  // 'tls' hoặc 'ssl'
define('SMTP_AUTH', true);                      // Có cần xác thực không

// Lưu ý: Với Gmail, bạn cần:
// 1. Bật "Less secure app access" HOẶC
// 2. Tạo "App Password" tại: https://myaccount.google.com/apppasswords
// 3. Sử dụng App Password thay vì mật khẩu thường

// ==================== MOMO PAYMENT CONFIGURATION ====================
// Cấu hình MoMo Payment API
// Đăng ký tại: https://business.momo.vn/
define('MOMO_PRODUCTION', false); // true cho production, false cho sandbox
define('MOMO_PARTNER_CODE', ''); // Partner Code từ MoMo
define('MOMO_ACCESS_KEY', ''); // Access Key từ MoMo
define('MOMO_SECRET_KEY', ''); // Secret Key từ MoMo

// Lưu ý: 
// 1. Đăng ký tài khoản MoMo Doanh Nghiệp tại https://business.momo.vn/
// 2. Lấy Partner Code, Access Key, Secret Key từ dashboard MoMo
// 3. Cấu hình IPN URL và Return URL trong dashboard MoMo
// 4. Test với sandbox trước khi chuyển sang production