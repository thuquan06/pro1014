<?php
/**
 * AttendanceModel - Quản lý điểm danh tại điểm dừng nghỉ
 * UC-Attendance: Điểm danh khách hàng tại các điểm dừng nghỉ của tour
 */
class AttendanceModel extends BaseModel
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
            // Tạo bảng diem_danh_diem_nghi nếu chưa có
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `diem_danh_diem_nghi` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_phan_cong` INT(11) NULL DEFAULT NULL COMMENT 'ID phân công HDV',
                `id_lich_trinh` INT(11) NULL DEFAULT NULL COMMENT 'ID lịch trình (lichtrinhtheoday)',
                `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour (backup)',
                `diem_nghi` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Tên điểm nghỉ',
                `ngay_thu` INT(11) NULL DEFAULT NULL COMMENT 'Ngày thứ mấy của tour',
                `ngay_diem_danh` DATE NULL DEFAULT NULL COMMENT 'Ngày điểm danh',
                `gio_diem_danh` TIME NULL DEFAULT NULL COMMENT 'Giờ điểm danh',
                `so_nguoi_co_mat` INT(11) DEFAULT 0 COMMENT 'Số người có mặt',
                `so_nguoi_vang_mat` INT(11) DEFAULT 0 COMMENT 'Số người vắng mặt',
                `danh_sach_co_mat` TEXT NULL DEFAULT NULL COMMENT 'Danh sách người có mặt (JSON)',
                `danh_sach_vang_mat` TEXT NULL DEFAULT NULL COMMENT 'Danh sách người vắng mặt (JSON)',
                `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú',
                `id_hdv` INT(11) NULL DEFAULT NULL COMMENT 'ID HDV điểm danh',
                `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                KEY `idx_phan_cong` (`id_phan_cong`),
                KEY `idx_lich_trinh` (`id_lich_trinh`),
                KEY `idx_tour` (`id_tour`),
                KEY `idx_hdv` (`id_hdv`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Điểm danh tại điểm dừng nghỉ'");
        } catch (PDOException $e) {
            error_log("Lỗi ensureTablesExist AttendanceModel: " . $e->getMessage());
        }
    }

    /**
     * Tạo điểm danh mới
     */
    public function createAttendance(array $data)
    {
        try {
            $sql = "INSERT INTO diem_danh_diem_nghi (
                        id_phan_cong, id_lich_trinh, id_tour, diem_nghi, ngay_thu,
                        ngay_diem_danh, gio_diem_danh, so_nguoi_co_mat, so_nguoi_vang_mat,
                        danh_sach_co_mat, danh_sach_vang_mat, ghi_chu, id_hdv, ngay_tao
                    ) VALUES (
                        :id_phan_cong, :id_lich_trinh, :id_tour, :diem_nghi, :ngay_thu,
                        :ngay_diem_danh, :gio_diem_danh, :so_nguoi_co_mat, :so_nguoi_vang_mat,
                        :danh_sach_co_mat, :danh_sach_vang_mat, :ghi_chu, :id_hdv, NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id_phan_cong' => $data['id_phan_cong'] ?? null,
                ':id_lich_trinh' => $data['id_lich_trinh'] ?? null,
                ':id_tour' => $data['id_tour'] ?? null,
                ':diem_nghi' => $data['diem_nghi'] ?? null,
                ':ngay_thu' => $data['ngay_thu'] ?? null,
                ':ngay_diem_danh' => $data['ngay_diem_danh'] ?? date('Y-m-d'),
                ':gio_diem_danh' => $data['gio_diem_danh'] ?? date('H:i:s'),
                ':so_nguoi_co_mat' => $data['so_nguoi_co_mat'] ?? 0,
                ':so_nguoi_vang_mat' => $data['so_nguoi_vang_mat'] ?? 0,
                ':danh_sach_co_mat' => isset($data['danh_sach_co_mat']) ? json_encode($data['danh_sach_co_mat'], JSON_UNESCAPED_UNICODE) : null,
                ':danh_sach_vang_mat' => isset($data['danh_sach_vang_mat']) ? json_encode($data['danh_sach_vang_mat'], JSON_UNESCAPED_UNICODE) : null,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
                ':id_hdv' => $data['id_hdv'] ?? null,
            ]);

            return $result ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Lỗi createAttendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật điểm danh
     */
    public function updateAttendance($id, array $data)
    {
        try {
            $sql = "UPDATE diem_danh_diem_nghi SET
                        ngay_diem_danh = :ngay_diem_danh,
                        gio_diem_danh = :gio_diem_danh,
                        so_nguoi_co_mat = :so_nguoi_co_mat,
                        so_nguoi_vang_mat = :so_nguoi_vang_mat,
                        danh_sach_co_mat = :danh_sach_co_mat,
                        danh_sach_vang_mat = :danh_sach_vang_mat,
                        ghi_chu = :ghi_chu,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':ngay_diem_danh' => $data['ngay_diem_danh'] ?? date('Y-m-d'),
                ':gio_diem_danh' => $data['gio_diem_danh'] ?? date('H:i:s'),
                ':so_nguoi_co_mat' => $data['so_nguoi_co_mat'] ?? 0,
                ':so_nguoi_vang_mat' => $data['so_nguoi_vang_mat'] ?? 0,
                ':danh_sach_co_mat' => isset($data['danh_sach_co_mat']) ? json_encode($data['danh_sach_co_mat'], JSON_UNESCAPED_UNICODE) : null,
                ':danh_sach_vang_mat' => isset($data['danh_sach_vang_mat']) ? json_encode($data['danh_sach_vang_mat'], JSON_UNESCAPED_UNICODE) : null,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
                ':id' => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateAttendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy điểm danh theo ID
     */
    public function getAttendanceByID($id)
    {
        try {
            $sql = "SELECT dd.*, 
                           pc.id_hdv, pc.id_lich_khoi_hanh,
                           lt.ngay_thu, lt.noinghi, lt.tieude,
                           g.tengoi AS ten_tour
                    FROM diem_danh_diem_nghi dd
                    LEFT JOIN phan_cong_hdv pc ON dd.id_phan_cong = pc.id
                    LEFT JOIN lichtrinhtheoday lt ON dd.id_lich_trinh = lt.id
                    LEFT JOIN goidulich g ON dd.id_tour = g.id_goi
                    WHERE dd.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $result['danh_sach_co_mat'] = $this->parseJsonArray($result['danh_sach_co_mat'] ?? '[]');
                $result['danh_sach_vang_mat'] = $this->parseJsonArray($result['danh_sach_vang_mat'] ?? '[]');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceByID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả điểm danh theo phân công
     */
    public function getAttendancesByAssignmentID($assignmentId)
    {
        try {
            $sql = "SELECT dd.*, 
                           lt.ngay_thu, lt.noinghi, lt.tieude,
                           g.tengoi AS ten_tour
                    FROM diem_danh_diem_nghi dd
                    LEFT JOIN lichtrinhtheoday lt ON dd.id_lich_trinh = lt.id
                    LEFT JOIN goidulich g ON dd.id_tour = g.id_goi
                    WHERE dd.id_phan_cong = :id_phan_cong
                    ORDER BY dd.ngay_thu ASC, dd.ngay_diem_danh ASC, dd.gio_diem_danh ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_phan_cong' => $assignmentId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as &$result) {
                $result['danh_sach_co_mat'] = $this->parseJsonArray($result['danh_sach_co_mat'] ?? '[]');
                $result['danh_sach_vang_mat'] = $this->parseJsonArray($result['danh_sach_vang_mat'] ?? '[]');
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi getAttendancesByAssignmentID: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy điểm danh theo lịch trình
     */
    public function getAttendanceByScheduleID($scheduleId, $assignmentId)
    {
        try {
            $sql = "SELECT * FROM diem_danh_diem_nghi 
                    WHERE id_lich_trinh = :id_lich_trinh 
                      AND id_phan_cong = :id_phan_cong
                    ORDER BY ngay_diem_danh DESC, gio_diem_danh DESC
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_lich_trinh' => $scheduleId,
                ':id_phan_cong' => $assignmentId
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $result['danh_sach_co_mat'] = $this->parseJsonArray($result['danh_sach_co_mat'] ?? '[]');
                $result['danh_sach_vang_mat'] = $this->parseJsonArray($result['danh_sach_vang_mat'] ?? '[]');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceByScheduleID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra quyền truy cập của HDV
     */
    public function checkGuideAccess($attendanceId, $guideId)
    {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM diem_danh_diem_nghi dd
                    LEFT JOIN phan_cong_hdv pc ON dd.id_phan_cong = pc.id
                    WHERE dd.id = :attendance_id AND pc.id_hdv = :guide_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':attendance_id' => $attendanceId,
                ':guide_id' => $guideId
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log("Lỗi checkGuideAccess: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa điểm danh
     */
    public function deleteAttendance($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM diem_danh_diem_nghi WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteAttendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Parse JSON string thành mảng
     */
    private function parseJsonArray($jsonString)
    {
        if (empty($jsonString)) {
            return [];
        }
        $decoded = json_decode($jsonString, true);
        return $decoded ?: [];
    }
}

