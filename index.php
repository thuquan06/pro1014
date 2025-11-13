<?php
/**
 * Index.php - Main Router
 * ĐÃ CẬP NHẬT: Session security, error handling, validation
 */

// 1. CẤU HÌNH SESSION AN TOÀN
ini_set('session.cookie_httponly', 1); // Không cho JavaScript đọc cookie
ini_set('session.cookie_secure', 0);   // Set = 1 nếu dùng HTTPS
ini_set('session.use_strict_mode', 1); // Chặn session fixation
ini_set('session.cookie_samesite', 'Lax'); // CSRF protection

// Khởi động session
session_start();

// 2. CẤU HÌNH ERROR REPORTING
// Trong development: hiện lỗi
// Trong production: log lỗi, không hiện
$isProduction = false; // Đổi thành true khi deploy

if ($isProduction) {
    // PRODUCTION: Log lỗi, không hiển thị
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', PATH_ROOT . '/logs/error.log');
} else {
    // DEVELOPMENT: Hiển thị lỗi
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// 3. LOAD REQUIRED FILES
require_once './commons/env.php';
require_once './commons/function.php';

// 4. LẤY ACTION (với validation)
$act = $_GET['act'] ?? 'home';

// Validate action để tránh injection
if (!preg_match('/^[a-z0-9-]+$/i', $act)) {
    http_response_code(400);
    die("Invalid action");
}

// 5. ERROR HANDLING WRAPPER
try {
    
    // Load models & controllers cho admin routes
    if (strpos($act, 'admin') === 0 || in_array($act, ['login', 'login-handle', 'logout'])) {
    // Nạp các model nền tảng
    require_once './models/BaseModel.php';
    require_once './models/DashboardModel.php';
    require_once './models/TourModel.php';
    require_once './models/AdminModel.php';
    require_once './models/ProvinceModel.php';

    // Nạp lớp controller nền tảng trước các controller khác
    require_once './controllers/BaseController.php';
    require_once './controllers/AdminController.php';
}




    // 6. ROUTING
    switch ($act) {
        // ===== PUBLIC ROUTES =====
        case 'home':
        require_once './models/BaseModel.php';
        require_once './models/ProductModel.php';
        require_once './controllers/ProductController.php';
        (new ProductController())->Home();
        break;


        // ===== AUTH ROUTES (không cần login) =====
        case 'login':
            (new AdminController())->login();
            break;
            
        case 'login-handle':
            (new AdminController())->handleLogin();
            break;
            
        case 'logout':
            (new AdminController())->logout();
            break;

        // ===== ADMIN ROUTES (cần login) =====
        case 'admin':
            (new AdminController())->dashboard();
            break;

        // --- Tour Management ---
        case 'admin-tours':
            (new AdminController())->listTours();
            break;
            
        case 'admin-tour-create':
            (new AdminController())->createTour();
            break;
            
        case 'admin-tour-store':
            (new AdminController())->storeTour();
            break;
            
        case 'admin-tour-edit':
            (new AdminController())->editTour();
            break;
            
        case 'admin-tour-update':
            (new AdminController())->updateTour();
            break;
            
        case 'admin-tour-delete':
            (new AdminController())->deleteTour();
            break;
            
        case 'admin-tour-update-image':
            (new AdminController())->updateTourImage();
            break;
            case 'admin-tour-toggle':
    (new AdminController())->toggleTourStatus();
    break;


        // ===== 404 NOT FOUND =====
        default:
            http_response_code(404);
            
            if ($isProduction) {
                // Production: Trang 404 đẹp
                if (file_exists('./views/errors/404.php')) {
                    require './views/errors/404.php';
                } else {
                    echo "404 - Trang không tìm thấy";
                }
            } else {
                // Development: Hiển thị thông tin debug
                echo "<h1>404 - Page Not Found</h1>";
                echo "<p>Action requested: <strong>" . htmlspecialchars($act) . "</strong></p>";
                echo "<p>Available actions:</p>";
                echo "<ul>";
                echo "<li>home</li>";
                echo "<li>login</li>";
                echo "<li>admin</li>";
                echo "<li>admin-tours</li>";
                echo "</ul>";
            }
            break;
    }

} catch (Exception $e) {
    // 7. GLOBAL ERROR HANDLER
    
    // Log lỗi
    error_log("Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Hiển thị cho user
    http_response_code(500);
    
    if ($isProduction) {
        // Production: Thông báo chung chung
        echo "Đã có lỗi xảy ra. Vui lòng thử lại sau hoặc liên hệ quản trị viên.";
    } else {
        // Development: Hiển thị chi tiết lỗi
        echo "<h1>Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}