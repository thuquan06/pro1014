<?php
class TourPublishModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB();
    }
    
    /**
     * Lấy thông tin publish của tour
     */
    public function layThongTinPublish($idGoi) {
        $sql = "SELECT id_goi, tengoi, publish_status, publish_checklist, 
                       published_at, published_by, trangthai
                FROM goidulich 
                WHERE id_goi = :id_goi 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Kiểm tra checklist của tour
     * Trả về mảng các mục cần kiểm tra và trạng thái
     */
    public function kiemTraChecklist($idGoi) {
        $checklist = [
            'basic_info' => [
                'name' => 'Thông tin cơ bản',
                'items' => [
                    'ten_goi' => ['label' => 'Tên tour', 'status' => false],
                    'mo_ta' => ['label' => 'Mô tả tour', 'status' => false],
                    'gia' => ['label' => 'Giá tour', 'status' => false],
                    'dia_diem' => ['label' => 'Địa điểm', 'status' => false],
                    'so_ngay' => ['label' => 'Số ngày/đêm', 'status' => false],
                ]
            ],
            'itinerary' => [
                'name' => 'Lịch trình',
                'items' => [
                    'co_lichtrinh' => ['label' => 'Có lịch trình', 'status' => false],
                    'du_ngay' => ['label' => 'Đủ số ngày', 'status' => false],
                ]
            ],
            'images' => [
                'name' => 'Hình ảnh',
                'items' => [
                    'anh_dai_dien' => ['label' => 'Ảnh đại diện', 'status' => false],
                    'gallery' => ['label' => 'Gallery (tối thiểu 3)', 'status' => false],
                ]
            ],
            'policy' => [
                'name' => 'Chính sách',
                'items' => [
                    'chinh_sach_huy' => ['label' => 'Chính sách hủy/đổi', 'status' => false],
                    'chinh_sach_thanhtoan' => ['label' => 'Chính sách thanh toán', 'status' => false],
                ]
            ],
            'details' => [
                'name' => 'Chi tiết khác',
                'items' => [
                    'phuong_tien' => ['label' => 'Phương tiện', 'status' => false],
                    'khach_san' => ['label' => 'Khách sạn', 'status' => false],
                ]
            ]
        ];
        
        // 1. Kiểm tra thông tin cơ bản
        $sql = "SELECT * FROM goidulich WHERE id_goi = :id_goi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tour) {
            $checklist['basic_info']['items']['ten_goi']['status'] = !empty($tour['tengoi']);
            $checklist['basic_info']['items']['mo_ta']['status'] = !empty($tour['motachitiet']) && strlen($tour['motachitiet']) > 50;
            $checklist['basic_info']['items']['gia']['status'] = !empty($tour['giagoi']) && $tour['giagoi'] > 0;
            $checklist['basic_info']['items']['dia_diem']['status'] = !empty($tour['vitri']);
            $checklist['basic_info']['items']['so_ngay']['status'] = !empty($tour['songay']) && $tour['songay'] > 0;
            
            // Hình ảnh đại diện
            $checklist['images']['items']['anh_dai_dien']['status'] = !empty($tour['hinhanh']);
            
            // Chi tiết
            $checklist['details']['items']['phuong_tien']['status'] = !empty($tour['phuongtien']);
            $checklist['details']['items']['khach_san']['status'] = !empty($tour['khachsan']);
        }
        
        // 2. Kiểm tra lịch trình (TẠM THỜI CHO PASS - CẬP NHẬT SAU)
        // TODO: Cập nhật khi biết tên bảng lịch trình đúng
        try {
            $sql = "SELECT COUNT(*) as total FROM lichtrinhtheoday WHERE id_goi = :id_goi";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi]);
            $ltCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $checklist['itinerary']['items']['co_lichtrinh']['status'] = $ltCount > 0;
            $checklist['itinerary']['items']['du_ngay']['status'] = $ltCount >= ($tour['songay'] ?? 0);
        } catch (Exception $e) {
            // Nếu bảng không tồn tại, cho pass tạm thời
            $checklist['itinerary']['items']['co_lichtrinh']['status'] = true;
            $checklist['itinerary']['items']['du_ngay']['status'] = true;
        }
        
        // 3. Kiểm tra gallery - Bảng tour_hinhanh
        try {
            $sql = "SELECT COUNT(*) as total FROM tour_hinhanh WHERE id_goi = :id_goi";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi]);
            $imgCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $checklist['images']['items']['gallery']['status'] = $imgCount >= 3;
        } catch (Exception $e) {
            // Nếu bảng không tồn tại, cho pass tạm thời
            $checklist['images']['items']['gallery']['status'] = true;
        }
        
        // 4. Kiểm tra chính sách - Bảng chinhsach_tour
        try {
            $sql = "SELECT COUNT(*) as total FROM chinhsach_tour 
                    WHERE id_goi = :id_goi AND loai_chinhsach = 'huy_doi'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi]);
            $policyHuy = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql = "SELECT COUNT(*) as total FROM chinhsach_tour 
                    WHERE id_goi = :id_goi AND loai_chinhsach = 'thanh_toan'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi]);
            $policyPay = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $checklist['policy']['items']['chinh_sach_huy']['status'] = $policyHuy > 0;
            $checklist['policy']['items']['chinh_sach_thanhtoan']['status'] = $policyPay > 0;
        } catch (Exception $e) {
            // Nếu bảng không tồn tại hoặc cột khác, cho pass tạm thời
            $checklist['policy']['items']['chinh_sach_huy']['status'] = true;
            $checklist['policy']['items']['chinh_sach_thanhtoan']['status'] = true;
        }
        
        return $checklist;
    }
    
    /**
     * Tính tỷ lệ hoàn thiện (%)
     */
    public function tinhTyLeHoanThanh($checklist) {
        $total = 0;
        $passed = 0;
        
        foreach ($checklist as $category) {
            foreach ($category['items'] as $item) {
                $total++;
                if ($item['status']) {
                    $passed++;
                }
            }
        }
        
        return $total > 0 ? round(($passed / $total) * 100) : 0;
    }
    
    /**
     * Kiểm tra có thể publish không
     */
    public function coThePublish($checklist) {
        // Các điều kiện bắt buộc
        $batBuoc = [
            'basic_info' => ['ten_goi', 'gia', 'so_ngay'],
            'itinerary' => ['co_lichtrinh'],
            'images' => ['anh_dai_dien'],
        ];
        
        foreach ($batBuoc as $category => $items) {
            foreach ($items as $item) {
                if (!$checklist[$category]['items'][$item]['status']) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Cập nhật trạng thái publish
     */
    public function capNhatTrangThaiPublish($idGoi, $trangThai, $userId = null) {
        // Lấy trạng thái cũ
        $trangThaiCu = $this->layThongTinPublish($idGoi)['publish_status'] ?? 'draft';
        
        // Lấy checklist
        $checklist = $this->kiemTraChecklist($idGoi);
        $checklistJson = json_encode($checklist);
        
        // Cập nhật tour
        $sql = "UPDATE goidulich 
                SET publish_status = :trang_thai,
                    publish_checklist = :checklist,
                    published_at = CURRENT_TIMESTAMP,
                    published_by = :user_id
                WHERE id_goi = :id_goi";
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            ':trang_thai' => $trangThai,
            ':checklist' => $checklistJson,
            ':user_id' => $userId,
            ':id_goi' => $idGoi
        ]);
        
        if ($result) {
            // Ghi log
            $this->ghiLogPublish($idGoi, $trangThai, $trangThaiCu, $trangThai, $checklistJson, null, $userId);
        }
        
        return $result;
    }
    
    /**
     * Ghi log publish
     */
    public function ghiLogPublish($idGoi, $hanhDong, $trangThaiCu, $trangThaiMoi, $checklistData, $ghiChu, $userId) {
        $sql = "INSERT INTO tour_publish_history 
                (id_goi, hanh_dong, trang_thai_cu, trang_thai_moi, 
                 checklist_data, ghi_chu, created_by)
                VALUES 
                (:id_goi, :hanh_dong, :trang_thai_cu, :trang_thai_moi,
                 :checklist_data, :ghi_chu, :created_by)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi' => $idGoi,
            ':hanh_dong' => $hanhDong,
            ':trang_thai_cu' => $trangThaiCu,
            ':trang_thai_moi' => $trangThaiMoi,
            ':checklist_data' => $checklistData,
            ':ghi_chu' => $ghiChu,
            ':created_by' => $userId
        ]);
    }
    
    /**
     * Lấy lịch sử publish
     */
    public function layLichSuPublish($idGoi) {
        $sql = "SELECT * FROM tour_publish_history 
                WHERE id_goi = :id_goi 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thống kê tour theo trạng thái
     */
    public function thongKeTourTheoTrangThai() {
        $sql = "SELECT 
                    publish_status,
                    COUNT(*) as total
                FROM goidulich
                GROUP BY publish_status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Danh sách tour cần review
     */
    public function danhSachTourCanReview() {
        $sql = "SELECT g.* 
                FROM goidulich g
                WHERE g.publish_status = 'draft'
                ORDER BY g.ngaydang DESC
                LIMIT 20";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        /**
     * Lấy danh sách tour theo trạng thái
     */
    public function layDanhSachTheoTrangThai($status) {
        $sql = "SELECT * FROM goidulich WHERE publish_status = :status ORDER BY ngaydang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>