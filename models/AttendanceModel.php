<?php
/**
 * AttendanceModel - Quản lý điểm danh thành viên
 */
class AttendanceModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Đảm bảo bảng booking_attendance tồn tại
     */
    private function ensureTableExists()
    {
        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'booking_attendance'")->rowCount() > 0;
            
            if (!$tableExists) {
                $createTableSQL = "CREATE TABLE `booking_attendance` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `id_booking` INT(11) NOT NULL COMMENT 'ID booking',
                    `id_booking_detail` INT(11) NOT NULL COMMENT 'ID thành viên trong booking_detail',
                    `id_hdv` INT(11) NOT NULL COMMENT 'ID hướng dẫn viên điểm danh',
                    `id_lich_khoi_hanh` INT(11) NULL DEFAULT NULL COMMENT 'ID lịch khởi hành',
                    `ngay_diem_danh` DATE NOT NULL COMMENT 'Ngày điểm danh',
                    `gio_diem_danh` TIME NULL DEFAULT NULL COMMENT 'Giờ điểm danh',
                    `trang_thai` TINYINT(1) DEFAULT 1 COMMENT '1=Có mặt, 0=Vắng mặt',
                    `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú',
                    `ngay_tao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                    `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                    KEY `idx_booking` (`id_booking`),
                    KEY `idx_booking_detail` (`id_booking_detail`),
                    KEY `idx_hdv` (`id_hdv`),
                    KEY `idx_lich_khoi_hanh` (`id_lich_khoi_hanh`),
                    KEY `idx_ngay_diem_danh` (`ngay_diem_danh`),
                    UNIQUE KEY `unique_attendance` (`id_booking_detail`, `id_lich_khoi_hanh`, `ngay_diem_danh`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng điểm danh thành viên'";
                $this->conn->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureTableExists AttendanceModel: " . $e->getMessage());
        }
    }

    /**
     * Lấy điểm danh theo booking
     */
    public function getAttendanceByBooking($id_booking, $id_lich_khoi_hanh = null)
    {
        try {
            $sql = "SELECT 
                        a.*,
                        bd.ho_ten,
                        bd.so_dien_thoai,
                        hdv.ho_ten as ten_hdv
                    FROM booking_attendance a
                    INNER JOIN booking_detail bd ON a.id_booking_detail = bd.id
                    LEFT JOIN huong_dan_vien hdv ON a.id_hdv = hdv.id
                    WHERE a.id_booking = :id_booking";
            
            $params = [':id_booking' => $id_booking];
            
            if ($id_lich_khoi_hanh !== null) {
                $sql .= " AND a.id_lich_khoi_hanh = :id_lich_khoi_hanh";
                $params[':id_lich_khoi_hanh'] = $id_lich_khoi_hanh;
            }
            
            $sql .= " ORDER BY a.ngay_diem_danh DESC, a.gio_diem_danh DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceByBooking AttendanceModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy điểm danh theo lịch khởi hành
     */
    public function getAttendanceByDeparturePlan($id_lich_khoi_hanh, $ngay_diem_danh = null)
    {
        try {
            $sql = "SELECT 
                        a.*,
                        bd.ho_ten,
                        bd.so_dien_thoai,
                        bd.loai_khach,
                        b.ma_booking,
                        hdv.ho_ten as ten_hdv
                    FROM booking_attendance a
                    INNER JOIN booking_detail bd ON a.id_booking_detail = bd.id
                    INNER JOIN booking b ON a.id_booking = b.id
                    LEFT JOIN huong_dan_vien hdv ON a.id_hdv = hdv.id
                    WHERE a.id_lich_khoi_hanh = :id_lich_khoi_hanh";
            
            $params = [':id_lich_khoi_hanh' => $id_lich_khoi_hanh];
            
            if ($ngay_diem_danh !== null) {
                $sql .= " AND a.ngay_diem_danh = :ngay_diem_danh";
                $params[':ngay_diem_danh'] = $ngay_diem_danh;
            }
            
            $sql .= " ORDER BY b.ma_booking ASC, bd.ho_ten ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceByDeparturePlan AttendanceModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Điểm danh thành viên
     */
    public function markAttendance($data)
    {
        try {
            // Kiểm tra xem đã điểm danh chưa
            $existing = $this->getAttendanceByMemberAndDate(
                $data['id_booking_detail'],
                $data['id_lich_khoi_hanh'] ?? null,
                $data['ngay_diem_danh']
            );
            
            if ($existing) {
                // Cập nhật điểm danh đã có
                $sql = "UPDATE booking_attendance SET
                            id_hdv = :id_hdv,
                            trang_thai = :trang_thai,
                            gio_diem_danh = :gio_diem_danh,
                            ghi_chu = :ghi_chu,
                            ngay_cap_nhat = NOW()
                        WHERE id = :id";
                
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([
                    ':id' => $existing['id'],
                    ':id_hdv' => $data['id_hdv'],
                    ':trang_thai' => $data['trang_thai'] ?? 1,
                    ':gio_diem_danh' => $data['gio_diem_danh'] ?? date('H:i:s'),
                    ':ghi_chu' => $data['ghi_chu'] ?? null
                ]);
            } else {
                // Tạo điểm danh mới
                $sql = "INSERT INTO booking_attendance (
                            id_booking, id_booking_detail, id_hdv, id_lich_khoi_hanh,
                            ngay_diem_danh, gio_diem_danh, trang_thai, ghi_chu
                        ) VALUES (
                            :id_booking, :id_booking_detail, :id_hdv, :id_lich_khoi_hanh,
                            :ngay_diem_danh, :gio_diem_danh, :trang_thai, :ghi_chu
                        )";
                
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([
                    ':id_booking' => $data['id_booking'],
                    ':id_booking_detail' => $data['id_booking_detail'],
                    ':id_hdv' => $data['id_hdv'],
                    ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'] ?? null,
                    ':ngay_diem_danh' => $data['ngay_diem_danh'],
                    ':gio_diem_danh' => $data['gio_diem_danh'] ?? date('H:i:s'),
                    ':trang_thai' => $data['trang_thai'] ?? 1,
                    ':ghi_chu' => $data['ghi_chu'] ?? null
                ]);
            }
        } catch (PDOException $e) {
            error_log("Lỗi markAttendance AttendanceModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy điểm danh theo thành viên và ngày
     */
    private function getAttendanceByMemberAndDate($id_booking_detail, $id_lich_khoi_hanh, $ngay_diem_danh)
    {
        try {
            $sql = "SELECT * FROM booking_attendance 
                    WHERE id_booking_detail = :id_booking_detail 
                    AND ngay_diem_danh = :ngay_diem_danh";
            
            $params = [
                ':id_booking_detail' => $id_booking_detail,
                ':ngay_diem_danh' => $ngay_diem_danh
            ];
            
            if ($id_lich_khoi_hanh !== null) {
                $sql .= " AND id_lich_khoi_hanh = :id_lich_khoi_hanh";
                $params[':id_lich_khoi_hanh'] = $id_lich_khoi_hanh;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceByMemberAndDate AttendanceModel: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Điểm danh hàng loạt
     */
    public function markAttendanceBatch($id_lich_khoi_hanh, $id_hdv, $ngay_diem_danh, $attendanceList)
    {
        try {
            $this->conn->beginTransaction();
            
            foreach ($attendanceList as $item) {
                $data = [
                    'id_booking' => $item['id_booking'],
                    'id_booking_detail' => $item['id_booking_detail'],
                    'id_hdv' => $id_hdv,
                    'id_lich_khoi_hanh' => $id_lich_khoi_hanh,
                    'ngay_diem_danh' => $ngay_diem_danh,
                    'trang_thai' => $item['trang_thai'] ?? 1,
                    'ghi_chu' => $item['ghi_chu'] ?? null
                ];
                
                if (!$this->markAttendance($data)) {
                    throw new Exception("Không thể điểm danh thành viên ID: " . $item['id_booking_detail']);
                }
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi markAttendanceBatch AttendanceModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thống kê điểm danh theo booking
     */
    public function getAttendanceStatsByBooking($id_booking)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as tong_so,
                        SUM(CASE WHEN trang_thai = 1 THEN 1 ELSE 0 END) as co_mat,
                        SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END) as vang_mat
                    FROM booking_attendance
                    WHERE id_booking = :id_booking";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAttendanceStatsByBooking AttendanceModel: " . $e->getMessage());
            return ['tong_so' => 0, 'co_mat' => 0, 'vang_mat' => 0];
        }
    }
}

