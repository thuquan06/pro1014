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
            
            // Thêm cột loai_booking nếu chưa có
            if (!in_array('loai_booking', $columns)) {
                $this->conn->exec("ALTER TABLE booking ADD COLUMN `loai_booking` TINYINT(1) DEFAULT 1 COMMENT '1=Cá nhân, 2=Gia đình, 3=Nhóm, 4=Đoàn' AFTER `so_em_be`");
            }
        } catch (PDOException $e) {
            error_log("Lỗi ensureColumnsExist BookingModel: " . $e->getMessage());
        }
        
        // Đảm bảo bảng booking_detail tồn tại
        $this->ensureBookingDetailTableExists();
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
                       g.mato as ma_tour
                FROM booking b
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi
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
                       g.noixuatphat as noi_xuat_phat
                FROM booking b
                LEFT JOIN lich_khoi_hanh lkh ON b.id_lich_khoi_hanh = lkh.id
                LEFT JOIN goidulich g ON b.id_tour = g.id_goi
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
                        ma_booking, id_lich_khoi_hanh, id_tour, voucher_id, voucher_code,
                        ho_ten, so_dien_thoai, email, dia_chi,
                        so_nguoi_lon, so_tre_em, so_tre_nho, loai_booking, tong_tien, voucher_discount, tien_dat_coc,
                        trang_thai, ghi_chu, nguoi_tao, ngay_dat
                    ) VALUES (
                        :ma_booking, :id_lich_khoi_hanh, :id_tour, :voucher_id, :voucher_code,
                        :ho_ten, :so_dien_thoai, :email, :dia_chi,
                        :so_nguoi_lon, :so_tre_em, :so_tre_nho, :loai_booking, :tong_tien, :voucher_discount, :tien_dat_coc,
                        :trang_thai, :ghi_chu, :nguoi_tao, NOW()
                    )";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':ma_booking' => $ma_booking,
                ':id_lich_khoi_hanh' => $data['id_lich_khoi_hanh'],
                ':id_tour' => $id_tour,
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

            $oldTrangThai = $oldBooking['trang_thai'];
            $newTrangThai = $data['trang_thai'] ?? $oldTrangThai;
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
            
            // Cập nhật booking
            $sql = "UPDATE booking SET
                        ho_ten = :ho_ten,
                        so_dien_thoai = :so_dien_thoai,
                        email = :email,
                        dia_chi = :dia_chi,
                        so_nguoi_lon = :so_nguoi_lon,
                        so_tre_em = :so_tre_em,
                        so_tre_nho = :so_tre_nho,
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
            // Lấy booking để cộng lại số chỗ
            $booking = $this->getBookingById($id);
            if (!$booking) {
                return ['success' => false, 'message' => 'Không tìm thấy booking'];
            }

            // Nếu booking không ở trạng thái hủy, cộng lại số chỗ
            if ($booking['trang_thai'] != 5) {
                $so_nguoi = ($booking['so_nguoi_lon'] ?? 0) + ($booking['so_tre_em'] ?? 0) + ($booking['so_tre_nho'] ?? 0) + ($booking['so_em_be'] ?? 0);
                $this->updateSeats($booking['id_lich_khoi_hanh'], $so_nguoi, 'add');
            }

            // Xóa booking
            $sql = "DELETE FROM booking WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([':id' => $id]);

            if (!$result) {
                return ['success' => false, 'message' => 'Không thể xóa booking'];
            }

            return ['success' => true, 'message' => 'Xóa booking thành công'];
        } catch (PDOException $e) {
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

            $oldTrangThai = $booking['trang_thai'];
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
    public function getBookingDetails($id_booking)
    {
        try {
            $sql = "SELECT * FROM booking_detail WHERE id_booking = :id_booking ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_booking' => $id_booking]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getBookingDetails BookingModel: " . $e->getMessage());
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

    
}




     
  

