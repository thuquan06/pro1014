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
    private $departurePlanModel;
    private $pretripChecklistModel;
    private $guideModel;
    private $assignmentModel;
    private $serviceModel;
    private $serviceAssignmentModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
        $this->tourModel      = new TourModel();
        $this->adminModel     = new AdminModel();
        $this->provinceModel  = new ProvinceModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->pretripChecklistModel = new PretripChecklistModel();
        $this->guideModel = new GuideModel();
        $this->assignmentModel = new AssignmentModel();
        $this->serviceModel = new ServiceModel();
        $this->serviceAssignmentModel = new ServiceAssignmentModel();
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
        
        // Lấy danh sách lịch khởi hành của tour
        $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($id);
        
        // Render view
        // Load view
        ob_start();
        require_once './views/admin/tours/detail.php';
        $content = ob_get_clean();
        
        require_once './views/admin/layout.php';
    }

    /* ==================== DEPARTURE PLAN MANAGEMENT ==================== */

    /**
     * Danh sách lịch khởi hành
     * Route: ?act=admin-departure-plans
     * Route: ?act=admin-departure-plans&tour_id=X (filter theo tour)
     */
    public function listDeparturePlans() {
        $this->checkLogin();
        
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : null;
        $tour = null;
        
        if ($tourId && $tourId > 0) {
            // Lấy lịch khởi hành theo tour ID
            $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($tourId);
            // Lấy thông tin tour để hiển thị
            $tour = $this->tourModel->getTourByID($tourId);
        } else {
            // Lấy tất cả lịch khởi hành
            $departurePlans = $this->departurePlanModel->getAllDeparturePlans();
        }
        
        // Lấy checklist cho mỗi departure plan
        $checklists = [];
        foreach ($departurePlans as $plan) {
            $checklist = $this->pretripChecklistModel->getChecklistByDeparturePlanID($plan['id']);
            if ($checklist) {
                $checklists[$plan['id']] = $checklist;
            }
        }
        
        $this->loadView('admin/departure-plans/list', compact('departurePlans', 'tour', 'tourId', 'checklists'), 'admin/layout');
    }

    /**
     * Form tạo lịch khởi hành
     * Route: ?act=admin-departure-plan-create
     */
    public function createDeparturePlan() {
        $this->checkLogin();
        $tours = $this->tourModel->getAllTours();
        $tourId = $_GET['id_tour'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->departurePlanModel->createDeparturePlan($_POST);
            $redirectTourId = $_POST['id_tour'] ?? $tourId;

            if ($result) {
                $_SESSION['success'] = 'Tạo lịch khởi hành thành công!';
                // Redirect về trang list với tour_id nếu có
                $redirectUrl = BASE_URL . '?act=admin-departure-plans';
                if ($redirectTourId) {
                    $redirectUrl .= '&tour_id=' . $redirectTourId;
                }
                $this->redirect($redirectUrl);
            } else {
                $error = 'Không thể tạo lịch khởi hành. Vui lòng kiểm tra lại dữ liệu.';
                $this->loadView('admin/departure-plans/create', compact('tours', 'error', 'tourId'), 'admin/layout');
            }
        } else {
            $this->loadView('admin/departure-plans/create', compact('tours', 'tourId'), 'admin/layout');
        }
    }

    /**
     * Form sửa lịch khởi hành
     * Route: ?act=admin-departure-plan-edit&id=X
     */
    public function editDeparturePlan() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-departure-plans');
        }

        $departurePlan = $this->departurePlanModel->getDeparturePlanByID($id);
        if (!$departurePlan) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            $this->redirect(BASE_URL . '?act=admin-departure-plans');
        }

        $tours = $this->tourModel->getAllTours();
        $this->loadView('admin/departure-plans/edit', compact('departurePlan', 'tours', 'tourId'), 'admin/layout');
    }

    /**
     * Cập nhật lịch khởi hành
     * Route: ?act=admin-departure-plan-update
     */
    public function updateDeparturePlan() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ';
                $this->redirect(BASE_URL . '?act=admin-departure-plans');
            }

            $result = $this->departurePlanModel->updateDeparturePlan($id, $_POST);
            $redirectTourId = $_POST['id_tour'] ?? null;

            if ($result) {
                $_SESSION['success'] = 'Cập nhật lịch khởi hành thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật lịch khởi hành';
            }

            // Redirect về trang list với tour_id nếu có
            $redirectUrl = BASE_URL . '?act=admin-departure-plans';
            if ($redirectTourId) {
                $redirectUrl .= '&tour_id=' . $redirectTourId;
            }
            $this->redirect($redirectUrl);
        }

        $this->redirect(BASE_URL . '?act=admin-departure-plans');
    }

    /**
     * Xóa lịch khởi hành
     * Route: ?act=admin-departure-plan-delete&id=X
     */
    public function deleteDeparturePlan() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-departure-plans');
        }

        $result = $this->departurePlanModel->deleteDeparturePlan($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa lịch khởi hành thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa lịch khởi hành';
        }
        
        // Redirect về trang list với tour_id nếu có
        $redirectUrl = BASE_URL . '?act=admin-departure-plans';
        if ($tourId) {
            $redirectUrl .= '&tour_id=' . $tourId;
        }
        $this->redirect($redirectUrl);
    }

    /**
     * Toggle trạng thái lịch khởi hành
     * Route: ?act=admin-departure-plan-toggle&id=X
     */
    public function toggleDeparturePlanStatus() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        
        if ($id) {
            $this->departurePlanModel->toggleStatus($id);
        }
        
        // Redirect về trang list với tour_id nếu có
        $redirectUrl = BASE_URL . "?act=admin-departure-plans";
        if ($tourId) {
            $redirectUrl .= "&tour_id=" . $tourId;
        }
        
        header("Location: " . $redirectUrl);
        exit();
    }

    /* ==================== PRETRIP CHECKLIST MANAGEMENT ==================== */

    /**
     * Danh sách checklist
     * Route: ?act=admin-pretrip-checklists
     */
    public function listPretripChecklists() {
        $this->checkLogin();
        $checklists = $this->pretripChecklistModel->getAllChecklists();
        $this->loadView('admin/pretrip-checklists/list', compact('checklists'), 'admin/layout');
    }

    /**
     * Form tạo/sửa checklist
     * Route: ?act=admin-pretrip-checklist-create
     * Route: ?act=admin-pretrip-checklist-edit&id=X
     */
    public function createPretripChecklist() {
        $this->checkLogin();
        
        $departurePlanId = $_GET['departure_plan_id'] ?? null;
        $departurePlan = null;
        $checklist = null;
        $allDeparturePlans = [];
        
        if ($departurePlanId) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($departurePlanId);
            // Kiểm tra xem đã có checklist chưa
            $checklist = $this->pretripChecklistModel->getChecklistByDeparturePlanID($departurePlanId);
        } else {
            // Lấy tất cả lịch khởi hành để hiển thị trong dropdown
            $allDeparturePlans = $this->departurePlanModel->getAllDeparturePlans();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($checklist) {
                // Cập nhật checklist đã tồn tại
                $result = $this->pretripChecklistModel->updateChecklist($checklist['id'], $_POST);
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật checklist thành công!';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật checklist';
                }
            } else {
                // Tạo checklist mới
                $result = $this->pretripChecklistModel->createChecklist($_POST);
                if ($result) {
                    $_SESSION['success'] = 'Tạo checklist thành công!';
                } else {
                    $_SESSION['error'] = 'Không thể tạo checklist';
                }
            }
            
            $redirectUrl = BASE_URL . '?act=admin-departure-plans';
            if ($departurePlanId) {
                $departurePlan = $this->departurePlanModel->getDeparturePlanByID($departurePlanId);
                if ($departurePlan && $departurePlan['id_tour']) {
                    $redirectUrl .= '&tour_id=' . $departurePlan['id_tour'];
                }
            }
            $this->redirect($redirectUrl);
        }

        // Lấy lại checklist sau khi có departure plan
        if ($departurePlanId && !$checklist) {
            $checklist = null;
        }

        $this->loadView('admin/pretrip-checklists/create', compact('departurePlan', 'checklist', 'departurePlanId', 'allDeparturePlans'), 'admin/layout');
    }

    /**
     * Form sửa checklist
     * Route: ?act=admin-pretrip-checklist-edit&id=X
     */
    public function editPretripChecklist() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $checklist = $this->pretripChecklistModel->getChecklistByID($id);
        if (!$checklist) {
            $_SESSION['error'] = 'Không tìm thấy checklist';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $departurePlan = null;
        if ($checklist['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($checklist['id_lich_khoi_hanh']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->pretripChecklistModel->updateChecklist($id, $_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật checklist thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật checklist';
            }

            $redirectUrl = BASE_URL . '?act=admin-departure-plans';
            if ($departurePlan && $departurePlan['id_tour']) {
                $redirectUrl .= '&tour_id=' . $departurePlan['id_tour'];
            }
            $this->redirect($redirectUrl);
        }

        $this->loadView('admin/pretrip-checklists/edit', compact('checklist', 'departurePlan'), 'admin/layout');
    }

    /**
     * Xóa checklist
     * Route: ?act=admin-pretrip-checklist-delete&id=X
     */
    public function deletePretripChecklist() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID checklist không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $checklist = $this->pretripChecklistModel->getChecklistByID($id);
        $result = $this->pretripChecklistModel->deleteChecklist($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa checklist thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa checklist';
        }
        
        $redirectUrl = BASE_URL . '?act=admin-departure-plans';
        if ($checklist && $checklist['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($checklist['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $redirectUrl .= '&tour_id=' . $departurePlan['id_tour'];
            }
        }
        $this->redirect($redirectUrl);
    }

    /* ==================== GUIDE MANAGEMENT ==================== */

    /**
     * Danh sách HDV
     * Route: ?act=admin-guides
     */
    public function listGuides() {
        $this->checkLogin();
        
        $filters = [];
        if (!empty($_GET['ky_nang'])) {
            $filters['ky_nang'] = $_GET['ky_nang'];
        }
        if (!empty($_GET['tuyen_chuyen'])) {
            $filters['tuyen_chuyen'] = $_GET['tuyen_chuyen'];
        }
        if (!empty($_GET['ngon_ngu'])) {
            $filters['ngon_ngu'] = $_GET['ngon_ngu'];
        }
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
        }

        $guides = $this->guideModel->getAllGuides($filters);
        $this->loadView('admin/guides/list', compact('guides', 'filters'), 'admin/layout');
    }

    /**
     * Form tạo HDV
     * Route: ?act=admin-guide-create
     */
    public function createGuide() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->guideModel->createGuide($_POST);
            if ($result) {
                $_SESSION['success'] = 'Tạo HDV thành công!';
                $this->redirect(BASE_URL . '?act=admin-guides');
            } else {
                $error = 'Không thể tạo HDV. Vui lòng kiểm tra lại dữ liệu.';
                $this->loadView('admin/guides/create', compact('error'), 'admin/layout');
            }
        } else {
            $this->loadView('admin/guides/create', [], 'admin/layout');
        }
    }

    /**
     * Form sửa HDV
     * Route: ?act=admin-guide-edit&id=X
     */
    public function editGuide() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-guides');
        }

        $guide = $this->guideModel->getGuideByID($id);
        if (!$guide) {
            $_SESSION['error'] = 'Không tìm thấy HDV';
            $this->redirect(BASE_URL . '?act=admin-guides');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->guideModel->updateGuide($id, $_POST);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật HDV thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật HDV';
            }
            $this->redirect(BASE_URL . '?act=admin-guides');
        }

        $this->loadView('admin/guides/edit', compact('guide'), 'admin/layout');
    }

    /**
     * Xóa HDV
     * Route: ?act=admin-guide-delete&id=X
     */
    public function deleteGuide() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID HDV không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-guides');
        }

        $result = $this->guideModel->deleteGuide($id);
        if ($result) {
            $_SESSION['success'] = 'Xóa HDV thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa HDV';
        }
        $this->redirect(BASE_URL . '?act=admin-guides');
    }

    /**
     * Toggle trạng thái HDV
     * Route: ?act=admin-guide-toggle&id=X
     */
    public function toggleGuideStatus() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->guideModel->toggleStatus($id);
        }
        $this->redirect(BASE_URL . '?act=admin-guides');
    }

    /* ==================== ASSIGNMENT MANAGEMENT ==================== */

    /**
     * Danh sách phân công HDV
     * Route: ?act=admin-assignments
     */
    public function listAssignments() {
        $this->checkLogin();
        
        $filters = [];
        if (!empty($_GET['id_lich_khoi_hanh'])) {
            $filters['id_lich_khoi_hanh'] = (int)$_GET['id_lich_khoi_hanh'];
        }
        if (!empty($_GET['id_hdv'])) {
            $filters['id_hdv'] = (int)$_GET['id_hdv'];
        }
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
        }

        $assignments = $this->assignmentModel->getAllAssignments($filters);
        $this->loadView('admin/assignments/list', compact('assignments', 'filters'), 'admin/layout');
    }

    /**
     * Form tạo phân công HDV
     * Route: ?act=admin-assignment-create
     */
    public function createAssignment() {
        $this->checkLogin();

        $departurePlanId = $_GET['departure_plan_id'] ?? null;
        $departurePlan = null;
        if ($departurePlanId) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($departurePlanId);
        }

        $guides = $this->guideModel->getAllGuides(['trang_thai' => 1]);
        $departurePlans = $this->departurePlanModel->getAllDeparturePlans();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trùng lịch
            $conflicts = $this->assignmentModel->checkScheduleConflict(
                $_POST['id_hdv'],
                $_POST['ngay_bat_dau'],
                $_POST['ngay_ket_thuc']
            );

            if (!empty($conflicts) && empty($_POST['force_assign'])) {
                $error = 'HDV này đã có lịch trùng trong khoảng thời gian này!';
                $conflictDetails = $conflicts;
                $this->loadView('admin/assignments/create', compact('guides', 'departurePlans', 'departurePlan', 'departurePlanId', 'error', 'conflictDetails'), 'admin/layout');
                return;
            }

            $result = $this->assignmentModel->createAssignment($_POST);
            if ($result) {
                $_SESSION['success'] = 'Phân công HDV thành công!';
                $redirectUrl = BASE_URL . '?act=admin-assignments';
                if ($departurePlanId) {
                    $redirectUrl .= '&id_lich_khoi_hanh=' . $departurePlanId;
                }
                $this->redirect($redirectUrl);
            } else {
                $error = 'Không thể phân công HDV. Vui lòng kiểm tra lại dữ liệu.';
                $this->loadView('admin/assignments/create', compact('guides', 'departurePlans', 'departurePlan', 'departurePlanId', 'error'), 'admin/layout');
            }
        } else {
            $this->loadView('admin/assignments/create', compact('guides', 'departurePlans', 'departurePlan', 'departurePlanId'), 'admin/layout');
        }
    }

    /**
     * Form sửa phân công HDV
     * Route: ?act=admin-assignment-edit&id=X
     */
    public function editAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-assignments');
        }

        $assignment = $this->assignmentModel->getAssignmentByID($id);
        if (!$assignment) {
            $_SESSION['error'] = 'Không tìm thấy phân công';
            $this->redirect(BASE_URL . '?act=admin-assignments');
        }

        $guides = $this->guideModel->getAllGuides(['trang_thai' => 1]);
        $departurePlans = $this->departurePlanModel->getAllDeparturePlans();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trùng lịch (loại trừ phân công hiện tại)
            $conflicts = $this->assignmentModel->checkScheduleConflict(
                $_POST['id_hdv'],
                $_POST['ngay_bat_dau'],
                $_POST['ngay_ket_thuc'],
                $id
            );

            if (!empty($conflicts) && empty($_POST['force_assign'])) {
                $error = 'HDV này đã có lịch trùng trong khoảng thời gian này!';
                $conflictDetails = $conflicts;
                $this->loadView('admin/assignments/edit', compact('assignment', 'guides', 'departurePlans', 'error', 'conflictDetails'), 'admin/layout');
                return;
            }

            $result = $this->assignmentModel->updateAssignment($id, $_POST);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật phân công thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật phân công';
            }
            $this->redirect(BASE_URL . '?act=admin-assignments');
        }

        $this->loadView('admin/assignments/edit', compact('assignment', 'guides', 'departurePlans'), 'admin/layout');
    }

    /**
     * Xóa phân công HDV
     * Route: ?act=admin-assignment-delete&id=X
     */
    public function deleteAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-assignments');
        }

        $result = $this->assignmentModel->deleteAssignment($id);
        if ($result) {
            $_SESSION['success'] = 'Xóa phân công thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa phân công';
        }
        $this->redirect(BASE_URL . '?act=admin-assignments');
    }

    /**
     * Toggle trạng thái phân công
     * Route: ?act=admin-assignment-toggle&id=X
     */
    public function toggleAssignmentStatus() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->assignmentModel->toggleStatus($id);
        }
        $this->redirect(BASE_URL . '?act=admin-assignments');
    }

    /* ==================== SERVICE MANAGEMENT ==================== */

    /**
     * Danh sách dịch vụ
     * Route: ?act=admin-services
     */
    public function listServices() {
        $this->checkLogin();
        
        $filters = [];
        if (!empty($_GET['loai_dich_vu'])) {
            $filters['loai_dich_vu'] = $_GET['loai_dich_vu'];
        }
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
        }
        if (!empty($_GET['nha_cung_cap'])) {
            $filters['nha_cung_cap'] = $_GET['nha_cung_cap'];
        }

        $services = $this->serviceModel->getAllServices($filters);
        $serviceTypes = ServiceModel::getServiceTypes();
        $this->loadView('admin/services/list', compact('services', 'filters', 'serviceTypes'), 'admin/layout');
    }

    /**
     * Form tạo dịch vụ
     * Route: ?act=admin-service-create
     */
    public function createService() {
        $this->checkLogin();

        $serviceTypes = ServiceModel::getServiceTypes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->serviceModel->createService($_POST);
            if ($result) {
                $_SESSION['success'] = 'Tạo dịch vụ thành công!';
                $this->redirect(BASE_URL . '?act=admin-services');
            } else {
                $error = 'Không thể tạo dịch vụ. Vui lòng kiểm tra lại dữ liệu.';
                $this->loadView('admin/services/create', compact('serviceTypes', 'error'), 'admin/layout');
            }
        } else {
            $this->loadView('admin/services/create', compact('serviceTypes'), 'admin/layout');
        }
    }

    /**
     * Form sửa dịch vụ
     * Route: ?act=admin-service-edit&id=X
     */
    public function editService() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-services');
        }

        $service = $this->serviceModel->getServiceByID($id);
        if (!$service) {
            $_SESSION['error'] = 'Không tìm thấy dịch vụ';
            $this->redirect(BASE_URL . '?act=admin-services');
        }

        $serviceTypes = ServiceModel::getServiceTypes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->serviceModel->updateService($id, $_POST);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật dịch vụ thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật dịch vụ';
            }
            $this->redirect(BASE_URL . '?act=admin-services');
        }

        $this->loadView('admin/services/edit', compact('service', 'serviceTypes'), 'admin/layout');
    }

    /**
     * Xóa dịch vụ
     * Route: ?act=admin-service-delete&id=X
     */
    public function deleteService() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID dịch vụ không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-services');
        }

        $result = $this->serviceModel->deleteService($id);
        if ($result) {
            $_SESSION['success'] = 'Xóa dịch vụ thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa dịch vụ';
        }
        $this->redirect(BASE_URL . '?act=admin-services');
    }

    /**
     * Toggle trạng thái dịch vụ
     * Route: ?act=admin-service-toggle&id=X
     */
    public function toggleServiceStatus() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->serviceModel->toggleStatus($id);
        }
        $this->redirect(BASE_URL . '?act=admin-services');
    }

    /* ==================== SERVICE ASSIGNMENT MANAGEMENT ==================== */

    /**
     * Danh sách gán dịch vụ
     * Route: ?act=admin-service-assignments
     */
    public function listServiceAssignments() {
        $this->checkLogin();
        
        $filters = [];
        if (!empty($_GET['id_lich_khoi_hanh'])) {
            $filters['id_lich_khoi_hanh'] = (int)$_GET['id_lich_khoi_hanh'];
        }
        if (!empty($_GET['loai_dich_vu'])) {
            $filters['loai_dich_vu'] = $_GET['loai_dich_vu'];
        }
        if (!empty($_GET['trang_thai'])) {
            $filters['trang_thai'] = $_GET['trang_thai'];
        }

        $assignments = $this->serviceAssignmentModel->getAllAssignments($filters);
        $serviceTypes = ServiceModel::getServiceTypes();
        $statuses = ServiceAssignmentModel::getStatuses();
        $this->loadView('admin/service-assignments/list', compact('assignments', 'filters', 'serviceTypes', 'statuses'), 'admin/layout');
    }

    /**
     * Form tạo gán dịch vụ
     * Route: ?act=admin-service-assignment-create
     */
    public function createServiceAssignment() {
        $this->checkLogin();

        $departurePlanId = $_GET['departure_plan_id'] ?? null;
        $departurePlan = null;
        if ($departurePlanId) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($departurePlanId);
        }

        $services = $this->serviceModel->getAllServices(['trang_thai' => 1]);
        $departurePlans = $this->departurePlanModel->getAllDeparturePlans();
        $serviceTypes = ServiceModel::getServiceTypes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->serviceAssignmentModel->createAssignment($_POST);
            if ($result) {
                $_SESSION['success'] = 'Gán dịch vụ thành công!';
                $redirectUrl = BASE_URL . '?act=admin-service-assignments';
                if ($departurePlanId) {
                    $redirectUrl .= '&id_lich_khoi_hanh=' . $departurePlanId;
                }
                $this->redirect($redirectUrl);
            } else {
                $error = 'Không thể gán dịch vụ. Vui lòng kiểm tra lại dữ liệu.';
                $this->loadView('admin/service-assignments/create', compact('services', 'departurePlans', 'departurePlan', 'departurePlanId', 'serviceTypes', 'error'), 'admin/layout');
            }
        } else {
            $this->loadView('admin/service-assignments/create', compact('services', 'departurePlans', 'departurePlan', 'departurePlanId', 'serviceTypes'), 'admin/layout');
        }
    }

    /**
     * Form sửa gán dịch vụ
     * Route: ?act=admin-service-assignment-edit&id=X
     */
    public function editServiceAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $assignment = $this->serviceAssignmentModel->getAssignmentByID($id);
        if (!$assignment) {
            $_SESSION['error'] = 'Không tìm thấy gán dịch vụ';
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $services = $this->serviceModel->getAllServices(['trang_thai' => 1]);
        $departurePlans = $this->departurePlanModel->getAllDeparturePlans();
        $serviceTypes = ServiceModel::getServiceTypes();
        $statuses = ServiceAssignmentModel::getStatuses();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->serviceAssignmentModel->updateAssignment($id, $_POST);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật gán dịch vụ thành công!';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật gán dịch vụ';
            }
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $this->loadView('admin/service-assignments/edit', compact('assignment', 'services', 'departurePlans', 'serviceTypes', 'statuses'), 'admin/layout');
    }

    /**
     * Xác nhận dịch vụ
     * Route: ?act=admin-service-assignment-confirm&id=X
     */
    public function confirmServiceAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $result = $this->serviceAssignmentModel->confirmAssignment($id);
        if ($result) {
            $_SESSION['success'] = 'Xác nhận dịch vụ thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xác nhận dịch vụ';
        }
        $this->redirect(BASE_URL . '?act=admin-service-assignments');
    }

    /**
     * Hủy gán dịch vụ
     * Route: ?act=admin-service-assignment-cancel&id=X
     */
    public function cancelServiceAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $result = $this->serviceAssignmentModel->cancelAssignment($id);
        if ($result) {
            $_SESSION['success'] = 'Hủy dịch vụ thành công!';
        } else {
            $_SESSION['error'] = 'Không thể hủy dịch vụ';
        }
        $this->redirect(BASE_URL . '?act=admin-service-assignments');
    }

    /**
     * Xóa gán dịch vụ
     * Route: ?act=admin-service-assignment-delete&id=X
     */
    public function deleteServiceAssignment() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-service-assignments');
        }

        $result = $this->serviceAssignmentModel->deleteAssignment($id);
        if ($result) {
            $_SESSION['success'] = 'Xóa gán dịch vụ thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa gán dịch vụ';
        }
        $this->redirect(BASE_URL . '?act=admin-service-assignments');
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