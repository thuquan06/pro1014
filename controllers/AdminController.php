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
    
    // ✅ DÙNG require_once TRỰC TIẾP thay vì loadView
    require_once './views/admin/login.php';
}

    // Xử lý đăng nhập
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Rate limiting check
        $identifier = $username ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $rateLimit = checkRateLimit($identifier, 5, 900); // 5 attempts in 15 minutes

        if (!$rateLimit['allowed']) {
            $waitMinutes = ceil($rateLimit['wait_time'] / 60);
            $error = "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$waitMinutes} phút.";
            return $this->loadView('admin/login', compact('error'));
        }

        // Validation

        if (empty($username) || empty($password)) {
            $error = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
            return $this->loadView('admin/login', compact('error'));
        }

        if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
            recordFailedAttempt($identifier);
            $error = "Tên đăng nhập không hợp lệ!";
            return $this->loadView('admin/login', compact('error'));
        }

        // Check login credentials
        $admin = $this->adminModel->checkLogin($username, $password);

        if ($admin) {
            // Reset rate limit on successful login
            resetRateLimit($identifier);    
            // Regenerate session ID for security
            session_regenerate_id(true);

            $_SESSION['alogin'] = $admin['UserName'];
            $_SESSION['admin_id'] = $admin['id'] ?? null;
            $_SESSION['login_time'] = time();
                        
            error_log("Successful login: " . $username);
            $this->redirect(BASE_URL . '?act=admin');
        } else {
            // Record failed attempt
            recordFailedAttempt($identifier);
            $remaining = $rateLimit['remaining'] - 1;
            if ($remaining > 0) {
                $error = "Tài khoản hoặc mật khẩu không đúng! (Còn {$remaining} lần thử)";
            } else {
                $error = "Tài khoản hoặc mật khẩu không đúng! Bạn đã hết lượt thử.";
            }
            error_log("Failed login attempt: " . $username);
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
            // Validate input
            $validator = new Validator($_POST);
            $validator->required('tengoi', 'Tên gói tour là bắt buộc')
                      ->minLength('tengoi', 5, 'Tên gói tour phải có ít nhất 5 ký tự')
                      ->maxLength('tengoi', 255, 'Tên gói tour không được quá 255 ký tự')
                      ->required('noixuatphat', 'Nơi xuất phát là bắt buộc')
                      ->required('vitri', 'Vị trí là bắt buộc')
                      ->required('giagoi', 'Giá gói là bắt buộc')
                      ->numeric('giagoi', 'Giá gói phải là số')
                      ->min('giagoi', 0, 'Giá gói phải lớn hơn 0')
                      ->numeric('giatreem', 'Giá trẻ em phải là số')
                      ->numeric('giatrenho', 'Giá trẻ nhỏ phải là số')
                      ->required('songay', 'Số ngày là bắt buộc')
                      ->integer('songay', 'Số ngày phải là số nguyên')
                      ->min('songay', 1, 'Số ngày phải lớn hơn 0');

            if ($validator->fails()) {
                $error = $validator->firstError();
                $provinces = $this->provinceModel->getAll();
                return $this->loadView('admin/tours/create', compact('provinces', 'error'), 'admin/layout');
            }
            // Validate and upload image
            $hinhanh = null;
            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                 $fileValidation = Validator::validateFile($_FILES['packageimage'], [
                    'maxSize' => 5242880, // 5MB
                    'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
                ]);

                if (!$fileValidation['valid']) {
                    $error = $fileValidation['error'];
                    $provinces = $this->provinceModel->getAll();
                    return $this->loadView('admin/tours/create', compact('provinces', 'error'), 'admin/layout');
                }

                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
                if ($hinhanh === null) {
                    $error = "Upload ảnh thất bại.";
                    $provinces = $this->provinceModel->getAll();
                    return $this->loadView('admin/tours/create', compact('provinces', 'error'), 'admin/layout');
                }
            } else {
                $error = "Ảnh tour là bắt buộc.";
                $provinces = $this->provinceModel->getAll();
                return $this->loadView('admin/tours/create', compact('provinces', 'error'), 'admin/layout');
            }

            $validated = $validator->validated();
            $validated['hinhanh'] = $hinhanh;
            $validated['quocgia'] = sanitizeInput($validated['quocgia'] ?? 'Việt Nam');
            $validated['ten_tinh'] = sanitizeInput($validated['ten_tinh'] ?? null);
            $validated['khuyenmai'] = isset($validated['khuyenmai']) ? 1 : 0;
            $validated['nuocngoai'] = isset($validated['nuocngoai']) ? 1 : 0;

            $this->tourModel->createTour($validated, null);
            $_SESSION['success'] = 'Tạo tour thành công!';
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
            $id = filter_var($_POST['id_goi'] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                $_SESSION['error'] = 'ID tour không hợp lệ';
                $this->redirect(BASE_URL . '?act=admin-tours');
            }

            // Validate input
            $validator = new Validator($_POST);
            $validator->required('tengoi', 'Tên gói tour là bắt buộc')
                      ->minLength('tengoi', 5, 'Tên gói tour phải có ít nhất 5 ký tự')
                      ->maxLength('tengoi', 255, 'Tên gói tour không được quá 255 ký tự')
                      ->required('noixuatphat', 'Nơi xuất phát là bắt buộc')
                      ->required('vitri', 'Vị trí là bắt buộc')
                      ->required('giagoi', 'Giá gói là bắt buộc')
                      ->numeric('giagoi', 'Giá gói phải là số')
                      ->min('giagoi', 0, 'Giá gói phải lớn hơn 0')
                      ->numeric('giatreem', 'Giá trẻ em phải là số')
                      ->numeric('giatrenho', 'Giá trẻ nhỏ phải là số')
                      ->required('songay', 'Số ngày là bắt buộc')
                      ->integer('songay', 'Số ngày phải là số nguyên')
                      ->min('songay', 1, 'Số ngày phải lớn hơn 0');

            if ($validator->fails()) {
                $_SESSION['error'] = $validator->firstError();
                $this->redirect(BASE_URL . '?act=admin-tour-edit&id=' . $id);
            }

            $validated = $validator->validated();
            $validated['quocgia'] = sanitizeInput($validated['quocgia'] ?? 'Việt Nam');
            $validated['ten_tinh'] = sanitizeInput($validated['ten_tinh'] ?? null);
            $validated['khuyenmai'] = isset($_POST['khuyenmai']) ? 1 : 0;
            $validated['nuocngoai'] = isset($_POST['nuocngoai']) ? 1 : 0;

            $this->tourModel->updateTour($id, $validated);
            $_SESSION['success'] = 'Cập nhật tour thành công!';
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
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID tour không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $tour = $this->tourModel->getTourByID($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        // Delete image file if exists
        if (!empty($tour['hinhanh'])) {
            deleteFile($tour['hinhanh']);
        }
        
        $this->tourModel->deleteTour($id);
        $_SESSION['success'] = 'Xóa tour thành công!';
        $this->redirect(BASE_URL . '?act=admin-tours');
    }

    /* ==================== PRIVATE ==================== */

    private function checkLogin() {
        if (empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=login');
        }
    }

    public function toggleTourStatus() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $model = new TourModel();
        $model->toggleStatus($id);
    }
    header("Location: " . BASE_URL . "?act=admin-tours");
    exit();
}

}
?>