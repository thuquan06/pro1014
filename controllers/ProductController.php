<?php
/**
 * ProductController - Controller cho trang client/public
 */
class ProductController
{
    private $tourModel;
    private $departurePlanModel;
    private $tourChiTietModel;
    private $blogModel;

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
     * Load BlogModel (lazy load)
     */
    private function getBlogModel()
    {
        if (!$this->blogModel) {
            require_once './models/BlogModel.php';
            $this->blogModel = new BlogModel();
        }
        return $this->blogModel;
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
     * Trang giới thiệu
     */
    public function about()
    {
        // Lấy thống kê
        $allTours = $this->tourModel->getAllTours();
        $totalTours = count($allTours);
        $totalCustomers = 100000; // Default value
        $totalDestinations = count(array_unique(array_filter(array_column($allTours, 'ten_tinh'))));
        
        // Load view
        $pageTitle = 'Giới thiệu - StarVel Travel';
        $pageDescription = 'Tìm hiểu về StarVel Travel - Chuyên cung cấp các tour du lịch trong nước và quốc tế chất lượng cao';
        $content = $this->loadView('client/about', [
            'totalTours' => $totalTours,
            'totalCustomers' => $totalCustomers,
            'totalDestinations' => $totalDestinations
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false,
            'breadcrumb' => [
                ['title' => 'Giới thiệu']
            ]
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Trang liên hệ
     */
    public function contact()
    {
        $message = '';
        $messageType = '';
        
        // Xử lý form liên hệ nếu có POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $messageText = $_POST['message'] ?? '';
            
            // Validate
            if (empty($name) || empty($email) || empty($messageText)) {
                $message = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
                $messageType = 'error';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Email không hợp lệ.';
                $messageType = 'error';
            } else {
                // Ở đây có thể gửi email hoặc lưu vào database
                // Hiện tại chỉ hiển thị thông báo thành công
                $message = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.';
                $messageType = 'success';
            }
        }
        
        // Load view
        $pageTitle = 'Liên hệ - StarVel Travel';
        $pageDescription = 'Liên hệ với StarVel Travel - Chúng tôi luôn sẵn sàng hỗ trợ bạn';
        $content = $this->loadView('client/contact', [
            'message' => $message,
            'messageType' => $messageType
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false,
            'breadcrumb' => [
                ['title' => 'Liên hệ']
            ]
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Danh sách blog/tin tức
     */
    public function listBlogs()
    {
        $blogModel = $this->getBlogModel();
        
        // Pagination
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;
        
        // Lấy tất cả blogs
        $allBlogs = $blogModel->getAll();
        
        // Pagination
        $totalBlogs = count($allBlogs);
        $totalPages = ceil($totalBlogs / $perPage);
        $offset = ($page - 1) * $perPage;
        $blogs = array_slice($allBlogs, $offset, $perPage);
        
        // Lấy blog nổi bật (3 bài mới nhất)
        $featuredBlogs = array_slice($allBlogs, 0, 3);
        
        // Load view
        $pageTitle = 'Tin tức - StarVel Travel';
        $pageDescription = 'Cập nhật tin tức du lịch, kinh nghiệm và hướng dẫn từ StarVel Travel';
        $content = $this->loadView('client/blog', [
            'blogs' => $blogs,
            'featuredBlogs' => $featuredBlogs,
            'totalBlogs' => $totalBlogs,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false,
            'breadcrumb' => [
                ['title' => 'Tin tức']
            ]
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Chi tiết blog
     */
    public function detailBlog()
    {
        $blogId = $_GET['id'] ?? null;
        
        if (!$blogId) {
            header('Location: ' . BASE_URL . '?act=blog');
            exit;
        }
        
        $blogModel = $this->getBlogModel();
        $blog = $blogModel->getById($blogId);
        
        if (!$blog) {
            header('Location: ' . BASE_URL . '?act=blog');
            exit;
        }
        
        // Lấy blog liên quan (cùng chủ đề hoặc mới nhất)
        $allBlogs = $blogModel->getAll();
        $relatedBlogs = array_filter($allBlogs, function($b) use ($blogId) {
            return $b['id_blog'] != $blogId;
        });
        $relatedBlogs = array_slice($relatedBlogs, 0, 3);
        
        // Load view
        $pageTitle = htmlspecialchars($blog['chude'] ?? 'Tin tức') . ' - StarVel Travel';
        $pageDescription = htmlspecialchars($blog['tomtat'] ?? '');
        $content = $this->loadView('client/blog-detail', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false,
            'breadcrumb' => [
                ['title' => 'Tin tức', 'url' => BASE_URL . '?act=blog'],
                ['title' => htmlspecialchars($blog['chude'] ?? 'Chi tiết')]
            ]
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Trang đặt tour
     */
    public function booking()
    {
        $tourId = $_GET['tour_id'] ?? null;
        $departureId = $_GET['departure_id'] ?? null;
        
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
        
        // Lấy lịch khởi hành
        $departurePlans = $this->departurePlanModel->getDeparturePlansByTourID($tourId);
        
        // Nếu có departure_id, lấy thông tin lịch khởi hành cụ thể
        $selectedDeparture = null;
        if ($departureId) {
            $selectedDeparture = $this->departurePlanModel->getDeparturePlanByID($departureId);
        }
        
        // Load view
        $pageTitle = 'Đặt tour - ' . htmlspecialchars($tour['tengoi']) . ' - StarVel';
        $pageDescription = 'Đặt tour ' . htmlspecialchars($tour['tengoi']);
        $content = $this->loadView('client/booking', [
            'tour' => $tour,
            'departurePlans' => $departurePlans,
            'selectedDeparture' => $selectedDeparture
        ]);
        
        extract([
            'content' => $content,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'showBanner' => false,
            'breadcrumb' => [
                ['title' => 'Tour', 'url' => BASE_URL . '?act=tours'],
                ['title' => htmlspecialchars($tour['tengoi']), 'url' => BASE_URL . '?act=tour-detail&id=' . $tourId],
                ['title' => 'Đặt tour']
            ]
        ]);
        require_once './views/client/layout.php';
    }
    
    /**
     * Xử lý submit booking form
     */
    public function submitBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
        
        try {
            // Load HoadonModel
            require_once './models/HoadonModel.php';
            $hoadonModel = new HoadonModel();
            
            // Validate và lấy dữ liệu
            $tourId = $_POST['tour_id'] ?? null;
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $nguoilon = intval($_POST['nguoilon'] ?? 1);
            $treem = intval($_POST['treem'] ?? 0);
            $trenho = intval($_POST['trenho'] ?? 0);
            $embe = intval($_POST['embe'] ?? 0);
            $sophong = intval($_POST['sophong'] ?? 1);
            $phongdon = isset($_POST['phongdon']) ? 1 : 0;
            $ghichu = trim($_POST['ghichu'] ?? '');
            $departureId = !empty($_POST['departure_id']) ? $_POST['departure_id'] : null;
            
            // Validate
            if (empty($tourId)) {
                echo "<script>alert('Lỗi: Không tìm thấy thông tin tour!'); window.history.back();</script>";
                exit;
            }
            
            if (empty($email) || empty($phone) || empty($name)) {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin bắt buộc (Họ tên, Email, Số điện thoại)!'); window.history.back();</script>";
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Email không hợp lệ!'); window.history.back();</script>";
                exit;
            }
            
            // Validate số lượng người
            if ($nguoilon < 1) {
                echo "<script>alert('Số lượng người lớn phải ít nhất là 1!'); window.history.back();</script>";
                exit;
            }
            
            // Kiểm tra tour có tồn tại không
            $tour = $this->tourModel->getTourByID($tourId);
            if (!$tour) {
                echo "<script>alert('Tour không tồn tại!'); window.location.href = '" . BASE_URL . "?act=tours';</script>";
                exit;
            }
            
            // Tính ngày vào và ngày ra từ lịch khởi hành nếu có
            $ngayvao = null;
            $ngayra = null;
            if ($departureId) {
                $departure = $this->departurePlanModel->getDeparturePlanByID($departureId);
                if ($departure && !empty($departure['ngay_khoi_hanh'])) {
                    $ngayvao = $departure['ngay_khoi_hanh'];
                    // Tính ngày ra dựa trên số ngày tour
                    if ($tour && !empty($tour['songay'])) {
                        $days = intval($tour['songay']);
                        $ngayra = date('Y-m-d', strtotime($ngayvao . ' + ' . $days . ' days'));
                    }
                }
            }
            
            // Tạo hóa đơn
            $bookingData = [
                'id_goi' => $tourId,
                'id_ks' => null,
                'email_nguoidung' => $email,
                'nguoilon' => $nguoilon,
                'treem' => $treem,
                'trenho' => $trenho,
                'embe' => $embe,
                'phongdon' => $phongdon,
                'ngayvao' => $ngayvao,
                'ngayra' => $ngayra,
                'sophong' => $sophong,
                'ghichu' => $ghichu . "\nTên khách hàng: " . $name . "\nSố điện thoại: " . $phone,
                'trangthai' => 0 // Chờ xác nhận
            ];
            
            // Log booking data for debugging
            error_log("Attempting to create booking. Tour ID: $tourId, Email: $email, Data: " . print_r($bookingData, true));
            
            $hoadonId = $hoadonModel->createHoadon($bookingData);
            
            if ($hoadonId) {
                error_log("Booking created successfully. Hoadon ID: $hoadonId");
                // Redirect đến trang xác nhận
                header('Location: ' . BASE_URL . '?act=booking-confirm&id=' . $hoadonId);
                exit;
            } else {
                error_log("Booking failed - createHoadon returned false. Tour ID: $tourId, Email: $email");
                error_log("Booking data was: " . print_r($bookingData, true));
                
                // In development mode, show more details
                $isDevelopment = !defined('IS_PRODUCTION') || !IS_PRODUCTION;
                $errorMessage = 'Đặt tour thất bại! Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ.';
                
                if ($isDevelopment) {
                    $errorMessage .= '\n\nLỗi chi tiết đã được ghi vào log. Vui lòng kiểm tra PHP error log.';
                }
                
                echo "<script>alert('" . addslashes($errorMessage) . "'); window.history.back();</script>";
                exit;
            }
        } catch (Exception $e) {
            error_log("Booking error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            echo "<script>alert('Đã có lỗi xảy ra khi đặt tour. Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ.'); window.history.back();</script>";
            exit;
        }
    }
    
    /**
     * Trang xác nhận đặt tour
     */
    public function bookingConfirm()
    {
        $hoadonId = $_GET['id'] ?? null;
        
        if (!$hoadonId) {
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
        
        require_once './models/HoadonModel.php';
        $hoadonModel = new HoadonModel();
        
        $hoadon = $hoadonModel->getHoadonById($hoadonId);
        
        if (!$hoadon) {
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
        
        $tour = $this->tourModel->getTourByID($hoadon['id_goi']);
        $total = $hoadonModel->calculateTotal($hoadonId);
        
        // Load view
        $pageTitle = 'Xác nhận đặt tour - StarVel';
        $pageDescription = 'Xác nhận đặt tour thành công';
        $content = $this->loadView('client/booking-confirm', [
            'hoadon' => $hoadon,
            'tour' => $tour,
            'total' => $total
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
