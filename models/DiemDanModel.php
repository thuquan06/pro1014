<?php
/**
 * DiemDanModel - Quản lý điểm danh thành viên
 */
class DiemDanModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Đảm bảo bảng diem_dan tồn tại
     */
    private function ensureTableExists()
    {
        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'diem_dan'")->rowCount() > 0;
            
            if (!$tableExists) {
                $createTableSQL = "CREATE TABLE `diem_dan` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `id_booking` INT(11) NOT NULL COMMENT 'ID booking',
                    `id_thanh_vien` INT(11) NOT NULL COMMENT 'ID thành viên (từ booking_detail)',
                    `id_hdv` INT(11) NOT NULL COMMENT 'ID HDV điểm danh',
                    `id_lich_khoi_hanh` INT(11) NULL DEFAULT NULL COMMENT 'ID lịch trình',
                    `trang_thai` TINYINT(1) DEFAULT 1 COMMENT '1=Có mặt, 2=Vắng mặt, 3=Có lý do',
                    `thoi_gian_diem_dan` DATETIME NULL DEFAULT NULL COMMENT 'Thời gian điểm danh',
                    `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú',
                    `ngay_tao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                    `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                    KEY `idx_booking` (`id_booking`),
                    KEY `idx_thanh_vien` (`id_thanh_vien`),
                    KEY `idx_hdv` (`id_hdv`),
                    KEY `idx_lich_khoi_hanh` (`id_lich_khoi_hanh`),
                    KEY `idx_thoi_gian` (`thoi_gian_diem_dan`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng điểm danh thành viên'";
                $this->conn->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureTableExists DiemDanModel: " . $e->getMessage());
        }
    }

    /**
     * Lấy điểm danh theo booking
     */
    public function getDiemDanByBooking($id_booking, $id_lich_khoi_hanh = null)
    {
        try {
            $sql = "SELECT dd.*, 
                       bd.ho_ten as ten_thanh_vien,
                       hdv.ho_ten as ten_hdv,
                       hdv.so_dien_thoai as sdt_hdv
                    FROM diem_dan dd
                    INNER JOIN booking_detail bd ON dd.id_thanh_vien = bd.id
                    LEFT JOIN huong_dan_vien hdv ON dd.id_hdv = hdv.id
                    WHERE dd.id_booking = :id_booking";
            
            $params = [':id_booking' => $id_booking];
            
            if ($id_lich_khoi_hanh !== null) {
                $sql .= " AND dd.id_lich_khoi_hanh = :id_lich_khoi_hanh";
                $params[':id_lich_khoi_hanh'] = $id_lich_khoi_hanh;
            }
            
            $sql .= " ORDER BY dd.thoi_gian_diem_dan DESC, bd.id ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDiemDanByBooking DiemDanModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy điểm danh theo lịch trình
     */
    public function getDiemDanByLichKhoiHanh($id_lich_khoi_hanh)
    {
        try {
            $sql = "SELECT dd.*, 
                       bd.ho_ten as ten_thanh_vien,
                       bd.so_dien_thoai as sdt_thanh_vien,
                       b.ma_booking,
                       b.loai_booking,
                       hdv.ho_ten as ten_hdv
                    FROM diem_dan dd
                    INNER JOIN booking_detail bd ON dd.id_thanh_vien = bd.id
                    INNER JOIN booking b ON dd.id_booking = b.id
                    LEFT JOIN huong_dan_vien hdv ON dd.id_hdv = hdv.id
                    WHERE dd.id_lich_khoi_hanh = :id_lich_khoi_hanh
                    ORDER BY dd.thoi_gian_diem_dan DESC, b.ma_booking ASC, bd.id ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_lich_khoi_hanh' => $id_lich_khoi_hanh]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDiemDanByLichKhoiHanh DiemDanModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách thành viên cần điểm danh cho một lịch trình
     */
    public function getMembersForAttendance($id_lich_khoi_hanh)
    {
        try {
            $sql = "SELECT 
                       bd.id as id_thanh_vien,
                       bd.id_booking,
                       bd.ho_ten,
                       bd.gioi_tinh,
                       bd.ngay_sinh,
                       bd.so_dien_thoai,
                       bd.loai_khach,
                       b.ma_booking,
                       b.loai_booking,
                       lkh.ngay_khoi_hanh,
                       lkh.gio_khoi_hanh
                    FROM booking_detail bd
                    INNER JOIN booking b ON bd.id_booking = b.id
                    INNER JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                    WHERE b.id_lich_khoi_hanh = :id_lich_khoi_hanh
                      AND b.trang_thai NOT IN (5) -- Không lấy booking đã hủy
                    ORDER BY b.ma_booking ASC, bd.id ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_lich_khoi_hanh' => $id_lich_khoi_hanh]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMembersForAttendance DiemDanModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy điểm danh mới nhất của một thành viên trong một booking
     */
    public function getLatestDiemDan($id_booking, $id_thanh_vien, $id_lich_khoi_hanh = null)
    {
        try {
            $sql = "SELECT * FROM diem_dan 
                    WHERE id_booking = :id_booking 
                      AND id_thanh_vien = :id_thanh_vien";
            
            $params = [
                ':id_booking' => $id_booking,
                ':id_thanh_vien' => $id_thanh_vien
            ];
            
            if ($id_lich_khoi_hanh !== null) {
                $sql .= " AND id_lich_khoi_hanh = :id_lich_khoi_hanh";
                $params[':id_lich_khoi_hanh'] = $id_lich_khoi_hanh;
            }
            
            $sql .= " ORDER BY thoi_gian_diem_dan DESC LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLatestDiemDan DiemDanModel: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo điểm danh mới
     */
    public function createDiemDan($data)
    {
        try {
            $sql = "INSERT INTO diem_dan (
                        id_booking, id_thanh_vien, id_hdv, id_lich_khoi_hanh,
                        trang_thai, thoi_gian_diem_dan, ghi_chu
                    ) VALUES (
                        :id_booking, :id_thanh_vien, :id_hdv, :id_lich_khoi_hanh,
                        :trang_thai, :thoi_gian_diem_dan, :ghi_chu
                    )";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id_booking' => $data['id_booking'],
                ':id_thanh_vien' => $data['id_thanh_vien'],
                ':id_hdv' => $data['id_hdv'],
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
                ':thoi_gian_diem_dan' => $data['thoi_gian_diem_dan'] ?? date('Y-m-d H:i:s'),
                ':ghi_chu' => $data['ghi_chu'] ?? null
            ]);
            
            if ($result) {
                return ['success' => true, 'id' => $this->conn->lastInsertId()];
            }
            
            return ['success' => false, 'message' => 'Không thể tạo điểm danh'];
        } catch (PDOException $e) {
            error_log("Lỗi createDiemDan DiemDanModel: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Điểm danh nhiều thành viên cùng lúc
     */
    public function batchDiemDan($id_lich_khoi_hanh, $id_hdv, $attendanceData)
    {
        try {
            $this->conn->beginTransaction();
            
            $results = [];
            foreach ($attendanceData as $data) {
                $result = $this->createDiemDan([
                    'id_booking' => $data['id_booking'],
                    'id_thanh_vien' => $data['id_thanh_vien'],
                    'id_hdv' => $id_hdv,
                    'id_lich_khoi_hanh' => $id_lich_khoi_hanh,
                    'trang_thai' => $data['trang_thai'] ?? 1,
                    'thoi_gian_diem_dan' => date('Y-m-d H:i:s'),
                    'ghi_chu' => $data['ghi_chu'] ?? null
                ]);
                
                if (!$result['success']) {
                    $this->conn->rollBack();
                    return ['success' => false, 'message' => 'Lỗi khi điểm danh thành viên: ' . ($data['ho_ten'] ?? 'N/A')];
                }
                
                $results[] = $result;
            }
            
            $this->conn->commit();
            return ['success' => true, 'count' => count($results)];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi batchDiemDan DiemDanModel: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Lấy thống kê điểm danh theo lịch trình
     */
    public function getAttendanceStats($id_lich_khoi_hanh)
    {
        try {
            $sql = "SELECT 
                       COUNT(DISTINCT dd.id_thanh_vien) as tong_thanh_vien,
                       SUM(CASE WHEN dd.trang_thai = 1 THEN 1 ELSE 0 END) as co_mat,
                       SUM(CASE WHEN dd.trang_thai = 2 THEN 1 ELSE 0 END) as vang_mat,
                       SUM(CASE WHEN dd.trang_thai = 3 THEN 1 ELSE 0 END) as co_ly_do
                    FROM diem_dan dd
                    WHERE dd.id_lich_khoi_hanh = :id_lich_khoi_hanh
                      AND dd.thoi_gian_diem_dan >= DATE(NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_lich_khoi_hanh' => $id_lich_khoi_hanh]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceStats DiemDanModel: " . $e->getMessage());
            return [
                'tong_thanh_vien' => 0,
                'co_mat' => 0,
                'vang_mat' => 0,
                'co_ly_do' => 0
            ];
        }
    }

    /**
     * Kiểm tra HDV có quyền điểm danh lịch trình này không
     */
    public function canHdvAttend($id_hdv, $id_lich_khoi_hanh)
    {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM phan_cong_hdv 
                    WHERE id_hdv = :id_hdv 
                      AND id_lich_khoi_hanh = :id_lich_khoi_hanh
                      AND trang_thai = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_hdv' => $id_hdv,
                ':id_lich_khoi_hanh' => $id_lich_khoi_hanh
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (PDOException $e) {
            error_log("Lỗi canHdvAttend DiemDanModel: " . $e->getMessage());
            return false;
        }
    }
}

