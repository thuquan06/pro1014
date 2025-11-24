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
     * Hủy hóa đơn
     */
    public function cancel()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $result = $this->hoadonModel->cancelHoadon($id);
            
            if ($result) {
                echo "<script>alert('Hủy hóa đơn thành công!'); window.location='?act=hoadon-list';</script>";
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
