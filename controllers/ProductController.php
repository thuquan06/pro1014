<?php
/**
 * ProductController - Controller cho trang client/public
 */
class ProductController
{
    private $tourModel;
    private $departurePlanModel;
    private $tourChiTietModel;

    public function __construct()
    {
        // Load env để có BASE_URL
        if (!defined('BASE_URL')) {
            require_once './commons/env.php';
        }
        require_once './models/BaseModel.php';
        require_once './models/TourModel.php';
        require_once './models/DeparturePlanModel.php';
        require_once './models/TourChiTietModel.php';
        $this->tourModel = new TourModel();
        $this->departurePlanModel = new DeparturePlanModel();
        $this->tourChiTietModel = new TourChiTietModel();
    }

    /**
     * Trang chủ
     */
    public function Home()
    {
        // Lấy tất cả tours
        $allTours = $this->tourModel->getAllTours();
        
        // Tour nổi bật (có khuyến mãi hoặc mới nhất)
        $featuredTours = array_filter($allTours, function($tour) {
            return (!empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1) || 
                   (!empty($tour['ngaydang']) && strtotime($tour['ngaydang']) > strtotime('-30 days'));
        });
        
        // Nếu không có tour khuyến mãi, lấy 6 tour mới nhất
        if (empty($featuredTours)) {
            $featuredTours = array_slice($allTours, 0, 6);
        } else {
            $featuredTours = array_slice($featuredTours, 0, 6);
        }
        
        // Tour trong nước
        $domesticTours = array_filter($allTours, function($tour) {
            return empty($tour['nuocngoai']) || $tour['nuocngoai'] == 0;
        });
        
        // Thống kê
        $totalTours = count($allTours);
        $totalCustomers = 100000; // Default value
        $totalDestinations = count(array_unique(array_filter(array_column($allTours, 'ten_tinh'))));
        
        // Load view với layout
        $pageTitle = 'Việt Nam Travel - StarVel';
        $pageDescription = 'Khám phá các tour du lịch trong nước và quốc tế hấp dẫn';
        $content = $this->loadView('client/home', [
            'featuredTours' => $featuredTours,
            'domesticTours' => array_slice($domesticTours, 0, 3),
            'totalTours' => $totalTours,
            'totalCustomers' => $totalCustomers,
            'totalDestinations' => $totalDestinations
        ]);
        
        // Load layout
        extract([
            'content' => $content, 
            'pageTitle' => $pageTitle, 
            'pageDescription' => $pageDescription,
            'showBanner' => true  // Show banner on homepage
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Danh sách tour
     */
    public function listTours()
    {
        // Lấy các tham số filter
        $type = $_GET['type'] ?? ''; // domestic, international
        $province = $_GET['province'] ?? '';
        $promo = $_GET['promo'] ?? '';
        $sort = $_GET['sort'] ?? 'newest'; // newest, price_low, price_high
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;
        
        // Lấy tất cả tours
        $allTours = $this->tourModel->getAllTours();
        
        // Filter tours
        $filteredTours = $allTours;
        
        // Filter theo loại
        if ($type === 'domestic') {
            $filteredTours = array_filter($filteredTours, function($tour) {
                return empty($tour['nuocngoai']) || $tour['nuocngoai'] == 0;
            });
        } elseif ($type === 'international') {
            $filteredTours = array_filter($filteredTours, function($tour) {
                return !empty($tour['nuocngoai']) && $tour['nuocngoai'] == 1;
            });
        }
        
        // Filter theo tỉnh/thành phố
        if (!empty($province)) {
            $filteredTours = array_filter($filteredTours, function($tour) use ($province) {
                return !empty($tour['ten_tinh']) && $tour['ten_tinh'] == $province;
            });
        }
        
        // Filter theo khuyến mãi
        if ($promo == '1') {
            $filteredTours = array_filter($filteredTours, function($tour) {
                return !empty($tour['khuyenmai']) && $tour['khuyenmai'] == 1;
            });
        }
        
        // Sort tours
        if ($sort === 'price_low') {
            usort($filteredTours, function($a, $b) {
                return ($a['giagoi'] ?? 0) - ($b['giagoi'] ?? 0);
            });
        } elseif ($sort === 'price_high') {
            usort($filteredTours, function($a, $b) {
                return ($b['giagoi'] ?? 0) - ($a['giagoi'] ?? 0);
            });
        } else { // newest
            usort($filteredTours, function($a, $b) {
                $dateA = strtotime($a['ngaydang'] ?? '1970-01-01');
                $dateB = strtotime($b['ngaydang'] ?? '1970-01-01');
                return $dateB - $dateA;
            });
        }
        
        // Reset array keys
        $filteredTours = array_values($filteredTours);
        
        // Pagination
        $totalTours = count($filteredTours);
        $totalPages = ceil($totalTours / $perPage);
        $offset = ($page - 1) * $perPage;
        $tours = array_slice($filteredTours, $offset, $perPage);
        
        // Lấy danh sách tỉnh/thành phố để filter
        $provinces = [];
        foreach ($allTours as $tour) {
            if (!empty($tour['ten_tinh'])) {
                $provinces[$tour['ten_tinh']] = $tour['ten_tinh'];
            }
        }
        sort($provinces);
        
        // Load view
        $pageTitle = 'Danh Sách Tour - StarVel';
        $pageDescription = 'Khám phá các tour du lịch trong nước và quốc tế';
        $content = $this->loadView('client/tours', [
            'tours' => $tours,
            'totalTours' => $totalTours,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $perPage,
            'provinces' => $provinces,
            'filters' => [
                'type' => $type,
                'province' => $province,
                'promo' => $promo,
                'sort' => $sort
            ]
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Chi tiết tour
     */
    public function detailTour()
    {
        $tourId = $_GET['id'] ?? null;
        
        if (!$tourId) {
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
        
        // Lấy thông tin tour
        $tour = $this->tourModel->getTourByID($tourId);
        
        if (!$tour) {
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
        
        // Lấy lịch trình theo ngày
        $itinerary = $this->tourChiTietModel->layLichTrinh($tourId);
        
        // Lấy gallery ảnh
        $gallery = $this->tourChiTietModel->layDanhSachAnh($tourId);
        
        // Lấy lịch khởi hành
        $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($tourId);
        
        // Lấy chính sách
        $policies = $this->tourChiTietModel->layChinhSach($tourId);
        
        // Load view
        $pageTitle = htmlspecialchars($tour['tengoi']) . ' - StarVel';
        $pageDescription = htmlspecialchars($tour['vitri'] ?? 'Tour du lịch') . ' - ' . htmlspecialchars($tour['tengoi']);
        $content = $this->loadView('client/tour-detail', [
            'tour' => $tour,
            'itinerary' => $itinerary,
            'gallery' => $gallery,
            'departurePlans' => $departurePlans,
            'policies' => $policies
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Load view (helper method)
     */
    private function loadView($view, $data = [])
    {
        extract($data);
        ob_start();
        require_once './views/' . $view . '.php';
        return ob_get_clean();
    }
}
