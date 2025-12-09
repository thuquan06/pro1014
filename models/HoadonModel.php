<?php
/**
 * HoadonModel - Quản lý hóa đơn/booking
 * Created: 2025
 */
class HoadonModel extends BaseModel
{
    /**
     * Đảm bảo cột trang_thai_hoa_don tồn tại trong bảng booking
     */
    private function ensureInvoiceStatusColumnExists()
    {
        try {
            $columns = $this->conn->query("SHOW COLUMNS FROM booking")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('trang_thai_hoa_don', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `trang_thai_hoa_don` TINYINT(1) DEFAULT 0 COMMENT '0=Chưa xuất, 1=Đã xuất, 2=Đã gửi, 3=Hủy' AFTER `trang_thai`");
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureInvoiceStatusColumnExists: " . $e->getMessage());
        }
    }

    /**
     * Lấy tất cả hóa đơn từ bảng booking
     */
    public function getAllHoadon()
    {
        $this->ensureInvoiceStatusColumnExists();
        $sql = "SELECT b.id as id_hoadon,
                       b.ma_booking,
                       b.email as email_nguoidung,
                       b.ho_ten,
                       b.so_dien_thoai,
                       b.id_tour as id_goi,
                       b.so_nguoi_lon as nguoilon,
                       b.so_tre_em as treem,
                       b.so_tre_nho as trenho,
                       b.so_em_be as embe,
                       b.tong_tien,
                       b.tien_dat_coc,
                       b.trang_thai as trangthai,
                       COALESCE(b.trang_thai_hoa_don, 0) as trang_thai_hoa_don,
                       b.ngay_dat as ngaydat,
                       b.ngay_thanh_toan,
                       b.ghi_chu as ghichu,
                       g.tengoi as ten_goi,
                       lkh.ngay_khoi_hanh as ngayvao,
                       lkh.ngay_ket_thuc as ngayra
                FROM booking b 
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi 
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                ORDER BY b.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo ID từ bảng booking
     */
    public function getHoadonById($id)
    {
        $this->ensureInvoiceStatusColumnExists();
        $sql = "SELECT b.id as id_hoadon,
                       b.ma_booking,
                       b.email as email_nguoidung,
                       b.ho_ten,
                       b.so_dien_thoai,
                       b.id_tour as id_goi,
                       b.so_nguoi_lon as nguoilon,
                       b.so_tre_em as treem,
                       b.so_tre_nho as trenho,
                       b.so_em_be as embe,
                       b.tong_tien,
                       b.tien_dat_coc,
                       b.trang_thai as trangthai,
                       COALESCE(b.trang_thai_hoa_don, 0) as trang_thai_hoa_don,
                       b.ngay_dat as ngaydat,
                       b.ngay_thanh_toan,
                       b.ghi_chu as ghichu,
                       b.dia_chi,
                       g.tengoi as ten_goi, 
                       g.giagoi, 
                       g.giatreem, 
                       g.giatrenho,
                       lkh.ngay_khoi_hanh as ngayvao,
                       lkh.ngay_ket_thuc as ngayra,
                       lkh.gia_nguoi_lon,
                       lkh.gia_tre_em,
                       lkh.gia_tre_nho
                FROM booking b 
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi 
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo email người dùng từ bảng booking
     */
    public function getHoadonByEmail($email)
    {
        $sql = "SELECT b.id as id_hoadon,
                       b.ma_booking,
                       b.email as email_nguoidung,
                       b.ho_ten,
                       b.id_tour as id_goi,
                       b.so_nguoi_lon as nguoilon,
                       b.so_tre_em as treem,
                       b.so_tre_nho as trenho,
                       b.so_em_be as embe,
                       b.trang_thai as trangthai,
                       b.ngay_dat as ngaydat,
                       g.tengoi as ten_goi 
                FROM booking b 
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi 
                WHERE b.email = :email 
                ORDER BY b.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo trạng thái từ bảng booking
     * Map trạng thái: booking (0,2,3,4,5) -> hoadon (0,1,2)
     */
    public function getHoadonByStatus($trangthai)
    {
        // Map trạng thái từ hoadon sang booking
        // hoadon: 0=Chờ xác nhận, 1=Đã xác nhận, 2=Hoàn thành
        // booking: 0=Chờ xử lý, 2=Đã đặt cọc, 3=Đã thanh toán, 4=Hoàn thành, 5=Hủy
        $bookingStatuses = [];
        switch($trangthai) {
            case 0: // Chờ xác nhận
                $bookingStatuses = [0]; // Chờ xử lý
                break;
            case 1: // Đã xác nhận
                $bookingStatuses = [2, 3]; // Đã đặt cọc, Đã thanh toán
                break;
            case 2: // Hoàn thành
                $bookingStatuses = [4]; // Hoàn thành
                break;
        }
        
        $placeholders = implode(',', array_fill(0, count($bookingStatuses), '?'));
        $sql = "SELECT b.id as id_hoadon,
                       b.ma_booking,
                       b.email as email_nguoidung,
                       b.ho_ten,
                       b.id_tour as id_goi,
                       b.so_nguoi_lon as nguoilon,
                       b.so_tre_em as treem,
                       b.so_tre_nho as trenho,
                       b.so_em_be as embe,
                       b.trang_thai as trangthai,
                       b.ngay_dat as ngaydat,
                       g.tengoi as ten_goi 
                FROM booking b 
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi 
                WHERE b.trang_thai IN ($placeholders) 
                ORDER BY b.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($bookingStatuses);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo hóa đơn mới
     */
    public function createHoadon(array $data)
    {
        try {
            // Validate required fields
            if (empty($data['email_nguoidung'])) {
                error_log("Lỗi createHoadon: email_nguoidung is required");
                return false;
            }

            $sql = "INSERT INTO hoadon (
                        id_goi, id_ks, email_nguoidung, nguoilon, treem, trenho, embe,
                        phongdon, ngayvao, ngayra, sophong, ghichu, trangthai, ngaydat
                    ) VALUES (
                        :id_goi, :id_ks, :email_nguoidung, :nguoilon, :treem, :trenho, :embe,
                        :phongdon, :ngayvao, :ngayra, :sophong, :ghichu, :trangthai, NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            
            $params = [
                ':id_goi'           => $data['id_goi'] ?? null,
                ':id_ks'            => $data['id_ks'] ?? null,
                ':email_nguoidung'  => $data['email_nguoidung'],
                ':nguoilon'         => $data['nguoilon'] ?? 1,
                ':treem'            => $data['treem'] ?? 0,
                ':trenho'           => $data['trenho'] ?? 0,
                ':embe'             => $data['embe'] ?? 0,
                ':phongdon'         => $data['phongdon'] ?? 0,
                ':ngayvao'          => $data['ngayvao'] ?? null,
                ':ngayra'           => $data['ngayra'] ?? null,
                ':sophong'          => $data['sophong'] ?? 1,
                ':ghichu'           => $data['ghichu'] ?? '',
                ':trangthai'        => $data['trangthai'] ?? 0,
            ];

            $result = $stmt->execute($params);

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Lỗi createHoadon execute failed: " . print_r($errorInfo, true));
                error_log("SQL: " . $sql);
                error_log("Params: " . print_r($params, true));
                return false;
            }

            $lastInsertId = $this->conn->lastInsertId();
            if (!$lastInsertId) {
                error_log("Lỗi createHoadon: lastInsertId is empty");
                return false;
            }

            return $lastInsertId;
        } catch (PDOException $e) {
            $errorMsg = "Lỗi createHoadon PDOException: " . $e->getMessage();
            $errorMsg .= " | Code: " . $e->getCode();
            $errorMsg .= " | File: " . $e->getFile() . ":" . $e->getLine();
            error_log($errorMsg);
            error_log("Data: " . print_r($data, true));
            return false;
        } catch (Exception $e) {
            $errorMsg = "Lỗi createHoadon Exception: " . $e->getMessage();
            $errorMsg .= " | File: " . $e->getFile() . ":" . $e->getLine();
            error_log($errorMsg);
            error_log("Data: " . print_r($data, true));
            return false;
        }
    }

    /**
     * Cập nhật hóa đơn
     */
    public function updateHoadon($id, array $data)
    {
        try {
            $this->ensureInvoiceStatusColumnExists();
            
            // Cập nhật vào bảng booking (không cập nhật trang_thai booking)
            $sql = "UPDATE booking SET
                        so_nguoi_lon        = :nguoilon,
                        so_tre_em           = :treem,
                        so_tre_nho          = :trenho,
                        so_em_be            = :embe,
                        ghi_chu             = :ghichu,
                        trang_thai_hoa_don  = :trang_thai_hoa_don,
                        ngay_cap_nhat       = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':nguoilon'          => $data['nguoilon'] ?? 1,
                ':treem'             => $data['treem'] ?? 0,
                ':trenho'            => $data['trenho'] ?? 0,
                ':embe'              => $data['embe'] ?? 0,
                ':ghichu'            => $data['ghichu'] ?? '',
                ':trang_thai_hoa_don' => $data['trang_thai_hoa_don'] ?? 0,
                ':id'                => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật trạng thái hóa đơn từ bảng booking
     * Map trạng thái: hoadon (0,1,2) -> booking (0,2,3,4)
     */
    public function updateStatus($id, $trangthai)
    {
        try {
            // Map trạng thái từ hoadon sang booking
            $bookingStatus = 0;
            switch($trangthai) {
                case 0: // Chờ xác nhận
                    $bookingStatus = 0; // Chờ xử lý
                    break;
                case 1: // Đã xác nhận
                    $bookingStatus = 2; // Đã đặt cọc
                    break;
                case 2: // Hoàn thành
                    $bookingStatus = 4; // Hoàn thành
                    break;
            }
            
            $sql = "UPDATE booking SET trang_thai = :trang_thai, ngay_cap_nhat = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':trang_thai' => $bookingStatus,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hủy hóa đơn từ bảng booking
     */
    public function cancelHoadon($id, $lyDoHuy = null)
    {
        try {
            $sql = "UPDATE booking SET trang_thai = 5, ghi_chu = CONCAT(COALESCE(ghi_chu, ''), ' - Lý do hủy: ', :ly_do_huy), ngay_cap_nhat = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':ly_do_huy' => $lyDoHuy ?? 'Không có lý do'
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cancelHoadon: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác nhận hóa đơn (chuyển từ chờ xử lý sang đã đặt cọc) từ bảng booking
     */
    public function confirmHoadon($id)
    {
        try {
            $sql = "UPDATE booking SET trang_thai = 2, ngay_cap_nhat = NOW() WHERE id = :id AND trang_thai != 5";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi confirmHoadon: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hoàn thành hóa đơn (chuyển từ đã đặt cọc/thanh toán sang hoàn thành) từ bảng booking
     */
    public function completeHoadon($id)
    {
        try {
            $sql = "UPDATE booking SET trang_thai = 4, ngay_cap_nhat = NOW() WHERE id = :id AND trang_thai != 5";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi completeHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa hóa đơn từ bảng booking
     */
    public function deleteHoadon($id)
    {
        try {
            $sql = "DELETE FROM booking WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính tổng tiền hóa đơn từ bảng booking
     */
    public function calculateTotal($id_hoadon)
    {
        $hoadon = $this->getHoadonById($id_hoadon);
        if (!$hoadon) return 0;

        // Nếu đã có tong_tien trong booking thì dùng luôn
        if (!empty($hoadon['tong_tien'])) {
            return (float)$hoadon['tong_tien'];
        }

        // Nếu không có, tính từ giá lịch khởi hành hoặc giá tour
        $total = 0;
        if (!empty($hoadon['gia_nguoi_lon'])) {
            $total += ($hoadon['nguoilon'] ?? 0) * ($hoadon['gia_nguoi_lon'] ?? 0);
            $total += ($hoadon['treem'] ?? 0) * ($hoadon['gia_tre_em'] ?? 0);
            $total += ($hoadon['trenho'] ?? 0) * ($hoadon['gia_tre_nho'] ?? 0);
        } else {
            // Fallback về giá tour
        $total += ($hoadon['nguoilon'] ?? 0) * ($hoadon['giagoi'] ?? 0);
        $total += ($hoadon['treem'] ?? 0) * ($hoadon['giatreem'] ?? 0);
        $total += ($hoadon['trenho'] ?? 0) * ($hoadon['giatrenho'] ?? 0);
        }
        // embe thường miễn phí

        return $total;
    }


    /**
     * Cập nhật trạng thái hóa đơn (0=Chưa xuất, 1=Đã xuất, 2=Đã gửi, 3=Hủy)
     */
    public function updateInvoiceStatus($id, $trang_thai_hoa_don)
    {
        $this->ensureInvoiceStatusColumnExists();
        try {
            $sql = "UPDATE booking SET trang_thai_hoa_don = :trang_thai_hoa_don, ngay_cap_nhat = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':trang_thai_hoa_don' => (int)$trang_thai_hoa_don,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateInvoiceStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thống kê hóa đơn từ bảng booking theo trạng thái hóa đơn
     */
    public function getStatistics()
    {
        $this->ensureInvoiceStatusColumnExists();
        $sql = "SELECT 
                    COUNT(*) as total_hoadon,
                    SUM(CASE WHEN COALESCE(trang_thai_hoa_don, 0) = 0 THEN 1 ELSE 0 END) as chua_xuat,
                    SUM(CASE WHEN COALESCE(trang_thai_hoa_don, 0) = 1 THEN 1 ELSE 0 END) as da_xuat,
                    SUM(CASE WHEN COALESCE(trang_thai_hoa_don, 0) = 2 THEN 1 ELSE 0 END) as da_gui,
                    SUM(CASE WHEN COALESCE(trang_thai_hoa_don, 0) = 3 OR trang_thai = 5 THEN 1 ELSE 0 END) as da_huy
                FROM booking";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
