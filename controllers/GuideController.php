<?php
require_once './commons/function.php';
require_once './models/BookingModel.php';
require_once './models/ServiceAssignmentModel.php';
require_once './models/DeparturePlanModel.php';
require_once './models/TourModel.php';
require_once './models/TourJournalModel.php';
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
    private $bookingModel;

    public function __construct() {
        $this->guideModel = new GuideModel();
        $this->assignmentModel = new AssignmentModel();
        $this->serviceAssignmentModel = new ServiceAssignmentModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->tourModel = new TourModel();
        $this->journalModel = new TourJournalModel();
        $this->bookingModel = new BookingModel();
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

        // Lịch hôm nay và trong 7 ngày
        $today = date('Y-m-d');
        $weekEnd = date('Y-m-d', strtotime('+7 days'));
        $todayAssignments = $this->guideModel->getAssignmentsByGuideID($guideId, [
            'from_date' => $today,
            'to_date' => $today
        ]);
        $weekAssignments = $this->guideModel->getAssignmentsByGuideID($guideId, [
            'from_date' => $today,
            'to_date' => $weekEnd
        ]);

        // Lấy phân công mới (trong 7 ngày gần đây, chưa được xác nhận)
        $newAssignments = $this->getNewAssignments($guideId);
        
        // Thông báo điều hành
        $announcements = [];
        if (!empty($newAssignments)) {
            foreach ($newAssignments as $assignment) {
                $announcements[] = [
                    'type' => 'new_assignment',
                    'message' => 'Bạn có phân công mới: ' . ($assignment['ten_tour'] ?? 'Tour') . 
                                ($assignment['ngay_khoi_hanh'] ? ' - Khởi hành: ' . date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) : ''),
                    'assignment_id' => $assignment['id'],
                    'created_at' => $assignment['ngay_cap_nhat'] ?? date('Y-m-d H:i:s')
                ];
            }
        }
        
        $this->loadView('guide/dashboard', compact('guide', 'stats', 'recentAssignments', 'todayAssignments', 'weekAssignments', 'announcements', 'newAssignments'), 'guide/layout');
    }

    /**
     * Lấy các phân công mới (trong 7 ngày gần đây, chưa được xác nhận)
     */
    private function getNewAssignments($guideId)
    {
        try {
            $conn = connectDB();
            $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
            
            // Thử query với cả ngay_tao và ngay_cap_nhat
            try {
                $sql = "SELECT pc.*, 
                               dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                               g.tengoi AS ten_tour, g.id_goi AS id_tour
                        FROM phan_cong_hdv pc
                        LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                        LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                        WHERE pc.id_hdv = :id_hdv
                          AND (pc.ngay_cap_nhat >= :seven_days_ago OR pc.ngay_tao >= :seven_days_ago)
                          AND (pc.da_nhan = 0 OR pc.da_nhan IS NULL)
                          AND pc.trang_thai = 1
                        ORDER BY pc.ngay_cap_nhat DESC, pc.ngay_tao DESC
                        LIMIT 5";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':id_hdv' => $guideId,
                    ':seven_days_ago' => $sevenDaysAgo
                ]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Nếu không có trường ngay_tao, thử query không có điều kiện ngay_tao
                $sql = "SELECT pc.*, 
                               dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                               g.tengoi AS ten_tour, g.id_goi AS id_tour
                        FROM phan_cong_hdv pc
                        LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                        LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                        WHERE pc.id_hdv = :id_hdv
                          AND pc.ngay_cap_nhat >= :seven_days_ago
                          AND (pc.da_nhan = 0 OR pc.da_nhan IS NULL)
                          AND pc.trang_thai = 1
                        ORDER BY pc.ngay_cap_nhat DESC
                        LIMIT 5";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':id_hdv' => $guideId,
                    ':seven_days_ago' => $sevenDaysAgo
                ]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            error_log("Lỗi lấy phân công mới: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Danh sách phân công của guide
     * Route: ?act=guide-assignments
     */
    public function listAssignments() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $filters = [];
        
        // Chỉ lấy các phân công chưa xác nhận (da_nhan = 0)
        $filters['da_nhan'] = 0;
        
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

        // Bổ sung tổng khách và trạng thái (thủ công theo trang_thai hoặc tính theo ngày nếu chưa set)
        foreach ($assignments as &$a) {
            $a['tong_khach'] = 0;
            $a['trang_thai_hien_thi'] = 'Chưa xác định';
            $a['nhan_trang_thai'] = !empty($a['da_nhan']) ? 'Đã nhận' : 'Chờ nhận';
            // Trang thái thủ công: 0=Ready,1=Đang diễn ra,2=Hoàn thành
            if (isset($a['trang_thai'])) {
                if ($a['trang_thai'] == 0) $a['trang_thai_hien_thi'] = 'Ready';
                elseif ($a['trang_thai'] == 1) $a['trang_thai_hien_thi'] = 'Đang diễn ra';
                elseif ($a['trang_thai'] == 2) $a['trang_thai_hien_thi'] = 'Hoàn thành';
            }
            if (!empty($a['id_lich_khoi_hanh'])) {
                $bookings = $this->bookingModel->getBookingsByDeparturePlan($a['id_lich_khoi_hanh']);
                foreach ($bookings as $b) {
                    $details = $this->bookingModel->getBookingDetails($b['id']);
                    $a['tong_khach'] += count($details);
                }
            }
        }
        unset($a);
        
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

        // Tính trạng thái hiển thị (thủ công 0/1/2)
        $assignment['trang_thai_hien_thi'] = 'Chưa xác định';
        if (isset($assignment['trang_thai'])) {
            if ($assignment['trang_thai'] == 0) $assignment['trang_thai_hien_thi'] = 'Ready';
            elseif ($assignment['trang_thai'] == 1) $assignment['trang_thai_hien_thi'] = 'Đang diễn ra';
            elseif ($assignment['trang_thai'] == 2) $assignment['trang_thai_hien_thi'] = 'Hoàn thành';
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
        
        // Lấy bookings và khách
        $bookings = [];
        $members = [];
        $guestStats = ['NL' => 0, 'TE' => 0, 'EB' => 0];
        $tongTienTour = 0; // Tổng tiền từ tất cả bookings
        $attendanceStats = ['total' => 0, 'co_mat' => 0, 'vang_mat' => 0, 'chua_diem_danh' => 0]; // Thống kê điểm danh
        if ($departurePlan) {
            $bookings = $this->bookingModel->getBookingsByDeparturePlan($departurePlan['id']);
            foreach ($bookings as $booking) {
                // Tính tổng tiền từ các booking
                if (!empty($booking['tong_tien'])) {
                    $tongTienTour += (float)$booking['tong_tien'];
                }
                
                $details = $this->bookingModel->getBookingDetails($booking['id']);
                foreach ($details as $detail) {
                    $members[] = [
                        'id' => $detail['id'],
                        'id_booking' => $booking['id'],
                        'ma_booking' => $booking['ma_booking'],
                        'ho_ten' => $detail['ho_ten'],
                        'so_dien_thoai' => $detail['so_dien_thoai'],
                        'loai_khach' => $detail['loai_khach']
                    ];
                    $lk = strtoupper($detail['loai_khach'] ?? '');
                    if ($lk === 'TE') $guestStats['TE']++;
                    elseif ($lk === 'EB') $guestStats['EB']++;
                    else $guestStats['NL']++;
                }
            }
            
            // Lấy thống kê điểm danh cho ngày hôm nay
            require_once './models/AttendanceModel.php';
            $attendanceModel = new AttendanceModel();
            $todayAttendance = $attendanceModel->getAttendanceByDeparturePlan($departurePlan['id'], date('Y-m-d'));
            
            $attendanceStats['total'] = count($members);
            $attendanceStats['co_mat'] = 0;
            $attendanceStats['vang_mat'] = 0;
            $attendanceStats['chua_diem_danh'] = count($members);
            
            if (!empty($todayAttendance)) {
                foreach ($todayAttendance as $att) {
                    if ($att['trang_thai'] == 1) {
                        $attendanceStats['co_mat']++;
                    } elseif ($att['trang_thai'] == 0) {
                        $attendanceStats['vang_mat']++;
                    }
                }
                $attendanceStats['chua_diem_danh'] = $attendanceStats['total'] - count($todayAttendance);
            }
        }

        // Tự động điền số điều hành bằng số điện thoại HDV nếu chưa có
        if (empty($assignment['so_dieu_hanh']) && !empty($assignment['so_dien_thoai'])) {
            // Tự động cập nhật số điều hành bằng số điện thoại HDV
            $this->assignmentModel->updateContactNumbers($assignmentId, $assignment['so_dien_thoai'], $assignment['so_khan_cap'] ?? null);
            // Reload assignment để lấy giá trị mới
            $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        }
        
        // Parse lịch trình theo ngày
        $itineraryDays = [];
        if (!empty($departurePlan['chuongtrinh'])) {
            $chuongtrinh_raw = trim($departurePlan['chuongtrinh']);
            
            // Thử parse JSON trước (format mới)
            // Loại bỏ các ký tự whitespace thừa ở đầu và cuối
            $chuongtrinh_clean = trim($chuongtrinh_raw);
            // Tìm phần JSON hợp lệ (bắt đầu bằng [ và kết thúc bằng ])
            if (preg_match('/\[.*\]/s', $chuongtrinh_clean, $jsonMatch)) {
                $jsonString = $jsonMatch[0];
            } else {
                $jsonString = $chuongtrinh_clean;
            }
            
            $jsonData = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData) && !empty($jsonData)) {
                // Format JSON: [{"ngay": 1, "tieu_de": "...", "noi_dung": "..."}, ...]
                foreach ($jsonData as $day) {
                    if (isset($day['ngay']) && isset($day['noi_dung'])) {
                        $dayNum = (int)$day['ngay'];
                        $dayTitle = 'Ngày ' . $dayNum;
                        if (!empty($day['tieu_de'])) {
                            $dayTitle .= ': ' . htmlspecialchars($day['tieu_de']);
                        }
                        // Làm sạch nội dung HTML
                        $content = trim($day['noi_dung']);
                        // Đảm bảo không có ký tự JSON thừa
                        $content = preg_replace('/[\s]*[\}\]\"]+[\s]*$/', '', $content);
                        $content = preg_replace('/^[\s]*[\{\[\"]+[\s]*/', '', $content);
                        $itineraryDays[$dayNum] = [
                            'title' => $dayTitle,
                            'content' => $content // Đã là HTML và đã làm sạch
                        ];
                    }
                }
                ksort($itineraryDays);
            } else {
                // Format cũ: HTML/text với markers "Ngày X"
                $chuongtrinh = html_entity_decode((string)$chuongtrinh_raw, ENT_QUOTES, 'UTF-8');
                preg_match_all('/(?:NGÀY|Day|Ngày)\s*(\d+)(?:\s*:\s*([^<\n]+))?/i', $chuongtrinh, $matches, PREG_OFFSET_CAPTURE);
                if (!empty($matches[0])) {
                    $markers = [];
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        $dayNum = (int)$matches[1][$i][0];
                        $pos = $matches[0][$i][1];
                        $fullMatch = $matches[0][$i][0];
                        $title = isset($matches[2][$i]) ? trim(strip_tags($matches[2][$i][0])) : '';
                        $afterText = substr($chuongtrinh, $pos, 500);
                        $endPos = $pos + strlen($fullMatch);
                        if (preg_match('/<\/[^>]+>/', $afterText, $closeTag, PREG_OFFSET_CAPTURE)) {
                            $tagEnd = $pos + $closeTag[0][1] + strlen($closeTag[0][0]);
                            if ($tagEnd > $endPos) $endPos = $tagEnd;
                        }
                        if (!isset($markers[$dayNum]) || $markers[$dayNum]['pos'] > $pos) {
                            $markers[$dayNum] = [
                                'day' => $dayNum,
                                'pos' => $pos,
                                'end_pos' => $endPos,
                                'title' => $title
                            ];
                        }
                    }
                    uasort($markers, fn($a, $b) => $a['pos'] - $b['pos']);
                    $markerList = array_values($markers);
                    for ($i = 0; $i < count($markerList); $i++) {
                        $marker = $markerList[$i];
                        $dayNum = $marker['day'];
                        $contentStart = $marker['end_pos'];
                        $contentEnd = ($i < count($markerList) - 1) ? $markerList[$i + 1]['pos'] : strlen($chuongtrinh);
                        $dayContent = trim(substr($chuongtrinh, $contentStart, $contentEnd - $contentStart));
                        $dayContent = preg_replace('/<[^>]*>\s*(?:NGÀY|Day|Ngày)\s*\d+[^<]*\s*<\/[^>]*>/is', '', $dayContent);
                        $dayContent = trim($dayContent);
                        $dayTitle = 'Ngày ' . $dayNum;
                        if (!empty($marker['title'])) {
                            $dayTitle .= ': ' . htmlspecialchars($marker['title']);
                        }
                        $itineraryDays[$dayNum] = [
                            'title' => $dayTitle,
                            'content' => $dayContent
                        ];
                    }
                }
                if (empty($itineraryDays)) {
                    $itineraryDays[1] = [
                        'title' => 'Ngày 1',
                        'content' => $chuongtrinh
                    ];
                }
                ksort($itineraryDays);
            }
        }
        
        // Lấy thông tin điểm danh cho từng thành viên
        $todayAttendanceMap = [];
        if ($departurePlan && !empty($members)) {
            require_once './models/AttendanceModel.php';
            $attendanceModel = new AttendanceModel();
            $todayAttendance = $attendanceModel->getAttendanceByDeparturePlan($departurePlan['id'], date('Y-m-d'));
            foreach ($todayAttendance as $att) {
                $todayAttendanceMap[$att['id_booking_detail']] = $att;
            }
        }
        
        $this->loadView(
            'guide/assignments/detail',
            compact('assignment', 'departurePlan', 'tour', 'services', 'checklist', 'checklistItems', 'completionPercentage', 'bookings', 'members', 'guestStats', 'itineraryDays', 'tongTienTour', 'attendanceStats', 'todayAttendanceMap'),
            'guide/layout'
        );
    }

    /**
     * Xác nhận đã nhận tour
     * Route: ?act=guide-assignment-confirm&id=X
     */
    public function confirmAssignment() {
        $this->checkLogin();
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['id'] ?? null;
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }

        $ok = $this->assignmentModel->confirmReceived($assignmentId);
        if ($ok) {
            $_SESSION['success'] = 'Đã xác nhận nhận tour. Tour đã được chuyển sang Lịch làm việc.';
            // Redirect về trang schedule (lịch làm việc) sau khi xác nhận
            $this->redirect(BASE_URL . '?act=guide-schedule');
        } else {
            $_SESSION['error'] = 'Không thể xác nhận nhận tour.';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
    }

    /**
     * Cập nhật trạng thái phân công (Ready/Đang diễn ra/Hoàn thành)
     * Route: ?act=guide-assignment-status (POST)
     */
    public function updateAssignmentStatus() {
        $this->checkLogin();
        $guideId = $_SESSION['guide_id'];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        $assignmentId = $_POST['assignment_id'] ?? null;
        $status = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : null;
        if ($assignmentId === null || $status === null || $status < 0 || $status > 2) {
            $_SESSION['error'] = 'Dữ liệu trạng thái không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }

        // Chỉ cho phép tiến tới (Ready -> Đang diễn ra -> Hoàn thành), không hạ cấp
        $currentStatus = isset($assignment['trang_thai']) ? (int)$assignment['trang_thai'] : 0;
        if ($status < $currentStatus) {
            $_SESSION['error'] = 'Không thể chuyển ngược trạng thái (chỉ được tiến tới).';
            $this->redirect(BASE_URL . '?act=guide-assignment-detail&id=' . $assignmentId);
        }

        // Kiểm tra điều kiện: Chỉ cho phép đổi sang "Đang diễn ra" (status = 1) khi đã điểm danh hết
        if ($status == 1 && $currentStatus == 0) {
            if (!$this->checkAllMembersAttended($assignment['id_lich_khoi_hanh'])) {
                $_SESSION['error'] = 'Không thể chuyển sang "Đang diễn ra". Vui lòng điểm danh tất cả thành viên trước.';
                $this->redirect(BASE_URL . '?act=guide-assignment-detail&id=' . $assignmentId);
            }
        }

        $ok = $this->assignmentModel->setStatus($assignmentId, $status);
        if ($ok) {
            $_SESSION['success'] = 'Đã cập nhật trạng thái.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái.';
        }
        $this->redirect(BASE_URL . '?act=guide-assignment-detail&id=' . $assignmentId);
    }

    /**
     * Kiểm tra xem tất cả thành viên đã điểm danh chưa (ngày khởi hành)
     */
    private function checkAllMembersAttended($id_lich_khoi_hanh)
    {
        if (empty($id_lich_khoi_hanh)) {
            return false; // Không có lịch khởi hành thì không thể kiểm tra
        }

        try {
            // Lấy ngày khởi hành
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($id_lich_khoi_hanh);
            if (!$departurePlan || empty($departurePlan['ngay_khoi_hanh'])) {
                return false;
            }

            $ngay_khoi_hanh = $departurePlan['ngay_khoi_hanh'];

            // Lấy tất cả thành viên từ booking_detail của các booking thuộc lịch khởi hành này
            $bookings = $this->bookingModel->getBookingsByDeparturePlan($id_lich_khoi_hanh);
            $allMemberIds = [];

            foreach ($bookings as $booking) {
                // Chỉ tính các booking không bị hủy
                if (isset($booking['trang_thai']) && $booking['trang_thai'] == 'huy') {
                    continue;
                }

                $details = $this->bookingModel->getBookingDetails($booking['id']);
                foreach ($details as $detail) {
                    $allMemberIds[] = $detail['id'];
                }
            }

            if (empty($allMemberIds)) {
                return true; // Không có thành viên nào thì coi như đã điểm danh hết
            }

            // Lấy tất cả điểm danh trong ngày khởi hành một lần
            require_once './models/AttendanceModel.php';
            $attendanceModel = new AttendanceModel();
            $attendance = $attendanceModel->getAttendanceByDeparturePlan($id_lich_khoi_hanh, $ngay_khoi_hanh);
            
            // Tạo mảng ID các thành viên đã điểm danh
            $attendedMemberIds = [];
            foreach ($attendance as $att) {
                if ($att['ngay_diem_danh'] == $ngay_khoi_hanh) {
                    $attendedMemberIds[] = $att['id_booking_detail'];
                }
            }

            // Kiểm tra xem tất cả thành viên đã điểm danh chưa
            $attendedMemberIds = array_unique($attendedMemberIds);
            $allMemberIds = array_unique($allMemberIds);
            
            return count($allMemberIds) > 0 && count($attendedMemberIds) == count($allMemberIds) && 
                   empty(array_diff($allMemberIds, $attendedMemberIds));
        } catch (Exception $e) {
            error_log("Lỗi checkAllMembersAttended: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật số điều hành và số khẩn cấp
     * Route: ?act=guide-assignment-update-contacts (POST)
     */
    public function updateContactNumbers() {
        $this->checkLogin();
        $guideId = $_SESSION['guide_id'];
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=guide-assignments');
            return;
        }
        
        $assignmentId = filter_var($_POST['assignment_id'] ?? 0, FILTER_VALIDATE_INT);
        $soDieuHanh = trim($_POST['so_dieu_hanh'] ?? '');
        $soKhanCap = trim($_POST['so_khan_cap'] ?? '');
        
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
            return;
        }
        
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền';
            $this->redirect(BASE_URL . '?act=guide-assignments');
            return;
        }
        
        $result = $this->assignmentModel->updateContactNumbers($assignmentId, $soDieuHanh, $soKhanCap);
        
        if ($result) {
            $_SESSION['success'] = 'Đã cập nhật số liên hệ thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật số liên hệ.';
        }
        
        $this->redirect(BASE_URL . '?act=guide-assignment-detail&id=' . $assignmentId);
    }

    /**
     * Trang điểm danh thành viên
     * Route: ?act=guide-attendance&assignment_id=X
     */
    public function attendance() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['assignment_id'] ?? null;
        $ngay_diem_danh = $_GET['ngay_diem_danh'] ?? date('Y-m-d');
        
        if (!$assignmentId) {
            $_SESSION['error'] = 'ID phân công không hợp lệ';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        $assignment = $this->assignmentModel->getAssignmentByID($assignmentId);
        
        if (!$assignment || $assignment['id_hdv'] != $guideId) {
            $_SESSION['error'] = 'Không tìm thấy phân công hoặc bạn không có quyền xem';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Lấy thông tin lịch khởi hành
        $departurePlan = null;
        $tour = null;
        if ($assignment['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($assignment['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
            }
        }
        
        if (!$departurePlan) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            $this->redirect(BASE_URL . '?act=guide-assignments');
        }
        
        // Lấy tên tour từ tour hoặc departurePlan
        if ($tour && !empty($tour['tengoi'])) {
            $departurePlan['ten_tour'] = $tour['tengoi'];
        } elseif (empty($departurePlan['ten_tour'])) {
            $departurePlan['ten_tour'] = 'Tour chưa có tên';
        }
        
        // Lấy danh sách thành viên từ các booking trong lịch khởi hành
        require_once './models/BookingModel.php';
        require_once './models/AttendanceModel.php';
        $bookingModel = new BookingModel();
        $attendanceModel = new AttendanceModel();
        
        // Lấy tất cả booking của lịch khởi hành
        $bookings = $bookingModel->getBookingsByDeparturePlan($departurePlan['id']);
        
        $members = [];
        foreach ($bookings as $booking) {
            $bookingDetails = $bookingModel->getBookingDetails($booking['id']);
            foreach ($bookingDetails as $detail) {
                $members[] = [
                    'id' => $detail['id'],
                    'id_booking' => $booking['id'],
                    'ma_booking' => $booking['ma_booking'],
                    'ho_ten' => $detail['ho_ten'],
                    'so_dien_thoai' => $detail['so_dien_thoai'] ?? null,
                    'loai_khach' => $detail['loai_khach'] ?? null,
                    'gioi_tinh' => $detail['gioi_tinh'] ?? null,
                    'ngay_sinh' => $detail['ngay_sinh'] ?? null
                ];
            }
        }
        
        // Lấy điểm danh đã có
        $attendance = $attendanceModel->getAttendanceByDeparturePlan($departurePlan['id'], $ngay_diem_danh);
        
        $this->loadView('guide/attendance', compact('assignment', 'departurePlan', 'tour', 'members', 'attendance', 'ngay_diem_danh'), 'guide/layout');
    }

    /**
     * Lưu điểm danh (AJAX)
     * Route: ?act=guide-attendance-save
     */
    public function saveAttendance() {
        // Tắt output buffering và đảm bảo không có output nào trước JSON
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Set JSON header ngay từ đầu để tránh output HTML
        header('Content-Type: application/json');
        
        // Tắt display_errors tạm thời để tránh PHP warnings/errors làm hỏng JSON
        $oldDisplayErrors = ini_get('display_errors');
        ini_set('display_errors', 0);
        
        // Kiểm tra login mà không redirect (vì đây là AJAX)
        if (empty($_SESSION['guide_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập. Vui lòng đăng nhập lại.']);
            ini_set('display_errors', $oldDisplayErrors);
            exit;
        }
        
        try {
        $guideId = $_SESSION['guide_id'];
            
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("JSON decode error: " . json_last_error_msg());
                echo json_encode(['success' => false, 'message' => 'Dữ liệu JSON không hợp lệ: ' . json_last_error_msg()]);
                ini_set('display_errors', $oldDisplayErrors);
                exit;
            }
        
        if (!$data || !isset($data['id_lich_khoi_hanh']) || !isset($data['ngay_diem_danh']) || !isset($data['attendance'])) {
                error_log("Missing required fields. Data: " . print_r($data, true));
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ. Thiếu thông tin bắt buộc.']);
                ini_set('display_errors', $oldDisplayErrors);
                exit;
        }
        
            // Require model trong try-catch để bắt lỗi
            try {
        require_once './models/AttendanceModel.php';
        $attendanceModel = new AttendanceModel();
            } catch (Exception $e) {
                error_log("Error loading AttendanceModel: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Lỗi khởi tạo model: ' . $e->getMessage()]);
                ini_set('display_errors', $oldDisplayErrors);
                exit;
            }
        
        $result = $attendanceModel->markAttendanceBatch(
            $data['id_lich_khoi_hanh'],
            $data['id_hdv'] ?? $guideId,
            $data['ngay_diem_danh'],
            $data['attendance']
        );
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Điểm danh thành công']);
        } else {
                error_log("markAttendanceBatch failed for lich_khoi_hanh: " . $data['id_lich_khoi_hanh']);
                echo json_encode(['success' => false, 'message' => 'Không thể lưu điểm danh. Vui lòng kiểm tra lại dữ liệu.']);
        }
        } catch (Exception $e) {
            error_log("saveAttendance exception: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        } finally {
            // Khôi phục display_errors
            ini_set('display_errors', $oldDisplayErrors);
        }
        exit; // Đảm bảo không có output nào sau JSON
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
     * Lịch làm việc của guide - Chỉ hiển thị các phân công đã xác nhận (da_nhan = 1)
     * Route: ?act=guide-schedule
     */
    public function schedule() {
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        // Chỉ lấy các phân công đã xác nhận (da_nhan = 1)
        $assignments = $this->guideModel->getAssignmentsByGuideID($guideId, ['da_nhan' => 1]);
        
        // Tính toán trạng thái hiển thị cho mỗi assignment
        foreach ($assignments as &$a) {
            $a['trang_thai_hien_thi'] = 'Chưa xác định';
            if (isset($a['trang_thai'])) {
                if ($a['trang_thai'] == 0) $a['trang_thai_hien_thi'] = 'Ready';
                elseif ($a['trang_thai'] == 1) $a['trang_thai_hien_thi'] = 'Đang diễn ra';
                elseif ($a['trang_thai'] == 2) $a['trang_thai_hien_thi'] = 'Hoàn thành';
            }
        }
        unset($a);
        
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
        
        // Nếu chưa chọn phân công, hiển thị form chọn phân công trước
        if (!$assignmentId) {
            $assignments = $this->guideModel->getAssignmentsByGuideID($guideId);
            $this->loadView('guide/journals/select-assignment', compact('assignments'), 'guide/layout');
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
        try {
            // Debug log
            error_log("createIncident called - GET: " . print_r($_GET, true) . " POST: " . print_r($_POST, true));
            
        $this->checkLogin();
        
        $guideId = $_SESSION['guide_id'];
        $assignmentId = $_GET['assignment_id'] ?? $_POST['assignment_id'] ?? null;
            
            error_log("createIncident - guideId: $guideId, assignmentId: " . ($assignmentId ?? 'null'));
        
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
            
            // Không redirect nếu không có assignment_id - chỉ hiển thị form chọn phân công
        
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
            // Lấy cả phân công đã xác nhận (da_nhan = 1) để có thể tạo báo cáo
        $assignments = [];
        if (!$assignment) {
                // Lấy tất cả phân công đã xác nhận (da_nhan = 1) để có thể tạo báo cáo
                try {
                    $assignments = $this->guideModel->getAssignmentsByGuideID($guideId, ['da_nhan' => 1]);
                    error_log("createIncident - Found " . count($assignments) . " assignments");
                } catch (Exception $e) {
                    error_log("createIncident - Error getting assignments: " . $e->getMessage());
                    $assignments = [];
                }
        }
        
            try {
        $incidentModel = $this->getIncidentReportModel();
        $incidentTypes = $incidentModel->getIncidentTypes();
        $severityLevels = $incidentModel->getSeverityLevels();
        
                error_log("createIncident - About to load view");
                
                // Luôn hiển thị form, kể cả khi không có assignments
        $this->loadView('guide/incidents/create', compact('assignment', 'departurePlan', 'tour', 'incidentTypes', 'severityLevels', 'assignments'), 'guide/layout');
            } catch (Exception $e) {
                error_log("createIncident - Error loading view: " . $e->getMessage());
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Error in createIncident: " . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect(BASE_URL . '?act=guide-incidents');
        }
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

