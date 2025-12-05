<?php 

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL'       , 'http://localhost:8888/pro1014/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 8889);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'pro1014');  // Tên database

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
