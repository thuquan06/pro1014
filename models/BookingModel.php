<?php
/**
 * BookingModel - Quản lý Booking
 * UC-View-Booking, UC-Create-Booking, UC-Update-Booking
 */
class BookingModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Đảm bảo bảng booking tồn tại
     */
    private function ensureTableExists()
    {
        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'booking'")->rowCount() > 0;
            
            // Nếu bảng đã tồn tại, kiểm tra và thêm các cột mới nếu chưa có
            if ($tableExists) {
                $this->ensureColumnsExist();
            }
            
            if (!$tableExists) {
                $createTableSQL = "CREATE TABLE `booking` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `ma_booking` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã booking tự động',
                    `id_lich_khoi_hanh` INT(11) NULL DEFAULT NULL COMMENT 'ID lịch khởi hành',
                    `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour (backup)',
                    `id_hdv` INT(11) NULL DEFAULT NULL COMMENT 'ID hướng dẫn viên được phân công',
                    `ho_ten` VARCHAR(255) NOT NULL COMMENT 'Họ tên khách hàng',
                    `so_dien_thoai` VARCHAR(20) NOT NULL COMMENT 'Số điện thoại',
                    `email` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Email',
                    `so_nguoi_lon` INT(11) DEFAULT 0 COMMENT 'Số người lớn',
                    `so_tre_em` INT(11) DEFAULT 0 COMMENT 'Số trẻ em',
                    `so_tre_nho` INT(11) DEFAULT 0 COMMENT 'Số trẻ nhỏ',
                    `so_em_be` INT(11) DEFAULT 0 COMMENT 'Số em bé',
                    `loai_booking` TINYINT(1) DEFAULT 1 COMMENT '1=Cá nhân, 2=Gia đình, 3=Nhóm, 4=Đoàn',
                    `dia_chi` TEXT NULL DEFAULT NULL COMMENT 'Địa chỉ khách hàng',
                    `tong_tien` DECIMAL(15,2) DEFAULT 0 COMMENT 'Tổng tiền',
                    `tien_dat_coc` DECIMAL(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc',
                    `trang_thai` TINYINT(1) DEFAULT 0 COMMENT '0=Chờ xử lý, 2=Đã đặt cọc, 3=Đã thanh toán, 4=Hoàn thành, 5=Hủy',
                    `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú',
                    `nguoi_tao` INT(11) NULL DEFAULT NULL COMMENT 'ID admin tạo booking',
                    `ngay_dat` DATETIME NULL DEFAULT NULL COMMENT 'Ngày đặt',
                    `ngay_thanh_toan` DATETIME NULL DEFAULT NULL COMMENT 'Ngày thanh toán',
                    `ngay_tao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                    `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                    KEY `idx_lich_khoi_hanh` (`id_lich_khoi_hanh`),
                    KEY `idx_tour` (`id_tour`),
                    KEY `idx_hdv` (`id_hdv`),
                    KEY `idx_trang_thai` (`trang_thai`),
                    KEY `idx_ngay_dat` (`ngay_dat`),
                    KEY `idx_ma_booking` (`ma_booking`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng booking'";
                $this->conn->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureTableExists BookingModel: " . $e->getMessage());
        }
    }

    /**
     * Đảm bảo các cột mới tồn tại trong bảng booking
     */
    private function ensureColumnsExist()
    {
        try {
            $columns = $this->conn->query("SHOW COLUMNS FROM booking")->fetchAll(PDO::FETCH_COLUMN);
            
            // Thêm cột dia_chi nếu chưa có
            if (!in_array('dia_chi', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `dia_chi` TEXT NULL DEFAULT NULL COMMENT 'Địa chỉ khách hàng' AFTER `email`");
            }
            
            // Thêm cột tien_dat_coc nếu chưa có
            if (!in_array('tien_dat_coc', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `tien_dat_coc` DECIMAL(15,2) DEFAULT 0 COMMENT 'Số tiền đặt cọc' AFTER `tong_tien`");
            }

            // Thêm cột voucher nếu chưa có
            if (!in_array('voucher_id', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `voucher_id` INT(11) NULL DEFAULT NULL COMMENT 'ID voucher áp dụng' AFTER `id_tour`");
            }
            if (!in_array('voucher_code', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `voucher_code` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Mã voucher' AFTER `voucher_id`");
            }
            if (!in_array('voucher_discount', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `voucher_discount` DECIMAL(15,2) DEFAULT 0 COMMENT 'Số tiền giảm' AFTER `tong_tien`");
            }
            
            // Thêm cột ngay_thanh_toan nếu chưa có
            if (!in_array('ngay_thanh_toan', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `ngay_thanh_toan` DATETIME NULL DEFAULT NULL COMMENT 'Ngày thanh toán' AFTER `ngay_dat`");
            }
            
            // Thêm cột trang_thai_hoa_don nếu chưa có
            if (!in_array('trang_thai_hoa_don', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `trang_thai_hoa_don` TINYINT(1) DEFAULT 0 COMMENT '0=Chưa xuất, 1=Đã xuất, 2=Đã gửi, 3=Hủy' AFTER `trang_thai`");
            }
            
            // Thêm cột vai_tro nếu chưa có
            if (!in_array('vai_tro', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `vai_tro` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Vai trò của HDV (HDV chính, HDV phụ, Trợ lý)' AFTER `id_hdv`");
            }
            
            // Đảm bảo bảng booking_hdv tồn tại để lưu nhiều HDV cho một booking
            $this->ensureBookingHdvTableExists();
            
            // Thêm cột loai_booking nếu chưa có
            if (!in_array('loai_booking', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `loai_booking` TINYINT(1) DEFAULT 1 COMMENT '1=Cá nhân, 2=Gia đình, 3=Nhóm, 4=Đoàn' AFTER `so_em_be`");
            }
            
            // Thêm cột id_hdv nếu chưa có
            if (!in_array('id_hdv', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `id_hdv` INT(11) NULL DEFAULT NULL COMMENT 'ID hướng dẫn viên được phân công' AFTER `id_tour`");
                $this->conn->exec("ALTER TABLE booking ADD KEY `idx_hdv` (`id_hdv`)");
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureColumnsExist BookingModel: " . $e->getMessage());
        }
        
        // Đảm bảo bảng booking_detail tồn tại
        $this->ensureBookingDetailTableExists();
        
        // Đảm bảo bảng booking_hdv tồn tại
        $this->ensureBookingHdvTableExists();
    }
    
    /**
     * Đảm bảo bảng booking_hdv tồn tại để lưu nhiều HDV cho một booking
     */
    private function ensureBookingHdvTableExists()
    {
        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'booking_hdv'")->rowCount() > 0;
            
            if (!$tableExists) {
                $createTableSQL = "CREATE TABLE `booking_hdv` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `id_booking` INT(11) NOT NULL COMMENT 'ID booking',
                    `id_hdv` INT(11) NOT NULL COMMENT 'ID hướng dẫn viên',
                    `vai_tro` VARCHAR(50) NOT NULL DEFAULT 'HDV chính' COMMENT 'Vai trò (HDV chính, HDV phụ, Trợ lý)',
                    `ngay_tao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                    KEY `idx_booking` (`id_booking`),
                    KEY `idx_hdv` (`id_hdv`),
                    FOREIGN KEY (`id_booking`) REFERENCES `booking`(`id`) ON DELETE CASCADE,
                    FOREIGN KEY (`id_hdv`) REFERENCES `huong_dan_vien`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu nhiều HDV cho một booking'";
                $this->conn->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureBookingHdvTableExists: " . $e->getMessage());
        }
    }
    
    /**
     * Đảm bảo bảng booking_detail tồn tại
     */
    private function ensureBookingDetailTableExists()
    {
        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'booking_detail'")->rowCount() > 0;
            if (!$tableExists) {
                $createTableSQL = "CREATE TABLE `booking_detail` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `id_booking` INT(11) NOT NULL COMMENT 'ID booking',
                    `ho_ten` VARCHAR(255) NOT NULL COMMENT 'Họ tên khách',
                    `gioi_tinh` TINYINT(1) NULL DEFAULT NULL COMMENT '0=Nữ, 1=Nam',
                    `ngay_sinh` DATE NULL DEFAULT NULL COMMENT 'Ngày sinh',
                    `so_cmnd_cccd` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Số CMND/CCCD',
                    `so_dien_thoai` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Số điện thoại',
                    `loai_khach` TINYINT(1) DEFAULT 1 COMMENT '1=Người lớn, 2=Trẻ em, 3=Trẻ nhỏ, 4=Em bé',
                    `ngay_tao` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                    KEY `idx_booking` (`id_booking`),
                    FOREIGN KEY (`id_booking`) REFERENCES `booking`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng chi tiết khách trong booking'";
                $this->conn->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureBookingDetailTableExists BookingModel: " . $e->getMessage());
        }
    }

    /**
     * Tạo mã booking tự động (BK + YYYYMMDD + số thứ tự)
     */
    private function generateBookingCode()
    {
        $prefix = 'BK' . date('Ymd');
        $sql = "SELECT COUNT(*) as count FROM booking WHERE ma_booking LIKE :prefix";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':prefix' => $prefix . '%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'] ?? 0;
        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $sequence;
    }

    /**
     * Lấy tất cả bookings với filter
     */
    public function getAllBookings($filters = [])
    {
        $sql = "SELECT b.*, 
                       lkh.ngay_khoi_hanh, 
                       lkh.gia_nguoi_lon, 
                       lkh.gia_tre_em, 
                       lkh.gia_tre_nho,
                       g.tengoi as ten_tour,
                       g.mato as ma_tour,
                       hdv.ho_ten as ten_hdv,
                       hdv.so_dien_thoai as sdt_hdv,
                       hdv.email as email_hdv
                FROM booking b
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi
                LEFT JOIN huong_dan_vien hdv ON b.id_hdv = hdv.id
                WHERE 1=1";
        $params = [];

        // Filter theo tour
        if (!empty($filters['id_tour'])) {
            $sql .= " AND b.id_tour = :id_tour";
            $params[':id_tour'] = $filters['id_tour'];
        }

        // Filter theo trạng thái
        if (isset($filters['trang_thai']) && $filters['trang_thai'] !== '') {
            $sql .= " AND b.trang_thai = :trang_thai";
            $params[':trang_thai'] = (int)$filters['trang_thai'];
        }

        // Filter theo tên khách
        if (!empty($filters['ho_ten'])) {
            $sql .= " AND b.ho_ten LIKE :ho_ten";
            $params[':ho_ten'] = '%' . $filters['ho_ten'] . '%';
        }

        $sql .= " ORDER BY b.ngay_dat DESC, b.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy booking theo ID
     */
    public function getBookingById($id)
    {
        $sql = "SELECT b.*, 
                       lkh.ngay_khoi_hanh, 
                       lkh.ngay_ket_thuc,
                       lkh.gio_khoi_hanh,
                       lkh.gio_tap_trung,
                       lkh.diem_tap_trung,
                       lkh.gia_nguoi_lon, 
                       lkh.gia_tre_em, 
                       lkh.gia_tre_nho,
                       lkh.so_cho,
                       lkh.so_cho_da_dat,
                       lkh.so_cho_con_lai,
                       g.tengoi as ten_tour,
                       g.mato as ma_tour,
                       g.noixuatphat as noi_xuat_phat,
                       hdv.ho_ten as ten_hdv,
                       hdv.so_dien_thoai as sdt_hdv,
                       hdv.email as email_hdv
                FROM booking b
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi
                LEFT JOIN huong_dan_vien hdv ON b.id_hdv = hdv.id
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra số chỗ còn lại của lịch khởi hành
     */
    public function checkAvailableSeats($id_lich_khoi_hanh, $so_nguoi = 0)
    {
        $sql = "SELECT so_cho, so_cho_da_dat, so_cho_con_lai 
                FROM lich_khoi_hanh 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_lich_khoi_hanh]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$plan) {
            return ['available' => false, 'message' => 'Không tìm thấy lịch khởi hành'];
        }

        $so_cho_con_lai = $plan['so_cho_con_lai'] ?? ($plan['so_cho'] - $plan['so_cho_da_dat']);
        
        if ($so_cho_con_lai < $so_nguoi) {
            return [
                'available' => false, 
                'message' => "Chỉ còn {$so_cho_con_lai} chỗ. Không đủ số chỗ yêu cầu ({$so_nguoi} chỗ).",
                'so_cho_con_lai' => $so_cho_con_lai
            ];
        }

        return [
            'available' => true,
            'so_cho_con_lai' => $so_cho_con_lai,
            'so_cho' => $plan['so_cho'],
            'so_cho_da_dat' => $plan['so_cho_da_dat']
        ];
    }

    /**
     * Tính tổng tiền booking
     */
    public function calculateTotal($id_lich_khoi_hanh, $so_nguoi_lon, $so_tre_em, $so_tre_nho)
    {
        $sql = "SELECT gia_nguoi_lon, gia_tre_em, gia_tre_nho 
                FROM lich_khoi_hanh 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_lich_khoi_hanh]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$plan) {
            return 0;
        }

        $total = 0;
        $total += ($so_nguoi_lon ?? 0) * ($plan['gia_nguoi_lon'] ?? 0);
        $total += ($so_tre_em ?? 0) * ($plan['gia_tre_em'] ?? 0);
        $total += ($so_tre_nho ?? 0) * ($plan['gia_tre_nho'] ?? 0);

        return $total;
    }

    /**
     * Tạo booking mới
     */
    public function createBooking($data)
    {
        try {
            // Validate required fields
            if (empty($data['id_lich_khoi_hanh']) || empty($data['ho_ten']) || empty($data['so_dien_thoai'])) {
                return ['success' => false, 'message' => 'Thiếu thông tin bắt buộc'];
            }

            $so_nguoi = ($data['so_nguoi_lon'] ?? 0) + ($data['so_tre_em'] ?? 0) + ($data['so_tre_nho'] ?? 0);
            
            // Kiểm tra số chỗ
            $seatCheck = $this->checkAvailableSeats($data['id_lich_khoi_hanh'], $so_nguoi);
            if (!$seatCheck['available']) {
                return ['success' => false, 'message' => $seatCheck['message']];
            }

            // Tính tổng tiền
            $tong_tien = $this->calculateTotal(
                $data['id_lich_khoi_hanh'],
                $data['so_nguoi_lon'] ?? 0,
                $data['so_tre_em'] ?? 0,
                $data['so_tre_nho'] ?? 0
            );

            // Lấy id_tour từ lịch khởi hành
            $sql = "SELECT id_tour FROM lich_khoi_hanh WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $data['id_lich_khoi_hanh']]);
            $plan = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_tour = $plan['id_tour'] ?? null;

            // Tạo mã booking
            $ma_booking = $this->generateBookingCode();

            // Validate loại booking
            $loai_booking = $data['loai_booking'] ?? 1; // Mặc định: Cá nhân
            if (!in_array($loai_booking, [1, 2, 3, 4])) {
                $loai_booking = 1;
            }
            
            // Validate danh sách khách cho nhóm/đoàn
            $danh_sach_khach = $data['danh_sach_khach'] ?? [];
            if (in_array($loai_booking, [3, 4]) && !empty($danh_sach_khach)) {
                // Kiểm tra số lượng khách nhập phải khớp tổng số khách
                $so_khach_nhap = count($danh_sach_khach);
                if ($so_khach_nhap != $so_nguoi) {
                    return ['success' => false, 'message' => "Số lượng khách trong danh sách ({$so_khach_nhap}) không khớp với tổng số khách ({$so_nguoi})"];
                }
            }

            // Tạo booking
            $sql = "INSERT INTO booking (
                        ma_booking, id_lich_khoi_hanh, id_tour, id_hdv, vai_tro, voucher_id, voucher_code,
                        ho_ten, so_dien_thoai, email, dia_chi,
                        so_nguoi_lon, so_tre_em, so_tre_nho, loai_booking, tong_tien, voucher_discount, tien_dat_coc,
                        trang_thai, ghi_chu, nguoi_tao, ngay_dat
                    ) VALUES (
                        :ma_booking, :id_lich_khoi_hanh, :id_tour, :id_hdv, :vai_tro, :voucher_id, :voucher_code,
                        :ho_ten, :so_dien_thoai, :email, :dia_chi,
                        :so_nguoi_lon, :so_tre_em, :so_tre_nho, :loai_booking, :tong_tien, :voucher_discount, :tien_dat_coc,
                        :trang_thai, :ghi_chu, :nguoi_tao, NOW()
                    )";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':ma_booking' => $ma_booking,
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'],
                ':id_tour' => $id_tour,
                ':id_hdv' => isset($data['id_hdv']) && $data['id_hdv'] !== '' ? (int)$data['id_hdv'] : null,
                ':vai_tro' => $data['vai_tro'] ?? null,
                ':voucher_id' => $data['voucher_id'] ?? null,
                ':voucher_code' => $data['voucher_code'] ?? null,
                ':ho_ten' => $data['ho_ten'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':email' => $data['email'] ?? null,
                ':dia_chi' => $data['dia_chi'] ?? null,
                ':so_nguoi_lon' => $data['so_nguoi_lon'] ?? 0,
                ':so_tre_em' => $data['so_tre_em'] ?? 0,
                ':so_tre_nho' => $data['so_tre_nho'] ?? 0,
                ':loai_booking' => $loai_booking,
                ':tong_tien' => $data['tong_tien_override'] ?? $tong_tien,
                ':voucher_discount' => $data['voucher_discount'] ?? 0,
                ':tien_dat_coc' => $data['tien_dat_coc'] ?? 0,
                ':trang_thai' => 0, // Mặc định: Chờ xử lý (Đã tạo bởi Admin)
                ':ghi_chu' => $data['ghi_chu'] ?? null,
                ':nguoi_tao' => $_SESSION['aid'] ?? null
            ]);

            if (!$result) {
                return ['success' => false, 'message' => 'Không thể tạo booking'];
            }

            $booking_id = $this->conn->lastInsertId();

            // Lưu danh sách khách nếu là nhóm/đoàn
            if (in_array($loai_booking, [3, 4]) && !empty($danh_sach_khach)) {
                $this->saveBookingDetails($booking_id, $danh_sach_khach);
            } elseif ($loai_booking == 1) {
                // Nếu là booking cá nhân, tự động tạo thành viên từ thông tin người đăng ký
                $memberData = [
                    'ho_ten' => $data['ho_ten'],
                    'so_dien_thoai' => $data['so_dien_thoai'],
                    'loai_khach' => 1, // Mặc định là Người lớn
                    'gioi_tinh' => null,
                    'ngay_sinh' => null,
                    'so_cmnd_cccd' => null
                ];
                $this->createBookingMember($booking_id, $memberData);
            }

            // Lưu nhiều HDV nếu có
            if (!empty($data['danh_sach_hdv']) && is_array($data['danh_sach_hdv'])) {
                $this->saveBookingGuides($booking_id, $data['danh_sach_hdv']);
            } elseif (!empty($data['id_hdv']) && !empty($data['vai_tro'])) {
                // Nếu chỉ có một HDV, lưu vào booking_hdv
                $this->saveBookingGuides($booking_id, [[
                    'id_hdv' => $data['id_hdv'],
                    'vai_tro' => $data['vai_tro']
                ]]);
            }

            // Trừ số chỗ trong lịch khởi hành
            $this->updateSeats($data['id_lich_khoi_hanh'], $so_nguoi, 'subtract');

            return ['success' => true, 'id' => $booking_id, 'ma_booking' => $ma_booking];
        } catch (PDOException $e) {
            error_log("Lỗi createBooking: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Cập nhật booking
     */
    public function updateBooking($id, $data)
    {
        try {
            // Lấy booking hiện tại
            $oldBooking = $this->getBookingById($id);
            if (!$oldBooking) {
                return ['success' => false, 'message' => 'Không tìm thấy booking'];
            }

            $oldTrangThai = (int)($oldBooking['trang_thai'] ?? 0);
            $newTrangThai = isset($data['trang_thai']) ? (int)$data['trang_thai'] : $oldTrangThai;
            
            // Kiểm tra quy trình thay đổi trạng thái
            // Quy trình: 0 (Chờ xử lý) -> 2 (Đã đặt cọc) -> 3 (Đã thanh toán) -> 4 (Đã hoàn thành)
            // Có thể hủy (5) từ trạng thái Chờ xử lý hoặc Đã đặt cọc
            // Không cho phép hủy khi đã thanh toán hoặc đã hoàn thành (đảm bảo tính toàn vẹn dữ liệu tài chính)
            // Có thể quay lại từ hủy (5) về các trạng thái hợp lệ
            if ($oldTrangThai != $newTrangThai) {
                $allowedTransitions = [
                    0 => [2, 5], // Chờ xử lý -> Đã đặt cọc hoặc Hủy
                    2 => [3, 5], // Đã đặt cọc -> Đã thanh toán hoặc Hủy
                    3 => [4],    // Đã thanh toán -> chỉ có thể Đã hoàn thành (không cho hủy)
                    4 => [],     // Đã hoàn thành -> không thể thay đổi trạng thái
                    5 => [0, 2], // Hủy -> có thể quay lại Chờ xử lý hoặc Đã đặt cọc
                ];
                
                if (isset($allowedTransitions[$oldTrangThai]) && !in_array($newTrangThai, $allowedTransitions[$oldTrangThai])) {
                    $statusNames = [
                        0 => 'Chờ xử lý',
                        2 => 'Đã đặt cọc',
                        3 => 'Đã thanh toán',
                        4 => 'Đã hoàn thành',
                        5 => 'Hủy'
                    ];
                    $oldName = $statusNames[$oldTrangThai] ?? 'Trạng thái hiện tại';
                    $newName = $statusNames[$newTrangThai] ?? 'Trạng thái mới';
                    
                    // Xác định trạng thái tiếp theo hợp lệ
                    $nextStatuses = array_map(function($s) use ($statusNames) {
                        return $statusNames[$s] ?? '';
                    }, $allowedTransitions[$oldTrangThai]);
                    
                    return [
                        'success' => false, 
                        'message' => "Không thể chuyển từ '{$oldName}' sang '{$newName}'. Quy trình: " . implode(' → ', $nextStatuses)
                    ];
                }
            }
            
            $oldSoNguoi = ($oldBooking['so_nguoi_lon'] ?? 0) + ($oldBooking['so_tre_em'] ?? 0) + ($oldBooking['so_tre_nho'] ?? 0);
            
            // Nếu thay đổi số khách, kiểm tra lại số chỗ
            if (isset($data['so_nguoi_lon']) || isset($data['so_tre_em']) || isset($data['so_tre_nho'])) {
                $newSoNguoi = ($data['so_nguoi_lon'] ?? $oldBooking['so_nguoi_lon'] ?? 0) + 
                              ($data['so_tre_em'] ?? $oldBooking['so_tre_em'] ?? 0) + 
                              ($data['so_tre_nho'] ?? $oldBooking['so_tre_nho'] ?? 0);
                
                if ($newSoNguoi != $oldSoNguoi) {
                    $seatCheck = $this->checkAvailableSeats($oldBooking['id_lich_khoi_hanh'], $newSoNguoi - $oldSoNguoi);
                    if (!$seatCheck['available']) {
                        return ['success' => false, 'message' => $seatCheck['message']];
                    }
                }
            }

            // Tính lại tổng tiền nếu thay đổi số khách hoặc voucher
            $tong_tien = $oldBooking['tong_tien'];
            $needRecalculate = isset($data['so_nguoi_lon']) || isset($data['so_tre_em']) || isset($data['so_tre_nho']);
            $voucherChanged = isset($data['voucher_id']) || isset($data['voucher_code']);
            
            if ($needRecalculate || $voucherChanged) {
                $tong_tien = $this->calculateTotal(
                    $oldBooking['id_lich_khoi_hanh'],
                    $data['so_nguoi_lon'] ?? $oldBooking['so_nguoi_lon'] ?? 0,
                    $data['so_tre_em'] ?? $oldBooking['so_tre_em'] ?? 0,
                    $data['so_tre_nho'] ?? $oldBooking['so_tre_nho'] ?? 0
                );
                
                // Áp dụng voucher nếu có
                $voucherDiscount = (float)($data['voucher_discount'] ?? 0);
                $tong_tien = max(0, $tong_tien - $voucherDiscount);
            }

            // Xử lý ngày thanh toán
            $ngay_thanh_toan = null;
            if (!empty($data['ngay_thanh_toan'])) {
                // Nếu có nhập ngày thanh toán, dùng giá trị đó
                $ngay_thanh_toan = $data['ngay_thanh_toan'];
            } elseif ($newTrangThai == 3 && $oldTrangThai != 3) {
                // Nếu chuyển sang "Đã thanh toán" và chưa có ngày, set ngày hiện tại
                $ngay_thanh_toan = date('Y-m-d H:i:s');
            } elseif ($newTrangThai == 3 && $oldTrangThai == 3) {
                // Nếu vẫn ở trạng thái "Đã thanh toán", giữ nguyên ngày cũ
                $ngay_thanh_toan = $oldBooking['ngay_thanh_toan'] ?? null;
            } elseif ($newTrangThai == 4 && $oldTrangThai == 3) {
                // Nếu chuyển từ "Đã thanh toán" sang "Hoàn thành", giữ nguyên ngày thanh toán
                $ngay_thanh_toan = $oldBooking['ngay_thanh_toan'] ?? null;
            }
            // Các trạng thái khác: ngay_thanh_toan = null

            // Xử lý voucher
            $voucherId = isset($data['voucher_id']) ? ($data['voucher_id'] ?: null) : $oldBooking['voucher_id'];
            $voucherCode = isset($data['voucher_code']) ? ($data['voucher_code'] ?: null) : $oldBooking['voucher_code'];
            $voucherDiscount = isset($data['voucher_discount']) ? (float)($data['voucher_discount'] ?? 0) : ($oldBooking['voucher_discount'] ?? 0);
            
            // Xử lý id_hdv
            $idHdv = isset($data['id_hdv']) ? ($data['id_hdv'] !== '' ? (int)$data['id_hdv'] : null) : ($oldBooking['id_hdv'] ?? null);
            
            // Xử lý vai_tro
            $vaiTro = isset($data['vai_tro']) ? ($data['vai_tro'] !== '' ? $data['vai_tro'] : null) : ($oldBooking['vai_tro'] ?? null);
            
            // Cập nhật booking
            $sql = "UPDATE booking SET
                        ho_ten = :ho_ten,
                        so_dien_thoai = :so_dien_thoai,
                        email = :email,
                        dia_chi = :dia_chi,
                        so_nguoi_lon = :so_nguoi_lon,
                        so_tre_em = :so_tre_em,
                        so_tre_nho = :so_tre_nho,
                        id_hdv = :id_hdv,
                        vai_tro = :vai_tro,
                        tong_tien = :tong_tien,
                        voucher_id = :voucher_id,
                        voucher_code = :voucher_code,
                        voucher_discount = :voucher_discount,
                        tien_dat_coc = :tien_dat_coc,
                        trang_thai = :trang_thai,
                        ngay_thanh_toan = :ngay_thanh_toan,
                        ghi_chu = :ghi_chu
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':ho_ten' => $data['ho_ten'] ?? $oldBooking['ho_ten'],
                ':so_dien_thoai' => $data['so_dien_thoai'] ?? $oldBooking['so_dien_thoai'],
                ':email' => $data['email'] ?? $oldBooking['email'],
                ':dia_chi' => $data['dia_chi'] ?? $oldBooking['dia_chi'] ?? null,
                ':so_nguoi_lon' => $data['so_nguoi_lon'] ?? $oldBooking['so_nguoi_lon'] ?? 0,
                ':so_tre_em' => $data['so_tre_em'] ?? $oldBooking['so_tre_em'] ?? 0,
                ':so_tre_nho' => $data['so_tre_nho'] ?? $oldBooking['so_tre_nho'] ?? 0,
                ':id_hdv' => $idHdv,
                ':vai_tro' => $vaiTro,
                ':tong_tien' => $tong_tien,
                ':voucher_id' => $voucherId,
                ':voucher_code' => $voucherCode,
                ':voucher_discount' => $voucherDiscount,
                ':tien_dat_coc' => $data['tien_dat_coc'] ?? $oldBooking['tien_dat_coc'] ?? 0,
                ':trang_thai' => $newTrangThai,
                ':ngay_thanh_toan' => $ngay_thanh_toan,
                ':ghi_chu' => $data['ghi_chu'] ?? $oldBooking['ghi_chu'],
                ':id' => $id
            ]);

            if (!$result) {
                return ['success' => false, 'message' => 'Không thể cập nhật booking'];
            }

            // Xử lý số chỗ khi thay đổi trạng thái hoặc số khách
            if ($oldTrangThai != $newTrangThai) {
                // Nếu hủy booking (trạng thái = 5) → cộng lại số chỗ
                if ($newTrangThai == 5 && $oldTrangThai != 5) {
                    $this->updateSeats($oldBooking['id_lich_khoi_hanh'], $oldSoNguoi, 'add');
                }
                // Nếu xác nhận booking (từ chờ xử lý sang đã xác nhận) → đã trừ rồi khi tạo
                // Nếu hủy rồi lại xác nhận → trừ lại
                if ($newTrangThai != 5 && $oldTrangThai == 5) {
                    $newSoNguoi = ($data['so_nguoi_lon'] ?? $oldBooking['so_nguoi_lon'] ?? 0) + 
                                  ($data['so_tre_em'] ?? $oldBooking['so_tre_em'] ?? 0) + 
                                  ($data['so_tre_nho'] ?? $oldBooking['so_tre_nho'] ?? 0) + 
                                  ($data['so_em_be'] ?? $oldBooking['so_em_be'] ?? 0);
                    $this->updateSeats($oldBooking['id_lich_khoi_hanh'], $newSoNguoi, 'subtract');
                }
            }

            // Xử lý thay đổi số khách
            if (isset($data['so_nguoi_lon']) || isset($data['so_tre_em']) || isset($data['so_tre_nho']) || isset($data['so_em_be'])) {
                $newSoNguoi = ($data['so_nguoi_lon'] ?? $oldBooking['so_nguoi_lon'] ?? 0) + 
                              ($data['so_tre_em'] ?? $oldBooking['so_tre_em'] ?? 0) + 
                              ($data['so_tre_nho'] ?? $oldBooking['so_tre_nho'] ?? 0) + 
                              ($data['so_em_be'] ?? $oldBooking['so_em_be'] ?? 0);
                
                if ($newSoNguoi != $oldSoNguoi && $newTrangThai != 5) {
                    $diff = $newSoNguoi - $oldSoNguoi;
                    if ($diff > 0) {
                        $this->updateSeats($oldBooking['id_lich_khoi_hanh'], $diff, 'subtract');
                    } else {
                        $this->updateSeats($oldBooking['id_lich_khoi_hanh'], abs($diff), 'add');
                    }
                }
            }

            // Xử lý danh sách HDV
            if (!empty($data['danh_sach_hdv']) && is_array($data['danh_sach_hdv'])) {
                $this->saveBookingGuides($id, $data['danh_sach_hdv']);
            } elseif (!empty($data['id_hdv']) && !empty($data['vai_tro'])) {
                // Nếu chỉ có một HDV, lưu vào booking_hdv
                $this->saveBookingGuides($id, [[
                    'id_hdv' => $data['id_hdv'],
                    'vai_tro' => $data['vai_tro']
                ]]);
            }

            return ['success' => true, 'message' => 'Cập nhật booking thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi updateBooking: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Cập nhật số chỗ trong lịch khởi hành
     */
    private function updateSeats($id_lich_khoi_hanh, $so_nguoi, $operation = 'subtract')
    {
        try {
            if ($operation === 'subtract') {
                $sql = "UPDATE lich_khoi_hanh SET 
                        so_cho_da_dat = so_cho_da_dat + :so_nguoi,
                        so_cho_con_lai = GREATEST(0, so_cho_con_lai - :so_nguoi)
                        WHERE id = :id";
            } else {
                $sql = "UPDATE lich_khoi_hanh SET 
                        so_cho_da_dat = GREATEST(0, so_cho_da_dat - :so_nguoi),
                        so_cho_con_lai = so_cho_con_lai + :so_nguoi
                        WHERE id = :id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':so_nguoi' => $so_nguoi,
                ':id' => $id_lich_khoi_hanh
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateSeats: " . $e->getMessage());
        }
    }

    /**
     * Xóa booking
     */
    public function deleteBooking($id)
    {
        try {
            // Lấy booking để kiểm tra trạng thái
            $booking = $this->getBookingById($id);
            if (!$booking) {
                return ['success' => false, 'message' => 'Không tìm thấy booking'];
            }

            // Chỉ cho phép xóa booking ở trạng thái "Chờ xử lý" (0) hoặc "Đã hủy" (5)
            // Không cho phép xóa booking đã đặt cọc, đã thanh toán, hoặc đã hoàn thành
            $trangThai = (int)($booking['trang_thai'] ?? 0);
            if ($trangThai == 2 || $trangThai == 3 || $trangThai == 4) {
                return [
                    'success' => false, 
                    'message' => 'Không thể xóa booking đã đặt cọc, thanh toán hoặc hoàn thành. Vui lòng hủy booking thay vì xóa.'
                ];
            }

            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // Xóa các dữ liệu liên quan trước
            $this->deleteBookingGuides($id);
            $this->deleteBookingDetails($id);

            // Nếu booking không ở trạng thái hủy, cộng lại số chỗ
            if ($trangThai != 5) {
                $so_nguoi = ($booking['so_nguoi_lon'] ?? 0) + ($booking['so_tre_em'] ?? 0) + ($booking['so_tre_nho'] ?? 0) + ($booking['so_em_be'] ?? 0);
                $this->updateSeats($booking['id_lich_khoi_hanh'], $so_nguoi, 'add');
            }

            // Xóa booking
            $sql = "DELETE FROM booking WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([':id' => $id]);

            if (!$result) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Không thể xóa booking'];
            }

            // Commit transaction
            $this->conn->commit();

            return ['success' => true, 'message' => 'Xóa booking thành công'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Lỗi deleteBooking: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Thống kê booking
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_booking,
                    SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END) as cho_xu_ly,
                    SUM(CASE WHEN trang_thai = 2 THEN 1 ELSE 0 END) as da_dat_coc,
                    SUM(CASE WHEN trang_thai = 3 THEN 1 ELSE 0 END) as da_thanh_toan,
                    SUM(CASE WHEN trang_thai = 4 THEN 1 ELSE 0 END) as hoan_thanh,
                    SUM(CASE WHEN trang_thai = 5 THEN 1 ELSE 0 END) as huy,
                    SUM(CASE WHEN DATE(ngay_dat) = CURDATE() THEN 1 ELSE 0 END) as hom_nay,
                    SUM(CASE WHEN WEEK(ngay_dat) = WEEK(NOW()) AND YEAR(ngay_dat) = YEAR(NOW()) THEN 1 ELSE 0 END) as tuan_nay,
                    SUM(CASE WHEN MONTH(ngay_dat) = MONTH(NOW()) AND YEAR(ngay_dat) = YEAR(NOW()) THEN 1 ELSE 0 END) as thang_nay,
                    SUM(tong_tien) as tong_doanh_thu,
                    SUM(CASE WHEN trang_thai IN (3, 4) THEN tong_tien ELSE 0 END) as doanh_thu_da_thanh_toan
                FROM booking";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái nhanh
     */
    public function quickChangeStatus($id, $trang_thai, $tien_dat_coc = null)
    {
        try {
            // Lấy booking hiện tại
            $booking = $this->getBookingById($id);
            if (!$booking) {
                return ['success' => false, 'message' => 'Không tìm thấy booking'];
            }

            $oldTrangThai = (int)($booking['trang_thai'] ?? 0);
            $newTrangThai = (int)$trang_thai;
            
            // Kiểm tra quy trình thay đổi trạng thái
            // Quy trình: 0 (Chờ xử lý) -> 2 (Đã đặt cọc) -> 3 (Đã thanh toán) -> 4 (Đã hoàn thành)
            // Có thể hủy (5) từ trạng thái Chờ xử lý hoặc Đã đặt cọc
            // Không cho phép hủy khi đã thanh toán hoặc đã hoàn thành (đảm bảo tính toàn vẹn dữ liệu tài chính)
            // Có thể quay lại từ hủy (5) về các trạng thái hợp lệ
            
            $allowedTransitions = [
                0 => [2, 5], // Chờ xử lý -> Đã đặt cọc hoặc Hủy
                2 => [3, 5], // Đã đặt cọc -> Đã thanh toán hoặc Hủy
                3 => [4],    // Đã thanh toán -> chỉ có thể Đã hoàn thành (không cho hủy)
                4 => [],     // Đã hoàn thành -> không thể thay đổi trạng thái
                5 => [0, 2], // Hủy -> có thể quay lại Chờ xử lý hoặc Đã đặt cọc
            ];
            
            // Nếu không phải chuyển về cùng trạng thái và không nằm trong quy trình cho phép
            if ($oldTrangThai != $newTrangThai && isset($allowedTransitions[$oldTrangThai])) {
                if (!in_array($newTrangThai, $allowedTransitions[$oldTrangThai])) {
                    $statusNames = [
                        0 => 'Chờ xử lý',
                        2 => 'Đã đặt cọc',
                        3 => 'Đã thanh toán',
                        4 => 'Đã hoàn thành',
                        5 => 'Hủy'
                    ];
                    $oldName = $statusNames[$oldTrangThai] ?? 'Trạng thái hiện tại';
                    $newName = $statusNames[$newTrangThai] ?? 'Trạng thái mới';
                    
                    // Xác định trạng thái tiếp theo hợp lệ
                    $nextStatuses = array_map(function($s) use ($statusNames) {
                        return $statusNames[$s] ?? '';
                    }, $allowedTransitions[$oldTrangThai]);
                    
                    return [
                        'success' => false, 
                        'message' => "Không thể chuyển từ '{$oldName}' sang '{$newName}'. Quy trình: " . implode(' → ', $nextStatuses)
                    ];
                }
            }
            
            $so_nguoi = ($booking['so_nguoi_lon'] ?? 0) + ($booking['so_tre_em'] ?? 0) + ($booking['so_tre_nho'] ?? 0) + ($booking['so_em_be'] ?? 0);

            // Xử lý ngày thanh toán
            $ngay_thanh_toan = $booking['ngay_thanh_toan'] ?? null;
            if ($trang_thai == 3 && $oldTrangThai != 3 && empty($ngay_thanh_toan)) {
                // Nếu chuyển sang "Đã thanh toán" và chưa có ngày, set ngày hiện tại
                $ngay_thanh_toan = date('Y-m-d H:i:s');
            } elseif ($trang_thai != 3 && $trang_thai != 4) {
                // Nếu không phải "Đã thanh toán" hoặc "Hoàn thành", xóa ngày thanh toán
                $ngay_thanh_toan = null;
            }

            // Xử lý số tiền đặt cọc: nếu có truyền vào và trạng thái là "Đã đặt cọc", cập nhật
            $tienDatCoc = $booking['tien_dat_coc'] ?? 0;
            if ($tien_dat_coc !== null && $trang_thai == 2) {
                $tienDatCoc = (float)$tien_dat_coc;
            }

            // Cập nhật trạng thái, ngày thanh toán và số tiền đặt cọc
            $sql = "UPDATE booking SET trang_thai = :trang_thai, ngay_thanh_toan = :ngay_thanh_toan, tien_dat_coc = :tien_dat_coc WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':trang_thai' => $trang_thai,
                ':ngay_thanh_toan' => $ngay_thanh_toan,
                ':tien_dat_coc' => $tienDatCoc,
                ':id' => $id
            ]);

            if (!$result) {
                return ['success' => false, 'message' => 'Không thể cập nhật trạng thái'];
            }

            // Xử lý số chỗ khi thay đổi trạng thái
            if ($oldTrangThai != $trang_thai) {
                // Nếu hủy booking (trạng thái = 5) → cộng lại số chỗ
                if ($trang_thai == 5 && $oldTrangThai != 5) {
                    $this->updateSeats($booking['id_lich_khoi_hanh'], $so_nguoi, 'add');
                }
                // Nếu hủy rồi lại xác nhận → trừ lại
                if ($trang_thai != 5 && $oldTrangThai == 5) {
                    $this->updateSeats($booking['id_lich_khoi_hanh'], $so_nguoi, 'subtract');
                }
            }

            return ['success' => true, 'message' => 'Cập nhật trạng thái thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi quickChangeStatus: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Validate số điện thoại Việt Nam
     */
    public static function validatePhone($phone)
    {
        // Loại bỏ khoảng trắng và ký tự đặc biệt
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // Kiểm tra độ dài 10 số và bắt đầu bằng 0
        return preg_match('/^0[0-9]{9}$/', $phone);
    }

    /**
     * Validate email
     */
    public static function validateEmail($email)
    {
        if (empty($email)) {
            return true; // Email không bắt buộc
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Lấy danh sách trạng thái booking
     */
    public static function getStatusList()
    {
        return [
            0 => 'Chờ xử lý',
            2 => 'Đã đặt cọc',
            3 => 'Đã thanh toán',
            4 => 'Hoàn thành',
            5 => 'Hủy'
        ];
    }
    
    /**
     * Lấy danh sách loại booking
     */
    public static function getBookingTypeList()
    {
        return [
            1 => 'Cá nhân',
            2 => 'Gia đình',
            3 => 'Nhóm',
            4 => 'Đoàn'
        ];
    }
    
    /**
     * Lưu danh sách khách vào booking_detail
     */
    private function saveBookingDetails($id_booking, $danh_sach_khach)
    {
        try {
            $sql = "INSERT INTO booking_detail (
                        id_booking, ho_ten, gioi_tinh, ngay_sinh, so_cmnd_cccd, so_dien_thoai, loai_khach
                    ) VALUES (
                        :id_booking, :ho_ten, :gioi_tinh, :ngay_sinh, :so_cmnd_cccd, :so_dien_thoai, :loai_khach
                    )";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($danh_sach_khach as $khach) {
                $stmt->execute([
                    ':id_booking' => $id_booking,
                    ':ho_ten' => $khach['ho_ten'] ?? '',
                    ':gioi_tinh' => isset($khach['gioi_tinh']) ? (int)$khach['gioi_tinh'] : null,
                    ':ngay_sinh' => !empty($khach['ngay_sinh']) ? $khach['ngay_sinh'] : null,
                    ':so_cmnd_cccd' => $khach['so_cmnd_cccd'] ?? null,
                    ':so_dien_thoai' => $khach['so_dien_thoai'] ?? null,
                    ':loai_khach' => $khach['loai_khach'] ?? 1
                ]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi saveBookingDetails BookingModel: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy danh sách khách trong booking
     */
    public function getBookingDetails($id_booking, $includeRegistrant = true)
    {
        try {
            $sql = "SELECT * FROM booking_detail WHERE id_booking = :id_booking ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Nếu là booking cá nhân và chưa có thành viên nào, tự động thêm người đăng ký
            if ($includeRegistrant && empty($details)) {
                // Lấy thông tin booking
                $bookingSql = "SELECT ho_ten, so_dien_thoai, loai_booking FROM booking WHERE id = :id_booking";
                $bookingStmt = $this->conn->prepare($bookingSql);
                $bookingStmt->execute([':id_booking' => $id_booking]);
                $booking = $bookingStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($booking && (int)($booking['loai_booking'] ?? 1) == 1) {
                    // Tạo thành viên từ thông tin người đăng ký
                    $memberData = [
                        'ho_ten' => $booking['ho_ten'] ?? '',
                        'so_dien_thoai' => $booking['so_dien_thoai'] ?? '',
                        'loai_khach' => 1, // Mặc định là Người lớn
                        'gioi_tinh' => null,
                        'ngay_sinh' => null,
                        'so_cmnd_cccd' => null
                    ];
                    
                    // Tự động tạo thành viên
                    $this->createBookingMember($id_booking, $memberData);
                    
                    // Lấy lại danh sách sau khi tạo
                    $stmt->execute([':id_booking' => $id_booking]);
                    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            
            return $details;
        } catch (PDOException $e) {
            error_log("Lỗi getBookingDetails BookingModel: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lưu danh sách HDV cho booking
     */
    private function saveBookingGuides($id_booking, $danh_sach_hdv)
    {
        try {
            // Đảm bảo bảng booking_hdv tồn tại
            $this->ensureBookingHdvTableExists();
            
            // Xóa các HDV cũ của booking này
            $this->deleteBookingGuides($id_booking);
            
            // Thêm các HDV mới
            if (!empty($danh_sach_hdv) && is_array($danh_sach_hdv)) {
                $sql = "INSERT INTO booking_hdv (id_booking, id_hdv, vai_tro) VALUES (:id_booking, :id_hdv, :vai_tro)";
                $stmt = $this->conn->prepare($sql);
                
                $savedCount = 0;
                foreach ($danh_sach_hdv as $hdv) {
                    // Kiểm tra cả id_hdv và vai_tro có giá trị hợp lệ
                    $id_hdv = isset($hdv['id_hdv']) ? (int)$hdv['id_hdv'] : 0;
                    $vai_tro = isset($hdv['vai_tro']) ? trim($hdv['vai_tro']) : '';
                    
                    if ($id_hdv > 0 && !empty($vai_tro)) {
                        $stmt->execute([
                            ':id_booking' => $id_booking,
                            ':id_hdv' => $id_hdv,
                            ':vai_tro' => $vai_tro
                        ]);
                        $savedCount++;
                        error_log("Saved HDV assignment: booking_id=$id_booking, hdv_id=$id_hdv, vai_tro=$vai_tro");
                    } else {
                        error_log("Skipped invalid HDV data: " . print_r($hdv, true));
                    }
                }
                error_log("Total saved " . $savedCount . " HDV assignments for booking ID: " . $id_booking);
            }
        } catch (PDOException $e) {
            error_log("Lỗi saveBookingGuides BookingModel: " . $e->getMessage());
            error_log("Booking ID: " . $id_booking);
            error_log("HDV Data: " . print_r($danh_sach_hdv, true));
        }
    }
    
    /**
     * Migrate dữ liệu từ booking.id_hdv sang booking_hdv
     * Chỉ migrate các booking có id_hdv nhưng chưa có trong booking_hdv
     */
    private function migrateBookingHdvData()
    {
        try {
            // Kiểm tra xem có booking nào có id_hdv nhưng chưa có trong booking_hdv không
            $sql = "SELECT b.id, b.id_hdv, b.vai_tro 
                    FROM booking b 
                    WHERE b.id_hdv IS NOT NULL 
                    AND b.id_hdv > 0
                    AND NOT EXISTS (
                        SELECT 1 FROM booking_hdv bh WHERE bh.id_booking = b.id
                    )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $bookingsToMigrate = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($bookingsToMigrate)) {
                $insertSql = "INSERT INTO booking_hdv (id_booking, id_hdv, vai_tro) VALUES (:id_booking, :id_hdv, :vai_tro)";
                $insertStmt = $this->conn->prepare($insertSql);
                
                foreach ($bookingsToMigrate as $booking) {
                    $vaiTro = !empty($booking['vai_tro']) ? $booking['vai_tro'] : 'HDV chính';
                    $insertStmt->execute([
                        ':id_booking' => $booking['id'],
                        ':id_hdv' => $booking['id_hdv'],
                        ':vai_tro' => $vaiTro
                    ]);
                }
                
                error_log("Migrated " . count($bookingsToMigrate) . " booking HDV records to booking_hdv table");
            }
        } catch (PDOException $e) {
            error_log("Lỗi migrateBookingHdvData BookingModel: " . $e->getMessage());
        }
    }
    
    /**
     * Xóa tất cả HDV của một booking
     */
    private function deleteBookingGuides($id_booking)
    {
        try {
            $sql = "DELETE FROM booking_hdv WHERE id_booking = :id_booking";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteBookingGuides BookingModel: " . $e->getMessage());
        }
    }
    
    /**
     * Lấy danh sách HDV của một booking
     */
    public function getBookingGuides($id_booking)
    {
        try {
            $sql = "SELECT bh.*, hdv.ho_ten, hdv.so_dien_thoai, hdv.email
                    FROM booking_hdv bh
                    LEFT JOIN huong_dan_vien hdv ON bh.id_hdv = hdv.id
                    WHERE bh.id_booking = :id_booking
                    ORDER BY 
                        CASE bh.vai_tro
                            WHEN 'HDV chính' THEN 1
                            WHEN 'HDV phụ' THEN 2
                            WHEN 'Trợ lý' THEN 3
                            ELSE 4
                        END,
                        hdv.ho_ten ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getBookingGuides BookingModel: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tất cả phân công HDV từ booking_hdv
     */
    public function getAllBookingAssignments($filters = [])
    {
        try {
            // Đảm bảo bảng booking_hdv tồn tại
            $this->ensureBookingHdvTableExists();
            
            // Migrate dữ liệu từ booking.id_hdv sang booking_hdv nếu chưa có
            $this->migrateBookingHdvData();
            
            // Debug: Kiểm tra số lượng booking có id_hdv
            $checkBooking = $this->conn->query("SELECT COUNT(*) as total FROM booking WHERE id_hdv IS NOT NULL AND id_hdv > 0")->fetch(PDO::FETCH_ASSOC);
            error_log("Total bookings with id_hdv: " . ($checkBooking['total'] ?? 0));
            
            // Debug: Kiểm tra số lượng trong booking_hdv
            $checkBookingHdv = $this->conn->query("SELECT COUNT(*) as total FROM booking_hdv")->fetch(PDO::FETCH_ASSOC);
            error_log("Total records in booking_hdv: " . ($checkBookingHdv['total'] ?? 0));
            
            // Debug: Lấy chi tiết các record trong booking_hdv
            $debugRecords = $this->conn->query("SELECT bh.*, b.ma_booking FROM booking_hdv bh LEFT JOIN booking b ON bh.id_booking = b.id LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            error_log("Sample booking_hdv records: " . print_r($debugRecords, true));
            
            $sql = "SELECT 
                        bh.id,
                        bh.id_booking,
                        bh.id_hdv,
                        bh.vai_tro,
                        b.ma_booking,
                        b.ho_ten as ten_khach,
                        b.so_dien_thoai,
                        b.trang_thai as trang_thai_booking,
                        hdv.ho_ten as ten_hdv,
                        hdv.email as email_hdv,
                        hdv.so_dien_thoai as sdt_hdv,
                        lkh.ngay_khoi_hanh as ngay_khoi_hanh_lich,
                        lkh.gio_khoi_hanh,
                        COALESCE(g.tengoi, 'N/A') as ten_tour,
                        g.mato as ma_tour,
                        COALESCE(lkh.id_tour, b.id_tour, g.id_goi) as id_tour
                    FROM booking_hdv bh
                    INNER JOIN booking b ON bh.id_booking = b.id
                    LEFT JOIN huong_dan_vien hdv ON bh.id_hdv = hdv.id
                    LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                    LEFT JOIN goidulich g ON COALESCE(lkh.id_tour, b.id_tour) = g.id_goi
                    WHERE bh.id_hdv IS NOT NULL AND bh.id_hdv > 0";
            
            $params = [];
            
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
            
            // Filter theo mã booking
            if (!empty($filters['ma_booking'])) {
                $sql .= " AND b.ma_booking LIKE :ma_booking";
                $params[':ma_booking'] = '%' . $filters['ma_booking'] . '%';
            }
            
            $sql .= " ORDER BY COALESCE(lkh.ngay_khoi_hanh, b.ngay_dat) DESC, hdv.ho_ten ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug: Log số lượng kết quả
            error_log("getAllBookingAssignments returned " . count($results) . " results");
            if (!empty($results)) {
                error_log("First result: " . print_r($results[0], true));
            }
            
            // Nếu không có kết quả, thử migrate lại và query lại
            if (empty($results)) {
                // Migrate lại một lần nữa để đảm bảo
                $this->migrateBookingHdvData();
                
                // Query lại
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($params);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("After retry, returned " . count($results) . " results");
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi getAllBookingAssignments BookingModel: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            return [];
        }
    }
    
    /**
     * Xóa danh sách khách của booking
     */
    private function deleteBookingDetails($id_booking)
    {
        try {
            $sql = "DELETE FROM booking_detail WHERE id_booking = :id_booking";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteBookingDetails BookingModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách booking theo lịch khởi hành
     */
    public function getBookingsByDeparturePlan($id_lich_khoi_hanh)
    {
        try {
            $sql = "SELECT * FROM booking WHERE id_lich_khoi_hanh = :id_lich_khoi_hanh ORDER BY ma_booking ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_lich_khoi_hanh' => $id_lich_khoi_hanh]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getBookingsByDeparturePlan BookingModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả thành viên booking với filter
     */
    public function getAllBookingMembers($filters = [])
    {
        try {
            $sql = "SELECT 
                        bd.id,
                        bd.id_booking,
                        bd.ho_ten,
                        bd.gioi_tinh,
                        bd.ngay_sinh,
                        bd.so_cmnd_cccd,
                        bd.so_dien_thoai,
                        bd.loai_khach,
                        b.ma_booking,
                        b.ho_ten as ten_khach_booking,
                        b.email as email_booking,
                        b.trang_thai as trang_thai_booking,
                        b.loai_booking,
                        lkh.ngay_khoi_hanh,
                        g.tengoi as ten_tour,
                        g.id_goi as id_tour
                    FROM booking_detail bd
                    INNER JOIN booking b ON bd.id_booking = b.id
                    LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                    LEFT JOIN goidulich g ON COALESCE(lkh.id_tour, b.id_tour) = g.id_goi
                    WHERE 1=1";
            
            $params = [];
            
            // Filter theo mã booking
            if (!empty($filters['ma_booking'])) {
                $sql .= " AND b.ma_booking LIKE :ma_booking";
                $params[':ma_booking'] = '%' . $filters['ma_booking'] . '%';
            }
            
            // Filter theo họ tên
            if (!empty($filters['ho_ten'])) {
                $sql .= " AND bd.ho_ten LIKE :ho_ten";
                $params[':ho_ten'] = '%' . $filters['ho_ten'] . '%';
            }
            
            // Filter theo số điện thoại
            if (!empty($filters['so_dien_thoai'])) {
                $sql .= " AND bd.so_dien_thoai LIKE :so_dien_thoai";
                $params[':so_dien_thoai'] = '%' . $filters['so_dien_thoai'] . '%';
            }
            
            // Filter theo loại khách
            if (isset($filters['loai_khach']) && $filters['loai_khach'] !== '') {
                $sql .= " AND bd.loai_khach = :loai_khach";
                $params[':loai_khach'] = (int)$filters['loai_khach'];
            }
            
            // Filter theo ngày sinh từ
            if (!empty($filters['ngay_sinh_tu'])) {
                $sql .= " AND bd.ngay_sinh >= :ngay_sinh_tu";
                $params[':ngay_sinh_tu'] = $filters['ngay_sinh_tu'];
            }
            
            // Filter theo ngày sinh đến
            if (!empty($filters['ngay_sinh_den'])) {
                $sql .= " AND bd.ngay_sinh <= :ngay_sinh_den";
                $params[':ngay_sinh_den'] = $filters['ngay_sinh_den'];
            }
            
            // Đếm tổng số bản ghi (trước khi thêm LIMIT)
            $countSql = "SELECT COUNT(*) as total FROM ($sql) as count_query";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Phân trang
            $page = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
            $perPage = isset($filters['per_page']) ? max(1, (int)$filters['per_page']) : 20;
            $offset = ($page - 1) * $perPage;
            
            $sql .= " ORDER BY b.ngay_dat DESC, bd.id ASC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'data' => $results,
                'total' => (int)$totalRecords,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($totalRecords / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Lỗi getAllBookingMembers BookingModel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm thành viên mới vào booking
     */
    public function createBookingMember($id_booking, $data)
    {
        try {
            // Kiểm tra booking tồn tại
            $booking = $this->getBookingById($id_booking);
            if (!$booking) {
                return ['success' => false, 'message' => 'Booking không tồn tại'];
            }
            
            // Kiểm tra booking có thể chỉnh sửa không
            if (!$this->canEditBooking($id_booking)) {
                return ['success' => false, 'message' => 'Booking đang ở trạng thái không thể thêm thành viên'];
            }
            
            // Validate dữ liệu
            $validation = $this->validateMemberData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }
            
            $sql = "INSERT INTO booking_detail (
                        id_booking, ho_ten, gioi_tinh, ngay_sinh, 
                        so_cmnd_cccd, so_dien_thoai, loai_khach
                    ) VALUES (
                        :id_booking, :ho_ten, :gioi_tinh, :ngay_sinh,
                        :so_cmnd_cccd, :so_dien_thoai, :loai_khach
                    )";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_booking' => $id_booking,
                ':ho_ten' => trim($data['ho_ten']),
                ':gioi_tinh' => isset($data['gioi_tinh']) ? (int)$data['gioi_tinh'] : null,
                ':ngay_sinh' => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
                ':so_cmnd_cccd' => !empty($data['so_cmnd_cccd']) ? trim($data['so_cmnd_cccd']) : null,
                ':so_dien_thoai' => !empty($data['so_dien_thoai']) ? trim($data['so_dien_thoai']) : null,
                ':loai_khach' => isset($data['loai_khach']) ? (int)$data['loai_khach'] : 1
            ]);
            
            return ['success' => true, 'id' => $this->conn->lastInsertId(), 'message' => 'Thêm thành viên thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi createBookingMember BookingModel: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Cập nhật thông tin thành viên
     */
    public function updateBookingMember($id, $data)
    {
        try {
            // Lấy thông tin thành viên
            $member = $this->getBookingMemberById($id);
            if (!$member) {
                return ['success' => false, 'message' => 'Thành viên không tồn tại'];
            }
            
            // Kiểm tra booking có thể chỉnh sửa không
            if (!$this->canEditBooking($member['id_booking'])) {
                return ['success' => false, 'message' => 'Booking đang ở trạng thái không thể chỉnh sửa'];
            }
            
            // Validate dữ liệu
            $validation = $this->validateMemberData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }
            
            $sql = "UPDATE booking_detail SET
                        ho_ten = :ho_ten,
                        gioi_tinh = :gioi_tinh,
                        ngay_sinh = :ngay_sinh,
                        so_cmnd_cccd = :so_cmnd_cccd,
                        so_dien_thoai = :so_dien_thoai,
                        loai_khach = :loai_khach
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':ho_ten' => trim($data['ho_ten']),
                ':gioi_tinh' => isset($data['gioi_tinh']) ? (int)$data['gioi_tinh'] : null,
                ':ngay_sinh' => !empty($data['ngay_sinh']) ? $data['ngay_sinh'] : null,
                ':so_cmnd_cccd' => !empty($data['so_cmnd_cccd']) ? trim($data['so_cmnd_cccd']) : null,
                ':so_dien_thoai' => !empty($data['so_dien_thoai']) ? trim($data['so_dien_thoai']) : null,
                ':loai_khach' => isset($data['loai_khach']) ? (int)$data['loai_khach'] : 1
            ]);
            
            return ['success' => true, 'message' => 'Cập nhật thành viên thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi updateBookingMember BookingModel: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Xóa thành viên
     */
    public function deleteBookingMember($id)
    {
        try {
            // Lấy thông tin thành viên
            $member = $this->getBookingMemberById($id);
            if (!$member) {
                return ['success' => false, 'message' => 'Thành viên không tồn tại'];
            }
            
            // Kiểm tra booking có thể chỉnh sửa không
            if (!$this->canEditBooking($member['id_booking'])) {
                return ['success' => false, 'message' => 'Booking đang ở trạng thái không thể xóa thành viên'];
            }
            
            $sql = "DELETE FROM booking_detail WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            return ['success' => true, 'message' => 'Xóa thành viên thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi deleteBookingMember BookingModel: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    /**
     * Lấy thông tin thành viên theo ID
     */
    public function getBookingMemberById($id)
    {
        try {
            $sql = "SELECT * FROM booking_detail WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getBookingMemberById BookingModel: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra booking có thể chỉnh sửa không
     */
    public function canEditBooking($id_booking)
    {
        try {
            $booking = $this->getBookingById($id_booking);
            if (!$booking) {
                return false;
            }
            
            $trangThai = (int)($booking['trang_thai'] ?? 0);
            
            // Booking ở trạng thái đã hoàn thành hoặc đã hủy thì không thể chỉnh sửa
            if ($trangThai == 4 || $trangThai == 5) {
                return false;
            }
            
            // Có thể thêm kiểm tra khác: đã xuất hóa đơn, đã khởi hành, etc.
            // Nếu có trạng thái hóa đơn, kiểm tra thêm
            if (isset($booking['trang_thai_hoa_don'])) {
                $trangThaiHoaDon = (int)$booking['trang_thai_hoa_don'];
                // Đã xuất hóa đơn hoặc đã gửi thì không thể chỉnh sửa
                if ($trangThaiHoaDon >= 2) {
                    return false;
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi canEditBooking BookingModel: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate dữ liệu thành viên
     */
    private function validateMemberData($data)
    {
        // Họ tên bắt buộc
        if (empty(trim($data['ho_ten'] ?? ''))) {
            return ['valid' => false, 'message' => 'Họ tên không được để trống'];
        }
        
        // Email đúng định dạng (nếu có)
        if (!empty($data['email'] ?? '')) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['valid' => false, 'message' => 'Email không hợp lệ'];
            }
        }
        
        // Số điện thoại đúng định dạng (nếu có)
        if (!empty($data['so_dien_thoai'] ?? '')) {
            $phone = trim($data['so_dien_thoai']);
            // Kiểm tra định dạng số điện thoại Việt Nam
            if (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $phone)) {
                return ['valid' => false, 'message' => 'Số điện thoại không hợp lệ'];
            }
        }
        
        // Loại khách hợp lệ
        if (isset($data['loai_khach'])) {
            $loaiKhach = (int)$data['loai_khach'];
            if (!in_array($loaiKhach, [1, 2, 3, 4])) {
                return ['valid' => false, 'message' => 'Loại khách không hợp lệ'];
            }
        }
        
        return ['valid' => true];
    }

    
}
