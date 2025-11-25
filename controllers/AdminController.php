<?php
/**
 * AdminController - Quản lý Dashboard và chức năng Admin
 * 
 * VERSION: 1.1 - ĐÃ FIX
 * - ✅ Fix đăng nhập sai không load menu admin
 * - ✅ Fix đếm số lần thử đúng (5→4→3→2→1→khóa)
 * - ✅ Rate limiting: 5 lần / 15 phút
 * - ✅ Validation đầy đủ cho Tour CRUD
 * - ✅ Password security (MD5 + Bcrypt)
 * - ✅ Session security
 * - ✅ Error logging
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

    /**
     * Hiển thị form đăng nhập
     * Route: ?act=login
     */
    public function login() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }
        
        // Load form login (KHÔNG có layout admin)
        require_once './views/admin/login.php';
    }

    /**
     * Xử lý đăng nhập
     * Route: ?act=login-handle (POST)
     */
    public function handleLogin() {
        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Rate limiting check
        $identifier = $username ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $rateLimit = checkRateLimit($identifier, 5, 900); // 5 attempts in 15 minutes

        // Nếu bị khóa
        if (!$rateLimit['allowed']) {
            $waitMinutes = ceil($rateLimit['wait_time'] / 60);
            $error = "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$waitMinutes} phút.";
            require_once './views/admin/login.php';
            exit;
        }

        // Validation: Empty fields
        if (empty($username) || empty($password)) {
            $error = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
            require_once './views/admin/login.php';
            exit;
        }

        // Validation: Username format
        if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
            recordFailedAttempt($identifier);
            $rateLimit = checkRateLimit($identifier, 5, 900);
            $remaining = $rateLimit['remaining'];
            $error = "Tên đăng nhập không hợp lệ! (Còn {$remaining} lần thử)";
            require_once './views/admin/login.php';
            exit;
        }

        // Check login credentials
        $admin = $this->adminModel->checkLogin($username, $password);

        if ($admin) {
            // ✅ ĐĂNG NHẬP THÀNH CÔNG
            
            // Reset rate limit
            resetRateLimit($identifier);
            
            // Regenerate session ID (security)
            session_regenerate_id(true);
            
            // Lưu thông tin vào session
            $_SESSION['alogin'] = $admin['UserName'];
            $_SESSION['admin_id'] = $admin['id'] ?? null;
            $_SESSION['login_time'] = time();
            
            // Log
            error_log("✓ Successful login: " . $username);
            
            // Redirect đến dashboard
            $this->redirect(BASE_URL . '?act=admin');
            
        } else {
            // ❌ ĐĂNG NHẬP THẤT BẠI
            
            // Ghi nhận thất bại
            recordFailedAttempt($identifier);
            
            // Lấy lại rate limit SAU KHI ghi nhận
            $rateLimit = checkRateLimit($identifier, 5, 900);
            $remaining = $rateLimit['remaining'];
            
            // Thông báo lỗi
            if ($remaining > 0) {
                $error = "Tài khoản hoặc mật khẩu không đúng! (Còn {$remaining} lần thử)";
            } else {
                $error = "Tài khoản hoặc mật khẩu không đúng! Tài khoản tạm khóa 15 phút.";
            }
            
            // Log
            error_log("✗ Failed login attempt: " . $username . " - Remaining: " . $remaining);
            
            // Hiển thị lại form login với lỗi
            require_once './views/admin/login.php';
            exit;
        }
    }

    /**
     * Đăng xuất
     * Route: ?act=logout
     */
    public function logout() {
        if (!empty($_SESSION['alogin'])) {
            error_log("Admin logout: " . $_SESSION['alogin']);
        }

        // Xóa tất cả session
        $_SESSION = [];
        
        // Xóa cookie session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Hủy session
        session_destroy();
        
        // Redirect về login
        $this->redirect(BASE_URL . '?act=login');
    }

    /* ==================== DASHBOARD ==================== */

    /**
     * Trang Dashboard
     * Route: ?act=admin
     */
    public function dashboard() {
        $this->checkLogin();
        $stats = $this->dashboardModel->getStatistics();
        $this->loadView('admin/dashboard', compact('stats'), 'admin/layout');
    }

    /* ==================== TOUR MANAGEMENT ==================== */

    /**
     * Danh sách tour
     * Route: ?act=admin-tours
     */
    public function listTours() {
        $this->checkLogin();
        $tours = $this->tourModel->getAllTours();
        $this->loadView('admin/tours/list', compact('tours'), 'admin/layout');
    }

    /**
     * Form tạo tour
     * Route: ?act=admin-tour-create
     */
    public function createTour() {
        $this->checkLogin();

        $provinces = $this->provinceModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file   = $_FILES['packageimage'] ?? null;
            $result = $this->tourModel->createTour($_POST, $file);

            if ($result) { 
                $msg = "Thêm tour thành công!"; 
            } else { 
                $error = "Không thể thêm tour. Vui lòng kiểm tra lại dữ liệu."; 
            }

            $this->loadView('admin/tours/create', compact('provinces','msg','error'), 'admin/layout');
        } else {
            $this->loadView('admin/tours/create', compact('provinces'), 'admin/layout');
        }
    }

    /**
     * Lưu tour vào DB (với validation đầy đủ)
     * Route: ?act=admin-tour-store
     */
    public function storeTour() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ===== VALIDATE INPUT =====
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

            // ===== VALIDATE & UPLOAD IMAGE =====
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

            // ===== PREPARE DATA =====
            $validated = $validator->validated();
            $validated['hinhanh'] = $hinhanh;
            $validated['quocgia'] = sanitizeInput($validated['quocgia'] ?? 'Việt Nam');
            $validated['ten_tinh'] = sanitizeInput($validated['ten_tinh'] ?? null);
            $validated['khuyenmai'] = isset($validated['khuyenmai']) ? 1 : 0;
            $validated['nuocngoai'] = isset($validated['nuocngoai']) ? 1 : 0;

            // ===== SAVE TO DATABASE =====
            $this->tourModel->createTour($validated, null);
            $_SESSION['success'] = 'Tạo tour thành công!';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $this->redirect(BASE_URL . '?act=admin-tour-create');
    }

    /**
     * Form sửa tour
     * Route: ?act=admin-tour-edit&id=X
     */
    public function editTour() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $tour = $this->tourModel->getTourByID($id);
        $provinces = $this->provinceModel->getAll();
        
        $this->loadView('admin/tours/edit', compact('tour', 'provinces'), 'admin/layout');
    }

    /**
     * Cập nhật tour
     * Route: ?act=admin-tour-update
     */
    public function updateTour() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate ID
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

    /**
     * Cập nhật ảnh tour
     * Route: ?act=admin-tour-update-image&id=X
     */
    public function updateTourImage() {
        $this->checkLogin();
        
        $id = $_REQUEST['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hinhanh = null;
            if (!empty($_FILES["packageimage"]) && $_FILES["packageimage"]["error"] == 0) {
                // Xóa ảnh cũ
                $oldTour = $this->tourModel->getTourByID($id);
                if ($oldTour && !empty($oldTour['hinhanh'])) {
                    deleteFile($oldTour['hinhanh']);
                }
                
                // Upload ảnh mới
                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
            }
            
            if ($hinhanh) {
                $this->tourModel->updateTourImage($id, $hinhanh);
                $msg = "Cập nhật ảnh thành công!";
            }
        }

        $tour = $this->tourModel->getTourByID($id);
        $this->loadView('admin/tours/update-image', compact('tour', 'msg'), 'admin/layout');
    }

    /**
     * Xóa tour
     * Route: ?act=admin-tour-delete&id=X
     */
    public function deleteTour() {
        $this->checkLogin();
        
        // Validate ID
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            $_SESSION['error'] = 'ID tour không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        // Check tour exists
        $tour = $this->tourModel->getTourByID($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        // Xóa ảnh nếu có
        if (!empty($tour['hinhanh'])) {
            deleteFile($tour['hinhanh']);
        }
        
        // Xóa tour trong database
        $this->tourModel->deleteTour($id);
        
        $_SESSION['success'] = 'Xóa tour thành công!';
        $this->redirect(BASE_URL . '?act=admin-tours');
    }

    /**
     * Toggle trạng thái tour (active/inactive)
     * Route: ?act=admin-tour-toggle&id=X
     */
    public function toggleTourStatus() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->tourModel->toggleStatus($id);
        }
        
        header("Location: " . BASE_URL . "?act=admin-tours");
        exit();
    }

    
    /**
     * Xem chi tiết tour đầy đủ
     * Route: ?act=admin-tour-detail&id=X
     */
    public function viewTourDetail() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        
        // Lấy thông tin tour
        $tour = $this->tourModel->getTourByID($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        
        // Render view
        // Load view
        ob_start();
        require_once './views/admin/tours/detail.php';
        $content = ob_get_clean();
        
        require_once './views/admin/layout.php';
    }

    /* ==================== HELPER METHODS ==================== */

    /**
     * Check đăng nhập - redirect nếu chưa login
     */
    private function checkLogin() {
        if (empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=login');
        }
    }
}