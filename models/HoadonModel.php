<?php
/**
 * HoadonModel - Quản lý hóa đơn/booking
 * Created: 2025
 */
class HoadonModel extends BaseModel
{
    /**
     * Lấy tất cả hóa đơn
     */
    public function getAllHoadon()
    {
        $sql = "SELECT h.*, g.tengoi as ten_goi 
                FROM hoadon h 
                LEFT JOIN goidulich g ON h.id_goi = g.id_goi 
                ORDER BY h.ngaydat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo ID
     */
    public function getHoadonById($id)
    {
        $sql = "SELECT h.*, g.tengoi as ten_goi, g.giagoi, g.giatreem, g.giatrenho
                FROM hoadon h 
                LEFT JOIN goidulich g ON h.id_goi = g.id_goi 
                WHERE h.id_hoadon = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo email người dùng
     */
    public function getHoadonByEmail($email)
    {
        $sql = "SELECT h.*, g.tengoi as ten_goi 
                FROM hoadon h 
                LEFT JOIN goidulich g ON h.id_goi = g.id_goi 
                WHERE h.email_nguoidung = :email 
                ORDER BY h.ngaydat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy hóa đơn theo trạng thái
     */
    public function getHoadonByStatus($trangthai)
    {
        $sql = "SELECT h.*, g.tengoi as ten_goi 
                FROM hoadon h 
                LEFT JOIN goidulich g ON h.id_goi = g.id_goi 
                WHERE h.trangthai = :trangthai 
                ORDER BY h.ngaydat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':trangthai' => $trangthai]);
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
            $sql = "UPDATE hoadon SET
                        id_goi          = :id_goi,
                        id_ks           = :id_ks,
                        email_nguoidung = :email_nguoidung,
                        nguoilon        = :nguoilon,
                        treem           = :treem,
                        trenho          = :trenho,
                        embe            = :embe,
                        phongdon        = :phongdon,
                        ngayvao         = :ngayvao,
                        ngayra          = :ngayra,
                        sophong         = :sophong,
                        ghichu          = :ghichu,
                        trangthai       = :trangthai,
                        ngaycapnhat     = NOW()
                    WHERE id_hoadon = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
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
                ':id'               => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật trạng thái hóa đơn
     */
    public function updateStatus($id, $trangthai)
    {
        try {
            $sql = "UPDATE hoadon SET trangthai = :trangthai, ngaycapnhat = NOW() WHERE id_hoadon = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':trangthai' => $trangthai,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hủy hóa đơn
     */
    public function cancelHoadon($id, $lyDoHuy = null)
    {
        try {
            $sql = "UPDATE hoadon SET huy = 1, ly_do_huy = :ly_do_huy, ngaycapnhat = NOW() WHERE id_hoadon = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':ly_do_huy' => $lyDoHuy
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cancelHoadon: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác nhận hóa đơn (chuyển từ chờ xác nhận sang đã xác nhận)
     */
    public function confirmHoadon($id)
    {
        try {
            $sql = "UPDATE hoadon SET trangthai = 1, ngaycapnhat = NOW() WHERE id_hoadon = :id AND huy = 0";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi confirmHoadon: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hoàn thành hóa đơn (chuyển từ đã xác nhận sang hoàn thành)
     */
    public function completeHoadon($id)
    {
        try {
            $sql = "UPDATE hoadon SET trangthai = 2, ngaycapnhat = NOW() WHERE id_hoadon = :id AND huy = 0";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi completeHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa hóa đơn
     */
    public function deleteHoadon($id)
    {
        try {
            $sql = "DELETE FROM hoadon WHERE id_hoadon = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteHoadon: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính tổng tiền hóa đơn
     */
    public function calculateTotal($id_hoadon)
    {
        $hoadon = $this->getHoadonById($id_hoadon);
        if (!$hoadon) return 0;

        $total = 0;
        $total += ($hoadon['nguoilon'] ?? 0) * ($hoadon['giagoi'] ?? 0);
        $total += ($hoadon['treem'] ?? 0) * ($hoadon['giatreem'] ?? 0);
        $total += ($hoadon['trenho'] ?? 0) * ($hoadon['giatrenho'] ?? 0);
        // embe thường miễn phí

        return $total;
    }


    /**
     * Thống kê hóa đơn
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_hoadon,
                    SUM(CASE WHEN trangthai = 0 THEN 1 ELSE 0 END) as cho_xacnhan,
                    SUM(CASE WHEN trangthai = 1 THEN 1 ELSE 0 END) as da_xacnhan,
                    SUM(CASE WHEN trangthai = 2 THEN 1 ELSE 0 END) as hoan_thanh,
                    SUM(CASE WHEN huy = 1 THEN 1 ELSE 0 END) as da_huy
                FROM hoadon";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
