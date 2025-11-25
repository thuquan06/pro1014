<?php
/**
 * DeparturePlanModel - Quản lý lịch khởi hành
 * UC-Departure-Plan: Tạo lịch khởi hành cho tour
 */
class DeparturePlanModel extends BaseModel
{
    /**
     * Lấy tất cả lịch khởi hành
     */
    public function getAllDeparturePlans()
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi 
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                ORDER BY dp.ngay_khoi_hanh DESC, dp.gio_khoi_hanh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch khởi hành theo ID
     */
    public function getDeparturePlanByID($id)
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi 
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE dp.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch khởi hành theo tour ID
     */
    public function getDeparturePlansByTourID($tourId)
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi 
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE dp.id_tour = :tour_id 
                ORDER BY dp.ngay_khoi_hanh DESC, dp.gio_khoi_hanh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo lịch khởi hành mới
     */
    public function createDeparturePlan(array $data)
    {
        try {
            $sql = "INSERT INTO lich_khoi_hanh (
                        id_tour, ngay_khoi_hanh, gio_khoi_hanh, 
                        diem_tap_trung, so_cho_du_kien, ghi_chu_van_hanh, 
                        trang_thai, ngay_tao, ngay_cap_nhat
                    ) VALUES (
                        :id_tour, :ngay_khoi_hanh, :gio_khoi_hanh,
                        :diem_tap_trung, :so_cho_du_kien, :ghi_chu_van_hanh,
                        :trang_thai, NOW(), NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_tour' => $data['id_tour'] ?? null,
                ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                ':so_cho_du_kien' => $data['so_cho_du_kien'] ?? null,
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo lịch khởi hành: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật lịch khởi hành
     */
    public function updateDeparturePlan($id, array $data)
    {
        try {
            $sql = "UPDATE lich_khoi_hanh SET
                        id_tour = :id_tour,
                        ngay_khoi_hanh = :ngay_khoi_hanh,
                        gio_khoi_hanh = :gio_khoi_hanh,
                        diem_tap_trung = :diem_tap_trung,
                        so_cho_du_kien = :so_cho_du_kien,
                        ghi_chu_van_hanh = :ghi_chu_van_hanh,
                        trang_thai = :trang_thai,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':id_tour' => $data['id_tour'] ?? null,
                ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                ':so_cho_du_kien' => $data['so_cho_du_kien'] ?? null,
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật lịch khởi hành: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa lịch khởi hành
     */
    public function deleteDeparturePlan($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM lich_khoi_hanh WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa lịch khởi hành: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle trạng thái lịch khởi hành
     */
    public function toggleStatus($id)
    {
        try {
            $sql = "UPDATE lich_khoi_hanh 
                    SET trang_thai = NOT trang_thai, ngay_cap_nhat = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi toggle trạng thái: " . $e->getMessage());
            return false;
        }
    }
}

