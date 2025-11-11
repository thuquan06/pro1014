<?php
class AuthController extends BaseController {

    protected $adminModel;

    public function __construct() {
        // Tạm thời không cần model, vì logic đơn giản
    }

    // Hiển thị form đăng nhập - từ index.php (admin)
    public function showLoginForm() {
        // File view này sẽ không dùng layout admin
        $this->loadView('admin/auth/login');
    }

    // Xử lý đăng nhập - từ index.php (admin)
    public function login() {
        $uname = $_POST['username'];
        $password = md5($_POST['password']);
        
        $conn = connectDB(); // Sử dụng hàm PDO từ function.php
        $sql = "SELECT UserName, Password FROM admin WHERE UserName=:username AND Password=:password";
        $query = $conn->prepare($sql);
        $query->bindParam(':username', $uname, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        
        if ($query->rowCount() > 0) {
            $_SESSION['alogin'] = $_POST['username'];
            $this->redirect(BASE_URL . '?act=admin-dashboard');
        } else {
            // Đặt thông báo lỗi vào session và quay lại trang login
            $_SESSION['error'] = 'Sai tài khoản hoặc mật khẩu';
            $this->redirect(BASE_URL . '?act=admin-login');
        }
    }

    // Xử lý đăng xuất - từ logout.php
    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        unset($_SESSION['alogin']);
        session_destroy();
        $this->redirect(BASE_URL . '?act=admin-login');
    }
}