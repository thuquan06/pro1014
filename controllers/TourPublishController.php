<?php
class TourPublishController {
    private $model;
    
    public function __construct() {
        require_once './models/TourPublishModel.php';
        $this->model = new TourPublishModel();
    }
    
    /**
     * Trang kiểm tra & publish tour
     */
    public function trangPublish() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        // Lấy thông tin tour
        $tour = $this->model->layThongTinPublish($idGoi);
        
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        // Kiểm tra checklist
        $checklist = $this->model->kiemTraChecklist($idGoi);
        $tyLeHoanThanh = $this->model->tinhTyLeHoanThanh($checklist);
        $coThePublish = $this->model->coThePublish($checklist);
        
        // Lấy lịch sử
        $lichsu = $this->model->layLichSuPublish($idGoi);
        
        require_once './views/admin/tours/publish/index.php';
    }
    
    /**
     * Thay đổi trạng thái publish
     */
    public function doiTrangThaiPublish() {
        $idGoi = $_GET['id_goi'] ?? 0;
        $trangThai = $_GET['status'] ?? '';
        
        if (!$idGoi || !in_array($trangThai, ['draft', 'internal', 'public'])) {
            $_SESSION['error'] = 'Thông tin không hợp lệ!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        // Kiểm tra checklist nếu publish public
        if ($trangThai === 'public') {
            $checklist = $this->model->kiemTraChecklist($idGoi);
            $coThePublish = $this->model->coThePublish($checklist);
            
            if (!$coThePublish) {
                $_SESSION['error'] = 'Tour chưa đủ điều kiện để publish! Vui lòng hoàn thiện thông tin.';
                header("Location: ?act=tour-publish&id_goi=$idGoi");
                exit;
            }
        }
        
        // Cập nhật
        if ($this->model->capNhatTrangThaiPublish($idGoi, $trangThai, $_SESSION['user_id'] ?? null)) {
            $messages = [
                'draft' => 'Đã chuyển về Draft!',
                'internal' => 'Đã publish nội bộ!',
                'public' => 'Đã publish công khai!'
            ];
            $_SESSION['success'] = $messages[$trangThai];
        } else {
            $_SESSION['error'] = 'Có lỗi khi cập nhật!';
        }
        
        header("Location: ?act=tour-publish&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Dashboard publish
     */
    public function dashboard() {
        $thongke = $this->model->thongKeTourTheoTrangThai();
        $tourCanReview = $this->model->danhSachTourCanReview();
        
        require_once './views/admin/tours/publish/dashboard.php';
    }

        /**
    * Danh sách tour theo trạng thái
    */
    public function danhSachTheoTrangThai() {
    $status = $_GET['status'] ?? 'draft';
    
    // Validate
    if (!in_array($status, ['draft', 'internal', 'public'])) {
        $status = 'draft';
    }
    
    // Tên trạng thái
    $statusNames = [
        'draft' => 'Draft',
        'internal' => 'Nội bộ',
        'public' => 'Công khai'
    ];
    $statusName = $statusNames[$status];
    
    // Lấy danh sách qua Model
    $tours = $this->model->layDanhSachTheoTrangThai($status);
    
    require_once './views/admin/tours/publish/list-by-status.php';
}
    
}
?>
