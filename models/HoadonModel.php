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
            $sql = "INSERT INTO hoadon (
                        id_goi, id_ks, email_nguoidung, nguoilon, treem, trenho, embe,
                        phongdon, ngayvao, ngayra, sophong, ghichu, trangthai, ngaydat
                    ) VALUES (
                        :id_goi, :id_ks, :email_nguoidung, :nguoilon, :treem, :trenho, :embe,
                        :phongdon, :ngayvao, :ngayra, :sophong, :ghichu, :trangthai, NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
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
            ]);

            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Lỗi createHoadon: " . $e->getMessage());
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
    public function cancelHoadon($id)
    {
        try {
            $sql = "UPDATE hoadon SET huy = 1, ngaycapnhat = NOW() WHERE id_hoadon = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi cancelHoadon: " . $e->getMessage());
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
