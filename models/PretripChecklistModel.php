<?php
/**
 * PretripChecklistModel - Quản lý checklist trước ngày đi
 * UC-Pretrip-Checklist: Checklist trước ngày đi cho tour
 */
class PretripChecklistModel extends BaseModel
{
    /**
     * Lấy tất cả checklist
     */
    public function getAllChecklists()
    {
        $sql = "SELECT c.*, dp.ngay_khoi_hanh, dp.gio_khoi_hanh, g.tengoi, g.id_goi
                FROM pretrip_checklist c
                LEFT JOIN lich_khoi_hanh dp ON c.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                ORDER BY c.ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy checklist theo ID
     */
    public function getChecklistByID($id)
    {
        $sql = "SELECT c.*, dp.ngay_khoi_hanh, dp.gio_khoi_hanh, g.tengoi, g.id_goi
                FROM pretrip_checklist c
                LEFT JOIN lich_khoi_hanh dp ON c.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE c.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy checklist theo lịch khởi hành ID
     */
    public function getChecklistByDeparturePlanID($departurePlanId)
    {
        $sql = "SELECT * FROM pretrip_checklist 
                WHERE id_lich_khoi_hanh = :departure_plan_id 
                ORDER BY ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':departure_plan_id' => $departurePlanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo checklist mới
     */
    public function createChecklist(array $data)
    {
        try {
            // Chuyển đổi các checkbox thành JSON
            $checklistItems = $this->prepareChecklistItems($data);
            
            $sql = "INSERT INTO pretrip_checklist (
                        id_lich_khoi_hanh, checklist_items, trang_thai, 
                        ghi_chu, ngay_tao, ngay_cap_nhat
                    ) VALUES (
                        :id_lich_khoi_hanh, :checklist_items, :trang_thai,
                        :ghi_chu, NOW(), NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':checklist_items' => json_encode($checklistItems, JSON_UNESCAPED_UNICODE),
                ':trang_thai' => $data['trang_thai'] ?? 0,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo checklist: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật checklist
     */
    public function updateChecklist($id, array $data)
    {
        try {
            // Chuyển đổi các checkbox thành JSON
            $checklistItems = $this->prepareChecklistItems($data);
            
            // Kiểm tra xem tất cả items đã được tick chưa
            $allChecked = $this->areAllItemsChecked($checklistItems);
            
            $sql = "UPDATE pretrip_checklist SET
                        id_lich_khoi_hanh = :id_lich_khoi_hanh,
                        checklist_items = :checklist_items,
                        trang_thai = :trang_thai,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':checklist_items' => json_encode($checklistItems, JSON_UNESCAPED_UNICODE),
                ':trang_thai' => $allChecked ? 1 : ($data['trang_thai'] ?? 0),
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật checklist: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa checklist
     */
    public function deleteChecklist($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM pretrip_checklist WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa checklist: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Chuẩn bị checklist items từ form data
     */
    private function prepareChecklistItems(array $data)
    {
        $defaultItems = [
            'tai_lieu' => ['label' => 'Tài liệu', 'checked' => false],
            'bang_ten' => ['label' => 'Bảng tên', 'checked' => false],
            'dung_cu_y_te' => ['label' => 'Dụng cụ y tế', 'checked' => false],
            've' => ['label' => 'Vé', 'checked' => false],
            'phong' => ['label' => 'Phòng', 'checked' => false],
            'xe' => ['label' => 'Xe/Phương tiện', 'checked' => false],
            'huong_dan_vien' => ['label' => 'Hướng dẫn viên', 'checked' => false],
            'thuc_an' => ['label' => 'Thức ăn/Đồ uống', 'checked' => false],
        ];

        // Cập nhật từ form data
        foreach ($defaultItems as $key => &$item) {
            $item['checked'] = isset($data['checklist_' . $key]) && $data['checklist_' . $key] == '1';
        }

        return $defaultItems;
    }

    /**
     * Kiểm tra xem tất cả items đã được tick chưa
     */
    private function areAllItemsChecked(array $checklistItems)
    {
        foreach ($checklistItems as $item) {
            if (!isset($item['checked']) || !$item['checked']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Parse checklist items từ JSON
     */
    public function parseChecklistItems($jsonString)
    {
        $items = json_decode($jsonString, true);
        return $items ?: [];
    }
}



