<?php
/**
 * File: controllers/TourVersionController.php
 * Quản lý phiên bản tour
 */

class TourVersionController {
    private $model;
    
    public function __construct() {
        require_once './models/TourVersionModel.php';
        $this->model = new TourVersionModel();
    }
    
    /**
     * Danh sách versions của tour
     */
    public function danhSachVersions() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            // Thay vì redirect, hiển thị trang chọn tour
            require_once './models/TourChiTietModel.php';
            $tourModel = new TourChiTietModel();
            $allTours = $tourModel->layTatCaTour();
            
            require_once './views/admin/tours/versions/select-tour.php';
            exit;
        }
        
        // Lấy danh sách versions
        $versions = $this->model->layDanhSachVersions($idGoi);
        
        // Lấy tất cả tour cho dropdown
        require_once './models/TourChiTietModel.php';
        $tourModel = new TourChiTietModel();
        $allTours = $tourModel->layTatCaTour();
        
        require_once './views/admin/tours/versions/index.php';
    }
    
    /**
     * Form thêm version mới
     */
    public function themVersion() {
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$idGoi) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            $tenPhienBan = trim($_POST['ten_phienban'] ?? '');
            $loaiPhienBan = $_POST['loai_phienban'] ?? '';
            $ngayBatDau = $_POST['ngay_batdau'] ?? '';
            $ngayKetThuc = $_POST['ngay_ketthuc'] ?? '';
            
            // Validate
            if (empty($tenPhienBan)) {
                $errors[] = 'Tên phiên bản không được để trống!';
            }
            
            if (empty($loaiPhienBan)) {
                $errors[] = 'Vui lòng chọn loại phiên bản!';
            }
            
            if (empty($ngayBatDau)) {
                $errors[] = 'Ngày bắt đầu không được để trống!';
            }
            
            if (empty($ngayKetThuc)) {
                $errors[] = 'Ngày kết thúc không được để trống!';
            }
            
            if (!empty($ngayBatDau) && !empty($ngayKetThuc) && strtotime($ngayBatDau) > strtotime($ngayKetThuc)) {
                $errors[] = 'Ngày bắt đầu phải trước ngày kết thúc!';
            }
            
            if (empty($errors)) {
                $data = [
                    'id_goi'        => $idGoi,
                    'ten_phienban'  => $tenPhienBan,
                    'loai_phienban' => $loaiPhienBan,
                    'mo_ta'         => trim($_POST['mo_ta'] ?? ''),
                    'ngay_batdau'   => $ngayBatDau,
                    'ngay_ketthuc'  => $ngayKetThuc,
                    'gia_nguoilon'  => !empty($_POST['gia_nguoilon']) ? floatval($_POST['gia_nguoilon']) : null,
                    'gia_treem'     => !empty($_POST['gia_treem']) ? floatval($_POST['gia_treem']) : null,
                    'gia_embe'      => !empty($_POST['gia_embe']) ? floatval($_POST['gia_embe']) : null,
                    'is_active'     => isset($_POST['is_active']) ? 1 : 0,
                    'is_default'    => isset($_POST['is_default']) ? 1 : 0,
                    'priority'      => intval($_POST['priority'] ?? 0),
                    'created_by'    => $_SESSION['user_id'] ?? null
                ];
                
                $newId = $this->model->themVersion($data);
                
                if ($newId) {
                    $_SESSION['success'] = 'Thêm phiên bản thành công!';
                    header("Location: ?act=tour-versions&id_goi=$idGoi");
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi khi thêm phiên bản!';
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        require_once './views/admin/tours/versions/create.php';
    }
    
    /**
     * Form sửa version
     */
    public function suaVersion() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$id || !$idGoi) {
            $_SESSION['error'] = 'Thiếu thông tin!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        $version = $this->model->layMotVersion($id);
        
        if (!$version) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản!';
            header("Location: ?act=tour-versions&id_goi=$idGoi");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            $tenPhienBan = trim($_POST['ten_phienban'] ?? '');
            $loaiPhienBan = $_POST['loai_phienban'] ?? '';
            $ngayBatDau = $_POST['ngay_batdau'] ?? '';
            $ngayKetThuc = $_POST['ngay_ketthuc'] ?? '';
            
            // Validate
            if (empty($tenPhienBan)) {
                $errors[] = 'Tên phiên bản không được để trống!';
            }
            
            if (empty($loaiPhienBan)) {
                $errors[] = 'Vui lòng chọn loại phiên bản!';
            }
            
            if (empty($ngayBatDau)) {
                $errors[] = 'Ngày bắt đầu không được để trống!';
            }
            
            if (empty($ngayKetThuc)) {
                $errors[] = 'Ngày kết thúc không được để trống!';
            }
            
            if (!empty($ngayBatDau) && !empty($ngayKetThuc) && strtotime($ngayBatDau) > strtotime($ngayKetThuc)) {
                $errors[] = 'Ngày bắt đầu phải trước ngày kết thúc!';
            }
            
            if (empty($errors)) {
                $data = [
                    'ten_phienban'  => $tenPhienBan,
                    'loai_phienban' => $loaiPhienBan,
                    'mo_ta'         => trim($_POST['mo_ta'] ?? ''),
                    'ngay_batdau'   => $ngayBatDau,
                    'ngay_ketthuc'  => $ngayKetThuc,
                    'gia_nguoilon'  => !empty($_POST['gia_nguoilon']) ? floatval($_POST['gia_nguoilon']) : null,
                    'gia_treem'     => !empty($_POST['gia_treem']) ? floatval($_POST['gia_treem']) : null,
                    'gia_embe'      => !empty($_POST['gia_embe']) ? floatval($_POST['gia_embe']) : null,
                    'is_active'     => isset($_POST['is_active']) ? 1 : 0,
                    'is_default'    => isset($_POST['is_default']) ? 1 : 0,
                    'priority'      => intval($_POST['priority'] ?? 0),
                    'updated_by'    => $_SESSION['user_id'] ?? null
                ];
                
                if ($this->model->suaVersion($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật phiên bản thành công!';
                    header("Location: ?act=tour-versions&id_goi=$idGoi");
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi khi cập nhật!';
                }
            } else {
                $_SESSION['errors'] = $errors;
                $version = array_merge($version, $_POST);
            }
        }
        
        require_once './views/admin/tours/versions/edit.php';
    }
    
    /**
     * Xóa version
     */
    public function xoaVersion() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->xoaVersion($id, $_SESSION['user_id'] ?? null)) {
            $_SESSION['success'] = 'Xóa phiên bản thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi khi xóa!';
        }
        
        header("Location: ?act=tour-versions&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Clone version
     */
    public function cloneVersion() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenMoi = trim($_POST['ten_moi'] ?? '');
            
            if (empty($tenMoi)) {
                $_SESSION['error'] = 'Vui lòng nhập tên phiên bản mới!';
            } else {
                $newId = $this->model->cloneVersion($id, $tenMoi, $_SESSION['user_id'] ?? null);
                
                if ($newId) {
                    $_SESSION['success'] = 'Clone phiên bản thành công!';
                    header("Location: ?act=tour-version-sua&id=$newId&id_goi=$idGoi");
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi khi clone!';
                }
            }
        }
        
        header("Location: ?act=tour-versions&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Đặt version làm mặc định
     */
    public function datMacDinh() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if ($this->model->datMacDinh($id, $_SESSION['user_id'] ?? null)) {
            $_SESSION['success'] = 'Đã đặt làm phiên bản mặc định!';
        } else {
            $_SESSION['error'] = 'Có lỗi khi đặt mặc định!';
        }
        
        header("Location: ?act=tour-versions&id_goi=$idGoi");
        exit;
    }
    
    /**
     * Xem lịch sử thay đổi
     */
    public function lichSuVersion() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        if (!$id || !$idGoi) {
            $_SESSION['error'] = 'Thiếu thông tin!';
            header('Location: ?act=admin-tours');
            exit;
        }
        
        $version = $this->model->layMotVersion($id);
        
        if (!$version) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản!';
            header("Location: ?act=tour-versions&id_goi=$idGoi");
            exit;
        }
        
        $lichsu = $this->model->layLichSu($id);
        
        require_once './views/admin/tours/versions/history.php';
    }
    
    /**
     * So sánh 2 versions
     */
    public function soSanhVersions() {
        $idGoi = $_GET['id_goi'] ?? 0;
        $id1 = $_GET['id1'] ?? 0;
        $id2 = $_GET['id2'] ?? 0;
        
        if (!$idGoi || !$id1 || !$id2) {
            $_SESSION['error'] = 'Thiếu thông tin để so sánh!';
            header("Location: ?act=tour-versions&id_goi=$idGoi");
            exit;
        }
        
        $version1 = $this->model->layMotVersion($id1);
        $version2 = $this->model->layMotVersion($id2);
        
        if (!$version1 || !$version2) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản!';
            header("Location: ?act=tour-versions&id_goi=$idGoi");
            exit;
        }
        
        require_once './views/admin/tours/versions/compare.php';
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive() {
        $id = $_GET['id'] ?? 0;
        $idGoi = $_GET['id_goi'] ?? 0;
        
        $version = $this->model->layMotVersion($id);
        
        if ($version) {
            $newStatus = $version['is_active'] ? 0 : 1;
            
            $data = [
                'ten_phienban'  => $version['ten_phienban'],
                'loai_phienban' => $version['loai_phienban'],
                'mo_ta'         => $version['mo_ta'],
                'ngay_batdau'   => $version['ngay_batdau'],
                'ngay_ketthuc'  => $version['ngay_ketthuc'],
                'gia_nguoilon'  => $version['gia_nguoilon'],
                'gia_treem'     => $version['gia_treem'],
                'gia_embe'      => $version['gia_embe'],
                'is_active'     => $newStatus,
                'is_default'    => $version['is_default'],
                'priority'      => $version['priority'],
                'updated_by'    => $_SESSION['user_id'] ?? null
            ];
            
            if ($this->model->suaVersion($id, $data)) {
                $_SESSION['success'] = $newStatus ? 'Đã kích hoạt phiên bản!' : 'Đã tắt phiên bản!';
            }
        }
        
        header("Location: ?act=tour-versions&id_goi=$idGoi");
        exit;
    }
}
?>