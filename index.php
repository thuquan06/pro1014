<?php

/**
 * Index.php - Main Router
 * ĐÃ CẬP NHẬT: Session security, error handling, validation, CHI TIẾT TOUR
 */

// 1. CẤU HÌNH SESSION AN TOÀN
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start();

// 2. CẤU HÌNH ERROR REPORTING
$isProduction = false;

if ($isProduction) {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', PATH_ROOT . '/logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// 3. LOAD REQUIRED FILES
require_once './commons/env.php';
require_once './commons/function.php';
require_once './commons/Validation.php';
// 4. LẤY ACTION
$act = $_GET['act'] ?? 'home';

// Validate action
if (!preg_match('/^[a-z0-9-]+$/i', $act)) {
    http_response_code(400);
    die("Invalid action");
}

// 5. ERROR HANDLING WRAPPER
try {

    // Load models & controllers cho admin routes
    if (strpos($act, 'admin') === 0 || strpos($act, 'tour-') === 0 || strpos($act, 'province') === 0 ||  strpos($act, 'blog') === 0 || strpos($act, 'hoadon') === 0 || in_array($act, ['login', 'login-handle', 'logout'])) {
        require_once './models/BaseModel.php';
        require_once './models/DashboardModel.php';
        require_once './models/TourModel.php';
        require_once './models/AdminModel.php';
        require_once './models/ProvinceModel.php';

        // Controller
        require_once './controllers/BaseController.php';
        require_once './controllers/ProvinceController.php';

        require_once './controllers/AdminController.php';

        // CHI TIẾT TOUR (MỚI)
        require_once './models/TourChiTietModel.php';
        require_once './controllers/TourChiTietController.php';

        // HÓA ĐƠN (MỚI)
        if (strpos($act, 'hoadon') === 0) {
            require_once './models/HoadonModel.php';
            require_once './controllers/HoadonController.php';
        }
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

        // ===== AUTH ROUTES =====
        case 'login':
            (new AdminController())->login();
            break;

        case 'login-handle':
            (new AdminController())->handleLogin();
            break;

        case 'logout':
            (new AdminController())->logout();
            break;

        // ===== ADMIN ROUTES =====
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

        case 'admin-tour-detail':
            (new AdminController())->viewTourDetail();
            break;

        // ==================== CHI TIẾT TOUR (MỚI) ====================

        // LỊCH TRÌNH
        case 'tour-lichtrinh':
            (new TourChiTietController())->danhSachLichTrinh();
            break;

        case 'tour-lichtrinh-them':
            (new TourChiTietController())->themLichTrinh();
            break;

        case 'tour-lichtrinh-sua':
            (new TourChiTietController())->suaLichTrinh();
            break;

        case 'tour-lichtrinh-xoa':
            (new TourChiTietController())->xoaLichTrinh();
            break;

        // GALLERY
        case 'tour-gallery':
            (new TourChiTietController())->danhSachHinhAnh();
            break;

        case 'tour-gallery-them':
            (new TourChiTietController())->themHinhAnh();
            break;

        case 'tour-gallery-dai-dien':
            (new TourChiTietController())->datAnhDaiDien();
            break;

        case 'tour-gallery-xoa':
            (new TourChiTietController())->xoaHinhAnh();
            break;

        // CHÍNH SÁCH
        case 'tour-chinhsach':
            (new TourChiTietController())->danhSachChinhSach();
            break;

        case 'tour-chinhsach-them':
            (new TourChiTietController())->themChinhSach();
            break;

        case 'tour-chinhsach-sua':
            (new TourChiTietController())->suaChinhSach();
            break;

        case 'tour-chinhsach-xoa':
            (new TourChiTietController())->xoaChinhSach();
            break;

        // PHÂN LOẠI
        case 'tour-phanloai':
            (new TourChiTietController())->quanLyPhanLoai();
            break;

        case 'tour-phanloai-loai':
            (new TourChiTietController())->capNhatLoaiTour();
            break;

        case 'tour-phanloai-tags':
            (new TourChiTietController())->capNhatTags();
            break;

        // TOURS VERSION
        case 'tour-versions':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->danhSachVersions();
            break;

        case 'tour-version-them':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->themVersion();
            break;

        case 'tour-version-sua':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->suaVersion();
            break;

        case 'tour-version-xoa':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->xoaVersion();
            break;

        case 'tour-version-clone':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->cloneVersion();
            break;

        case 'tour-version-macdinh':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->datMacDinh();
            break;

        case 'tour-version-toggle':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->toggleActive();
            break;

        case 'tour-version-lichsu':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->lichSuVersion();
            break;

        case 'tour-version-sosanh':
            require_once './controllers/TourVersionController.php';
            (new TourVersionController())->soSanhVersions();
            break;

        case 'tour-publish':

            // TOUR PUBLISH
            require_once './controllers/TourPublishController.php';
            (new TourPublishController())->trangPublish();
            break;

        case 'tour-publish-change':
            require_once './controllers/TourPublishController.php';
            (new TourPublishController())->doiTrangThaiPublish();
            break;

        case 'tour-publish-dashboard':
            require_once './controllers/TourPublishController.php';
            (new TourPublishController())->dashboard();
            break;
        case 'tour-publish-list':
            require_once './controllers/TourPublishController.php';
            (new TourPublishController())->danhSachTheoTrangThai();
            break;

        // ========== BLOG ADMIN ROUTES ==========
        // ========== BLOG ADMIN ROUTES ==========

        case 'blog-list':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->list();
            break;

        case 'blog-edit':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->edit();
            break;

        case 'blog-update':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->update();
            break;

        case 'blog-delete':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->delete();
            break;
        case 'blog-create':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->create();
            break;

        case 'blog-store':
            require_once "./models/BlogModel.php";
            require_once "./controllers/BlogController.php";
            (new BlogController())->store();
            break;


        case 'province-list':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->index();
            break;
        case 'province-create':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->create();
            break;
        case 'province-store':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->store();
            break;
        case 'province-edit':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->edit();
            break;
        case 'province-update':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->update();
            break;
        case 'province-delete':
            require_once './controllers/ProvinceController.php';
            $controller = new ProvinceController();
            $controller->delete();
            break;

                // ========== HÓA ĐƠN ROUTES (MỚI) ==========
        case 'hoadon-list':
            (new HoadonController())->list();
            break;

        case 'hoadon-detail':
            (new HoadonController())->detail();
            break;

        case 'hoadon-create':
            (new HoadonController())->create();
            break;

        case 'hoadon-edit':
            (new HoadonController())->edit();
            break;

        case 'hoadon-update-status':
            (new HoadonController())->updateStatus();
            break;

        case 'hoadon-cancel':
            (new HoadonController())->cancel();
            break;

        case 'hoadon-delete':
            (new HoadonController())->delete();
            break;

        case 'hoadon-filter':
            (new HoadonController())->filterByStatus();
            break;

        case 'hoadon-search':
            (new HoadonController())->searchByEmail();
            break;


        // API
        case 'api-tour-chitiet':
            (new TourChiTietController())->apiChiTiet();
            break;

        // ===== 404 NOT FOUND =====
        default:
            http_response_code(404);

            if ($isProduction) {
                if (file_exists('./views/errors/404.php')) {
                    require './views/errors/404.php';
                } else {
                    echo "404 - Trang không tìm thấy";
                }
            } else {
                echo "<h1>404 - Page Not Found</h1>";
                echo "<p>Action requested: <strong>" . htmlspecialchars($act) . "</strong></p>";
                echo "<p>Available actions:</p>";
                echo "<ul>";
                echo "<li>home</li>";
                echo "<li>login</li>";
                echo "<li>admin</li>";
                echo "<li>admin-tours</li>";
                echo "<li>tour-lichtrinh&id_goi=71</li>";
                echo "<li>tour-gallery&id_goi=71</li>";
                echo "<li>tour-chinhsach&id_goi=71</li>";
                echo "<li>tour-phanloai&id_goi=71</li>";
                echo "<li>hoadon-list</li>";
                echo "<li>hoadon-create</li>";
                echo "</ul>";
            }
            break;
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());

    http_response_code(500);

    if ($isProduction) {
        echo "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
    } else {
        echo "<h1>Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}
