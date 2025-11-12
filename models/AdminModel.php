<?php
/**
 * AdminModel - Quản lý admin authentication
 * ĐÃ CẬP NHẬT: Sử dụng password_hash() thay vì MD5
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
            
            // Nếu tìm thấy user và password đúng
            if ($admin && password_verify($password, $admin['Password'])) {
                return $admin;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
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
}