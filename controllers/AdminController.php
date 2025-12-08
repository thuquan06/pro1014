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
    private $departurePlanModel;
    private $pretripChecklistModel;
    private $guideModel;
    private $assignmentModel;
    private $serviceModel;
    private $bookingModel;
    private $voucherModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
        $this->tourModel      = new TourModel();
        $this->adminModel     = new AdminModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->pretripChecklistModel = new PretripChecklistModel();
        $this->guideModel = new GuideModel();
        $this->assignmentModel = new AssignmentModel();
        $this->serviceModel = new ServiceModel();
        require_once './models/BookingModel.php';
        $this->bookingModel = new BookingModel();
        require_once './models/VoucherModel.php';
        $this->voucherModel = new VoucherModel();
    }

    
    /* ==================== AUTH ==================== */

    /**
     * Hiển thị form đăng nhập và xử lý đăng nhập
     * Route: ?act=login
     */
    public function login() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }
        
        // Nếu là POST request → xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }
        
        // Load form login (KHÔNG có layout admin)
        require_once './views/admin/login.php';
    }

    /**
     * Hiển thị form quên mật khẩu
     * Route: ?act=forgot-password
     */
    public function forgotPassword() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }
        
        $error = null;
        $success = null;
        
        // Load form forgot password
        require_once './views/admin/forgot-password.php';
    }

    /**
     * Xử lý quên mật khẩu - Yêu cầu xác thực email trước
     * Route: ?act=forgot-password-handle
     */
    public function handleForgotPassword() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=forgot-password');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($username)) {
            $error = 'Vui lòng nhập tên đăng nhập';
            extract(['error' => $error, 'resetLinkDisplay' => null]);
            require_once './views/admin/forgot-password.php';
            return;
        }

        if (empty($email)) {
            $error = 'Vui lòng nhập email để xác thực';
            extract(['error' => $error, 'resetLinkDisplay' => null]);
            require_once './views/admin/forgot-password.php';
            return;
        }

        // Kiểm tra username và email có khớp không
        $admin = $this->adminModel->findByUsername($username);
        
        if (!$admin) {
            // Không tiết lộ username có tồn tại hay không (security best practice)
            $error = 'Nếu tài khoản và email khớp, bạn sẽ nhận được link reset qua email';
            extract(['error' => $error, 'resetLinkDisplay' => null]);
            require_once './views/admin/forgot-password.php';
            return;
        }

        // Kiểm tra email có khớp không
        $adminEmail = $this->adminModel->getAdminEmail($username);
        
        if (!$adminEmail || strtolower(trim($adminEmail)) !== strtolower(trim($email))) {
            // Không tiết lộ thông tin chi tiết (security best practice)
            $error = 'Nếu tài khoản và email khớp, bạn sẽ nhận được link reset qua email';
            extract(['error' => $error, 'resetLinkDisplay' => null]);
            require_once './views/admin/forgot-password.php';
            return;
        }

        // Email khớp → Tạo token và gửi email
        $token = $this->adminModel->createPasswordResetToken($username);
        
        if ($token) {
            // Tạo link reset
            $resetLink = BASE_URL . '?act=reset-password&token=' . $token;
            
            // Gửi email với link reset
            $emailSent = $this->sendPasswordResetEmail($email, $username, $resetLink);
            
            if ($emailSent) {
                $success = 'Link reset mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư (cả thư mục Spam).';
            } else {
                // Fallback: Hiển thị link nếu không gửi được email (chỉ trong môi trường dev)
                // Kiểm tra xem có phải localhost không
                $isLocalhost = (strpos(BASE_URL, 'localhost') !== false || strpos(BASE_URL, '127.0.0.1') !== false);
                
                if ($isLocalhost) {
                    // Trên localhost, hiển thị link để test
                    $resetLinkDisplay = $resetLink;
                    $success = 'Link reset mật khẩu đã được tạo. Vui lòng kiểm tra email hoặc sử dụng link bên dưới (chế độ phát triển).';
                } else {
                    // Trên production, không hiển thị link
                    $success = 'Link reset mật khẩu đã được tạo. Vui lòng kiểm tra email của bạn. Nếu không nhận được email, vui lòng kiểm tra lại cấu hình SMTP hoặc liên hệ quản trị viên.';
                }
            }
        } else {
            $error = 'Không thể tạo link reset. Vui lòng thử lại sau.';
        }

        extract(['error' => $error ?? null, 'success' => $success ?? null, 'resetLinkDisplay' => $resetLinkDisplay ?? null]);
        require_once './views/admin/forgot-password.php';
    }

    /**
     * Gửi email reset password
     * 
     * @param string $email
     * @param string $username
     * @param string $resetLink
     * @return bool
     */
    private function sendPasswordResetEmail($email, $username, $resetLink) {
        try {
            // Load EmailHelper
            require_once './commons/EmailHelper.php';
            
            $subject = 'Reset Mật khẩu - StarVel Admin';
            $message = $this->buildPasswordResetEmailTemplate($username, $resetLink);
            
            // Gửi email qua EmailHelper
            $emailHelper = new EmailHelper();
            $result = $emailHelper->send($email, $subject, $message);
            
            return $result;
        } catch (Exception $e) {
            error_log("Send email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo template email reset password
     * 
     * @param string $username
     * @param string $resetLink
     * @return string HTML template
     */
    private function buildPasswordResetEmailTemplate($username, $resetLink) {
        return "
        <!DOCTYPE html>
        <html lang='vi'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f4f4f4;
                }
                .email-container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: white;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .email-header { 
                    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e8ba3 100%); 
                    color: white; 
                    padding: 30px 20px; 
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 600;
                }
                .email-content { 
                    padding: 40px 30px; 
                    background: #ffffff;
                }
                .email-content p {
                    margin: 15px 0;
                    color: #555;
                    font-size: 16px;
                }
                .email-content .greeting {
                    font-size: 18px;
                    color: #333;
                    font-weight: 600;
                }
                .reset-button { 
                    display: inline-block; 
                    padding: 14px 35px; 
                    background: linear-gradient(135deg, #2563eb, #1e40af); 
                    color: white !important; 
                    text-decoration: none; 
                    border-radius: 8px; 
                    margin: 25px 0; 
                    font-weight: 600;
                    font-size: 16px;
                    text-align: center;
                    box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
                }
                .reset-button:hover {
                    background: linear-gradient(135deg, #1e40af, #1e3a8a);
                }
                .reset-link-box {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 15px;
                    margin: 20px 0;
                    word-break: break-all;
                    font-size: 14px;
                    color: #475569;
                }
                .reset-link-box a {
                    color: #2563eb;
                    text-decoration: none;
                }
                .warning-box {
                    background: #fef3c7;
                    border-left: 4px solid #f59e0b;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .warning-box p {
                    margin: 5px 0;
                    color: #92400e;
                    font-size: 14px;
                }
                .email-footer { 
                    background: #f8fafc;
                    padding: 25px 30px; 
                    text-align: center;
                    border-top: 1px solid #e2e8f0;
                }
                .email-footer p {
                    margin: 5px 0;
                    font-size: 12px; 
                    color: #64748b;
                }
                .email-footer a {
                    color: #2563eb;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>🔐 Reset Mật khẩu</h1>
                </div>
                <div class='email-content'>
                    <p class='greeting'>Xin chào <strong>{$username}</strong>,</p>
                    <p>Bạn đã yêu cầu reset mật khẩu cho tài khoản admin của StarVel.</p>
                    <p>Click vào nút bên dưới để đặt lại mật khẩu:</p>
                    <div style='text-align: center;'>
                        <a href='{$resetLink}' class='reset-button'>Đặt lại mật khẩu</a>
                    </div>
                    <p style='text-align: center; color: #64748b; font-size: 14px;'>Hoặc copy link sau vào trình duyệt:</p>
                    <div class='reset-link-box'>
                        <a href='{$resetLink}'>{$resetLink}</a>
                    </div>
                    <div class='warning-box'>
                        <p><strong>⚠️ Lưu ý quan trọng:</strong></p>
                        <p>• Link này có hiệu lực trong <strong>1 giờ</strong></p>
                        <p>• Link chỉ sử dụng được <strong>1 lần</strong></p>
                        <p>• Nếu bạn không yêu cầu reset mật khẩu, vui lòng bỏ qua email này</p>
                    </div>
                </div>
                <div class='email-footer'>
                    <p><strong>StarVel Admin System</strong></p>
                    <p>© 2025 StarVel. All rights reserved.</p>
                    <p>Email này được gửi tự động, vui lòng không reply.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Hiển thị form reset password
     * Route: ?act=reset-password&token=XXX
     */
    public function resetPassword() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }

        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = 'Token không hợp lệ';
            $this->redirect(BASE_URL . '?act=forgot-password');
        }

        // Verify token
        $tokenData = $this->adminModel->verifyResetToken($token);
        
        if (!$tokenData) {
            // Debug: Kiểm tra xem có phải do database chưa tạo không
            $errorMsg = 'Token không hợp lệ hoặc đã hết hạn';
            
            // Thử kiểm tra xem token có tồn tại trong DB không (kể cả đã hết hạn)
            try {
                $conn = connectDB();
                $sqlCheck = "SELECT * FROM password_reset_tokens WHERE token = :token LIMIT 1";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->execute([':token' => $token]);
                $tokenExists = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                
                if ($tokenExists) {
                    if ($tokenExists['used'] == 1) {
                        $errorMsg = 'Token này đã được sử dụng. Vui lòng tạo link reset mới.';
                    } elseif (strtotime($tokenExists['expires_at']) < time()) {
                        $errorMsg = 'Token đã hết hạn. Vui lòng tạo link reset mới.';
                    }
                } else {
                    $errorMsg = 'Token không tồn tại. Vui lòng kiểm tra lại link.';
                }
            } catch (PDOException $e) {
                // Nếu lỗi do bảng chưa tồn tại
                if (strpos($e->getMessage(), "doesn't exist") !== false) {
                    $errorMsg = 'Hệ thống chưa được cấu hình đúng. Vui lòng liên hệ quản trị viên.';
                }
            }
            
            $_SESSION['error'] = $errorMsg;
            $this->redirect(BASE_URL . '?act=forgot-password');
        }

        $error = null;
        
        // Truyền token vào view (sử dụng compact hoặc extract)
        extract(['token' => $token, 'error' => $error]);
        require_once './views/admin/reset-password.php';
    }

    /**
     * Xử lý reset password
     * Route: ?act=reset-password-handle
     */
    public function handleResetPassword() {
        // Nếu đã login → chuyển về dashboard
        if (!empty($_SESSION['alogin'])) {
            $this->redirect(BASE_URL . '?act=admin');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=forgot-password');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token)) {
            $_SESSION['error'] = 'Token không hợp lệ';
            $this->redirect(BASE_URL . '?act=forgot-password');
        }

        // Validate password
        if (empty($password) || strlen($password) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 ký tự';
            require_once './views/admin/reset-password.php';
            return;
        }

        if ($password !== $passwordConfirm) {
            $error = 'Mật khẩu xác nhận không khớp';
            require_once './views/admin/reset-password.php';
            return;
        }

        // Reset password
        $result = $this->adminModel->resetPasswordByToken($token, $password);
        
        if ($result) {
            $_SESSION['success'] = 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.';
            $this->redirect(BASE_URL . '?act=login');
        } else {
            $_SESSION['error'] = 'Không thể đặt lại mật khẩu. Token có thể đã hết hạn hoặc không hợp lệ.';
            $this->redirect(BASE_URL . '?act=forgot-password');
        }
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

        $services = $this->serviceModel->getAllServices(['trang_thai' => 1]);
        $serviceTypes = ServiceModel::getServiceTypes();
        
        // Lấy danh sách categories và tags
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        $categories = $tourChiTietModel->layTatCaLoaiTour();
        $tags = $tourChiTietModel->layTatCaTags();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file   = $_FILES['packageimage'] ?? null;
            $result = $this->tourModel->createTour($_POST, $file);

            if ($result) { 
                // Lưu dịch vụ được chọn
                if (!empty($_POST['dich_vu']) && is_array($_POST['dich_vu'])) {
                    $this->tourModel->saveTourServices($result, $_POST['dich_vu']);
                }
                
                // Lưu phân loại (categories) được chọn
                if (!empty($_POST['loai_ids']) && is_array($_POST['loai_ids'])) {
                    foreach ($_POST['loai_ids'] as $loaiId) {
                        $tourChiTietModel->ganLoaiTour($result, $loaiId);
                    }
                }
                
                // Lưu tags được chọn
                if (!empty($_POST['tag_ids']) && is_array($_POST['tag_ids'])) {
                    foreach ($_POST['tag_ids'] as $tagId) {
                        $tourChiTietModel->ganTag($result, $tagId);
                    }
                }
                
                $msg = "Thêm tour thành công!"; 
            } else { 
                $error = "Không thể thêm tour. Vui lòng kiểm tra lại dữ liệu."; 
            }

            $this->loadView('admin/tours/create', compact('services', 'serviceTypes', 'categories', 'tags', 'msg','error'), 'admin/layout');
        } else {
            $this->loadView('admin/tours/create', compact('services', 'serviceTypes', 'categories', 'tags'), 'admin/layout');
        }
    }

    /**
     * Validate tour data (dùng chung cho create và update)
     * @param array $data
     * @return Validator
     */
    private function validateTourData($data) {
        $validator = new Validator($data);
        
        // Mã tour
        $validator->required('mato', 'Mã tour là bắt buộc')
                  ->minLength('mato', 2, 'Mã tour phải có ít nhất 2 ký tự')
                  ->maxLength('mato', 50, 'Mã tour không được quá 50 ký tự');
        
        // Tên gói tour
        $validator->required('tengoi', 'Tên gói tour là bắt buộc')
                  ->minLength('tengoi', 5, 'Tên gói tour phải có ít nhất 5 ký tự')
                  ->maxLength('tengoi', 255, 'Tên gói tour không được quá 255 ký tự');
        
        // Nơi xuất phát
        $validator->required('noixuatphat', 'Nơi xuất phát là bắt buộc')
                  ->minLength('noixuatphat', 3, 'Nơi xuất phát phải có ít nhất 3 ký tự')
                  ->maxLength('noixuatphat', 255, 'Nơi xuất phát không được quá 255 ký tự');
        
        
        // Giá gói
        $validator->required('giagoi', 'Giá gói là bắt buộc')
                  ->numeric('giagoi', 'Giá gói phải là số')
                  ->min('giagoi', 1000, 'Giá gói phải lớn hơn hoặc bằng 1,000 VNĐ');
        
        // Giá trẻ em (nếu có)
        if (!empty($data['giatreem'])) {
            $validator->numeric('giatreem', 'Giá trẻ em phải là số')
                      ->min('giatreem', 0, 'Giá trẻ em phải lớn hơn hoặc bằng 0')
                      ->custom('giatreem', function($value) use ($data) {
                          return empty($data['giagoi']) || $value <= $data['giagoi'];
                      }, 'Giá trẻ em không được lớn hơn giá gói');
        }
        
        // Giá trẻ nhỏ (nếu có)
        if (!empty($data['giatrenho'])) {
            $validator->numeric('giatrenho', 'Giá trẻ nhỏ phải là số')
                      ->min('giatrenho', 0, 'Giá trẻ nhỏ phải lớn hơn hoặc bằng 0')
                      ->custom('giatrenho', function($value) use ($data) {
                          return empty($data['giagoi']) || $value <= $data['giagoi'];
                      }, 'Giá trẻ nhỏ không được lớn hơn giá gói');
        }
        
        // Số ngày (text format)
        $validator->required('songay', 'Số ngày là bắt buộc')
                  ->minLength('songay', 1, 'Số ngày không được để trống')
                  ->maxLength('songay', 50, 'Số ngày không được quá 50 ký tự');
        
        // Chi tiết gói
        if (!empty($data['chitietgoi'])) {
            $validator->maxLength('chitietgoi', 5000, 'Chi tiết gói không được quá 5000 ký tự');
        }

        // Chương trình - BỎ GIỚI HẠN
        // Không giới hạn ký tự cho chương trình

        // Lưu ý - BỎ GIỚI HẠN
        // Không giới hạn ký tự cho lưu ý

        // Quốc gia
        if (!empty($data['quocgia'])) {
            $validator->maxLength('quocgia', 100, 'Quốc gia không được quá 100 ký tự');
        }
        
        return $validator;
    }

    /* ==================== VOUCHER MANAGEMENT ==================== */

    /**
     * Danh sách voucher
     * Route: ?act=admin-vouchers
     */
    public function listVouchers() {
        $this->checkLogin();
        $filters = [
            'status' => $_GET['status'] ?? null,
            'q'      => $_GET['q'] ?? null
        ];
        $vouchers = $this->voucherModel->getAll($filters);
        $this->loadView('admin/vouchers/list', compact('vouchers', 'filters'), 'admin/layout');
    }

    /**
     * Form tạo voucher + xử lý lưu
     * Route: ?act=admin-voucher-create
     */
    public function createVoucher() {
        $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validateVoucherData($_POST);
            if ($validator->fails()) {
                $error = $validator->firstError();
                $errors = $validator->errors();
                $oldData = $_POST;
                return $this->loadView('admin/vouchers/create', compact('error', 'errors', 'oldData'), 'admin/layout');
            }

            $data = $validator->validated();
            $result = $this->voucherModel->create($data);
            if ($result) {
                $_SESSION['success'] = 'Tạo voucher thành công';
                $this->redirect(BASE_URL . '?act=admin-vouchers');
            } else {
                $error = 'Không thể tạo voucher. Vui lòng thử lại.';
                $oldData = $_POST;
                $this->loadView('admin/vouchers/create', compact('error', 'oldData'), 'admin/layout');
            }
            return;
        }

        $this->loadView('admin/vouchers/create', [], 'admin/layout');
    }

    /**
     * Form sửa voucher + xử lý lưu
     * Route: ?act=admin-voucher-edit&id=X
     */
    public function editVoucher() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect(BASE_URL . '?act=admin-vouchers');
        }

        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            $_SESSION['error'] = 'Không tìm thấy voucher';
            $this->redirect(BASE_URL . '?act=admin-vouchers');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validateVoucherData($_POST, $id);
            if ($validator->fails()) {
                $error = $validator->firstError();
                $errors = $validator->errors();
                $oldData = $_POST;
                return $this->loadView('admin/vouchers/edit', compact('error', 'errors', 'oldData', 'voucher'), 'admin/layout');
            }

            $data = $validator->validated();
            $ok = $this->voucherModel->update($id, $data);
            if ($ok) {
                $_SESSION['success'] = 'Cập nhật voucher thành công';
                $this->redirect(BASE_URL . '?act=admin-vouchers');
            } else {
                $error = 'Không thể cập nhật voucher';
                $oldData = $_POST;
                $this->loadView('admin/vouchers/edit', compact('error', 'oldData', 'voucher'), 'admin/layout');
            }
            return;
        }

        $this->loadView('admin/vouchers/edit', compact('voucher'), 'admin/layout');
    }

    /**
     * Xóa voucher
     * Route: ?act=admin-voucher-delete&id=X
     */
    public function deleteVoucher() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->voucherModel->delete($id);
            $_SESSION['success'] = 'Đã xóa voucher';
        }
        $this->redirect(BASE_URL . '?act=admin-vouchers');
    }

    /**
     * Thay đổi trạng thái voucher
     * Route: ?act=admin-voucher-toggle&id=X
     */
    public function toggleVoucher() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->voucherModel->toggleStatus($id);
            $_SESSION['success'] = 'Đã cập nhật trạng thái voucher';
        }
        $this->redirect(BASE_URL . '?act=admin-vouchers');
    }

    /**
     * Thay đổi trạng thái voucher qua AJAX
     * Route: ?act=admin-voucher-change-status&id=X&status=Y
     */
    public function changeVoucherStatus() {
        $this->checkLogin();
        header('Content-Type: application/json');
        
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if (!$id || $status === null) {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
            return;
        }
        
        $status = (int)$status;
        if ($status !== 0 && $status !== 1) {
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
            return;
        }
        
        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy voucher']);
            return;
        }
        
        $result = $this->voucherModel->update($id, ['is_active' => $status]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
        }
    }

    /**
     * Validate dữ liệu voucher
     */
    private function validateVoucherData($data, $id = null) {
        $validator = new Validator($data);
        $validator->required('code', 'Mã voucher bắt buộc')
                  ->maxLength('code', 50, 'Mã voucher tối đa 50 ký tự');
        $validator->required('discount_type', 'Loại giảm giá bắt buộc')
                  ->in('discount_type', ['percent', 'amount'], 'Loại giảm giá không hợp lệ');
        $validator->required('discount_value', 'Giá trị giảm bắt buộc')
                  ->numeric('discount_value', 'Giá trị giảm phải là số')
                  ->min('discount_value', 0, 'Giá trị giảm phải >= 0');
        $validator->numeric('min_order_amount', 'Đơn tối thiểu phải là số')
                  ->min('min_order_amount', 0, 'Đơn tối thiểu >= 0');
        if (!empty($data['usage_limit']) && $data['usage_limit'] !== '') {
            $validator->integer('usage_limit', 'Giới hạn lượt phải là số')
                      ->min('usage_limit', 1, 'Giới hạn lượt phải >= 1');
        }
        if (!empty($data['start_date'])) {
            $validator->date('start_date', 'Y-m-d', 'Ngày bắt đầu không hợp lệ');
        }
        if (!empty($data['end_date'])) {
            $validator->date('end_date', 'Y-m-d', 'Ngày kết thúc không hợp lệ');
        }
        $validator->in('is_active', ['0','1'], 'Trạng thái không hợp lệ');

        return $validator;
    }

    /**
     * Lưu tour vào DB (với validation đầy đủ)
     * Route: ?act=admin-tour-store
     */
    public function storeTour() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ===== VALIDATE INPUT =====
            $validator = $this->validateTourData($_POST);

            if ($validator->fails()) {
                $error = $validator->firstError();
                $errors = $validator->errors();
                $oldData = $_POST; // Giữ lại dữ liệu đã nhập
                return $this->loadView('admin/tours/create', compact('error', 'errors', 'oldData', 'services', 'serviceTypes', 'categories', 'tags'), 'admin/layout');
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
                    $errors = ['packageimage' => $fileValidation['error']];
                    $oldData = $_POST; // Giữ lại dữ liệu đã nhập
                    return $this->loadView('admin/tours/create', compact('error', 'errors', 'oldData'), 'admin/layout');
                }

                $hinhanh = uploadFile($_FILES["packageimage"], 'uploads/tours/');
                if ($hinhanh === null) {
                    $error = "Upload ảnh thất bại.";
                    $errors = ['packageimage' => "Upload ảnh thất bại."];
                    $oldData = $_POST; // Giữ lại dữ liệu đã nhập
                    return $this->loadView('admin/tours/create', compact('error', 'errors', 'oldData'), 'admin/layout');
                }
            } else {
                $error = "Ảnh tour là bắt buộc.";
                $errors = ['packageimage' => "Ảnh tour là bắt buộc."];
                $oldData = $_POST; // Giữ lại dữ liệu đã nhập
                return $this->loadView('admin/tours/create', compact('error', 'errors', 'oldData'), 'admin/layout');
            }

            // ===== PREPARE DATA =====
            $validated = $validator->validated();
            $validated['hinhanh'] = $hinhanh;
            $validated['quocgia'] = sanitizeInput($validated['quocgia'] ?? 'Việt Nam');
            $validated['khuyenmai'] = 0;
            $validated['khuyenmai_phantram'] = 0;
            $validated['khuyenmai_tungay'] = null;
            $validated['khuyenmai_denngay'] = null;
            $validated['khuyenmai_mota'] = null;
            $validated['nuocngoai'] = isset($validated['nuocngoai']) ? 1 : 0;
            
            // ===== SAVE TO DATABASE =====
            $validated['voucher_id'] = isset($_POST['voucher_id']) ? (int)$_POST['voucher_id'] : null;

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
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        
        $services = $this->serviceModel->getAllServices(['trang_thai' => 1]);
        $serviceTypes = ServiceModel::getServiceTypes();
        $tourServices = $this->tourModel->getTourServices($id);
        $selectedServiceIds = array_column($tourServices, 'id_dich_vu');
        
        // Lấy categories và tags
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        $categories = $tourChiTietModel->layTatCaLoaiTour();
        $tags = $tourChiTietModel->layTatCaTags();
        $selectedCategories = $tourChiTietModel->layLoaiTourCuaTour($id);
        $selectedTags = $tourChiTietModel->layTagsCuaTour($id);
        $selectedCategoryIds = array_column($selectedCategories, 'id');
        $selectedTagIds = array_column($selectedTags, 'id');
        
        $this->loadView('admin/tours/edit', compact('tour', 'services', 'serviceTypes', 'selectedServiceIds', 'categories', 'tags', 'selectedCategoryIds', 'selectedTagIds'), 'admin/layout');
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
            $validator = $this->validateTourData($_POST);

            if ($validator->fails()) {
                $_SESSION['error'] = $validator->firstError();
                $this->redirect(BASE_URL . '?act=admin-tour-edit&id=' . $id);
            }

            $validated = $validator->validated();
            
            // Đảm bảo các trường bắt buộc được lấy từ POST
            $validated['quocgia'] = sanitizeInput($_POST['quocgia'] ?? 'Việt Nam');
            $validated['khuyenmai'] = 0;
            $validated['khuyenmai_phantram'] = 0;
            $validated['khuyenmai_tungay'] = null;
            $validated['khuyenmai_denngay'] = null;
            $validated['khuyenmai_mota'] = null;
            $validated['nuocngoai'] = isset($_POST['nuocngoai']) ? (int)$_POST['nuocngoai'] : 0;
            
            // Lấy chuongtrinh từ hidden field (do được build bằng JavaScript)
            $validated['chuongtrinh'] = $_POST['chuongtrinh'] ?? '';
            // Lấy chitietgoi từ POST
            $validated['chitietgoi'] = $_POST['chitietgoi'] ?? '';
            // Lấy luuy từ POST
            $validated['luuy'] = $_POST['luuy'] ?? '';
            
            // Debug: Log dữ liệu trước khi update (có thể xóa sau)
            error_log("Update Tour ID: $id");
            error_log("Data: " . print_r($validated, true));
            
            $result = $this->tourModel->updateTour($id, $validated);
            
            if (!$result) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật tour. Vui lòng kiểm tra log để biết chi tiết.';
                error_log("Update tour failed for ID: $id");
                $this->redirect(BASE_URL . '?act=admin-tour-edit&id=' . $id);
                return;
            }
            
            // Lưu dịch vụ được chọn
            if (!empty($_POST['dich_vu']) && is_array($_POST['dich_vu'])) {
                $this->tourModel->saveTourServices($id, $_POST['dich_vu']);
            } else {
                // Nếu không chọn dịch vụ nào, xóa tất cả
                $this->tourModel->deleteTourServices($id);
            }
            
            // Cập nhật phân loại (categories)
            require_once './models/TourChiTietModel.php';
            $tourChiTietModel = new TourChiTietModel();
            
            // Xóa categories cũ
            $oldCategories = $tourChiTietModel->layLoaiTourCuaTour($id);
            foreach ($oldCategories as $cat) {
                $tourChiTietModel->xoaLoaiTour($id, $cat['id']);
            }
            // Thêm categories mới
            if (!empty($_POST['loai_ids']) && is_array($_POST['loai_ids'])) {
                foreach ($_POST['loai_ids'] as $loaiId) {
                    $tourChiTietModel->ganLoaiTour($id, $loaiId);
                }
            }
            
            // Cập nhật tags
            $oldTags = $tourChiTietModel->layTagsCuaTour($id);
            foreach ($oldTags as $tag) {
                $tourChiTietModel->xoaTag($id, $tag['id']);
            }
            // Thêm tags mới
            if (!empty($_POST['tag_ids']) && is_array($_POST['tag_ids'])) {
                foreach ($_POST['tag_ids'] as $tagId) {
                    $tourChiTietModel->ganTag($id, $tagId);
                }
            }
            
            $_SESSION['success'] = 'Cập nhật tour thành công!';
            $this->redirect(BASE_URL . '?act=admin-tour-detail&id=' . $id);
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
     * Xem chi tiết tour
     * Route: ?act=admin-tour-detail&id=X
     */
    public function viewTourDetail() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        
        $tour = $this->tourModel->getTourByID($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
        
        $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($id);
        $tourServices = $this->tourModel->getTourServices($id);
        $serviceTypes = ServiceModel::getServiceTypes();
        
        // Lấy categories và tags của tour
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        $tourCategories = $tourChiTietModel->layLoaiTourCuaTour($id);
        $tourTags = $tourChiTietModel->layTagsCuaTour($id);
        
        // Lấy checklist cho tour (từ departure plan đầu tiên nếu có)
        $checklist = null;
        $checklistItems = [];
        $completionPercentage = 0;
        if (!empty($departurePlans)) {
            $firstPlan = $departurePlans[0];
            $checklist = $this->pretripChecklistModel->getChecklistByDeparturePlanID($firstPlan['id']);
            if ($checklist) {
                $checklistItems = $this->pretripChecklistModel->getChecklistItems($checklist['id']);
                $completionPercentage = $this->pretripChecklistModel->getCompletionPercentage($checklist['id']);
            }
        }
        
        $this->loadView('admin/tours/detail', compact('tour', 'departurePlans', 'tourServices', 'serviceTypes', 'tourCategories', 'tourTags', 'checklist', 'checklistItems', 'completionPercentage'), 'admin/layout');
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
        $filters = [];
        
        // Lấy filter tên tour
        if (!empty($_GET['ten_tour'])) {
            $filters['ten_tour'] = trim($_GET['ten_tour']);
        }
        
        if ($tourId && $tourId > 0) {
            // Lấy lịch khởi hành theo tour ID
            $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($tourId);
            // Lấy thông tin tour để hiển thị
            $tour = $this->tourModel->getTourByID($tourId);
        } else {
            // Lấy tất cả lịch khởi hành với filter
            $departurePlans = $this->departurePlanModel->getAllDeparturePlans($filters);
        }
        
        // Lấy checklist cho mỗi departure plan
        $checklists = [];
        foreach ($departurePlans as $plan) {
            $checklist = $this->pretripChecklistModel->getChecklistByDeparturePlanID($plan['id']);
            if ($checklist) {
                $checklists[$plan['id']] = $checklist;
            }
        }
        
        $this->loadView('admin/departure-plans/list', compact('departurePlans', 'tour', 'tourId', 'checklists', 'filters'), 'admin/layout');
    }

    /**
     * Validate departure plan data (dùng chung cho create và update)
     * @param array $data
     * @return Validator
     */
    private function validateDeparturePlanData($data) {
        $validator = new Validator($data);
        
        // Tour ID
        $validator->required('id_tour', 'Tour là bắt buộc')
                  ->integer('id_tour', 'Tour ID phải là số nguyên')
                  ->min('id_tour', 1, 'Tour ID không hợp lệ');
        
        // Ngày khởi hành
        $validator->required('ngay_khoi_hanh', 'Ngày khởi hành là bắt buộc')
                  ->date('ngay_khoi_hanh', 'Y-m-d', 'Ngày khởi hành không hợp lệ (định dạng: YYYY-MM-DD)');
        
        // Giờ khởi hành
        $validator->required('gio_khoi_hanh', 'Giờ khởi hành là bắt buộc')
                  ->pattern('gio_khoi_hanh', '/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', 'Giờ khởi hành không hợp lệ (định dạng: HH:mm)');
        
        // Giờ tập trung
        $validator->required('gio_tap_trung', 'Giờ tập trung là bắt buộc')
                  ->pattern('gio_tap_trung', '/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', 'Giờ tập trung không hợp lệ (định dạng: HH:mm)');
        
        // Điểm tập trung
        $validator->required('diem_tap_trung', 'Điểm tập trung là bắt buộc')
                  ->minLength('diem_tap_trung', 5, 'Điểm tập trung phải có ít nhất 5 ký tự')
                  ->maxLength('diem_tap_trung', 255, 'Điểm tập trung không được quá 255 ký tự');
        
        // Số chỗ còn trống
        $validator->required('so_cho_con_trong', 'Số chỗ còn trống là bắt buộc')
                  ->integer('so_cho_con_trong', 'Số chỗ còn trống phải là số nguyên')
                  ->min('so_cho_con_trong', 0, 'Số chỗ còn trống phải lớn hơn hoặc bằng 0')
                  ->max('so_cho_con_trong', 1000, 'Số chỗ còn trống không được quá 1000');
        
        // Phương tiện
        $validator->required('phuong_tien', 'Phương tiện là bắt buộc')
                  ->minLength('phuong_tien', 2, 'Phương tiện phải có ít nhất 2 ký tự')
                  ->maxLength('phuong_tien', 255, 'Phương tiện không được quá 255 ký tự');
        
        // Ưu đãi giảm giá (nếu có)
        if (isset($data['uu_dai_giam_gia']) && $data['uu_dai_giam_gia'] !== '') {
            $validator->numeric('uu_dai_giam_gia', 'Ưu đãi giảm giá phải là số')
                      ->min('uu_dai_giam_gia', 0, 'Ưu đãi giảm giá không được nhỏ hơn 0')
                      ->max('uu_dai_giam_gia', 100, 'Ưu đãi giảm giá không được lớn hơn 100%');
        }
        
        // Ghi chú vận hành (nếu có)
        if (!empty($data['ghi_chu_van_hanh'])) {
            $validator->maxLength('ghi_chu_van_hanh', 2000, 'Ghi chú vận hành không được quá 2000 ký tự');
        }
        
        return $validator;
    }

    /**
     * Form tạo lịch khởi hành
     * Route: ?act=admin-departure-plan-create
     */
    public function createDeparturePlan() {
        $this->checkLogin();
        
        $tourId = $_GET['id_tour'] ?? null;
        
        // Lấy tất cả tours
        $allTours = $this->tourModel->getAllTours();
        
        // Lấy danh sách tour ID đã có lịch khởi hành
        $departurePlans = $this->departurePlanModel->getAllDeparturePlans();
        $toursWithPlans = [];
        foreach ($departurePlans as $dp) {
            if (!empty($dp['id_tour'])) {
                $toursWithPlans[$dp['id_tour']] = true;
            }
        }
        
        // Lọc bỏ các tour đã có lịch khởi hành khỏi danh sách
        $tours = [];
        foreach ($allTours as $tour) {
            if (!isset($toursWithPlans[$tour['id_goi']])) {
                $tours[] = $tour;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ===== VALIDATE INPUT =====
            $validator = $this->validateDeparturePlanData($_POST);
            
            if ($validator->fails()) {
                $error = $validator->firstError();
                $this->loadView('admin/departure-plans/create', compact('tours', 'error', 'tourId'), 'admin/layout');
                return;
            }
            
            // ===== PREPARE DATA =====
            $validated = $validator->validated();
            $validated['trang_thai'] = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;
            
            // ===== SAVE TO DATABASE =====
            $result = $this->departurePlanModel->createDeparturePlan($validated);
            $redirectTourId = $validated['id_tour'] ?? $tourId;

            if ($result) {
                $_SESSION['success'] = 'Tạo lịch khởi hành thành công!';
                // Redirect về trang list với tour_id nếu có
                $redirectUrl = BASE_URL . '?act=admin-departure-plans';
                if ($redirectTourId) {
                    $redirectUrl .= '&tour_id=' . $redirectTourId;
                }
                $this->redirect($redirectUrl);
            } else {
                // Kiểm tra lỗi database cụ thể
                $dbError = $this->departurePlanModel->getLastError();
                if ($dbError) {
                    $error = 'Không thể tạo lịch khởi hành: ' . $dbError;
                } else {
                    $error = 'Không thể tạo lịch khởi hành. Vui lòng kiểm tra lại dữ liệu và đảm bảo đã chạy migration để thêm cột phuong_tien vào bảng lich_khoi_hanh.';
                }
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
            // Validate ID
            $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ';
                $this->redirect(BASE_URL . '?act=admin-departure-plans');
            }
            
            // Check departure plan exists
            $existingPlan = $this->departurePlanModel->getDeparturePlanByID($id);
            if (!$existingPlan) {
                $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
                $this->redirect(BASE_URL . '?act=admin-departure-plans');
            }

            // ===== VALIDATE INPUT =====
            $validator = $this->validateDeparturePlanData($_POST);
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->firstError();
                $redirectTourId = $_POST['id_tour'] ?? null;
                $redirectUrl = BASE_URL . '?act=admin-departure-plan-edit&id=' . $id;
                if ($redirectTourId) {
                    $redirectUrl .= '&tour_id=' . $redirectTourId;
                }
                $this->redirect($redirectUrl);
                return;
            }
            
            // ===== PREPARE DATA =====
            $validated = $validator->validated();
            $validated['trang_thai'] = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;
            
            // ===== UPDATE DATABASE =====
            $result = $this->departurePlanModel->updateDeparturePlan($id, $validated);
            $redirectTourId = $validated['id_tour'] ?? null;

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

    /**
     * Xem chi tiết lịch khởi hành
     * Route: ?act=admin-departure-plan-detail&id=X
     */
    public function viewDeparturePlanDetail() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            $this->redirect(BASE_URL . '?act=admin-departure-plans');
        }
        
        $departurePlan = $this->departurePlanModel->getDeparturePlanByID($id);
        if (!$departurePlan) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            $this->redirect(BASE_URL . '?act=admin-departure-plans');
        }
        
        // Lấy thông tin tour nếu có
        $tour = null;
        if ($departurePlan['id_tour']) {
            $tour = $this->tourModel->getTourByID($departurePlan['id_tour']);
        }
        
        // Lấy checklist nếu có
        $checklist = null;
        $checklistItems = [];
        $completionPercentage = 0;
        if ($id) {
            $checklist = $this->pretripChecklistModel->getChecklistByDeparturePlanID($id);
            if ($checklist) {
                $checklistItems = $this->pretripChecklistModel->getChecklistItems($checklist['id']);
                $completionPercentage = $this->pretripChecklistModel->getCompletionPercentage($checklist['id']);
            }
        }
        
        // Lấy danh sách phân công HDV cho lịch khởi hành này
        require_once './models/AssignmentModel.php';
        $assignmentModel = new AssignmentModel();
        $assignments = $assignmentModel->getAssignmentsByDeparturePlanID($id);
        
        $this->loadView('admin/departure-plans/detail', compact('departurePlan', 'tour', 'checklist', 'checklistItems', 'completionPercentage', 'assignments'), 'admin/layout');
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

    /**
     * Quản lý checklist items (thêm/sửa/xóa)
     * Route: ?act=admin-pretrip-checklist-items&checklist_id=X
     */
    public function manageChecklistItems() {
        $this->checkLogin();
        
        $checklistId = $_GET['checklist_id'] ?? null;
        if (!$checklistId) {
            $_SESSION['error'] = 'ID checklist không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $checklist = $this->pretripChecklistModel->getChecklistByID($checklistId);
        if (!$checklist) {
            $_SESSION['error'] = 'Không tìm thấy checklist';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add_item') {
                $tenMuc = trim($_POST['ten_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');
                if ($tenMuc) {
                    $result = $this->pretripChecklistModel->createChecklistItem($checklistId, $tenMuc, $moTa ?: null);
                    if ($result) {
                        $_SESSION['success'] = 'Thêm mục checklist thành công!';
                        // Ghi log
                        $this->pretripChecklistModel->logHistory($checklistId, $result, 'create_item', $_SESSION['admin_id'] ?? null, 'admin', [
                            'ten_muc' => $tenMuc
                        ]);
                    } else {
                        $_SESSION['error'] = 'Không thể thêm mục checklist';
                    }
                }
            } elseif ($action === 'update_item') {
                $itemId = $_POST['item_id'] ?? null;
                $tenMuc = trim($_POST['ten_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');
                if ($itemId && $tenMuc) {
                    $result = $this->pretripChecklistModel->updateChecklistItem($itemId, $tenMuc, $moTa ?: null);
                    if ($result) {
                        $_SESSION['success'] = 'Cập nhật mục checklist thành công!';
                        // Ghi log
                        $this->pretripChecklistModel->logHistory($checklistId, $itemId, 'update_item', $_SESSION['admin_id'] ?? null, 'admin', [
                            'ten_muc' => $tenMuc
                        ]);
                    } else {
                        $_SESSION['error'] = 'Không thể cập nhật mục checklist';
                    }
                }
            } elseif ($action === 'delete_item') {
                $itemId = $_POST['item_id'] ?? null;
                if ($itemId) {
                    $item = $this->pretripChecklistModel->getChecklistItemByID($itemId);
                    $result = $this->pretripChecklistModel->deleteChecklistItem($itemId);
                    if ($result) {
                        $_SESSION['success'] = 'Xóa mục checklist thành công!';
                        // Ghi log
                        $this->pretripChecklistModel->logHistory($checklistId, $itemId, 'delete_item', $_SESSION['admin_id'] ?? null, 'admin', [
                            'ten_muc' => $item['ten_muc'] ?? ''
                        ]);
                    } else {
                        $_SESSION['error'] = 'Không thể xóa mục checklist';
                    }
                }
            }
            
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklist-items&checklist_id=' . $checklistId);
        }

        // Lấy items và history
        $items = $this->pretripChecklistModel->getChecklistItems($checklistId);
        $history = $this->pretripChecklistModel->getChecklistHistory($checklistId, 20);
        $completionPercentage = $this->pretripChecklistModel->getCompletionPercentage($checklistId);
        
        // Lấy thông tin departure plan
        $departurePlan = null;
        if ($checklist['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($checklist['id_lich_khoi_hanh']);
        }

        $this->loadView('admin/pretrip-checklists/items', compact('checklist', 'items', 'history', 'completionPercentage', 'departurePlan'), 'admin/layout');
    }

    /**
     * Duyệt trạng thái Ready
     * Route: ?act=admin-pretrip-checklist-approve-ready&checklist_id=X
     */
    public function approveReadyStatus() {
        $this->checkLogin();
        
        $checklistId = $_GET['checklist_id'] ?? null;
        if (!$checklistId) {
            $_SESSION['error'] = 'ID checklist không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $adminId = $_SESSION['admin_id'] ?? null;
        if (!$adminId) {
            $_SESSION['error'] = 'Không xác định được admin';
            $this->redirect(BASE_URL . '?act=admin-pretrip-checklists');
        }

        $result = $this->pretripChecklistModel->approveReadyStatus($checklistId, $adminId);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        $checklist = $this->pretripChecklistModel->getChecklistByID($checklistId);
        $redirectUrl = BASE_URL . '?act=admin-pretrip-checklist-items&checklist_id=' . $checklistId;
        if ($checklist && $checklist['id_lich_khoi_hanh']) {
            $departurePlan = $this->departurePlanModel->getDeparturePlanByID($checklist['id_lich_khoi_hanh']);
            if ($departurePlan && $departurePlan['id_tour']) {
                $redirectUrl = BASE_URL . '?act=admin-tour-detail&id=' . $departurePlan['id_tour'];
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
        if (!empty($_GET['ten_tour'])) {
            $filters['ten_tour'] = trim($_GET['ten_tour']);
        }
        if (!empty($_GET['ten_hdv'])) {
            $filters['ten_hdv'] = trim($_GET['ten_hdv']);
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
        if (!empty($_GET['ten_dich_vu'])) {
            $filters['ten_dich_vu'] = trim($_GET['ten_dich_vu']);
        }
        if (!empty($_GET['nha_cung_cap'])) {
            $filters['nha_cung_cap'] = trim($_GET['nha_cung_cap']);
        }
        if (!empty($_GET['loai_dich_vu'])) {
            $filters['loai_dich_vu'] = $_GET['loai_dich_vu'];
        }
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
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


    /* ==================== BOOKING MANAGEMENT ==================== */

    /**
     * UC-View-Booking: Danh sách bookings
     * Route: ?act=admin-bookings
     */
    public function listBookings() {
        $this->checkLogin();
        
        $filters = [];
        if (!empty($_GET['id_tour'])) {
            $filters['id_tour'] = (int)$_GET['id_tour'];
        }
        if (isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '') {
            $filters['trang_thai'] = (int)$_GET['trang_thai'];
        }
        if (!empty($_GET['ho_ten'])) {
            $filters['ho_ten'] = trim($_GET['ho_ten']);
        }

        $bookings = $this->bookingModel->getAllBookings($filters);
        $tours = $this->tourModel->getAllTours();
        $statusList = BookingModel::getStatusList();

        $this->loadView('admin/bookings/list', compact('bookings', 'filters', 'tours', 'statusList'), 'admin/layout');
    }

    /**
     * UC-View-Booking: Chi tiết booking
     * Route: ?act=admin-booking-detail&id=X
     */
    public function viewBookingDetail() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID booking không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-bookings');
        }

        $booking = $this->bookingModel->getBookingById($id);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            $this->redirect(BASE_URL . '?act=admin-bookings');
        }

        $statusList = BookingModel::getStatusList();
        $this->loadView('admin/bookings/detail', compact('booking', 'statusList'), 'admin/layout');
    }

    /**
     * UC-Create-Booking: Form tạo booking
     * Route: ?act=admin-booking-create
     */
    public function createBooking() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            if (empty($_POST['id_lich_khoi_hanh']) || empty($_POST['ho_ten']) || empty($_POST['so_dien_thoai'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                $tours = $this->tourModel->getAllTours();
                $selectedTourId = $_POST['id_tour'] ?? null;
                $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                return;
            }

            // Validate số điện thoại
            if (!BookingModel::validatePhone($_POST['so_dien_thoai'])) {
                $_SESSION['error'] = 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại 10 số bắt đầu bằng 0';
                $tours = $this->tourModel->getAllTours();
                $selectedTourId = $_POST['id_tour'] ?? null;
                $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                return;
            }

            // Validate email
            if (!empty($_POST['email']) && !BookingModel::validateEmail($_POST['email'])) {
                $_SESSION['error'] = 'Email không hợp lệ';
                $tours = $this->tourModel->getAllTours();
                $selectedTourId = $_POST['id_tour'] ?? null;
                $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                return;
            }

            // Validate loại booking
            if (empty($_POST['loai_booking']) || !in_array($_POST['loai_booking'], [1, 2, 3, 4])) {
                $_SESSION['error'] = 'Vui lòng chọn loại booking';
                $tours = $this->tourModel->getAllTours();
                $selectedTourId = $_POST['id_tour'] ?? null;
                $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                return;
            }

            // Validate danh sách khách cho nhóm/đoàn
            $loaiBooking = (int)$_POST['loai_booking'];
            if (in_array($loaiBooking, [3, 4])) {
                $soNguoiLon = (int)($_POST['so_nguoi_lon'] ?? 0);
                $soTreEm = (int)($_POST['so_tre_em'] ?? 0);
                $soTreNho = (int)($_POST['so_tre_nho'] ?? 0);
                $soEmBe = (int)($_POST['so_em_be'] ?? 0);
                $tongSoKhach = $soNguoiLon + $soTreEm + $soTreNho + $soEmBe;
                
                $danhSachKhach = $_POST['danh_sach_khach'] ?? [];
                if (empty($danhSachKhach) || count($danhSachKhach) != $tongSoKhach) {
                    $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin cho tất cả khách trong danh sách. Số lượng khách trong danh sách phải khớp với tổng số khách.';
                    $tours = $this->tourModel->getAllTours();
                    $selectedTourId = $_POST['id_tour'] ?? null;
                    $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                    $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                    return;
                }
                
                // Validate họ tên bắt buộc cho từng khách
                foreach ($danhSachKhach as $index => $khach) {
                    if (empty($khach['ho_ten'])) {
                        $_SESSION['error'] = "Vui lòng nhập họ tên cho khách thứ " . ($index + 1);
                        $tours = $this->tourModel->getAllTours();
                        $selectedTourId = $_POST['id_tour'] ?? null;
                        $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                        $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                        return;
                    }
                }
            }

            // Tính tổng tiền & áp voucher nếu có
            $baseTotal = $this->bookingModel->calculateTotal(
                $_POST['id_lich_khoi_hanh'],
                $_POST['so_nguoi_lon'] ?? 0,
                $_POST['so_tre_em'] ?? 0,
                $_POST['so_tre_nho'] ?? 0
            );

            $voucherId = null;
            $voucherCode = null;
            $voucherDiscount = 0;

            $voucherInput = trim($_POST['voucher_code'] ?? '');
            if ($voucherInput !== '') {
                $voucher = $this->voucherModel->findActiveByCode($voucherInput);
                if (!$voucher) {
                    $_SESSION['error'] = 'Mã voucher không hợp lệ hoặc đã hết hạn';
                    $tours = $this->tourModel->getAllTours();
                    $selectedTourId = $_POST['id_tour'] ?? null;
                    $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                    $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                    return;
                }

                $now = date('Y-m-d');
                $startOk = empty($voucher['start_date']) || $now >= $voucher['start_date'];
                $endOk = empty($voucher['end_date']) || $now <= $voucher['end_date'];
                if (!$startOk || !$endOk) {
                    $_SESSION['error'] = 'Voucher chưa bắt đầu hoặc đã hết hạn';
                    $tours = $this->tourModel->getAllTours();
                    $selectedTourId = $_POST['id_tour'] ?? null;
                    $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                    $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                    return;
                }

                if (!empty($voucher['usage_limit']) && (int)$voucher['used_count'] >= (int)$voucher['usage_limit']) {
                    $_SESSION['error'] = 'Voucher đã hết lượt sử dụng';
                    $tours = $this->tourModel->getAllTours();
                    $selectedTourId = $_POST['id_tour'] ?? null;
                    $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                    $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                    return;
                }

                if (!empty($voucher['min_order_amount']) && $baseTotal < (float)$voucher['min_order_amount']) {
                    $_SESSION['error'] = 'Tổng tiền chưa đạt mức tối thiểu để áp dụng voucher';
                    $tours = $this->tourModel->getAllTours();
                    $selectedTourId = $_POST['id_tour'] ?? null;
                    $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                    $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
                    return;
                }

                if ($voucher['discount_type'] === 'percent') {
                    $voucherDiscount = $baseTotal * ((float)$voucher['discount_value'] / 100);
                } else {
                    $voucherDiscount = (float)$voucher['discount_value'];
                }
                if ($voucherDiscount < 0) $voucherDiscount = 0;
                if ($voucherDiscount > $baseTotal) $voucherDiscount = $baseTotal;

                $voucherId = $voucher['id'];
                $voucherCode = $voucher['code'];
            }

            $finalTotal = max(0, $baseTotal - $voucherDiscount);
            $_POST['tong_tien_override'] = $finalTotal;
            $_POST['voucher_id'] = $voucherId;
            $_POST['voucher_code'] = $voucherCode;
            $_POST['voucher_discount'] = $voucherDiscount;

            $result = $this->bookingModel->createBooking($_POST);
            
            if ($result['success']) {
                if ($voucherId) {
                    $this->voucherModel->increaseUsage($voucherId);
                }
                $_SESSION['success'] = 'Tạo booking thành công! Mã booking: ' . $result['ma_booking'];
                $this->redirect(BASE_URL . '?act=admin-booking-detail&id=' . $result['id']);
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Không thể tạo booking';
                $tours = $this->tourModel->getAllTours();
                $selectedTourId = $_POST['id_tour'] ?? null;
                $departurePlans = $selectedTourId ? $this->departurePlanModel->getDeparturePlansByTourID($selectedTourId) : [];
                $this->loadView('admin/bookings/create', compact('tours', 'departurePlans', 'selectedTourId'), 'admin/layout');
            }
        } else {
            $tours = $this->tourModel->getAllTours();
            $this->loadView('admin/bookings/create', compact('tours'), 'admin/layout');
        }
    }

    /**
     * UC-Update-Booking: Form cập nhật booking
     * Route: ?act=admin-booking-edit&id=X
     */
    public function updateBooking() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID booking không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-bookings');
        }

        $booking = $this->bookingModel->getBookingById($id);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            $this->redirect(BASE_URL . '?act=admin-bookings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate số điện thoại
            if (!BookingModel::validatePhone($_POST['so_dien_thoai'])) {
                $_SESSION['error'] = 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại 10 số bắt đầu bằng 0';
                $statusList = BookingModel::getStatusList();
                $this->loadView('admin/bookings/edit', compact('booking', 'statusList'), 'admin/layout');
                return;
            }

            // Validate email
            if (!empty($_POST['email']) && !BookingModel::validateEmail($_POST['email'])) {
                $_SESSION['error'] = 'Email không hợp lệ';
                $statusList = BookingModel::getStatusList();
                $this->loadView('admin/bookings/edit', compact('booking', 'statusList'), 'admin/layout');
                return;
            }

            // Xử lý ngày thanh toán: chuyển từ datetime-local sang DATETIME
            if (!empty($_POST['ngay_thanh_toan'])) {
                $_POST['ngay_thanh_toan'] = date('Y-m-d H:i:s', strtotime($_POST['ngay_thanh_toan']));
            } else {
                $_POST['ngay_thanh_toan'] = null;
            }

            // Xử lý voucher nếu có
            $voucherId = null;
            $voucherCode = null;
            $voucherDiscount = 0;
            
            // Nếu có voucher_id từ form (đã được validate qua AJAX)
            if (!empty($_POST['voucher_id'])) {
                $voucherId = (int)$_POST['voucher_id'];
                $voucherCode = trim($_POST['voucher_code'] ?? '');
                $voucherDiscount = (float)($_POST['voucher_discount'] ?? 0);
            } elseif (!empty($_POST['voucher_code'])) {
                // Nếu chỉ có mã voucher, kiểm tra lại
                $voucherInput = trim($_POST['voucher_code']);
                $voucher = $this->voucherModel->findActiveByCode($voucherInput);
                if ($voucher) {
                    $now = date('Y-m-d');
                    $startOk = empty($voucher['start_date']) || $now >= $voucher['start_date'];
                    $endOk = empty($voucher['end_date']) || $now <= $voucher['end_date'];
                    
                    if ($startOk && $endOk) {
                        // Tính tổng tiền gốc để áp dụng voucher
                        $baseTotal = $this->bookingModel->calculateTotal(
                            $booking['id_lich_khoi_hanh'],
                            $_POST['so_nguoi_lon'] ?? $booking['so_nguoi_lon'] ?? 0,
                            $_POST['so_tre_em'] ?? $booking['so_tre_em'] ?? 0,
                            $_POST['so_tre_nho'] ?? $booking['so_tre_nho'] ?? 0
                        );
                        
                        // Tính số tiền giảm
                        if ($voucher['discount_type'] === 'percent') {
                            $voucherDiscount = ($baseTotal * (float)$voucher['discount_value']) / 100;
                        } else {
                            $voucherDiscount = (float)$voucher['discount_value'];
                        }
                        
                        // Kiểm tra min_order_amount
                        if (empty($voucher['min_order_amount']) || $baseTotal >= (float)$voucher['min_order_amount']) {
                            $voucherId = $voucher['id'];
                            $voucherCode = $voucher['code'];
                        }
                    }
                }
            }
            
            // Nếu không có voucher_id nhưng có voucher_code, xóa voucher
            if (empty($voucherId) && empty($_POST['voucher_code'])) {
                $voucherId = null;
                $voucherCode = null;
                $voucherDiscount = 0;
            }
            
            $_POST['voucher_id'] = $voucherId;
            $_POST['voucher_code'] = $voucherCode;
            $_POST['voucher_discount'] = $voucherDiscount;

            $result = $this->bookingModel->updateBooking($id, $_POST);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'] ?? 'Cập nhật booking thành công!';
                $this->redirect(BASE_URL . '?act=admin-booking-detail&id=' . $id);
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Không thể cập nhật booking';
                $statusList = BookingModel::getStatusList();
                $this->loadView('admin/bookings/edit', compact('booking', 'statusList'), 'admin/layout');
            }
        } else {
            $statusList = BookingModel::getStatusList();
            $this->loadView('admin/bookings/edit', compact('booking', 'statusList'), 'admin/layout');
        }
    }

    /**
     * Kiểm tra voucher (AJAX)
     * Route: ?act=admin-check-voucher&code=X
     */
    public function checkVoucher() {
        $this->checkLogin();
        header('Content-Type: application/json');
        
        $code = trim($_GET['code'] ?? '');
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã voucher']);
            return;
        }
        
        $voucher = $this->voucherModel->findActiveByCode($code);
        if (!$voucher) {
            echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ hoặc đã hết hạn']);
            return;
        }
        
        $now = date('Y-m-d');
        $startOk = empty($voucher['start_date']) || $now >= $voucher['start_date'];
        $endOk = empty($voucher['end_date']) || $now <= $voucher['end_date'];
        
        if (!$startOk || !$endOk) {
            echo json_encode(['success' => false, 'message' => 'Voucher chưa bắt đầu hoặc đã hết hạn']);
            return;
        }
        
        if (!empty($voucher['usage_limit']) && (int)$voucher['used_count'] >= (int)$voucher['usage_limit']) {
            echo json_encode(['success' => false, 'message' => 'Voucher đã hết lượt sử dụng']);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'voucher' => [
                'id' => $voucher['id'],
                'code' => $voucher['code'],
                'discount_type' => $voucher['discount_type'],
                'discount_value' => $voucher['discount_value'],
                'min_order_amount' => $voucher['min_order_amount']
            ]
        ]);
    }

    /**
     * Lấy danh sách lịch khởi hành theo tour (AJAX)
     * Route: ?act=admin-get-departure-plans&tour_id=X
     */
    public function getDeparturePlansByTour() {
        $this->checkLogin();
        
        $tourId = $_GET['tour_id'] ?? null;
        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Tour ID không hợp lệ']);
            return;
        }

        $plans = $this->departurePlanModel->getDeparturePlansByTourID($tourId);
        echo json_encode(['success' => true, 'plans' => $plans]);
    }

    /**
     * Tính tổng tiền booking (AJAX)
     * Route: ?act=admin-calculate-booking-total
     */
    public function calculateBookingTotal() {
        $this->checkLogin();
        
        $id_lich_khoi_hanh = $_POST['id_lich_khoi_hanh'] ?? null;
        $so_nguoi_lon = (int)($_POST['so_nguoi_lon'] ?? 0);
        $so_tre_em = (int)($_POST['so_tre_em'] ?? 0);
        $so_tre_nho = (int)($_POST['so_tre_nho'] ?? 0);
        $so_em_be = (int)($_POST['so_em_be'] ?? 0);

        if (!$id_lich_khoi_hanh) {
            echo json_encode(['success' => false, 'message' => 'Lịch khởi hành không hợp lệ']);
            return;
        }

        $tong_tien = $this->bookingModel->calculateTotal($id_lich_khoi_hanh, $so_nguoi_lon, $so_tre_em, $so_tre_nho, $so_em_be);
        $so_nguoi = $so_nguoi_lon + $so_tre_em + $so_tre_nho + $so_em_be;
        $seatCheck = $this->bookingModel->checkAvailableSeats($id_lich_khoi_hanh, $so_nguoi);

        echo json_encode([
            'success' => true,
            'tong_tien' => $tong_tien,
            'tong_tien_formatted' => number_format($tong_tien, 0, ',', '.') . ' đ',
            'seat_check' => $seatCheck
        ]);
    }

    /**
     * Xóa booking
     * Route: ?act=admin-booking-delete&id=X
     */
    public function deleteBooking() {
        $this->checkLogin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID booking không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-bookings');
        }

        $result = $this->bookingModel->deleteBooking($id);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'] ?? 'Xóa booking thành công!';
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Không thể xóa booking';
        }
        
        $this->redirect(BASE_URL . '?act=admin-bookings');
    }

    /**
     * Quick change status (AJAX)
     * Route: ?act=admin-booking-quick-change-status
     */
    public function quickChangeStatus() {
        $this->checkLogin();
        
        $id = $_POST['id'] ?? null;
        $trang_thai = $_POST['trang_thai'] ?? null;
        $tien_dat_coc = isset($_POST['tien_dat_coc']) ? (float)$_POST['tien_dat_coc'] : null;

        if (!$id || $trang_thai === null) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $result = $this->bookingModel->quickChangeStatus($id, (int)$trang_thai, $tien_dat_coc);
        echo json_encode($result);
    }

    /* ==================== CATEGORIES & TAGS MANAGEMENT ==================== */

    /**
     * Danh sách phân loại & tags
     * Route: ?act=admin-categories-tags
     */
    public function listCategoriesTags() {
        $this->checkLogin();
        
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        $categories = $tourChiTietModel->layTatCaLoaiTour();
        $tags = $tourChiTietModel->layTatCaTags();
        
        $this->loadView('admin/categories-tags/list', compact('categories', 'tags'), 'admin/layout');
    }

    /**
     * Tạo category hoặc tag mới
     * Route: ?act=admin-categories-tags-create
     */
    public function createCategoryOrTag() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=admin-categories-tags');
        }
        
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        
        $type = $_POST['type'] ?? '';
        
        if ($type === 'category') {
            $tenLoai = trim($_POST['ten_loai'] ?? '');
            $mota = trim($_POST['mota'] ?? '');
            
            if (empty($tenLoai)) {
                $_SESSION['error'] = 'Vui lòng nhập tên loại tour';
                $this->redirect(BASE_URL . '?act=admin-categories-tags');
            }
            
            $result = $tourChiTietModel->taoLoaiTour($tenLoai, $mota ?: null);
            
            if ($result) {
                $_SESSION['success'] = 'Đã thêm loại tour mới thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm loại tour. Có thể tên loại tour đã tồn tại.';
            }
        } elseif ($type === 'tag') {
            $tenTag = trim($_POST['ten_tag'] ?? '');
            
            if (empty($tenTag)) {
                $_SESSION['error'] = 'Vui lòng nhập tên tag';
                $this->redirect(BASE_URL . '?act=admin-categories-tags');
            }
            
            $result = $tourChiTietModel->taoTag($tenTag);
            
            if ($result) {
                $_SESSION['success'] = 'Đã thêm tag mới thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm tag. Có thể tên tag đã tồn tại.';
            }
        } else {
            $_SESSION['error'] = 'Loại không hợp lệ';
        }
        
        $this->redirect(BASE_URL . '?act=admin-categories-tags');
    }

    /**
     * Xóa category hoặc tag
     * Route: ?act=admin-categories-tags-delete
     */
    public function deleteCategoryOrTag() {
        $this->checkLogin();
        
        $type = $_GET['type'] ?? '';
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            $this->redirect(BASE_URL . '?act=admin-categories-tags');
        }
        
        require_once './models/TourChiTietModel.php';
        $tourChiTietModel = new TourChiTietModel();
        
        if ($type === 'category') {
            $result = $tourChiTietModel->xoaLoaiTourKhoiDB($id);
            
            if ($result) {
                $_SESSION['success'] = 'Đã xóa loại tour thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa loại tour.';
            }
        } elseif ($type === 'tag') {
            $result = $tourChiTietModel->xoaTagKhoiDB($id);
            
            if ($result) {
                $_SESSION['success'] = 'Đã xóa tag thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa tag.';
            }
        } else {
            $_SESSION['error'] = 'Loại không hợp lệ';
        }
        
        $this->redirect(BASE_URL . '?act=admin-categories-tags');
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
    