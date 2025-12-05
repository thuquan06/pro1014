<?php
/**
 * PretripChecklistModel - Quản lý checklist trước ngày đi
 * UC-Pretrip-Checklist: Checklist trước ngày đi cho tour
 * 
 * Features:
 * - Admin tạo/sửa/xóa checklist items
 * - HDV tick các mục đã hoàn thành
 * - Tracking ai tick mục nào - lúc nào
 * - Tự động chuyển trạng thái Ready khi 100% hoàn thành
 * - History/audit log
 */
class PretripChecklistModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTablesExist();
    }

    /**
     * Đảm bảo các bảng cần thiết tồn tại
     */
    private function ensureTablesExist()
    {
        try {
            // Tạo bảng pretrip_checklist nếu chưa có
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `pretrip_checklist` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_lich_khoi_hanh` INT(11) NULL DEFAULT NULL COMMENT 'ID lịch khởi hành',
                `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour (backup)',
                `checklist_items` TEXT NULL DEFAULT NULL COMMENT 'Checklist items dạng JSON (legacy)',
                `trang_thai` TINYINT(1) DEFAULT 0 COMMENT 'Trạng thái: 0=Chưa hoàn thành, 1=Ready',
                `trang_thai_ready` TINYINT(1) DEFAULT 0 COMMENT 'Đã duyệt Ready: 0=Chưa, 1=Đã duyệt',
                `nguoi_duyet_ready` INT(11) NULL DEFAULT NULL COMMENT 'ID admin duyệt Ready',
                `ngay_duyet_ready` DATETIME NULL DEFAULT NULL COMMENT 'Ngày duyệt Ready',
                `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú',
                `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                KEY `idx_lich_khoi_hanh` (`id_lich_khoi_hanh`),
                KEY `idx_tour` (`id_tour`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Checklist trước ngày đi'");

            // Tạo bảng pretrip_checklist_items - các mục checklist động
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `pretrip_checklist_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_checklist` INT(11) NOT NULL COMMENT 'ID checklist',
                `ten_muc` VARCHAR(255) NOT NULL COMMENT 'Tên mục checklist',
                `mo_ta` TEXT NULL DEFAULT NULL COMMENT 'Mô tả chi tiết',
                `da_hoan_thanh` TINYINT(1) DEFAULT 0 COMMENT 'Đã hoàn thành: 0=Chưa, 1=Đã',
                `nguoi_tick` INT(11) NULL DEFAULT NULL COMMENT 'ID người tick (HDV hoặc Admin)',
                `loai_nguoi_tick` ENUM('admin', 'guide') NULL DEFAULT NULL COMMENT 'Loại người tick',
                `ngay_tick` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tick',
                `thu_tu` INT(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị',
                `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                KEY `idx_checklist` (`id_checklist`),
                KEY `idx_da_hoan_thanh` (`da_hoan_thanh`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Các mục checklist'");

            // Tạo bảng pretrip_checklist_history - lịch sử thay đổi
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `pretrip_checklist_history` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_checklist` INT(11) NOT NULL COMMENT 'ID checklist',
                `id_item` INT(11) NULL DEFAULT NULL COMMENT 'ID item (nếu là thay đổi item)',
                `hanh_dong` VARCHAR(50) NOT NULL COMMENT 'Hành động: tick, untick, create_item, delete_item, update_item, approve_ready',
                `nguoi_thuc_hien` INT(11) NULL DEFAULT NULL COMMENT 'ID người thực hiện',
                `loai_nguoi_thuc_hien` ENUM('admin', 'guide') NULL DEFAULT NULL COMMENT 'Loại người thực hiện',
                `chi_tiet` TEXT NULL DEFAULT NULL COMMENT 'Chi tiết thay đổi (JSON)',
                `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                KEY `idx_checklist` (`id_checklist`),
                KEY `idx_item` (`id_item`),
                KEY `idx_ngay_tao` (`ngay_tao`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử checklist'");

            // Thêm các cột mới vào bảng pretrip_checklist nếu chưa có
            $columns = $this->conn->query("SHOW COLUMNS FROM pretrip_checklist")->fetchAll(PDO::FETCH_COLUMN);
            $columns = array_map('strtolower', $columns);
            
            if (!in_array('trang_thai_ready', $columns)) {
                $this->conn->exec("ALTER TABLE pretrip_checklist ADD COLUMN `trang_thai_ready` TINYINT(1) DEFAULT 0 COMMENT 'Đã duyệt Ready: 0=Chưa, 1=Đã duyệt' AFTER `trang_thai`");
            }
            if (!in_array('nguoi_duyet_ready', $columns)) {
                $this->conn->exec("ALTER TABLE pretrip_checklist ADD COLUMN `nguoi_duyet_ready` INT(11) NULL DEFAULT NULL COMMENT 'ID admin duyệt Ready' AFTER `trang_thai_ready`");
            }
            if (!in_array('ngay_duyet_ready', $columns)) {
                $this->conn->exec("ALTER TABLE pretrip_checklist ADD COLUMN `ngay_duyet_ready` DATETIME NULL DEFAULT NULL COMMENT 'Ngày duyệt Ready' AFTER `nguoi_duyet_ready`");
            }
            if (!in_array('id_tour', $columns)) {
                $this->conn->exec("ALTER TABLE pretrip_checklist ADD COLUMN `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour (backup)' AFTER `id_lich_khoi_hanh`");
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureTablesExist: " . $e->getMessage());
        }
    }
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

    /* ==================== CHECKLIST ITEMS MANAGEMENT ==================== */

    /**
     * Lấy tất cả items của một checklist
     */
    public function getChecklistItems($checklistId)
    {
        $sql = "SELECT ci.*, 
                       hdv.ho_ten AS ten_hdv,
                       admin.UserName AS ten_admin
                FROM pretrip_checklist_items ci
                LEFT JOIN huong_dan_vien hdv ON ci.nguoi_tick = hdv.id AND ci.loai_nguoi_tick = 'guide'
                LEFT JOIN admin ON ci.nguoi_tick = admin.id AND ci.loai_nguoi_tick = 'admin'
                WHERE ci.id_checklist = :checklist_id
                ORDER BY ci.thu_tu ASC, ci.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':checklist_id' => $checklistId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy item theo ID
     */
    public function getChecklistItemByID($itemId)
    {
        $sql = "SELECT * FROM pretrip_checklist_items WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo checklist item mới
     */
    public function createChecklistItem($checklistId, $tenMuc, $moTa = null, $thuTu = 0)
    {
        try {
            $sql = "INSERT INTO pretrip_checklist_items (
                        id_checklist, ten_muc, mo_ta, thu_tu, ngay_tao
                    ) VALUES (
                        :id_checklist, :ten_muc, :mo_ta, :thu_tu, NOW()
                    )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_checklist' => $checklistId,
                ':ten_muc' => $tenMuc,
                ':mo_ta' => $moTa,
                ':thu_tu' => $thuTu
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo checklist item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật checklist item
     */
    public function updateChecklistItem($itemId, $tenMuc, $moTa = null, $thuTu = null)
    {
        try {
            $sql = "UPDATE pretrip_checklist_items SET
                        ten_muc = :ten_muc,
                        mo_ta = :mo_ta";
            $params = [
                ':id' => $itemId,
                ':ten_muc' => $tenMuc,
                ':mo_ta' => $moTa
            ];
            
            if ($thuTu !== null) {
                $sql .= ", thu_tu = :thu_tu";
                $params[':thu_tu'] = $thuTu;
            }
            
            $sql .= ", ngay_cap_nhat = NOW() WHERE id = :id";
            $params[':id'] = $itemId;
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật checklist item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa checklist item
     */
    public function deleteChecklistItem($itemId)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM pretrip_checklist_items WHERE id = :id");
            return $stmt->execute([':id' => $itemId]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa checklist item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tick/Untick checklist item (cho HDV hoặc Admin)
     */
    public function toggleChecklistItem($itemId, $userId, $userType = 'guide', $checked = true)
    {
        try {
            $sql = "UPDATE pretrip_checklist_items SET
                        da_hoan_thanh = :da_hoan_thanh,
                        nguoi_tick = :nguoi_tick,
                        loai_nguoi_tick = :loai_nguoi_tick,
                        ngay_tick = NOW(),
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $itemId,
                ':da_hoan_thanh' => $checked ? 1 : 0,
                ':nguoi_tick' => $checked ? $userId : null,
                ':loai_nguoi_tick' => $checked ? $userType : null
            ]);

            if ($result) {
                // Ghi log
                $item = $this->getChecklistItemByID($itemId);
                $this->logHistory($item['id_checklist'], $itemId, $checked ? 'tick' : 'untick', $userId, $userType, [
                    'ten_muc' => $item['ten_muc'],
                    'checked' => $checked
                ]);

                // Kiểm tra xem tất cả items đã hoàn thành chưa
                $this->checkAndUpdateReadyStatus($item['id_checklist']);
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi toggle checklist item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra và cập nhật trạng thái Ready
     */
    public function checkAndUpdateReadyStatus($checklistId)
    {
        try {
            $items = $this->getChecklistItems($checklistId);
            $totalItems = count($items);
            $completedItems = 0;
            
            foreach ($items as $item) {
                if ($item['da_hoan_thanh'] == 1) {
                    $completedItems++;
                }
            }

            $allCompleted = ($totalItems > 0 && $completedItems == $totalItems);
            
            $sql = "UPDATE pretrip_checklist SET
                        trang_thai = :trang_thai,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $checklistId,
                ':trang_thai' => $allCompleted ? 1 : 0
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi checkAndUpdateReadyStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Duyệt trạng thái Ready (chỉ Admin)
     */
    public function approveReadyStatus($checklistId, $adminId)
    {
        try {
            // Kiểm tra xem tất cả items đã hoàn thành chưa
            $items = $this->getChecklistItems($checklistId);
            $totalItems = count($items);
            $completedItems = 0;
            
            foreach ($items as $item) {
                if ($item['da_hoan_thanh'] == 1) {
                    $completedItems++;
                }
            }

            if ($totalItems == 0) {
                return ['success' => false, 'message' => 'Checklist chưa có mục nào'];
            }

            if ($completedItems < $totalItems) {
                return ['success' => false, 'message' => 'Checklist chưa hoàn tất (' . $completedItems . '/' . $totalItems . ')'];
            }

            $sql = "UPDATE pretrip_checklist SET
                        trang_thai_ready = 1,
                        nguoi_duyet_ready = :admin_id,
                        ngay_duyet_ready = NOW(),
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $checklistId,
                ':admin_id' => $adminId
            ]);

            if ($result) {
                // Ghi log
                $this->logHistory($checklistId, null, 'approve_ready', $adminId, 'admin', [
                    'completed_items' => $completedItems,
                    'total_items' => $totalItems
                ]);
            }

            return ['success' => $result, 'message' => $result ? 'Đã duyệt Ready thành công' : 'Không thể duyệt Ready'];
        } catch (PDOException $e) {
            error_log("Lỗi approveReadyStatus: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    /**
     * Tính phần trăm hoàn thành
     */
    public function getCompletionPercentage($checklistId)
    {
        $items = $this->getChecklistItems($checklistId);
        $totalItems = count($items);
        if ($totalItems == 0) {
            return 0;
        }
        
        $completedItems = 0;
        foreach ($items as $item) {
            if ($item['da_hoan_thanh'] == 1) {
                $completedItems++;
            }
        }
        
        return round(($completedItems / $totalItems) * 100, 1);
    }

    /**
     * Kiểm tra xem HDV có được phân công cho tour/lịch khởi hành không
     */
    public function isGuideAssignedToDeparturePlan($guideId, $departurePlanId)
    {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM phan_cong_hdv
                    WHERE id_hdv = :guide_id AND id_lich_khoi_hanh = :departure_plan_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':guide_id' => $guideId,
                ':departure_plan_id' => $departurePlanId
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log("Lỗi isGuideAssignedToDeparturePlan: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy checklist theo tour ID
     */
    public function getChecklistByTourID($tourId)
    {
        $sql = "SELECT c.*, dp.ngay_khoi_hanh, dp.gio_khoi_hanh, g.tengoi
                FROM pretrip_checklist c
                LEFT JOIN lich_khoi_hanh dp ON c.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE c.id_tour = :tour_id OR dp.id_tour = :tour_id
                ORDER BY c.ngay_tao DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch sử checklist
     */
    public function getChecklistHistory($checklistId, $limit = 50)
    {
        $sql = "SELECT h.*,
                       hdv.ho_ten AS ten_hdv,
                       admin.UserName AS ten_admin
                FROM pretrip_checklist_history h
                LEFT JOIN huong_dan_vien hdv ON h.nguoi_thuc_hien = hdv.id AND h.loai_nguoi_thuc_hien = 'guide'
                LEFT JOIN admin ON h.nguoi_thuc_hien = admin.id AND h.loai_nguoi_thuc_hien = 'admin'
                WHERE h.id_checklist = :checklist_id
                ORDER BY h.ngay_tao DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':checklist_id', $checklistId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse chi_tiet JSON
        foreach ($results as &$result) {
            if (!empty($result['chi_tiet'])) {
                $result['chi_tiet'] = json_decode($result['chi_tiet'], true);
            }
        }
        
        return $results;
    }

    /**
     * Ghi log lịch sử
     */
    public function logHistory($checklistId, $itemId, $hanhDong, $nguoiThucHien, $loaiNguoiThucHien, $chiTiet = null)
    {
        try {
            $sql = "INSERT INTO pretrip_checklist_history (
                        id_checklist, id_item, hanh_dong, nguoi_thuc_hien, 
                        loai_nguoi_thuc_hien, chi_tiet, ngay_tao
                    ) VALUES (
                        :id_checklist, :id_item, :hanh_dong, :nguoi_thuc_hien,
                        :loai_nguoi_thuc_hien, :chi_tiet, NOW()
                    )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_checklist' => $checklistId,
                ':id_item' => $itemId,
                ':hanh_dong' => $hanhDong,
                ':nguoi_thuc_hien' => $nguoiThucHien,
                ':loai_nguoi_thuc_hien' => $loaiNguoiThucHien,
                ':chi_tiet' => $chiTiet ? json_encode($chiTiet, JSON_UNESCAPED_UNICODE) : null
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi logHistory: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật createChecklist để tự động tạo items mặc định
     */
    public function createChecklist(array $data)
    {
        try {
            // Lấy id_tour từ departure plan nếu có
            $idTour = $data['id_tour'] ?? null;
            if (!$idTour && !empty($data['id_lich_khoi_hanh'])) {
                $sql = "SELECT id_tour FROM lich_khoi_hanh WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':id' => $data['id_lich_khoi_hanh']]);
                $dp = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($dp) {
                    $idTour = $dp['id_tour'];
                }
            }

            $sql = "INSERT INTO pretrip_checklist (
                        id_lich_khoi_hanh, id_tour, checklist_items, trang_thai, 
                        ghi_chu, ngay_tao, ngay_cap_nhat
                    ) VALUES (
                        :id_lich_khoi_hanh, :id_tour, :checklist_items, :trang_thai,
                        :ghi_chu, NOW(), NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':id_tour' => $idTour,
                ':checklist_items' => isset($data['checklist_items']) ? json_encode($data['checklist_items'], JSON_UNESCAPED_UNICODE) : null,
                ':trang_thai' => $data['trang_thai'] ?? 0,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            $checklistId = $this->conn->lastInsertId();

            // Tạo các items mặc định nếu có items trong data
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $index => $item) {
                    $this->createChecklistItem(
                        $checklistId,
                        $item['ten_muc'] ?? $item['label'] ?? '',
                        $item['mo_ta'] ?? null,
                        $index
                    );
                }
            } elseif (empty($data['items'])) {
                // Tạo items mặc định
                $defaultItems = [
                    ['ten_muc' => 'Tài liệu', 'mo_ta' => 'Tài liệu tour, bản đồ, hướng dẫn'],
                    ['ten_muc' => 'Bảng tên', 'mo_ta' => 'Bảng tên đoàn, bảng chỉ dẫn'],
                    ['ten_muc' => 'Dụng cụ y tế', 'mo_ta' => 'Tủ thuốc, dụng cụ sơ cứu'],
                    ['ten_muc' => 'Vé', 'mo_ta' => 'Vé máy bay, vé tàu, vé tham quan'],
                    ['ten_muc' => 'Phòng', 'mo_ta' => 'Đặt phòng khách sạn, xác nhận'],
                    ['ten_muc' => 'Xe/Phương tiện', 'mo_ta' => 'Thuê xe, phương tiện di chuyển'],
                    ['ten_muc' => 'Hướng dẫn viên', 'mo_ta' => 'Phân công HDV, xác nhận'],
                    ['ten_muc' => 'Thức ăn/Đồ uống', 'mo_ta' => 'Đặt bữa ăn, đồ uống']
                ];
                foreach ($defaultItems as $index => $item) {
                    $this->createChecklistItem($checklistId, $item['ten_muc'], $item['mo_ta'], $index);
                }
            }

            // Ghi log
            $this->logHistory($checklistId, null, 'create_checklist', $data['nguoi_tao'] ?? null, 'admin', [
                'id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                'id_tour' => $idTour
            ]);

            return $checklistId;
        } catch (PDOException $e) {
            error_log("Lỗi tạo checklist: " . $e->getMessage());
            return false;
        }
    }
}



