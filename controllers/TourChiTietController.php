<?php
/**
 * TourChiTietController
 * Quản lý chi tiết tour: Lịch trình, Gallery, Chính sách, Tags
 */

class TourChiTietController
{
    private $model;
    
    public function __construct() {
        $this->model = new TourChiTietModel();
    }
    
    // ==================== LỊCH TRÌNH ====================
    
    /**
     * Trang quản lý lịch trình
     */
    public function danhSachLichTrinh() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=tour');
            exit;
        }
        
        $lichtrinh = $this->model->layLichTrinh($idGoi);
        
        require_once './views/admin/tours/lichtrinh/index.php';
    }
    
    /**
     * Form thêm lịch trình
     */
    public function themLichTrinh() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=tour');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_goi'   => $idGoi,
                'ngay_thu' => $_POST['ngay_thu'],
                'tieude'   => trim($_POST['tieude']),
                'mota'     => trim($_POST['mota']),
                'hoatdong' => trim($_POST['hoatdong'] ?? ''),
                'buaan'    => $_POST['buaan'] ?? '',
                'noinghi'  => trim($_POST['noinghi'] ?? '')
            ];
            
            // Validate
            $errors = [];
            if (empty($data['tieude'])) {
                $errors[] = 'Tiêu đề không được để trống';
            }
            if (empty($data['mota'])) {
                $errors[] = 'Mô tả không được để trống';
            }
            
            if (empty($errors)) {
                if ($this->model->themLichTrinh($data)) {
                    $_SESSION['success'] = 'Thêm lịch trình thành công!';
                    header("Location: ?act=tour-lichtrinh&id_goi=$idGoi");
                    exit;
                } else {
                    $errors[] = 'Có lỗi xảy ra, vui lòng thử lại!';
                }
            }
            
            $_SESSION['errors'] = $errors;
        }
        
        require_once './views/admin/tours/lichtrinh/create.php';
    }
    
    /**
     * Form sửa lịch trình
     */
    /**
 * Form sửa lịch trình - ĐÃ SỬA: LẤY DỮ LIỆU CŨ
 */
public function suaLichTrinh() {
    // BỎ check session ở đây
    
    // Lấy tham số
    $id = intval($_GET['id'] ?? 0);
    $idGoi = intval($_GET['id_goi'] ?? 0);
    
    // Kiểm tra tham số
    if (!$id || !$idGoi) {
        $_SESSION['error'] = 'Thiếu thông tin!';
        header('Location: ' . BASE_URL . '?act=tour-lichtrinh&id_goi=' . $idGoi);
        exit;
    }
    
    // Lấy dữ liệu
    $lichTrinh = $this->model->layMotNgay($id);
    
    if (!$lichTrinh) {
        $_SESSION['error'] = 'Không tìm thấy lịch trình!';
        header('Location: ' . BASE_URL . '?act=tour-lichtrinh&id_goi=' . $idGoi);
        exit;
    }
    
    // Kiểm tra thuộc tour
    if ($lichTrinh['id_goi'] != $idGoi) {
        $_SESSION['error'] = 'Lịch trình không thuộc tour này!';
        header('Location: ' . BASE_URL . '?act=tour-lichtrinh&id_goi=' . $idGoi);
        exit;
    }
    
    // Xử lý POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        
        $tieude = trim($_POST['tieude'] ?? '');
        $mota = trim($_POST['mota'] ?? '');
        $hoatdong = trim($_POST['hoatdong'] ?? '');
        $buaan = trim($_POST['buaan'] ?? '');
        $noinghi = trim($_POST['noinghi'] ?? '');
        
        if (empty($tieude)) {
            $errors[] = 'Tiêu đề không được để trống!';
        }
        
        if (empty($mota)) {
            $errors[] = 'Mô tả không được để trống!';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $lichTrinh['tieude'] = $tieude;
            $lichTrinh['mota'] = $mota;
            $lichTrinh['hoatdong'] = $hoatdong;
            $lichTrinh['buaan'] = $buaan;
            $lichTrinh['noinghi'] = $noinghi;
        } else {
            $data = [
                'tieude' => $tieude,
                'mota' => $mota,
                'hoatdong' => $hoatdong,
                'buaan' => $buaan,
                'noinghi' => $noinghi
            ];
            
            if ($this->model->suaLichTrinh($id, $data)) {
                $_SESSION['success'] = 'Cập nhật lịch trình thành công!';
                header('Location: ' . BASE_URL . '?act=tour-lichtrinh&id_goi=' . $idGoi);
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi khi cập nhật!';
            }
        }
    }
    
    // Render view
    require_once './views/admin/tours/lichtrinh/edit.php';
}
    
    /**
     * Xóa lịch trình
     */
    public function xoaLichTrinh() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->xoaLichTrinh($id)) {
            $_SESSION['success'] = 'Xóa thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header("Location: ?act=tour-lichtrinh&id_goi=$idGoi");
        exit;
    }
    
    // ==================== HÌNH ẢNH ====================
    
    /**
     * Trang quản lý gallery
     */
    public function danhSachHinhAnh() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=tour');
            exit;
        }
        
        $hinhanh = $this->model->layDanhSachAnh($idGoi);
        
        require_once './views/admin/tours/gallery/index.php';
    }
    
    /**
     * Upload ảnh mới
     */
    public function themHinhAnh() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
            $uploaded = 0;
            $errors = [];
            
            $targetDir = "uploads/tours/gallery/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . uniqid() . '_' . $_FILES['images']['name'][$key];
                    $targetFile = $targetDir . $fileName;
                    
                    // Validate file type
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($imageFileType, $allowed)) {
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFile)) {
                            $mota = $_POST['mota'][$key] ?? '';
                            $this->model->themAnh($idGoi, $targetFile, $mota, 0);
                            $uploaded++;
                        }
                    } else {
                        $errors[] = "File {$_FILES['images']['name'][$key]} không đúng định dạng";
                    }
                }
            }
            
            if ($uploaded > 0) {
                $_SESSION['success'] = "Đã upload $uploaded ảnh thành công!";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
            
            header("Location: ?act=tour-gallery&id_goi=$idGoi");
            exit;
        }
        
        require_once './views/admin/tours/gallery/upload.php';
    }
    
    /**
     * Đặt ảnh đại diện
     */
    public function datAnhDaiDien() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->datAnhDaiDien($idGoi, $id)) {
            $_SESSION['success'] = 'Đã đặt ảnh đại diện!';
        }
        
        header("Location: ?act=tour-gallery&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Xóa ảnh
     */
    public function xoaHinhAnh() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->xoaAnh($id)) {
            $_SESSION['success'] = 'Xóa ảnh thành công!';
        }
        
        header("Location: ?act=tour-gallery&id_goi=$idGoi");
        exit;
    }
    
    // ==================== CHÍNH SÁCH ====================
    
    /**
     * Trang quản lý chính sách
     */
    public function danhSachChinhSach() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=tour');
            exit;
        }
        
        $chinhsach = $this->model->layChinhSach($idGoi);
        
        require_once './views/admin/tours/chinhsach/index.php';
    }
    
    /**
     * Thêm chính sách
     */
    public function themChinhSach() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_goi'            => $idGoi,
                'loai_chinhsach'    => $_POST['loai_chinhsach'],
                'so_ngay_truoc'     => (int)$_POST['so_ngay_truoc'],
                'phantram_hoantien' => (float)$_POST['phantram_hoantien'],
                'noidung'           => trim($_POST['noidung'])
            ];
            
            if ($this->model->themChinhSach($data)) {
                $_SESSION['success'] = 'Thêm chính sách thành công!';
                header("Location: ?act=tour-chinhsach&id_goi=$idGoi");
                exit;
            }
        }
        
        require_once './views/admin/tours/chinhsach/create.php';
    }
    
    /**
     * Xóa chính sách
     */
    public function xoaChinhSach() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->xoaChinhSach($id)) {
            $_SESSION['success'] = 'Xóa thành công!';
        }
        
        header("Location: ?act=tour-chinhsach&id_goi=$idGoi");
        exit;
    }
    
    // ==================== LOẠI TOUR & TAGS ====================
    
    /**
     * Trang quản lý loại & tags
     */
    public function quanLyPhanLoai() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=tour');
            exit;
        }
        
        $tatCaLoai = $this->model->layTatCaLoaiTour();
        $loaiDaGan = $this->model->layLoaiTourCuaTour($idGoi);
        
        $tatCaTags = $this->model->layTatCaTags();
        $tagsDaGan = $this->model->layTagsCuaTour($idGoi);
        
        // Chuyển thành array ID để dễ so sánh
        $loaiIds = array_column($loaiDaGan, 'id');
        $tagIds = array_column($tagsDaGan, 'id');
        
        require_once './views/admin/tours/phanloai/index.php';
    }
    
    /**
     * Cập nhật loại tour
     */
    public function capNhatLoaiTour() {
        $idGoi = $_POST['id_goi'] ?? 0;
        $loaiIds = $_POST['loai_ids'] ?? [];
        
        // Xóa tất cả liên kết cũ
        foreach ($this->model->layLoaiTourCuaTour($idGoi) as $loai) {
            $this->model->xoaLoaiTour($idGoi, $loai['id']);
        }
        
        // Thêm liên kết mới
        foreach ($loaiIds as $loaiId) {
            $this->model->ganLoaiTour($idGoi, $loaiId);
        }
        
        $_SESSION['success'] = 'Cập nhật loại tour thành công!';
        header("Location: ?act=tour-phanloai&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Cập nhật tags
     */
    public function capNhatTags() {
        $idGoi = $_POST['id_goi'] ?? 0;
        $tagIds = $_POST['tag_ids'] ?? [];
        
        // Xóa tất cả tags cũ
        foreach ($this->model->layTagsCuaTour($idGoi) as $tag) {
            $this->model->xoaTag($idGoi, $tag['id']);
        }
        
        // Thêm tags mới
        foreach ($tagIds as $tagId) {
            $this->model->ganTag($idGoi, $tagId);
        }
        
        $_SESSION['success'] = 'Cập nhật tags thành công!';
        header("Location: ?act=tour-phanloai&id_goi=$idGoi");
        exit;
    }
    
    // ==================== API (JSON) ====================
    
    /**
     * API: Lấy chi tiết đầy đủ (JSON)
     */
    public function apiChiTiet() {
        header('Content-Type: application/json; charset=utf-8');
        
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu ID tour'
            ]);
            exit;
        }
        
        $chitiet = $this->model->layChiTietDayDu($idGoi);
        
        echo json_encode([
            'success' => true,
            'data' => $chitiet
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}