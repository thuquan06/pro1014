<?php
/**
 * ServiceModel - Quản lý Dịch vụ
 * UC-Assign-Services: Gán dịch vụ nội bộ/đối tác
 */
class ServiceModel extends BaseModel
{
    /**
     * Lấy tất cả dịch vụ
     */
    public function getAllServices($filters = [])
    {
        $sql = "SELECT * FROM dich_vu WHERE 1=1";
        $params = [];

        // Filter theo tên dịch vụ
        if (!empty($filters['ten_dich_vu'])) {
            $sql .= " AND ten_dich_vu LIKE :ten_dich_vu";
            $params[':ten_dich_vu'] = '%' . $filters['ten_dich_vu'] . '%';
        }

        // Filter theo nhà cung cấp
        if (!empty($filters['nha_cung_cap'])) {
            $sql .= " AND nha_cung_cap LIKE :nha_cung_cap";
            $params[':nha_cung_cap'] = '%' . $filters['nha_cung_cap'] . '%';
        }

        // Filter theo loại dịch vụ
        if (!empty($filters['loai_dich_vu'])) {
            $sql .= " AND loai_dich_vu = :loai_dich_vu";
            $params[':loai_dich_vu'] = $filters['loai_dich_vu'];
        }

        // Filter theo trạng thái
        if (isset($filters['trang_thai'])) {
            $sql .= " AND trang_thai = :trang_thai";
            $params[':trang_thai'] = $filters['trang_thai'];
        }

        $sql .= " ORDER BY loai_dich_vu, ten_dich_vu ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy dịch vụ theo ID
     */
    public function getServiceByID($id)
    {
        $sql = "SELECT * FROM dich_vu WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy dịch vụ theo loại
     */
    public function getServicesByType($loaiDichVu)
    {
        return $this->getAllServices(['loai_dich_vu' => $loaiDichVu, 'trang_thai' => 1]);
    }

    /**
     * Tạo dịch vụ mới
     */
    public function createService(array $data)
    {
        try {
            $sql = "INSERT INTO dich_vu (
                        ten_dich_vu, loai_dich_vu, mo_ta, nha_cung_cap,
                        lien_he, gia, don_vi, trang_thai, ghi_chu
                    ) VALUES (
                        :ten_dich_vu, :loai_dich_vu, :mo_ta, :nha_cung_cap,
                        :lien_he, :gia, :don_vi, :trang_thai, :ghi_chu
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':loai_dich_vu' => $data['loai_dich_vu'] ?? '',
                ':mo_ta' => $data['mo_ta'] ?? null,
                ':nha_cung_cap' => $data['nha_cung_cap'] ?? null,
                ':lien_he' => $data['lien_he'] ?? null,
                ':gia' => $data['gia'] ?? null,
                ':don_vi' => $data['don_vi'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật dịch vụ
     */
    public function updateService($id, array $data)
    {
        try {
            $sql = "UPDATE dich_vu SET
                        ten_dich_vu = :ten_dich_vu,
                        loai_dich_vu = :loai_dich_vu,
                        mo_ta = :mo_ta,
                        nha_cung_cap = :nha_cung_cap,
                        lien_he = :lien_he,
                        gia = :gia,
                        don_vi = :don_vi,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':loai_dich_vu' => $data['loai_dich_vu'] ?? '',
                ':mo_ta' => $data['mo_ta'] ?? null,
                ':nha_cung_cap' => $data['nha_cung_cap'] ?? null,
                ':lien_he' => $data['lien_he'] ?? null,
                ':gia' => $data['gia'] ?? null,
                ':don_vi' => $data['don_vi'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa dịch vụ
     */
    public function deleteService($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM dich_vu WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle trạng thái dịch vụ
     */
    public function toggleStatus($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE dich_vu SET trang_thai = NOT trang_thai WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi toggle status dịch vụ: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách loại dịch vụ
     */
    public static function getServiceTypes()
    {
        return [
            'xe' => 'Xe/Phương tiện',
            'khach_san' => 'Khách sạn',
            'nha_hang' => 'Nhà hàng',
            've_tham_quan' => 'Vé tham quan',
        ];
    }
}



