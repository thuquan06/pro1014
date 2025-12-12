<?php
/**
 * AdminModel - Quản lý admin authentication
 * ĐÃ CẬP NHẬT: Sử dụng password_hash() thay vì MD5
 * @author Tienhien109
 */
class AdminModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB();
    }

    /**
     * Kiểm tra đăng nhập với password_hash (SECURE)
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
                    error_log("Auto-migrated password to bcrypt for user: " . $username);
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
     * 
     * @param string $username
     * @param string $plainPassword
     * @return bool
     */
    private function updatePasswordToBcrypt($username, $plainPassword) {
        try {
            $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
            $sql = "UPDATE admin SET Password = :password WHERE UserName = :username";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':password' => $hashedPassword,
                ':username' => $username
            ]);
        } catch (PDOException $e) {
            error_log("Failed to migrate password: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Tìm admin theo username
     * 
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM admin WHERE UserName = :username LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Find user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo admin mới với password đã hash
     * 
     * @param string $username
     * @param string $password Password gốc (sẽ được hash)
     * @param string $email
     * @return bool
     */
    public function createAdmin($username, $password, $email = '') {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO admin (UserName, Password, Email) VALUES (:username, :password, :email)";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':email' => $email
            ]);
            
        } catch (PDOException $e) {
            error_log("Create admin error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Đổi mật khẩu admin
     * 
     * @param string $username
     * @param string $newPassword Password mới (gốc, chưa hash)
     * @return bool
     */
    public function changePassword($username, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            
            $sql = "UPDATE admin SET Password = :password WHERE UserName = :username";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute([
                ':password' => $hashedPassword,
                ':username' => $username
            ]);
            
        } catch (PDOException $e) {
            error_log("Change password error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra mật khẩu cũ có đúng không
     * 
     * @param string $username
     * @param string $oldPassword
     * @return bool
     */
    public function verifyCurrentPassword($username, $oldPassword) {
        $admin = $this->findByUsername($username);
        
        if (!$admin) {
            return false;
        }
        
        return password_verify($oldPassword, $admin['Password']);
    }

    /**
     * Tạo token reset password
     * 
     * @param string $username
     * @return string|false Token nếu thành công, false nếu thất bại
     */
    public function createPasswordResetToken($username) {
        try {
            // Kiểm tra username có tồn tại không
            $admin = $this->findByUsername($username);
            if (!$admin) {
                return false;
            }

            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(32));
            
            // Token hết hạn sau 1 giờ (sử dụng MySQL NOW() + INTERVAL để đảm bảo timezone đúng)
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Xóa các token cũ của user này
            try {
                $sqlDelete = "DELETE FROM password_reset_tokens WHERE username = :username";
                $stmtDelete = $this->conn->prepare($sqlDelete);
                $stmtDelete->execute([':username' => $username]);
            } catch (PDOException $e) {
                // Nếu bảng chưa tồn tại, bỏ qua lỗi này
                if (strpos($e->getMessage(), "doesn't exist") === false) {
                    error_log("Delete old tokens error: " . $e->getMessage());
                }
            }

            // Tạo token mới
            $sql = "INSERT INTO password_reset_tokens (username, token, expires_at) 
                    VALUES (:username, :token, :expires_at)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':username' => $username,
                ':token' => $token,
                ':expires_at' => $expiresAt
            ]);

            if ($result) {
                return $token;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Create reset token error: " . $e->getMessage());
            // Nếu lỗi do bảng chưa tồn tại, log rõ ràng hơn
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                error_log("ERROR: Bảng password_reset_tokens chưa được tạo! Vui lòng chạy file database/password_reset_simple.sql");
            }
            return false;
        }
    }

    /**
     * Kiểm tra token reset password có hợp lệ không
     * 
     * @param string $token
     * @return array|false Thông tin token nếu hợp lệ, false nếu không
     */
    public function verifyResetToken($token) {
        try {
            // Kiểm tra token có tồn tại không
            $sql = "SELECT * FROM password_reset_tokens 
                    WHERE token = :token 
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':token' => $token]);
            $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$tokenData) {
                return false;
            }

            // Kiểm tra đã sử dụng chưa
            if ($tokenData['used'] == 1) {
                error_log("Token đã được sử dụng: " . $token);
                return false;
            }

            // Kiểm tra hết hạn chưa (so sánh với thời gian hiện tại)
            $expiresAt = strtotime($tokenData['expires_at']);
            $now = time();
            
            if ($expiresAt <= $now) {
                error_log("Token đã hết hạn. Expires: " . $tokenData['expires_at'] . ", Now: " . date('Y-m-d H:i:s'));
                return false;
            }

            return $tokenData;
        } catch (PDOException $e) {
            error_log("Verify reset token error: " . $e->getMessage());
            // Nếu lỗi do bảng chưa tồn tại
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                error_log("ERROR: Bảng password_reset_tokens chưa được tạo! Vui lòng chạy file database/password_reset_simple.sql");
            }
            return false;
        }
    }

    /**
     * Đánh dấu token đã sử dụng
     * 
     * @param string $token
     * @return bool
     */
    public function markTokenAsUsed($token) {
        try {
            $sql = "UPDATE password_reset_tokens SET used = 1 WHERE token = :token";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':token' => $token]);
        } catch (PDOException $e) {
            error_log("Mark token as used error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reset password bằng token
     * 
     * @param string $token
     * @param string $newPassword
     * @return bool
     */
    public function resetPasswordByToken($token, $newPassword) {
        try {
            // Verify token
            $tokenData = $this->verifyResetToken($token);
            if (!$tokenData) {
                return false;
            }

            // Reset password
            $result = $this->changePassword($tokenData['username'], $newPassword);

            if ($result) {
                // Đánh dấu token đã sử dụng
                $this->markTokenAsUsed($token);
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Reset password by token error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy email của admin (nếu có)
     * 
     * @param string $username
     * @return string|false
     */
    public function getAdminEmail($username) {
        try {
            $admin = $this->findByUsername($username);
            return $admin && !empty($admin['Email']) ? $admin['Email'] : false;
        } catch (PDOException $e) {
            error_log("Get admin email error: " . $e->getMessage());
            return false;
        }
    }
}