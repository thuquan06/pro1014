<?php
/**
 * ProductController - Controller cho trang client/public
 */
class ProductController
{
    private $tourModel;
    private $departurePlanModel;

    public function __construct()
    {
        // Load env để có BASE_URL
        if (!defined('BASE_URL')) {
            require_once './commons/env.php';
        }
        require_once './models/BaseModel.php';
        require_once './models/TourModel.php';
        require_once './models/DeparturePlanModel.php';
        $this->tourModel = new TourModel();
        $this->departurePlanModel = new DeparturePlanModel();
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
