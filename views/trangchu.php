<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 80px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            font-size: 48px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        h2 {
            font-size: 24px;
            color: #666;
            margin-bottom: 50px;
            font-weight: 400;
        }

        .navigation {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-button {
            display: inline-block;
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            min-width: 200px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-guide {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-guide:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #999;
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 36px;
            }

            h2 {
                font-size: 20px;
            }

            .navigation {
                flex-direction: column;
            }

            .nav-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>

        <div class="navigation">
            <a href="?act=login" class="nav-button btn-admin">
                Đăng nhập Admin
            </a>
            <a href="?act=guide" class="nav-button btn-guide">
                Đăng nhập Guide
            </a>
        </div>

        <div class="footer">
            <p>&copy; Hệ thống quản lý Tour du lịch</p>
        </div>
    </div>
</body>
</html>
