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
    if (strpos($act, 'admin') === 0 || strpos($act, 'tour-') === 0 || strpos($act, 'province') === 0 ||  strpos($act, 'blog') === 0 || strpos($act, 'hoadon') === 0 || in_array($act, ['login', 'logout', 'forgot-password', 'forgot-password-handle', 'reset-password', 'reset-password-handle'])) {
        require_once './models/BaseModel.php';
        require_once './models/DashboardModel.php';
        require_once './models/TourModel.php';
        require_once './models/AdminModel.php';
        require_once './models/ProvinceModel.php';
        require_once './models/DeparturePlanModel.php';
        require_once './models/PretripChecklistModel.php';
        require_once './models/GuideModel.php';
        require_once './models/AssignmentModel.php';
        require_once './models/ServiceModel.php';
        require_once './models/ServiceAssignmentModel.php';
        require_once './models/UserModel.php';

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
        case '':
            require_once './models/BaseModel.php';
            require_once './models/TourModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './controllers/ProductController.php';
            (new ProductController())->Home();
            break;

        // ===== AUTH ROUTES =====
        case 'login':
            (new AdminController())->login();
            break;

        case 'logout':
            (new AdminController())->logout();
            break;

        case 'forgot-password':
            (new AdminController())->forgotPassword();
            break;

        case 'forgot-password-handle':
            (new AdminController())->handleForgotPassword();
            break;

        case 'reset-password':
            (new AdminController())->resetPassword();
            break;

        case 'reset-password-handle':
            (new AdminController())->handleResetPassword();
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

        // --- Departure Plan Management ---
        case 'admin-departure-plans':
            (new AdminController())->listDeparturePlans();
            break;

        case 'admin-departure-plan-create':
            (new AdminController())->createDeparturePlan();
            break;

        case 'admin-departure-plan-edit':
            (new AdminController())->editDeparturePlan();
            break;

        case 'admin-departure-plan-update':
            (new AdminController())->updateDeparturePlan();
            break;

        case 'admin-departure-plan-delete':
            (new AdminController())->deleteDeparturePlan();
            break;

        case 'admin-departure-plan-toggle':
            (new AdminController())->toggleDeparturePlanStatus();
            break;

        // ==================== PRETRIP CHECKLIST ====================
        case 'admin-pretrip-checklists':
            require_once './models/PretripChecklistModel.php';
            (new AdminController())->listPretripChecklists();
            break;

        case 'admin-pretrip-checklist-create':
            require_once './models/PretripChecklistModel.php';
            (new AdminController())->createPretripChecklist();
            break;

        case 'admin-pretrip-checklist-edit':
            require_once './models/PretripChecklistModel.php';
            (new AdminController())->editPretripChecklist();
            break;

        case 'admin-pretrip-checklist-delete':
            require_once './models/PretripChecklistModel.php';
            (new AdminController())->deletePretripChecklist();
            break;

        // ==================== GUIDE MANAGEMENT ====================
        case 'admin-guides':
            require_once './models/GuideModel.php';
            (new AdminController())->listGuides();
            break;

        case 'admin-guide-create':
            require_once './models/GuideModel.php';
            (new AdminController())->createGuide();
            break;

        case 'admin-guide-edit':
            require_once './models/GuideModel.php';
            (new AdminController())->editGuide();
            break;

        case 'admin-guide-delete':
            require_once './models/GuideModel.php';
            (new AdminController())->deleteGuide();
            break;

        case 'admin-guide-toggle':
            require_once './models/GuideModel.php';
            (new AdminController())->toggleGuideStatus();
            break;

        // ==================== ASSIGNMENT MANAGEMENT ====================
        case 'admin-assignments':
            require_once './models/AssignmentModel.php';
            (new AdminController())->listAssignments();
            break;

        case 'admin-assignment-create':
            require_once './models/AssignmentModel.php';
            (new AdminController())->createAssignment();
            break;

        case 'admin-assignment-edit':
            require_once './models/AssignmentModel.php';
            (new AdminController())->editAssignment();
            break;

        case 'admin-assignment-delete':
            require_once './models/AssignmentModel.php';
            (new AdminController())->deleteAssignment();
            break;

        case 'admin-assignment-toggle':
            require_once './models/AssignmentModel.php';
            (new AdminController())->toggleAssignmentStatus();
            break;

        // ==================== SERVICE MANAGEMENT ====================
        case 'admin-services':
            require_once './models/ServiceModel.php';
            (new AdminController())->listServices();
            break;

        case 'admin-service-create':
            require_once './models/ServiceModel.php';
            (new AdminController())->createService();
            break;

        case 'admin-service-edit':
            require_once './models/ServiceModel.php';
            (new AdminController())->editService();
            break;

        case 'admin-service-delete':
            require_once './models/ServiceModel.php';
            (new AdminController())->deleteService();
            break;

        case 'admin-service-toggle':
            require_once './models/ServiceModel.php';
            (new AdminController())->toggleServiceStatus();
            break;

        // ==================== SERVICE ASSIGNMENT MANAGEMENT ====================
        case 'admin-service-assignments':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->listServiceAssignments();
            break;

        case 'admin-service-assignment-create':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->createServiceAssignment();
            break;

        case 'admin-service-assignment-edit':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->editServiceAssignment();
            break;

        case 'admin-service-assignment-confirm':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->confirmServiceAssignment();
            break;

        case 'admin-service-assignment-cancel':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->cancelServiceAssignment();
            break;

        case 'admin-service-assignment-delete':
            require_once './models/ServiceAssignmentModel.php';
            (new AdminController())->deleteServiceAssignment();
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

        // ==================== TOUR PUBLISH ROUTES ====================
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

        // ==================== USER MANAGEMENT ====================
        case 'admin-users':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->listUsers();
            break;

        case 'admin-user-create':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->createUser();
            break;

        case 'admin-user-store':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->storeUser();
            break;

        case 'admin-user-edit':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->editUser();
            break;

        case 'admin-user-update':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->updateUser();
            break;

        case 'admin-user-delete':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->deleteUser();
            break;

        case 'admin-user-toggle':
            require_once './models/BaseModel.php';
            require_once './models/UserModel.php';
            (new AdminController())->toggleUserStatus();
            break;

        // ==================== GUIDE ROUTES ====================
        case 'guide-debug':
            // Debug page - chỉ dùng trong development
            require_once './views/guide/debug.php';
            break;
            
        case 'guide':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->login();
            break;

        case 'guide-logout':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->logout();
            break;

        case 'guide-dashboard':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->dashboard();
            break;

        case 'guide-assignments':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->listAssignments();
            break;

        case 'guide-assignment-detail':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->assignmentDetail();
            break;

        case 'guide-profile':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->profile();
            break;

        case 'guide-schedule':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->schedule();
            break;

        case 'guide-journals':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->listJournals();
            break;

        case 'guide-journal-create':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->createJournal();
            break;

        case 'guide-journal-detail':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->journalDetail();
            break;

        case 'guide-journal-edit':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->editJournal();
            break;

        case 'guide-journal-delete':
            require_once './models/BaseModel.php';
            require_once './models/GuideModel.php';
            require_once './models/AssignmentModel.php';
            require_once './models/ServiceAssignmentModel.php';
            require_once './models/DeparturePlanModel.php';
            require_once './models/TourModel.php';
            require_once './models/TourJournalModel.php';
            require_once './controllers/BaseController.php';
            require_once './controllers/GuideController.php';
            (new GuideController())->deleteJournal();
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
