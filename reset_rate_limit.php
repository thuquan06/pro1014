<?php
/**
 * Reset Rate Limit - Xóa giới hạn đăng nhập
 * Dùng khi bị khóa và muốn test lại
 * 
 * Truy cập: http://localhost/pro1014/reset_rate_limit.php
 */

session_start();

if (isset($_GET['confirm'])) {
    // Xóa tất cả rate limit keys trong session
    $cleared = 0;
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, 'rate_limit_') === 0) {
            unset($_SESSION[$key]);
            $cleared++;
        }
    }
    
    echo "<!DOCTYPE html>
    <html lang='vi'>
    <head>
        <meta charset='UTF-8'>
        <title>Reset Rate Limit</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                max-width: 600px; 
                margin: 100px auto; 
                padding: 20px; 
                text-align: center;
                background: #f5f5f5;
            }
            .success {
                background: #4CAF50;
                color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: white;
                color: #4CAF50;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
                font-weight: bold;
            }
            .btn:hover {
                background: #f1f1f1;
            }
        </style>
    </head>
    <body>
        <div class='success'>
            <h1>✅ Đã Reset!</h1>
            <p>Đã xóa {$cleared} rate limit key(s)</p>
            <p>Giờ bạn có thể đăng nhập lại</p>
            <a href='?act=login' class='btn'>Đi đến đăng nhập</a>
        </div>
    </body>
    </html>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Rate Limit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background: #45a049;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔓 Reset Rate Limit</h1>
        <p style="color: #666; margin-bottom: 30px;">Xóa giới hạn đăng nhập khi bị khóa</p>
        
        <div class="warning">
            <strong>⚠️ Lưu ý:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>Chỉ dùng khi test hoặc bị khóa nhầm</li>
                <li>Sẽ xóa tất cả rate limit trong session</li>
                <li>Cho phép đăng nhập lại ngay lập tức</li>
            </ul>
        </div>

        <div class="info">
            <strong>ℹ️ Thông tin:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li><strong>Rate Limit hiện tại:</strong> 5 lần thử / 15 phút</li>
                <li><strong>Khi hết lượt:</strong> Khóa 15 phút</li>
                <li><strong>Reset:</strong> Tự động sau 15 phút hoặc dùng tool này</li>
            </ul>
        </div>

        <div style="margin-top: 30px;">
            <a href="?confirm=1" class="btn btn-primary">✓ Reset Ngay</a>
            <a href="?act=login" class="btn btn-secondary">← Quay lại</a>
        </div>
    </div>
</body>
</html>
