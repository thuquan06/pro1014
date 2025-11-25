<?php
/**
 * ServiceAssignmentModel - Quản lý Gán dịch vụ
 * UC-Assign-Services: Gán dịch vụ cho lịch khởi hành với trạng thái xác nhận
 */
class ServiceAssignmentModel extends BaseModel
{
    /**
     * Lấy tất cả gán dịch vụ
     */
    public function getAllAssignments($filters = [])
    {
        $sql = "SELECT gdv.*, 
                       dv.ten_dich_vu, dv.loai_dich_vu, dv.don_vi,
                       dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                       g.tengoi AS ten_tour, g.id_goi AS id_tour
                FROM gan_dich_vu gdv
                LEFT JOIN dich_vu dv ON gdv.id_dich_vu = dv.id
                LEFT JOIN lich_khoi_hanh dp ON gdv.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE 1=1";
        $params = [];

        // Filter theo lịch khởi hành
        if (!empty($filters['id_lich_khoi_hanh'])) {
            $sql .= " AND gdv.id_lich_khoi_hanh = :id_lich_khoi_hanh";
            $params[':id_lich_khoi_hanh'] = $filters['id_lich_khoi_hanh'];
        }

        // Filter theo loại dịch vụ
        if (!empty($filters['loai_dich_vu'])) {
            $sql .= " AND dv.loai_dich_vu = :loai_dich_vu";
            $params[':loai_dich_vu'] = $filters['loai_dich_vu'];
        }

        // Filter theo trạng thái
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND gdv.trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        $sql .= " ORDER BY gdv.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy gán dịch vụ theo ID
     */
    public function getAssignmentByID($id)
    {
        $sql = "SELECT gdv.*, 
                       dv.ten_dich_vu, dv.loai_dich_vu, dv.don_vi, dv.gia AS gia_mac_dinh,
                       dp.ngay_khoi_hanh, dp.gio_khoi_hanh, dp.diem_tap_trung,
                       g.tengoi AS ten_tour, g.id_goi AS id_tour
                FROM gan_dich_vu gdv
                LEFT JOIN dich_vu dv ON gdv.id_dich_vu = dv.id
                LEFT JOIN lich_khoi_hanh dp ON gdv.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE gdv.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy gán dịch vụ theo lịch khởi hành
     */
    public function getAssignmentsByDeparturePlanID($departurePlanId)
    {
        return $this->getAllAssignments(['id_lich_khoi_hanh' => $departurePlanId]);
    }

    /**
     * Tạo gán dịch vụ mới
     */
    public function createAssignment(array $data)
    {
        try {
            $sql = "INSERT INTO gan_dich_vu (
                        id_lich_khoi_hanh, id_dich_vu, so_luong,
                        ngay_su_dung, gia_thuc_te, trang_thai, ghi_chu
                    ) VALUES (
                        :id_lich_khoi_hanh, :id_dich_vu, :so_luong,
                        :ngay_su_dung, :gia_thuc_te, :trang_thai, :ghi_chu
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':id_dich_vu' => $data['id_dich_vu'] ?? null,
                ':so_luong' => $data['so_luong'] ?? 1,
                ':ngay_su_dung' => $data['ngay_su_dung'] ?? null,
                ':gia_thuc_te' => $data['gia_thuc_te'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 'cho',
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo gán dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật gán dịch vụ
     */
    public function updateAssignment($id, array $data)
    {
        try {
            $sql = "UPDATE gan_dich_vu SET
                        id_lich_khoi_hanh = :id_lich_khoi_hanh,
                        id_dich_vu = :id_dich_vu,
                        so_luong = :so_luong,
                        ngay_su_dung = :ngay_su_dung,
                        gia_thuc_te = :gia_thuc_te,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':id_dich_vu' => $data['id_dich_vu'] ?? null,
                ':so_luong' => $data['so_luong'] ?? 1,
                ':ngay_su_dung' => $data['ngay_su_dung'] ?? null,
                ':gia_thuc_te' => $data['gia_thuc_te'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 'cho',
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật gán dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xác nhận dịch vụ
     */
    public function confirmAssignment($id, $nguoiXacNhan = null)
    {
        try {
            $sql = "UPDATE gan_dich_vu SET
                        trang_thai = 'da_xac_nhan',
                        ngay_xac_nhan = NOW(),
                        nguoi_xac_nhan = :nguoi_xac_nhan,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nguoi_xac_nhan' => $nguoiXacNhan ?? $_SESSION['alogin'] ?? 'System',
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi xác nhận dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hủy gán dịch vụ
     */
    public function cancelAssignment($id)
    {
        try {
            $sql = "UPDATE gan_dich_vu SET
                        trang_thai = 'huy',
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi hủy dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa gán dịch vụ
     */
    public function deleteAssignment($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM gan_dich_vu WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa gán dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách trạng thái
     */
    public static function getStatuses()
    {
        return [
            'cho' => 'Chờ xác nhận',
            'da_xac_nhan' => 'Đã xác nhận',
            'huy' => 'Đã hủy',
        ];
    }
}



