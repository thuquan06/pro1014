<?php
/**
 * HoadonController - Xử lý logic hóa đơn/booking
 * Created: 2025
 */
class HoadonController extends BaseController
{
    private $hoadonModel;
    private $tourModel;

    public function __construct()
    {
        $this->hoadonModel = new HoadonModel();
        $this->tourModel = new TourModel();
    }

    /**
     * Hiển thị danh sách hóa đơn
     */
    public function list()
    {
        $hoadons = $this->hoadonModel->getAllHoadon();
        $statistics = $this->hoadonModel->getStatistics();
        
        $this->loadView('admin/hoadon/list', [
            'hoadons' => $hoadons,
            'statistics' => $statistics
        ]);
    }

    /**
     * Hiển thị chi tiết hóa đơn
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.location='?act=hoadon-list';</script>";
            return;
        }

        $hoadon = $this->hoadonModel->getHoadonById($id);
        if (!$hoadon) {
            echo "<script>alert('Không tìm thấy hóa đơn!'); window.location='?act=hoadon-list';</script>";
            return;
        }

        $total = $this->hoadonModel->calculateTotal($id);

        $this->loadView('admin/hoadon/detail', [
            'hoadon' => $hoadon,
            'total' => $total
        ]);
    }

    /**
     * Xuất hóa đơn (Printable)
     */
    public function print()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.location='?act=hoadon-list';</script>";
            return;
        }

        $hoadon = $this->hoadonModel->getHoadonById($id);
        if (!$hoadon) {
            echo "<script>alert('Không tìm thấy hóa đơn!'); window.location='?act=hoadon-list';</script>";
            return;
        }

        $total = $this->hoadonModel->calculateTotal($id);

        // Cập nhật trạng thái hóa đơn thành "Đã xuất" (1) khi xuất hóa đơn
        if (($hoadon['trang_thai_hoa_don'] ?? 0) == 0) {
            $this->hoadonModel->updateInvoiceStatus($id, 1);
        }

        // Load view trực tiếp không qua layout
        $ROOT = rtrim(dirname(__DIR__), '/\\');
        $viewPath = $ROOT . '/views/admin/hoadon/print.php';
        
        if (is_file($viewPath)) {
            // Đảm bảo BASE_URL được định nghĩa
            if (!defined('BASE_URL')) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $script = dirname($_SERVER['SCRIPT_NAME'] ?? '');
                define('BASE_URL', rtrim($protocol . $host . $script, '/'));
            }
            
            extract(['hoadon' => $hoadon, 'total' => $total]);
            include $viewPath;
        } else {
            echo "❌ Không tìm thấy view: " . $viewPath;
        }
    }

    /**
     * Tạo hóa đơn mới
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->hoadonModel->createHoadon($_POST);

            if ($result) {
                echo "<script>alert('Tạo hóa đơn thành công!'); window.location='?act=hoadon-list';</script>";
            } else {
                echo "<script>alert('Tạo hóa đơn thất bại!');</script>";
            }
        } else {
            // Lấy danh sách tour để chọn
            $tours = $this->tourModel->getAllTours();
            $this->loadView('admin/hoadon/create', [
                'tours' => $tours
            ]);
        }
    }

    /**
     * Chỉnh sửa hóa đơn
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.location='?act=hoadon-list';</script>";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->hoadonModel->updateHoadon($id, $_POST);

            if ($result) {
                echo "<script>alert('Cập nhật hóa đơn thành công!'); window.location='?act=hoadon-detail&id={$id}';</script>";
            } else {
                echo "<script>alert('Cập nhật hóa đơn thất bại!');</script>";
            }
        } else {
            $hoadon = $this->hoadonModel->getHoadonById($id);
            $tours = $this->tourModel->getAllTours();
            
            if (!$hoadon) {
                echo "<script>alert('Không tìm thấy hóa đơn!'); window.location='?act=hoadon-list';</script>";
                return;
            }

            $this->loadView('admin/hoadon/edit', [
                'hoadon' => $hoadon,
                'tours' => $tours
            ]);
        }
    }

    /**
     * Cập nhật trạng thái
     */
    public function updateStatus()
    {
        $id = $_POST['id'] ?? null;
        $trangthai = $_POST['trangthai'] ?? null;

        if ($id && $trangthai !== null) {
            $result = $this->hoadonModel->updateStatus($id, $trangthai);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái thất bại!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
        }
    }

    /**
     * Cập nhật trạng thái hóa đơn mới (0=Chưa xuất, 1=Đã xuất, 2=Đã gửi, 3=Hủy)
     */
    public function updateInvoiceStatus()
    {
        $id = $_POST['id'] ?? null;
        $trang_thai_hoa_don = $_POST['trang_thai_hoa_don'] ?? null;

        if ($id && $trang_thai_hoa_don !== null) {
            $result = $this->hoadonModel->updateInvoiceStatus($id, $trang_thai_hoa_don);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái hóa đơn thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật trạng thái hóa đơn thất bại!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin!']);
        }
    }

    /**
     * Xác nhận hóa đơn (chuyển từ chờ xác nhận sang đã xác nhận)
     */
    public function confirm()
    {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if ($id) {
            $result = $this->hoadonModel->confirmHoadon($id);
            
            if ($result) {
                echo "<script>alert('Xác nhận hóa đơn thành công!'); window.location='?act=hoadon-detail&id={$id}';</script>";
            } else {
                echo "<script>alert('Xác nhận hóa đơn thất bại!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.history.back();</script>";
        }
    }

    /**
     * Hoàn thành hóa đơn (chuyển từ đã xác nhận sang hoàn thành)
     */
    public function complete()
    {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if ($id) {
            $result = $this->hoadonModel->completeHoadon($id);
            
            if ($result) {
                echo "<script>alert('Đánh dấu hoàn thành hóa đơn thành công!'); window.location='?act=hoadon-detail&id={$id}';</script>";
            } else {
                echo "<script>alert('Đánh dấu hoàn thành hóa đơn thất bại!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.history.back();</script>";
        }
    }

    /**
     * Hủy hóa đơn (yêu cầu lý do hủy)
     */
    public function cancel()
    {
        $id = $_POST['id'] ?? null;
        $lyDoHuy = trim($_POST['ly_do_huy'] ?? '');

        if ($id) {
            // Kiểm tra lý do hủy
            if (empty($lyDoHuy)) {
                echo "<script>alert('Vui lòng nhập lý do hủy!'); window.history.back();</script>";
                return;
            }

            $result = $this->hoadonModel->cancelHoadon($id, $lyDoHuy);
            
            if ($result) {
                echo "<script>alert('Hủy hóa đơn thành công!'); window.location='?act=hoadon-detail&id={$id}';</script>";
            } else {
                echo "<script>alert('Hủy hóa đơn thất bại!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.history.back();</script>";
        }
    }

    /**
     * Xóa hóa đơn
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $result = $this->hoadonModel->deleteHoadon($id);
            
            if ($result) {
                echo "<script>alert('Xóa hóa đơn thành công!'); window.location='?act=hoadon-list';</script>";
            } else {
                echo "<script>alert('Xóa hóa đơn thất bại!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ID hóa đơn không hợp lệ!'); window.history.back();</script>";
        }
    }

    /**
     * Lọc hóa đơn theo trạng thái
     */
    public function filterByStatus()
    {
        $trangthai = $_GET['trangthai'] ?? null;
        
        if ($trangthai !== null) {
            $hoadons = $this->hoadonModel->getHoadonByStatus($trangthai);
        } else {
            $hoadons = $this->hoadonModel->getAllHoadon();
        }
        
        $statistics = $this->hoadonModel->getStatistics();

        $this->loadView('admin/hoadon/list', [
            'hoadons' => $hoadons,
            'statistics' => $statistics,
            'filter_status' => $trangthai
        ]);
    }

    /**
     * Tìm kiếm hóa đơn theo email
     */
    public function searchByEmail()
    {
        $email = $_GET['email'] ?? '';
        
        if ($email) {
            $hoadons = $this->hoadonModel->getHoadonByEmail($email);
        } else {
            $hoadons = $this->hoadonModel->getAllHoadon();
        }
        
        $statistics = $this->hoadonModel->getStatistics();

        $this->loadView('admin/hoadon/list', [
            'hoadons' => $hoadons,
            'statistics' => $statistics,
            'search_email' => $email
        ]);
    }
}
