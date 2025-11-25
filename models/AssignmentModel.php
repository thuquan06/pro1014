<?php
/**
 * AssignmentModel - Quản lý Phân công HDV
 * UC-Assign-Guide: Phân công HDV với cảnh báo trùng lịch
 */
class AssignmentModel extends BaseModel
{
    /**
     * Lấy tất cả phân công
     */
    public function getAllAssignments($filters = [])
    {
        $sql = "SELECT pc.*, 
                       hdv.ho_ten, hdv.email, hdv.so_dien_thoai,
                       dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                       g.tengoi AS ten_tour, g.id_goi AS id_tour
                FROM phan_cong_hdv pc
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE 1=1";
        $params = [];

        // Filter theo lịch khởi hành
        if (!empty($filters['id_lich_khoi_hanh'])) {
            $sql .= " AND pc.id_lich_khoi_hanh = :id_lich_khoi_hanh";
            $params[':id_lich_khoi_hanh'] = $filters['id_lich_khoi_hanh'];
        }

        // Filter theo HDV
        if (!empty($filters['id_hdv'])) {
            $sql .= " AND pc.id_hdv = :id_hdv";
            $params[':id_hdv'] = $filters['id_hdv'];
        }

        // Filter theo trạng thái
        if (isset($filters['trang_thai'])) {
            $sql .= " AND pc.trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        $sql .= " ORDER BY pc.ngay_bat_dau DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy phân công theo ID
     */
    public function getAssignmentByID($id)
    {
        $sql = "SELECT pc.*, 
                       hdv.ho_ten, hdv.email, hdv.so_dien_thoai,
                       dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                       g.tengoi AS ten_tour, g.id_goi AS id_tour
                FROM phan_cong_hdv pc
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE pc.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy phân công theo lịch khởi hành
     */
    public function getAssignmentsByDeparturePlanID($departurePlanId)
    {
        return $this->getAllAssignments(['id_lich_khoi_hanh' => $departurePlanId]);
    }

    /**
     * Kiểm tra trùng lịch HDV
     * Trả về danh sách các phân công bị trùng
     */
    public function checkScheduleConflict($idHdv, $ngayBatDau, $ngayKetThuc, $excludeAssignmentId = null)
    {
        $sql = "SELECT pc.*, 
                       dp.ngay_khoi_hanh, dp.gio_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM phan_cong_hdv pc
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE pc.id_hdv = :id_hdv
                  AND pc.trang_thai = 1
                  AND (
                    (:ngay_bat_dau BETWEEN pc.ngay_bat_dau AND pc.ngay_ket_thuc)
                    OR (:ngay_ket_thuc BETWEEN pc.ngay_bat_dau AND pc.ngay_ket_thuc)
                    OR (pc.ngay_bat_dau BETWEEN :ngay_bat_dau AND :ngay_ket_thuc)
                    OR (pc.ngay_ket_thuc BETWEEN :ngay_bat_dau AND :ngay_ket_thuc)
                  )";

        $params = [
            ':id_hdv' => $idHdv,
            ':ngay_bat_dau' => $ngayBatDau,
            ':ngay_ket_thuc' => $ngayKetThuc,
        ];

        // Loại trừ phân công hiện tại khi update
        if ($excludeAssignmentId) {
            $sql .= " AND pc.id != :exclude_id";
            $params[':exclude_id'] = $excludeAssignmentId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo phân công mới
     */
    public function createAssignment(array $data)
    {
        try {
            $sql = "INSERT INTO phan_cong_hdv (
                        id_lich_khoi_hanh, id_hdv, vai_tro,
                        ngay_bat_dau, ngay_ket_thuc, luong,
                        trang_thai, ghi_chu
                    ) VALUES (
                        :id_lich_khoi_hanh, :id_hdv, :vai_tro,
                        :ngay_bat_dau, :ngay_ket_thuc, :luong,
                        :trang_thai, :ghi_chu
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':id_hdv' => $data['id_hdv'] ?? null,
                ':vai_tro' => $data['vai_tro'] ?? 'HDV chính',
                ':ngay_bat_dau' => $data['ngay_bat_dau'] ?? null,
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                ':luong' => $data['luong'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo phân công: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật phân công
     */
    public function updateAssignment($id, array $data)
    {
        try {
            $sql = "UPDATE phan_cong_hdv SET
                        id_lich_khoi_hanh = :id_lich_khoi_hanh,
                        id_hdv = :id_hdv,
                        vai_tro = :vai_tro,
                        ngay_bat_dau = :ngay_bat_dau,
                        ngay_ket_thuc = :ngay_ket_thuc,
                        luong = :luong,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':id_hdv' => $data['id_hdv'] ?? null,
                ':vai_tro' => $data['vai_tro'] ?? 'HDV chính',
                ':ngay_bat_dau' => $data['ngay_bat_dau'] ?? null,
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                ':luong' => $data['luong'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật phân công: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa phân công
     */
    public function deleteAssignment($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM phan_cong_hdv WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa phân công: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle trạng thái phân công
     */
    public function toggleStatus($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE phan_cong_hdv SET trang_thai = NOT trang_thai WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi toggle status phân công: " . $e->getMessage());
            return false;
        }
    }
}



