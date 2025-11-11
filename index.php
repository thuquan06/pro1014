<?php
session_start();
error_reporting(0);

require_once './commons/env.php';
require_once './commons/function.php';

$act = $_GET['act'] ?? 'home';

// Nạp cho admin & login
if (strpos($act, 'admin') === 0 || in_array($act, ['login','login-handle','logout'])) {
    require_once './models/DashboardModel.php';
    require_once './models/TourModel.php';
    require_once './models/AdminModel.php';
    require_once './controllers/AdminController.php';
}

switch ($act) {
    case 'home':
        require_once './models/ProductModel.php';
        require_once './controllers/ProductController.php';
        (new ProductController())->Home();
        break;

    // ==== LOGIN ====
    case 'login':
        (new AdminController())->login();
        break;
    case 'login-handle':
        (new AdminController())->handleLogin();
        break;
    case 'logout':
        (new AdminController())->logout();
        break;

    // ==== ADMIN ====
    case 'admin':
        (new AdminController())->dashboard();
        break;

    case 'admin-tours': (new AdminController())->listTours(); break;
    case 'admin-tour-create': (new AdminController())->createTour(); break;
    case 'admin-tour-store': (new AdminController())->storeTour(); break;
    case 'admin-tour-edit': (new AdminController())->editTour(); break;
    case 'admin-tour-update': (new AdminController())->updateTour(); break;
    case 'admin-tour-delete': (new AdminController())->deleteTour(); break;
    case 'admin-tour-update-image': (new AdminController())->updateTourImage(); break;

    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
