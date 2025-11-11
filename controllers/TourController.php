<?php
class TourController extends BaseController {

    protected $tourModel;
    protected $provinceModel;

    public function __construct() {
        $this->tourModel = new TourModel();
        $this->provinceModel = new ProvinceModel();
    }

    // (Read) Hiển thị danh sách tour - từ manage-packages.php
    public function listTours() {
        $tours = $this->tourModel->getAllTours();
        $data = [
            'tours' => $tours
        ];
        $this->loadView('admin/tours/list', $data, 'admin');
    }

    // (Create) Hiển thị form tạo tour - từ create-package.php
    public function showCreateForm() {
        $provinces = $this->provinceModel->getAllProvinces();
        $data = [
            'provinces' => $provinces
        ];
        $this->loadView('admin/tours/create', $data, 'admin');
    }

    // (Create) Xử lý lưu tour mới - từ create-package.php
    public function handleCreateTour() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            // Xử lý upload file
            $imagePath = '';
            if (!empty($_FILES["packageimage"]["name"])) {
                // Sử dụng hàm uploadFile từ /commons/function.php
                // Lưu ảnh vào 'public/admin/pacakgeimages/'
                $imagePath = uploadFile($_FILES["packageimage"], 'public/admin/pacakgeimages/');
                
                if ($imagePath === null) {
                    // Xử lý lỗi upload
                    $_SESSION['error'] = 'Upload ảnh thất bại.';
                    $this->redirect(BASE_URL . '?act=admin-tour-create');
                    return;
                }
                 // Chỉ lấy tên file, vì file gốc lưu vậy
                 $data['hinhanh'] = basename($imagePath);
            } else {
                 $data['hinhanh'] = ''; // Hoặc ảnh mặc định
            }

            // Gộp quốc gia nếu là tour trong nước
            if (isset($data['nuocngoai']) && $data['nuocngoai'] == 0) {
                $data['quocgia'] = 'Việt Nam';
            } else {
                $data['ten_tinh'] = ''; // Không có tỉnh nếu là tour quốc tế
            }

            // Gọi Model để lưu
            $result = $this->tourModel->createTour($data);

            if ($result) {
                $_SESSION['message'] = 'Tạo tour thành công!';
            } else {
                $_SESSION['error'] = 'Tạo tour thất bại!';
            }
            
            $this->redirect(BASE_URL . '?act=admin-tours');
        }
    }

    // (Update) Hiển thị form cập nhật - từ update-package.php
    public function showUpdateForm() {
        $pid = $_GET['id'] ?? null;
        if (!$pid) {
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $tour = $this->tourModel->getTourByID($pid);
        $provinces = $this->provinceModel->getAllProvinces();

        if (!$tour) {
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $data = [
            'tour' => $tour,
            'provinces' => $provinces
        ];
        $this->loadView('admin/tours/update', $data, 'admin');
    }

    // (Update) Xử lý cập nhật tour - từ update-package.php
    public function handleUpdateTour() {
        $pid = $_GET['id'] ?? null;
        if (!$pid || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '?act=admin-tours');
        }

        $data = $_POST;
        
        // Gộp quốc gia nếu là tour trong nước
        if (isset($data['nuocngoai']) && $data['nuocngoai'] == 0) {
            $data['quocgia'] = 'Việt Nam';
        }