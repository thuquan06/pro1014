<?php
/**
 * AdminController - Quản lý Dashboard và chức năng Admin
 * Đã cập nhật:
 * - Đồng bộ layout (views/admin/layout.php)
 * - Sử dụng loadView() chuẩn MVC
 * - Sửa lỗi gọi hàm Model và View
 */

class AdminController extends BaseController {
    private $dashboardModel;
    private $tourModel;
    private $adminModel;
    private $provinceModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
        $this->tourModel      = new TourModel();
        $this->adminModel     = new AdminModel();
        $this->provinceModel  = new ProvinceModel();
    }

    /* ==================== AUTH ==================== */

    // Hiển thị form đăng nhập
    public function login() {
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }
        $this->loadView('admin/login');
    }

    // Xử lý đăng nhập
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
            return $this->loadView('admin/login', compact('error'));
        }

        if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
            $error = "Tên đăng nhập không hợp lệ!";
            return $this->loadView('admin/login', compact('error'));
        }

        $admin = $this->adminModel->checkLogin($username, $password);

        if ($admin) {
            session_regenerate_id(true);
            $_SESSION['alogin'] = $admin['UserName'];
            $_SESSION['admin_id'] = $admin['id'] ?? null;
            $_SESSION['login_time'] = time();
            $this->redirect(BASE_URL . '?act=admin');
        } else {
            $error = "Tài khoản hoặc mật khẩu không đúng!";
            $this->loadView('admin/login', compact('error'));
        }
    }

    // Đăng xuất
    public function logout() {
        if (!empty($_SESSION['alogin'])) {
            error_log("Admin logout: " . $_SESSION['alogin']);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        $this->redirect(BASE_URL . '?act=login');
    }

    /* ==================== DASHBOARD ==================== */

    public function dashboard() {
    $this->checkLogin();
    $stats = $this->dashboardModel->getStatistics();
    $this->loadView('admin/dashboard', compact('stats'), 'admin/layout');
}


    /* ==================== TOUR ==================== */

    // Danh sách tour
   public function listTours() {
    $this->checkLogin();
    $tours = $this->tourModel->getAllTours();
    $this->loadView('admin/tours/list', compact('tours'), 'admin/layout');
}


    // Form tạo tour
    public function createTour() {
    $this->checkLogin();

    $provinceModel = new ProvinceModel();
    $provinces = $provinceModel->getAll(); // hoặc getAllProvinces() nếu bạn đặt tên vậy

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $file   = $_FILES['packageimage'] ?? null;
        $result = $this->tourModel->createTour($_POST, $file);

        if ($result) { $msg = "Thêm tour thành công!"; }
        else { $error = "Không thể thêm tour. Vui lòng kiểm tra lại dữ liệu."; }

        // ✅ layout phải là 'admin/layout'
        $this->loadView('admin/tours/create', compact('provinces','msg','error'), 'admin/layout');
    } else {
        $this->loadView('admin/tours/create', compact('provinces'), 'admin/layout');
    }
}



    // Lưu tour vào DB
    public function storeTour() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hinhanh = null;

            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
                if ($hinhanh === null) {
                    $error = "Upload ảnh thất bại.";
                    $provinces = $this->provinceModel->getAll();
                    return $this->loadView('admin/tours/create', compact('provinces', 'error'), 'admin/layout');
                }
            }

            $data = $_POST;
            $data['hinhanh'] = $hinhanh;
            $data['quocgia'] = $data['quocgia'] ?? 'Việt Nam';
            $data['ten_tinh'] = $data['ten_tinh'] ?? null;

            $this->tourModel->createTour($data, $_FILES['packageimage'] ?? null);
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $this->redirect(BASE_URL . '?act=admin-tour-create');
    }

    // Sửa tour
    public function editTour() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect(BASE_URL . '?act=admin-tours');

        $tour = $this->tourModel->getTourByID($id);
        $provinces = $this->provinceModel->getAll();
        $this->loadView('admin/tours/edit', compact('tour', 'provinces'), 'admin/layout');
    }

    // Cập nhật tour
    public function updateTour() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_goi'] ?? null;
            if (!$id) $this->redirect(BASE_URL . '?act=admin-tours');

            $data = $_POST;
            $data['quocgia'] = $data['quocgia'] ?? 'Việt Nam';
            $data['ten_tinh'] = $data['ten_tinh'] ?? null;

            $this->tourModel->updateTour($id, $data);
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        $this->redirect(BASE_URL . '?act=admin-tours');
    }

    // Cập nhật ảnh tour
    public function updateTourImage() {
        $this->checkLogin();
        $id = $_REQUEST['id'] ?? null;
        if (!$id) $this->redirect(BASE_URL . '?act=admin-tours');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hinhanh = null;
            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                $oldTour = $this->tourModel->getTourByID($id);
                if ($oldTour && !empty($oldTour['hinhanh'])) {
                    deleteFile($oldTour['hinhanh']);
                }
                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
            }
            if ($hinhanh) $this->tourModel->updateTourImage($id, $hinhanh);
            $msg = "Cập nhật ảnh thành công!";
        }

        $tour = $this->tourModel->getTourByID($id);
        $this->loadView('admin/tours/update-image', compact('tour', 'msg'), 'admin/layout');
    }

    // Xóa tour
    public function deleteTour() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;

        if ($id) {
            $tour = $this->tourModel->getTourByID($id);
            if ($tour && !empty($tour['hinhanh'])) {
                deleteFile($tour['hinhanh']);
            }
            $this->tourModel->deleteTour($id);
        }

        $this->redirect(BASE_URL . '?act=admin-tours');
    }

    /* ==================== PRIVATE ==================== */

    private function checkLogin() {
        if (empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=login');
        }
    }
}
?>