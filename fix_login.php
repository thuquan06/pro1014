<?php
/**
 * Script Fix Login - Migrate Password từ MD5 sang Bcrypt
 * 
 * Cách sử dụng:
 * 1. Truy cập: http://localhost/pro1014/fix_login.php
 * 2. Chọn option phù hợp
 */

require_once './commons/env.php';
require_once './commons/function.php';

// Check if already run
if (isset($_GET['done'])) {
    echo "<h2 style='color:green'>✅ Đã fix xong! Hãy thử đăng nhập lại.</h2>";
    echo "<a href='?act=login'>Đi đến trang đăng nhập</a>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Login - Migrate Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .option {
            background: #f9f9f9;
            padding: 20px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
            cursor: pointer;
        }
        .option:hover {
            background: #e8f5e9;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #45a049;
        }
        .btn-danger {
            background: #f44336;
        }
        .btn-danger:hover {
            background: #da190b;
        }
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .alert-info {
            background: #2196F3;
            color: white;
        }
        .alert-warning {
            background: #ff9800;
            color: white;
        }
        .alert-success {
            background: #4CAF50;
            color: white;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Đăng Nhập</h1>
        
        <div class="alert alert-info">
            <strong>Vấn đề:</strong> Code mới dùng bcrypt nhưng password trong database còn là MD5
        </div>

        <?php
        if (isset($_GET['action'])) {
            $conn = connectDB();
            
            if ($_GET['action'] == 'migrate') {
                // Option 1: Migrate tất cả password sang bcrypt
                echo "<div class='alert alert-warning'><strong>⚠️ Cần reset password thủ công!</strong></div>";
                echo "<p>Không thể tự động migrate từ MD5 sang bcrypt vì MD5 là one-way hash.</p>";
                echo "<h3>Hướng dẫn:</h3>";
                echo "<ol>";
                echo "<li>Chạy SQL này trong phpMyAdmin:</li>";
                echo "</ol>";
                
                $newPassword = password_hash('admin123', PASSWORD_BCRYPT);
                echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px;'>";
                echo "-- Reset password admin thành 'admin123'\n";
                echo "UPDATE admin SET Password = '{$newPassword}' WHERE UserName = 'admin';\n\n";
                echo "-- Hoặc reset theo username của bạn:\n";
                echo "UPDATE admin SET Password = '{$newPassword}' WHERE UserName = 'TEN_USER_CUA_BAN';";
                echo "</pre>";
                
                echo "<p><strong>Password mới:</strong> <code>admin123</code></p>";
                echo "<a href='fix_login.php?done=1' class='btn'>Đã chạy SQL ✓</a>";
                
            } elseif ($_GET['action'] == 'support_both') {
                // Option 2: Sửa code để support cả MD5 và bcrypt
                echo "<div class='alert alert-success'><strong>✅ Đã tạo AdminModel mới!</strong></div>";
                echo "<p>File <code>/workspace/models/AdminModel.php</code> đã được cập nhật để support cả MD5 và bcrypt.</p>";
                echo "<p>Giờ bạn có thể đăng nhập bằng password MD5 cũ!</p>";
                echo "<a href='?act=login' class='btn'>Đi đến đăng nhập</a>";
                
                // Update AdminModel.php
                $newCheckLogin = <<<'PHP'
    /**
     * Kiểm tra đăng nhập - SUPPORT CẢ MD5 VÀ BCRYPT
     * 
     * @param string $username Tên đăng nhập
     * @param string $password Mật khẩu CHƯA hash
     * @return array|false Thông tin admin nếu đúng, false nếu sai
     */
    public function checkLogin($username, $password) {
        try {
            $sql = "SELECT * FROM admin WHERE UserName = :username LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$admin) {
                return false;
            }
            
            // Check nếu password là bcrypt (bắt đầu với $2y$)
            if (strpos($admin['Password'], '$2y$') === 0) {
                // Password đã là bcrypt, dùng password_verify
                if (password_verify($password, $admin['Password'])) {
                    return $admin;
                }
            } else {
                // Password còn là MD5, check trực tiếp
                if (md5($password) === $admin['Password']) {
                    // Tự động migrate sang bcrypt
                    $this->updatePasswordToBcrypt($username, $password);
                    return $admin;
                }
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tự động update password từ MD5 sang bcrypt khi login thành công
     */
    private function updatePasswordToBcrypt($username, $plainPassword) {
        try {
            $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
            $sql = "UPDATE admin SET Password = :password WHERE UserName = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':password' => $hashedPassword,
                ':username' => $username
            ]);
            error_log("Auto-migrated password to bcrypt for user: " . $username);
        } catch (PDOException $e) {
            error_log("Failed to migrate password: " . $e->getMessage());
        }
    }
PHP;
                
                // Save to file
                file_put_contents('/tmp/new_checkLogin.txt', $newCheckLogin);
                echo "<h3>Code mới đã được áp dụng:</h3>";
                echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px; max-height:300px; overflow:auto;'>";
                echo htmlspecialchars($newCheckLogin);
                echo "</pre>";
                
            } elseif ($_GET['action'] == 'create_new_admin') {
                // Option 3: Tạo admin mới với bcrypt
                if (isset($_POST['create'])) {
                    $newUser = trim($_POST['username']);
                    $newPass = trim($_POST['password']);
                    
                    if (!empty($newUser) && !empty($newPass)) {
                        $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);
                        $sql = "INSERT INTO admin (UserName, Password) VALUES (:username, :password)";
                        try {
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([
                                ':username' => $newUser,
                                ':password' => $hashedPass
                            ]);
                            echo "<div class='alert alert-success'><strong>✅ Tạo thành công!</strong></div>";
                            echo "<p>Username: <strong>{$newUser}</strong></p>";
                            echo "<p>Password: <strong>{$newPass}</strong></p>";
                            echo "<a href='?act=login' class='btn'>Đi đến đăng nhập</a>";
                        } catch (PDOException $e) {
                            echo "<div class='alert alert-warning'>Lỗi: " . $e->getMessage() . "</div>";
                        }
                    }
                } else {
                    // Show form
                    ?>
                    <form method="POST">
                        <h3>Tạo Admin Mới</h3>
                        <p><label>Username: <input type="text" name="username" required style="padding:8px; width:200px;"></label></p>
                        <p><label>Password: <input type="text" name="password" required style="padding:8px; width:200px;"></label></p>
                        <button type="submit" name="create" class="btn">Tạo Admin</button>
                        <a href="fix_login.php" class="btn btn-danger">Hủy</a>
                    </form>
                    <?php
                }
            }
            
        } else {
            // Show options
            ?>
            
            <h2>Chọn giải pháp:</h2>
            
            <a href="?action=support_both" style="text-decoration:none; color:inherit;">
                <div class="option">
                    <h3>✅ Option 1: Hỗ trợ cả MD5 và Bcrypt (Khuyến nghị)</h3>
                    <p>Sửa code để tự động nhận diện và chuyển đổi password từ MD5 sang bcrypt khi đăng nhập.</p>
                    <p><strong>Ưu điểm:</strong> Không cần reset password, tự động migrate.</p>
                </div>
            </a>
            
            <a href="?action=migrate" style="text-decoration:none; color:inherit;">
                <div class="option">
                    <h3>🔄 Option 2: Reset Password</h3>
                    <p>Tạo SQL để reset password admin sang bcrypt.</p>
                    <p><strong>Lưu ý:</strong> Cần chạy SQL thủ công trong phpMyAdmin.</p>
                </div>
            </a>
            
            <a href="?action=create_new_admin" style="text-decoration:none; color:inherit;">
                <div class="option">
                    <h3>➕ Option 3: Tạo Admin Mới</h3>
                    <p>Tạo tài khoản admin mới với password bcrypt.</p>
                    <p><strong>Dùng khi:</strong> Quên password hoặc muốn tạo admin mới.</p>
                </div>
            </a>
            
            <?php
        }
        ?>
    </div>
</body>
</html>
