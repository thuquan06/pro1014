<?php
class AdminController {
    private $dashboardModel;
    private $tourModel;
    private $adminModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
        $this->tourModel      = new TourModel();
        $this->adminModel     = new AdminModel();
    }

    // ===== Auth =====
    public function login() {
        // Nếu đã login, vào thẳng admin
        if (!empty($_SESSION['alogin'])) {
            header('Location: ' . BASE_URL . '?act=admin'); exit;
        }
        require './views/admin/login.php';
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?act=login'); exit;
        }
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // MD5 như code gốc
        $admin = $this->adminModel->checkLogin($username, md5($password));

        /* Nếu dùng password_hash(), đổi như sau:
        $row = $this->adminModel->findByUsername($username);
        if ($row && password_verify($password, $row['Password'])) { $admin = $row; } else { $admin = false; }
        */

        if ($admin) {
            $_SESSION['alogin'] = $admin['UserName'];
            header('Location: ' . BASE_URL . '?act=admin'); exit;
        } else {
            $error = "Tài khoản hoặc mật khẩu không đúng!";
            require './views/admin/login.php';
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '?act=login'); exit;
    }

    // ===== Dashboard & Tours (giữ nguyên) =====
    public function dashboard() {
        // Bảo vệ admin (nếu muốn bỏ guard, xóa 2 dòng if/redirect)
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $stats = $this->dashboardModel->getStatistics();
        $viewContent = './views/admin/dashboard.php';
        require './views/admin/layout.php';
    }

    public function listTours() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $tours = $this->tourModel->getAllTours();
        $viewContent = './views/admin/tours/list.php';
        require './views/admin/layout.php';
    }

    public function createTour() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $provinces = $this->tourModel->getAllProvinces();
        $viewContent = './views/admin/tours/create.php';
        require './views/admin/layout.php';
    }

    public function storeTour() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hinhanh = null;
            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
            }
            $data = $_POST;
            $data['hinhanh'] = $hinhanh;
            $data['quocgia'] = $data['quocgia'] ?? 'Việt Nam';
            $data['ten_tinh'] = $data['ten_tinh'] ?? null;

            $this->tourModel->createTour($data);
            header('Location: ' . BASE_URL . '?act=admin-tours'); exit;
        }
        header('Location: ' . BASE_URL . '?act=admin-tour-create'); exit;
    }

    public function editTour() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: ' . BASE_URL . '?act=admin-tours'); exit; }
        $tour = $this->tourModel->getTourByID($id);
        $provinces = $this->tourModel->getAllProvinces();
        $viewContent = './views/admin/tours/edit.php';
        require './views/admin/layout.php';
    }

    public function updateTour() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_goi'] ?? null;
            if (!$id) { header('Location: ' . BASE_URL . '?act=admin-tours'); exit; }
            $data = $_POST;
            $data['quocgia'] = $data['quocgia'] ?? 'Việt Nam';
            $data['ten_tinh'] = $data['ten_tinh'] ?? null;
            $data['sonhan']  = $data['sonhan']  ?? 0;

            $this->tourModel->updateTour($id, $data);
            header('Location: ' . BASE_URL . '?act=admin-tours'); exit;
        }
        header('Location: ' . BASE_URL . '?act=admin-tours'); exit;
    }

    public function updateTourImage() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $id = $_REQUEST['id'] ?? null;
        if (!$id) { header('Location: ' . BASE_URL . '?act=admin-tours'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hinhanh = null;
            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                $oldTour = $this->tourModel->getTourByID($id);
                if ($oldTour && !empty($oldTour['hinhanh'])) deleteFile($oldTour['hinhanh']);
                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
            }
            if ($hinhanh) $this->tourModel->updateTourImage($id, $hinhanh);

            $msg = "Cập nhật ảnh thành công!";
            $tour = $this->tourModel->getTourByID($id);
            $viewContent = './views/admin/tours/update-image.php';
            require './views/admin/layout.php';
        } else {
            $tour = $this->tourModel->getTourByID($id);
            $viewContent = './views/admin/tours/update-image.php';
            require './views/admin/layout.php';
        }
    }

    public function deleteTour() {
        if (empty($_SESSION['alogin'])) { header('Location: ' . BASE_URL . '?act=login'); exit; }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $tour = $this->tourModel->getTourByID($id);
            if ($tour && !empty($tour['hinhanh'])) deleteFile($tour['hinhanh']);
            $this->tourModel->deleteTour($id);
        }
        header('Location: ' . BASE_URL . '?act=admin-tours'); exit;
    }
}
