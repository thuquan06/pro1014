<?php
/**
 * AssignmentModel - Quản lý Phân công HDV
 * UC-Assign-Guide: Phân công HDV với cảnh báo trùng lịch
 */
class AssignmentModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureColumns();
    }

    /**
     * Đảm bảo các cột cần thiết tồn tại
     */
    private function ensureColumns()
    {
        try {
            // Kiểm tra và thêm cột da_nhan
            $col = $this->conn->query("SHOW COLUMNS FROM phan_cong_hdv LIKE 'da_nhan'")->rowCount();
            if ($col === 0) {
                $this->conn->exec("ALTER TABLE phan_cong_hdv ADD COLUMN da_nhan TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'HDV đã nhận tour'");
            }
            
            // Kiểm tra và thêm cột so_dieu_hanh
            $col = $this->conn->query("SHOW COLUMNS FROM phan_cong_hdv LIKE 'so_dieu_hanh'")->rowCount();
            if ($col === 0) {
                $this->conn->exec("ALTER TABLE phan_cong_hdv ADD COLUMN so_dieu_hanh VARCHAR(20) NULL COMMENT 'Số điện thoại điều hành'");
            }
            
            // Kiểm tra và thêm cột so_khan_cap
            $col = $this->conn->query("SHOW COLUMNS FROM phan_cong_hdv LIKE 'so_khan_cap'")->rowCount();
            if ($col === 0) {
                $this->conn->exec("ALTER TABLE phan_cong_hdv ADD COLUMN so_khan_cap VARCHAR(20) NULL COMMENT 'Số điện thoại khẩn cấp'");
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureColumns phan_cong_hdv: " . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật số điều hành và số khẩn cấp
     */
    public function updateContactNumbers($id, $soDieuHanh, $soKhanCap)
    {
        try {
            $this->ensureColumns();
            $stmt = $this->conn->prepare("UPDATE phan_cong_hdv SET so_dieu_hanh = :so_dieu_hanh, so_khan_cap = :so_khan_cap, ngay_cap_nhat = NOW() WHERE id = :id");
            return $stmt->execute([
                ':so_dieu_hanh' => $soDieuHanh ?: null,
                ':so_khan_cap' => $soKhanCap ?: null,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateContactNumbers AssignmentModel: " . $e->getMessage());
            return false;
        }
    }
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

        // Filter theo tên tour
        if (!empty($filters['ten_tour'])) {
            $sql .= " AND g.tengoi LIKE :ten_tour";
            $params[':ten_tour'] = '%' . $filters['ten_tour'] . '%';
        }

        // Filter theo tên HDV
        if (!empty($filters['ten_hdv'])) {
            $sql .= " AND hdv.ho_ten LIKE :ten_hdv";
            $params[':ten_hdv'] = '%' . $filters['ten_hdv'] . '%';
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
    public function checkScheduleConflict($idHdv, $ngayBatDau, $ngayKetThuc, $excludeAssignmentId = null, $idLichKhoiHanh = null)
    {
        // Kiểm tra conflict dựa trên cả ngày của phân công VÀ ngày của lịch trình
        // Conflict xảy ra khi có bất kỳ overlap nào giữa 2 khoảng thời gian
        // Overlap: (start1 <= end2) AND (start2 <= end1)
        
        // Đảm bảo ngày không null
        if (empty($ngayBatDau) || empty($ngayKetThuc)) {
            return [];
        }
        
        $sql = "SELECT pc.*, 
                       dp.ngay_khoi_hanh, dp.ngay_ket_thuc AS dp_ngay_ket_thuc, dp.gio_khoi_hanh,
                       g.tengoi AS ten_tour,
                       -- Tính toán ngày thực tế để so sánh (ưu tiên ngày phân công, nếu không có thì dùng ngày lịch trình)
                       COALESCE(pc.ngay_bat_dau, dp.ngay_khoi_hanh) AS actual_start,
                       COALESCE(pc.ngay_ket_thuc, dp.ngay_ket_thuc) AS actual_end
                FROM phan_cong_hdv pc
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE pc.id_hdv = :id_hdv
                  AND pc.trang_thai = 1
                  AND (
                    -- Kiểm tra overlap dựa trên ngày thực tế (ngày phân công hoặc ngày lịch trình)
                    -- Overlap: (actual_start <= :ngay_ket_thuc) AND (:ngay_bat_dau <= actual_end)
                    (
                        COALESCE(pc.ngay_bat_dau, dp.ngay_khoi_hanh) IS NOT NULL 
                        AND COALESCE(pc.ngay_ket_thuc, dp.ngay_ket_thuc) IS NOT NULL
                        AND COALESCE(pc.ngay_bat_dau, dp.ngay_khoi_hanh) <= :ngay_ket_thuc 
                        AND :ngay_bat_dau <= COALESCE(pc.ngay_ket_thuc, dp.ngay_ket_thuc)
                    )
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

        // Loại trừ lịch trình hiện tại nếu đang tạo phân công cho cùng lịch trình
        if ($idLichKhoiHanh) {
            $sql .= " AND pc.id_lich_khoi_hanh != :id_lich_khoi_hanh";
            $params[':id_lich_khoi_hanh'] = $idLichKhoiHanh;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log để debug
        if (!empty($results)) {
            error_log("Conflict detected for HDV {$idHdv}: " . count($results) . " conflicts found");
            foreach ($results as $conflict) {
                error_log("  - Assignment ID: {$conflict['id']}, Lich khoi hanh: {$conflict['id_lich_khoi_hanh']}, Start: {$conflict['ngay_bat_dau']} or {$conflict['ngay_khoi_hanh']}, End: {$conflict['ngay_ket_thuc']} or {$conflict['dp_ngay_ket_thuc']}");
            }
        }
        
        return $results;
    }

    /**
     * Tạo phân công mới
     */
    public function createAssignment(array $data)
    {
        try {
            // Kiểm tra conflict trước khi tạo (nếu chưa được kiểm tra ở controller)
            if (isset($data['id_hdv']) && isset($data['ngay_bat_dau']) && isset($data['ngay_ket_thuc'])) {
                $conflicts = $this->checkScheduleConflict(
                    $data['id_hdv'],
                    $data['ngay_bat_dau'],
                    $data['ngay_ket_thuc'],
                    null,
                    $data['id_lich_khoi_hanh'] ?? null
                );
                
                if (!empty($conflicts)) {
                    error_log("Conflict detected when creating assignment: HDV {$data['id_hdv']} from {$data['ngay_bat_dau']} to {$data['ngay_ket_thuc']}");
                    return false; // Không cho phép tạo nếu có conflict
                }
            }
            
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
                ':trang_thai' => $data['trang_thai'] ?? 0, // Mặc định là Ready (0)
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

    /**
     * Xác nhận đã nhận tour
     */
    public function confirmReceived($id)
    {
        try {
            $this->ensureColumns();
            $stmt = $this->conn->prepare("UPDATE phan_cong_hdv SET da_nhan = 1, ngay_cap_nhat = NOW() WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi confirmReceived AssignmentModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Đặt trạng thái thủ công (0=Ready,1=Đang diễn ra,2=Hoàn thành)
     */
    public function setStatus($id, $status)
    {
        try {
            $this->ensureColumns();
            $stmt = $this->conn->prepare("UPDATE phan_cong_hdv SET trang_thai = :status, ngay_cap_nhat = NOW() WHERE id = :id");
            return $stmt->execute([
                ':id' => $id,
                ':status' => $status
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi setStatus phân công: " . $e->getMessage());
            return false;
        }
    }
}



