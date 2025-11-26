<?php
/**
 * GuideController - Quản lý giao diện và chức năng cho Hướng dẫn viên
 */
class GuideController extends BaseController {
    private $guideModel;
    private $assignmentModel;
    private $serviceAssignmentModel;
    private $departurePlanModel;
    private $tourModel;

    public function __construct() {
        $this->guideModel = new GuideModel();
        $this->assignmentModel = new AssignmentModel();
        $this->serviceAssignmentModel = new ServiceAssignmentModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Kiểm tra guide đã đăng nhập chưa
     */
    private function checkLogin() {
        if (empty($_SESSION['guide_id'])) {
            $this->redirect(BASE_URL . '?act=guide');
        }
    }

    /**
     * Hiển thị form đăng nhập guide
     * Route: ?act=guide
     */
    public function login() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['guide_id'])) {
            $this->redirect(BASE_URL . '?act=guide-dashboard');
        }
        
        $error = null;
        
        // Nếu là POST request → xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            if (empty($email) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ email và mật khẩu!';
            } else {
                $guide = $this->guideModel->checkLogin($email, $password);
                
                if ($guide) {
                    // Đăng nhập thành công
                    session_regenerate_id(true);
                    $_SESSION['guide_id'] = $guide['id'];
                    $_SESSION['guide_name'] = $guide['ho_ten'];
                    $_SESSION['guide_email'] = $guide['email'];
                    $_SESSION['guide_login_time'] = time();
                    
                    error_log("Guide login success: ID=" . $guide['id'] . ", Name=" . $guide['ho_ten']);
                    $this->redirect(BASE_URL . '?act=guide-dashboard');
                } else {
                    // Debug: Kiểm tra xem có guide nào với email này không
                    $allGuides = $this->guideModel->getAllGuides();
                    $foundGuide = null;
                    foreach ($allGuides as $g) {
                        if (strtolower(trim($g['email'])) === strtolower(trim($email))) {
                            $foundGuide = $g;
                            break;
                        }
                    }
                    
                    if ($foundGuide) {
                        $error = 'Mật khẩu không đúng! Vui lòng kiểm tra lại CMND/CCCD hoặc số điện thoại.';
                        error_log("Guide login failed: Email found but password wrong. Guide ID: " . $foundGuide['id']);
                    } else {
                        $error = 'Email không tồn tại trong hệ thống!';
                        error_log("Guide login failed: Email not found: " . $email);
                    }
                }
            }
        }
        
        // Load form login
        require_once './views/guide/login.php';
    }

    /**
     * Đăng xuất guide
     * Route: ?act=guide-logout
     */
    public function logout() {
        unset($_SESSION['guide_id']);
        unset($_SESSION['guide_name']);
        unset($_SESSION['guide_email']);
        session_destroy();
        $this->redirect(BASE_URL . '?act=guide');
    }

    /**
     * Dashboard của guide
     * Route: ?act=guide-dashboard
     */
    public function dashboard() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        
        // Kiểm tra guide có tồn tại không
        $guide = $this->guideModel->getGuideByID($guideId);
        if (!$guide) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên!';
            $this->redirect(BASE_URL . '?act=guide');
            return;
        }
        
        error_log("Guide dashboard: Loading for Guide ID: " . $guideId);
        
        // Lấy thống kê
        $allAssignments = $this->guideModel->getAssignmentsByGuideID($guideId);
        error_log("Guide dashboard: Found " . count($allAssignments) . " total assignments");
        
        $upcomingAssignments = $this->guideModel->getAssignmentsByGuideID($guideId, [
            'trang_thai' => 1,
            'from_date' => date('Y-m-d')
        ]);
        
        $completedAssignments = array_filter($allAssignments, function($a) {
            return $a['trang_thai'] == 1 && 
                   !empty($a['ngay_ket_thuc']) && 
                   strtotime($a['ngay_ket_thuc']) < time();
        });
        
        $stats = [
            'total' => count($allAssignments),
            'upcoming' => count($upcomingAssignments),
            'completed' => count($completedAssignments),
            'active' => count(array_filter($allAssignments, function($a) {
                return $a['trang_thai'] == 1;
            }))
        ];
        
        // Lấy các phân công sắp tới (5 cái gần nhất)
        $recentAssignments = array_slice($upcomingAssignments, 0, 5);
        
        $this->loadView('guide/dashboard', compact('guide', 'stats', 'recentAssignments'), 'guide/layout');
    }

    /**
     * Danh sách phân công của guide
     * Route: ?act=guide-assignments
     */
    public function listAssignments() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $filters = [];
        
        // Filter theo trạng thái
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
        }
        
        // Filter theo ngày
        if (!empty($_GET['from_date'])) {
            $filters['from_date'] = $_GET['from_date'];
        }
        if (!empty($_GET['to_date'])) {
            $filters['to_date'] = $_GET['to_date'];
        }
        
        $assignments = $this->guideModel->getAssignmentsByGuideID($guideId, $filters);
        
        $this->loadView('guide/assignments/list', compact('assignments', 'filters'), 'guide/layout');
    }

    /**
     * Chi tiết phân công
     * Route: ?act=guide-assignment-detail&id=X
     */
    public function assignmentDetail() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['id'] ?? null;
        
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền xem';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Lấy thông tin lịch khởi hành và tour
        $departurePlan = null;
        $tour = null;
        if ($assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        // Lấy dịch vụ được gán cho lịch khởi hành này
        $services = [];
        if ($assignment['id_lich_khoi_hanh']) {
            $services = $this->serviceAssignmentModel->getAssignmentsByDeparturePlanID($assignment['id_lich_khoi_hanh']);
        }
        
        $this->loadView('guide/assignments/detail', compact('assignment', 'departurePlan', 'tour', 'services'), 'guide/layout');
    }

    /**
     * Thông tin cá nhân của guide
     * Route: ?act=guide-profile
     */
    public function profile() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $guide = $this->guideModel->getGuideByID($guideId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            $this->redirect(BASE_URL . '?act=guide-dashboard');
        }
        
        // Parse JSON arrays
        $guide['ky_nang'] = $this->guideModel->parseJsonArray($guide['ky_nang'] ?? '[]');
        $guide['tuyen_chuyen'] = $this->guideModel->parseJsonArray($guide['tuyen_chuyen'] ?? '[]');
        $guide['ngon_ngu'] = $this->guideModel->parseJsonArray($guide['ngon_ngu'] ?? '[]');
        
        $this->loadView('guide/profile', compact('guide'), 'guide/layout');
    }

    /**
     * Lịch làm việc của guide
     * Route: ?act=guide-schedule
     */
    public function schedule() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignments = $this->guideModel->getAssignmentsByGuideID($guideId, ['trang_thai' => 1]);
        
        $this->loadView('guide/schedule', compact('assignments'), 'guide/layout');
    }
}

