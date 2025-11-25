<?php
/**
 * GuideModel - Quản lý Hướng dẫn viên
 * UC-Assign-Guide: Phân công HDV theo kỹ năng/tuyến/ngôn ngữ
 */
class GuideModel extends BaseModel
{
    /**
     * Lấy tất cả HDV
     */
    public function getAllGuides($filters = [])
    {
        $sql = "SELECT * FROM huong_dan_vien WHERE 1=1";
        $params = [];

        // Filter theo trạng thái
        if (isset($filters['trang_thai'])) {
            $sql .= " AND trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        // Filter theo kỹ năng
        if (!empty($filters['ky_nang'])) {
            $sql .= " AND (ky_nang LIKE :ky_nang OR ky_nang IS NULL)";
            $params[':ky_nang'] = '%' . $filters['ky_nang'] . '%';
        }

        // Filter theo tuyến chuyên
        if (!empty($filters['tuyen_chuyen'])) {
            $sql .= " AND (tuyen_chuyen LIKE :tuyen_chuyen OR tuyen_chuyen IS NULL)";
            $params[':tuyen_chuyen'] = '%' . $filters['tuyen_chuyen'] . '%';
        }

        // Filter theo ngôn ngữ
        if (!empty($filters['ngon_ngu'])) {
            $sql .= " AND (ngon_ngu LIKE :ngon_ngu OR ngon_ngu IS NULL)";
            $params[':ngon_ngu'] = '%' . $filters['ngon_ngu'] . '%';
        }

        $sql .= " ORDER BY ho_ten ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy HDV theo ID
     */
    public function getGuideByID($id)
    {
        $sql = "SELECT * FROM huong_dan_vien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo HDV mới
     */
    public function createGuide(array $data)
    {
        try {
            $sql = "INSERT INTO huong_dan_vien (
                        ho_ten, email, so_dien_thoai, cmnd_cccd, dia_chi,
                        ky_nang, tuyen_chuyen, ngon_ngu, kinh_nghiem,
                        danh_gia, trang_thai, ghi_chu
                    ) VALUES (
                        :ho_ten, :email, :so_dien_thoai, :cmnd_cccd, :dia_chi,
                        :ky_nang, :tuyen_chuyen, :ngon_ngu, :kinh_nghiem,
                        :danh_gia, :trang_thai, :ghi_chu
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ho_ten' => $data['ho_ten'] ?? '',
                ':email' => $data['email'] ?? null,
                ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
                ':cmnd_cccd' => $data['cmnd_cccd'] ?? null,
                ':dia_chi' => $data['dia_chi'] ?? null,
                ':ky_nang' => $this->prepareJsonArray($data['ky_nang'] ?? []),
                ':tuyen_chuyen' => $this->prepareJsonArray($data['tuyen_chuyen'] ?? []),
                ':ngon_ngu' => $this->prepareJsonArray($data['ngon_ngu'] ?? []),
                ':kinh_nghiem' => $data['kinh_nghiem'] ?? 0,
                ':danh_gia' => $data['danh_gia'] ?? 0.00,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo HDV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật HDV
     */
    public function updateGuide($id, array $data)
    {
        try {
            $sql = "UPDATE huong_dan_vien SET
                        ho_ten = :ho_ten,
                        email = :email,
                        so_dien_thoai = :so_dien_thoai,
                        cmnd_cccd = :cmnd_cccd,
                        dia_chi = :dia_chi,
                        ky_nang = :ky_nang,
                        tuyen_chuyen = :tuyen_chuyen,
                        ngon_ngu = :ngon_ngu,
                        kinh_nghiem = :kinh_nghiem,
                        danh_gia = :danh_gia,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':ho_ten' => $data['ho_ten'] ?? '',
                ':email' => $data['email'] ?? null,
                ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
                ':cmnd_cccd' => $data['cmnd_cccd'] ?? null,
                ':dia_chi' => $data['dia_chi'] ?? null,
                ':ky_nang' => $this->prepareJsonArray($data['ky_nang'] ?? []),
                ':tuyen_chuyen' => $this->prepareJsonArray($data['tuyen_chuyen'] ?? []),
                ':ngon_ngu' => $this->prepareJsonArray($data['ngon_ngu'] ?? []),
                ':kinh_nghiem' => $data['kinh_nghiem'] ?? 0,
                ':danh_gia' => $data['danh_gia'] ?? 0.00,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật HDV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa HDV
     */
    public function deleteGuide($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM huong_dan_vien WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa HDV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle trạng thái HDV
     */
    public function toggleStatus($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE huong_dan_vien SET trang_thai = NOT trang_thai WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi toggle status HDV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Chuẩn bị mảng thành JSON string
     */
    private function prepareJsonArray($data)
    {
        if (is_string($data)) {
            // Nếu đã là JSON string, kiểm tra xem có hợp lệ không
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_UNESCAPED_UNICODE);
            }
            // Nếu không phải JSON hợp lệ, coi như là string đơn giản
            return json_encode([$data], JSON_UNESCAPED_UNICODE);
        }
        if (is_array($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return json_encode([], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Parse JSON string thành mảng
     */
    public function parseJsonArray($jsonString)
    {
        if (empty($jsonString)) {
            return [];
        }
        $decoded = json_decode($jsonString, true);
        return $decoded ?: [];
    }
}

