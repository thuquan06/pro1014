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
    private $journalModel;
    private $incidentReportModel;
    private $pretripChecklistModel;

    public function __construct() {
        $this->guideModel = new GuideModel();
        $this->assignmentModel = new AssignmentModel();
        $this->serviceAssignmentModel = new ServiceAssignmentModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->tourModel = new TourModel();
        $this->journalModel = new TourJournalModel();
        // PretripChecklistModel và IncidentReportModel sẽ được khởi tạo khi cần (lazy loading)
    }

    /**
     * Lazy load PretripChecklistModel
     */
    private function getPretripChecklistModel() {
        if ($this->pretripChecklistModel === null) {
            require_once './models/PretripChecklistModel.php';
            $this->pretripChecklistModel = new PretripChecklistModel();
        }
        return $this->pretripChecklistModel;
    }

    /**
     * Lazy load IncidentReportModel
     */
    private function getIncidentReportModel() {
        if ($this->incidentReportModel === null) {
            require_once './models/IncidentReportModel.php';
            $this->incidentReportModel = new IncidentReportModel();
        }
        return $this->incidentReportModel;
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
        
        // Lấy checklist nếu có
        $checklist = null;
        $checklistItems = [];
        $completionPercentage = 0;
        if ($assignment['id_lich_khoi_hanh']) {
            $checklistModel = $this->getPretripChecklistModel();
            $checklist = $checklistModel->getChecklistByDeparturePlanID($assignment['id_lich_khoi_hanh']);
            if ($checklist) {
                $checklistItems = $checklistModel->getChecklistItems($checklist['id']);
                $completionPercentage = $checklistModel->getCompletionPercentage($checklist['id']);
            }
        }
        
        $this->loadView('guide/assignments/detail', compact('assignment', 'departurePlan', 'tour', 'services', 'checklist', 'checklistItems', 'completionPercentage'), 'guide/layout');
    }

    /**
     * Xem và tick checklist cho tour được phân công
     * Route: ?act=guide-checklist&assignment_id=X
     */
    public function viewChecklist() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['assignment_id'] ?? null;
        
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền xem';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Kiểm tra quyền truy cập checklist
        if (!$assignment['id_lich_khoi_hanh']) {
            $_SESSION['error'] = 'Phân công chưa có lịch khởi hành';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        $checklistModel = $this->getPretripChecklistModel();
        $hasAccess = $checklistModel->isGuideAssignedToDeparturePlan($guideId, $assignment['id_lich_khoi_hanh']);
        if (!$hasAccess) {
            $_SESSION['error'] = 'Bạn không có quyền cập nhật checklist của tour này';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Lấy checklist
        $checklist = $checklistModel->getChecklistByDeparturePlanID($assignment['id_lich_khoi_hanh']);
        if (!$checklist) {
            $_SESSION['error'] = 'Chưa có checklist cho tour này. Vui lòng liên hệ admin để tạo checklist.';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Xử lý POST request - tick/untick items
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $itemId = $_POST['item_id'] ?? null;
            
            if ($action === 'toggle_item' && $itemId) {
                $checklistModel = $this->getPretripChecklistModel();
                $currentItem = $checklistModel->getChecklistItemByID($itemId);
                if ($currentItem && $currentItem['id_checklist'] == $checklist['id']) {
                    $checked = !$currentItem['da_hoan_thanh'];
                    $result = $checklistModel->toggleChecklistItem($itemId, $guideId, 'guide', $checked);
                    if ($result) {
                        $_SESSION['success'] = $checked ? 'Đã đánh dấu hoàn thành!' : 'Đã bỏ đánh dấu hoàn thành!';
                    } else {
                        $_SESSION['error'] = 'Không thể cập nhật trạng thái';
                    }
                } else {
                    $_SESSION['error'] = 'Mục checklist không hợp lệ';
                }
            }
            
            $this->redirect(BASE_URL . '?act=guide-checklist&assignment_id=' . $assignmentId);
        }
        
        // Lấy items và thông tin liên quan
        $checklistModel = $this->getPretripChecklistModel();
        $items = $checklistModel->getChecklistItems($checklist['id']);
        $completionPercentage = $checklistModel->getCompletionPercentage($checklist['id']);
        $history = $checklistModel->getChecklistHistory($checklist['id'], 20);
        
        // Lấy thông tin tour và departure plan
        $departurePlan = null;
        $tour = null;
        if ($assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $this->loadView('guide/checklist', compact('assignment', 'checklist', 'items', 'completionPercentage', 'history', 'departurePlan', 'tour'), 'guide/layout');
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

    /**
     * Danh sách nhật ký tour
     * Route: ?act=guide-journals
     */
    public function listJournals() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $filters = [];
        
        // Filter theo phân công
        if (!empty($_GET['id_phan_cong'])) {
            $filters['id_phan_cong'] = (int)$_GET['id_phan_cong'];
        }
        
        // Filter theo ngày
        if (!empty($_GET['from_date'])) {
            $filters['from_date'] = $_GET['from_date'];
        }
        if (!empty($_GET['to_date'])) {
            $filters['to_date'] = $_GET['to_date'];
        }
        
        $journals = $this->journalModel->getJournalsByGuideID($guideId, $filters);
        
        // Lấy danh sách phân công để filter
        $assignments = $this->guideModel->getAssignmentsByGuideID($guideId);
        
        $this->loadView('guide/journals/list', compact('journals', 'filters', 'assignments'), 'guide/layout');
    }

    /**
     * Tạo nhật ký mới
     * Route: ?act=guide-journal-create&assignment_id=X
     */
    public function createJournal() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['assignment_id'] ?? null;
        
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
            return;
        }
        
        // Kiểm tra quyền truy cập phân công
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Bạn không có quyền tạo nhật ký cho phân công này';
            $this->redirect(BASE_URL . '?act=guide-assignments');
            return;
        }
        
        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $images = [];
            
            // Xử lý upload ảnh
            if (!empty($_FILES['hinh_anh']['name'][0])) {
                foreach ($_FILES['hinh_anh']['name'] as $key => $name) {
                    if ($_FILES['hinh_anh']['error'][$key] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['hinh_anh']['name'][$key],
                            'type' => $_FILES['hinh_anh']['type'][$key],
                            'tmp_name' => $_FILES['hinh_anh']['tmp_name'][$key],
                            'error' => $_FILES['hinh_anh']['error'][$key],
                            'size' => $_FILES['hinh_anh']['size'][$key]
                        ];
                        
                        $uploadedPath = uploadFile($file, 'uploads/journals/');
                        if ($uploadedPath) {
                            $images[] = $uploadedPath;
                        }
                    }
                }
            }
            
            $data = [
                'id_phan_cong' => $assignmentId,
                'ngay' => $_POST['ngay'] ?? date('Y-m-d'),
                'dien_bien' => $_POST['dien_bien'] ?? null,
                'su_co' => $_POST['su_co'] ?? null,
                'thoi_tiet' => $_POST['thoi_tiet'] ?? null,
                'diem_nhan' => $_POST['diem_nhan'] ?? null,
                'hinh_anh' => $images
            ];
            
            $journalId = $this->journalModel->createJournal($data);
            
            if ($journalId) {
                $_SESSION['success'] = 'Tạo nhật ký thành công!';
                $this->redirect(BASE_URL . '?act=guide-journal-detail&id=' . $journalId);
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi tạo nhật ký. Vui lòng thử lại.';
            }
        }
        
        // Lấy thông tin phân công và tour
        $departurePlan = null;
        $tour = null;
        if ($assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $this->loadView('guide/journals/create', compact('assignment', 'departurePlan', 'tour'), 'guide/layout');
    }

    /**
     * Chi tiết nhật ký
     * Route: ?act=guide-journal-detail&id=X
     */
    public function journalDetail() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $journalId = $_GET['id'] ?? null;
        
        if (!$journalId) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        $journal = $this->journalModel->getJournalByID($journalId);
        
        if (!$journal) {
            $_SESSION['error'] = 'Không tìm thấy nhật ký';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        // Kiểm tra quyền truy cập
        if (!$this->journalModel->checkGuideAccess($journalId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền xem nhật ký này';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        // Lấy thông tin phân công và tour
        $assignment = $this->assignmentModel->getAssignmentByID($journal['id_phan_cong']);
        $departurePlan = null;
        $tour = null;
        if ($assignment && $assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $this->loadView('guide/journals/detail', compact('journal', 'assignment', 'departurePlan', 'tour'), 'guide/layout');
    }

    /**
     * Chỉnh sửa nhật ký
     * Route: ?act=guide-journal-edit&id=X
     */
    public function editJournal() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $journalId = $_GET['id'] ?? null;
        
        if (!$journalId) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        $journal = $this->journalModel->getJournalByID($journalId);
        
        if (!$journal) {
            $_SESSION['error'] = 'Không tìm thấy nhật ký';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        // Kiểm tra quyền truy cập
        if (!$this->journalModel->checkGuideAccess($journalId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa nhật ký này';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $images = $journal['hinh_anh'] ?? [];
            
            // Xử lý upload ảnh mới
            if (!empty($_FILES['hinh_anh']['name'][0])) {
                foreach ($_FILES['hinh_anh']['name'] as $key => $name) {
                    if ($_FILES['hinh_anh']['error'][$key] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['hinh_anh']['name'][$key],
                            'type' => $_FILES['hinh_anh']['type'][$key],
                            'tmp_name' => $_FILES['hinh_anh']['tmp_name'][$key],
                            'error' => $_FILES['hinh_anh']['error'][$key],
                            'size' => $_FILES['hinh_anh']['size'][$key]
                        ];
                        
                        $uploadedPath = uploadFile($file, 'uploads/journals/');
                        if ($uploadedPath) {
                            $images[] = $uploadedPath;
                        }
                    }
                }
            }
            
            // Xử lý xóa ảnh
            if (!empty($_POST['delete_images'])) {
                $deleteImages = is_array($_POST['delete_images']) ? $_POST['delete_images'] : [$_POST['delete_images']];
                foreach ($deleteImages as $imageToDelete) {
                    if (($key = array_search($imageToDelete, $images)) !== false) {
                        deleteFile($imageToDelete);
                        unset($images[$key]);
                    }
                }
                $images = array_values($images); // Re-index array
            }
            
            $data = [
                'ngay' => $_POST['ngay'] ?? $journal['ngay'],
                'dien_bien' => $_POST['dien_bien'] ?? null,
                'su_co' => $_POST['su_co'] ?? null,
                'thoi_tiet' => $_POST['thoi_tiet'] ?? null,
                'diem_nhan' => $_POST['diem_nhan'] ?? null,
                'hinh_anh' => $images
            ];
            
            $result = $this->journalModel->updateJournal($journalId, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật nhật ký thành công!';
                $this->redirect(BASE_URL . '?act=guide-journal-detail&id=' . $journalId);
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật nhật ký. Vui lòng thử lại.';
            }
        }
        
        // Lấy thông tin phân công và tour
        $assignment = $this->assignmentModel->getAssignmentByID($journal['id_phan_cong']);
        $departurePlan = null;
        $tour = null;
        if ($assignment && $assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $this->loadView('guide/journals/edit', compact('journal', 'assignment', 'departurePlan', 'tour'), 'guide/layout');
    }

    /**
     * Xóa nhật ký
     * Route: ?act=guide-journal-delete&id=X
     */
    public function deleteJournal() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $journalId = $_GET['id'] ?? null;
        
        if (!$journalId) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        // Kiểm tra quyền truy cập
        if (!$this->journalModel->checkGuideAccess($journalId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền xóa nhật ký này';
            $this->redirect(BASE_URL . '?act=guide-journals');
            return;
        }
        
        $result = $this->journalModel->deleteJournal($journalId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa nhật ký thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa nhật ký. Vui lòng thử lại.';
        }
        
        $this->redirect(BASE_URL . '?act=guide-journals');
    }

    /**
     * Danh sách báo cáo sự cố
     * Route: ?act=guide-incidents
     */
    public function listIncidents() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $filters = [];
        
        // Filter theo phân công
        if (!empty($_GET['id_phan_cong'])) {
            $filters['id_phan_cong'] = (int)$_GET['id_phan_cong'];
        }
        
        // Filter theo mức độ
        if (!empty($_GET['muc_do'])) {
            $filters['muc_do'] = $_GET['muc_do'];
        }
        
        // Filter theo loại sự cố
        if (!empty($_GET['loai_su_co'])) {
            $filters['loai_su_co'] = $_GET['loai_su_co'];
        }
        
        // Filter theo ngày
        if (!empty($_GET['from_date'])) {
            $filters['from_date'] = $_GET['from_date'];
        }
        if (!empty($_GET['to_date'])) {
            $filters['to_date'] = $_GET['to_date'];
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incidents = $incidentModel->getReportsByGuideID($guideId, $filters);
        
        // Lấy danh sách phân công để filter
        $assignments = $this->guideModel->getAssignmentsByGuideID($guideId);
        
        // Lấy danh sách loại sự cố và mức độ
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
        $this->loadView('guide/incidents/list', compact('incidents', 'filters', 'assignments', 'incidentTypes', 'severityLevels'), 'guide/layout');
    }

    /**
     * Tạo báo cáo sự cố mới
     * Route: ?act=guide-incident-create&assignment_id=X
     */
    public function createIncident() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['assignment_id'] ?? $_POST['assignment_id'] ?? null;
        
        $assignment = null;
        $departurePlan = null;
        $tour = null;
        
        // Nếu có assignment_id, kiểm tra quyền truy cập
        if ($assignmentId) {
            $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
            if (!$assignment || $assignment['id_hdv'] != $guideId) {
                $_SESSION['error'] = 'Bạn không có quyền tạo báo cáo sự cố cho phân công này';
                $this->redirect(BASE_URL . '?act=guide-assignments');
                return;
            }
            
            // Lấy thông tin phân công và tour
            if ($assignment['id_lich_khoi_hanh']) {
                $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
                if ($departurePlan && $departurePlan['id_tour']) {
                    $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
                }
            }
        }
        
        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$assignmentId) {
                $_SESSION['error'] = 'Vui lòng chọn phân công';
            } else {
                require_once './commons/function.php';
                require_once './models/IncidentSuggestionHelper.php';
                
                $loaiSuCo = $_POST['loai_su_co'] ?? null;
                $moTa = $_POST['mo_ta'] ?? null;
                $mucDo = $_POST['muc_do'] ?? null;
                
                // Tự động đề xuất mức độ nếu chưa chọn
                if (empty($mucDo) && !empty($loaiSuCo) && !empty($moTa)) {
                    $mucDo = IncidentSuggestionHelper::suggestSeverity($loaiSuCo, $moTa);
                }
                $mucDo = $mucDo ?? 'thap';
                
                // Lấy gợi ý xử lý tự động
                $suggestion = null;
                if (!empty($loaiSuCo)) {
                    $suggestion = IncidentSuggestionHelper::getSuggestion($loaiSuCo, $mucDo);
                }
                
                // Xử lý upload ảnh
                $images = [];
                if (!empty($_FILES['hinh_anh']['name'][0])) {
                    foreach ($_FILES['hinh_anh']['name'] as $key => $name) {
                        if ($_FILES['hinh_anh']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['hinh_anh']['name'][$key],
                                'type' => $_FILES['hinh_anh']['type'][$key],
                                'tmp_name' => $_FILES['hinh_anh']['tmp_name'][$key],
                                'error' => $_FILES['hinh_anh']['error'][$key],
                                'size' => $_FILES['hinh_anh']['size'][$key]
                            ];
                            
                            $uploadedPath = uploadFile($file, 'uploads/incidents/');
                            if ($uploadedPath) {
                                $images[] = $uploadedPath;
                            }
                        }
                    }
                }
                
                $data = [
                    'id_phan_cong' => $assignmentId,
                    'loai_su_co' => $loaiSuCo,
                    'mo_ta' => $moTa,
                    'cach_xu_ly' => $_POST['cach_xu_ly'] ?? null,
                    'goi_y_xu_ly' => $suggestion ? json_encode($suggestion, JSON_UNESCAPED_UNICODE) : null,
                    'muc_do' => $mucDo,
                    'ngay_xay_ra' => $_POST['ngay_xay_ra'] ?? date('Y-m-d'),
                    'gio_xay_ra' => $_POST['gio_xay_ra'] ?? null,
                    'vi_tri_gps' => $_POST['vi_tri_gps'] ?? null,
                    'hinh_anh' => $images,
                    'thong_tin_khach' => $_POST['thong_tin_khach'] ?? null,
                ];
                
                $incidentModel = $this->getIncidentReportModel();
                $incidentId = $incidentModel->createReport($data);
                
                if ($incidentId) {
                    // Gửi email tự động nếu mức độ cao hoặc nghiêm trọng
                    if (in_array($mucDo, ['cao', 'nghiem_trong'])) {
                        require_once './commons/IncidentReportEmailHelper.php';
                        $incident = $incidentModel->getReportByID($incidentId);
                        $guide = $this->guideModel->getGuideByID($guideId);
                        IncidentReportEmailHelper::sendIncidentReport($incident, $guide, $tour, $assignment);
                    }
                    
                    $_SESSION['success'] = 'Tạo báo cáo sự cố thành công!' . 
                        (in_array($mucDo, ['cao', 'nghiem_trong']) ? ' Báo cáo đã được gửi tự động cho công ty.' : '');
                    $this->redirect(BASE_URL . '?act=guide-incident-detail&id=' . $incidentId);
                    return;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi tạo báo cáo sự cố. Vui lòng thử lại.';
                }
            }
        }
        
        // Lấy danh sách phân công để chọn nếu chưa có assignment
        $assignments = [];
        if (!$assignment) {
            $assignments = $this->guideModel->getAssignmentsByGuideID($guideId, ['trang_thai' => 1]);
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
        $this->loadView('guide/incidents/create', compact('assignment', 'departurePlan', 'tour', 'incidentTypes', 'severityLevels', 'assignments'), 'guide/layout');
    }

    /**
     * Chi tiết báo cáo sự cố
     * Route: ?act=guide-incident-detail&id=X
     */
    public function incidentDetail() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $incidentId = $_GET['id'] ?? null;
        
        if (!$incidentId) {
            $_SESSION['error'] = 'ID báo cáo sự cố không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incident = $incidentModel->getReportByID($incidentId);
        
        if (!$incident) {
            $_SESSION['error'] = 'Không tìm thấy báo cáo sự cố';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        // Kiểm tra quyền truy cập
        if (!$incidentModel->checkGuideAccess($incidentId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền xem báo cáo sự cố này';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        // Lấy thông tin phân công và tour
        $assignment = $this->assignmentModel->getAssignmentByID($incident['id_phan_cong']);
        $departurePlan = null;
        $tour = null;
        if ($assignment && $assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
        $this->loadView('guide/incidents/detail', compact('incident', 'assignment', 'departurePlan', 'tour', 'incidentTypes', 'severityLevels'), 'guide/layout');
    }

    /**
     * Chỉnh sửa báo cáo sự cố
     * Route: ?act=guide-incident-edit&id=X
     */
    public function editIncident() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $incidentId = $_GET['id'] ?? null;
        
        if (!$incidentId) {
            $_SESSION['error'] = 'ID báo cáo sự cố không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incident = $incidentModel->getReportByID($incidentId);
        
        if (!$incident) {
            $_SESSION['error'] = 'Không tìm thấy báo cáo sự cố';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        // Kiểm tra quyền truy cập
        if (!$incidentModel->checkGuideAccess($incidentId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa báo cáo sự cố này';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'loai_su_co' => $_POST['loai_su_co'] ?? null,
                'mo_ta' => $_POST['mo_ta'] ?? null,
                'cach_xu_ly' => $_POST['cach_xu_ly'] ?? null,
                'muc_do' => $_POST['muc_do'] ?? 'thap',
                'ngay_xay_ra' => $_POST['ngay_xay_ra'] ?? date('Y-m-d'),
            ];
            
            $result = $incidentModel->updateReport($incidentId, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật báo cáo sự cố thành công!';
                $this->redirect(BASE_URL . '?act=guide-incident-detail&id=' . $incidentId);
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật báo cáo sự cố. Vui lòng thử lại.';
            }
        }
        
        // Lấy thông tin phân công và tour
        $assignment = $this->assignmentModel->getAssignmentByID($incident['id_phan_cong']);
        $departurePlan = null;
        $tour = null;
        if ($assignment && $assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        $incidentModel = $this->getIncidentReportModel();
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
        $this->loadView('guide/incidents/edit', compact('incident', 'assignment', 'departurePlan', 'tour', 'incidentTypes', 'severityLevels'), 'guide/layout');
    }

    /**
     * Xóa báo cáo sự cố
     * Route: ?act=guide-incident-delete&id=X
     */
    public function deleteIncident() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $incidentId = $_GET['id'] ?? null;
        
        if (!$incidentId) {
            $_SESSION['error'] = 'ID báo cáo sự cố không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        // Kiểm tra quyền truy cập
        $incidentModel = $this->getIncidentReportModel();
        if (!$incidentModel->checkGuideAccess($incidentId, $guideId)) {
            $_SESSION['error'] = 'Bạn không có quyền xóa báo cáo sự cố này';
            $this->redirect(BASE_URL . '?act=guide-incidents');
            return;
        }
        
        $result = $incidentModel->deleteReport($incidentId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa báo cáo sự cố thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa báo cáo sự cố. Vui lòng thử lại.';
        }
        
        $this->redirect(BASE_URL . '?act=guide-incidents');
    }
}

