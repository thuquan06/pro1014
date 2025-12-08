<?php
/**
 * DeparturePlanModel - Quản lý lịch khởi hành
 * UC-Departure-Plan: Tạo lịch khởi hành cho tour
 */
class DeparturePlanModel extends BaseModel
{
    private $lastError = null;

    /**
     * Constructor - Tự động kiểm tra và tạo cột phuong_tien nếu chưa có
     */
    public function __construct()
    {
        parent::__construct();
        $this->ensurePhuongTienColumnExists();
    }

    /**
     * Lấy lỗi cuối cùng
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Đảm bảo tất cả các cột cần thiết tồn tại trong bảng lich_khoi_hanh
     */
    private function ensurePhuongTienColumnExists()
    {
        try {
            // Kiểm tra xem bảng có tồn tại không
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'lich_khoi_hanh'")->rowCount() > 0;
            if (!$tableExists) {
                // Tạo bảng mới nếu chưa tồn tại
                $createTableSQL = "CREATE TABLE `lich_khoi_hanh` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour',
                    `ngay_khoi_hanh` DATE NULL DEFAULT NULL COMMENT 'Ngày khởi hành',
                    `ngay_ket_thuc` DATE NULL DEFAULT NULL COMMENT 'Ngày kết thúc tour',
                    `gio_khoi_hanh` TIME NULL DEFAULT NULL COMMENT 'Giờ khởi hành',
                    `gio_tap_trung` TIME NULL DEFAULT NULL COMMENT 'Giờ tập trung',
                    `diem_tap_trung` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Điểm tập trung',
                    `so_cho` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ tối đa',
                    `so_cho_da_dat` INT(11) NULL DEFAULT 0 COMMENT 'Số chỗ đã có người đặt',
                    `so_cho_con_lai` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống',
                    `so_cho_con_trong` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống (cũ)',
                    `gia_nguoi_lon` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá người lớn',
                    `gia_tre_em` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá trẻ em',
                    `gia_tre_nho` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá trẻ nhỏ',
                    `phuong_tien` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Phương tiện di chuyển',
                    `uu_dai_giam_gia` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Ưu đãi giảm giá (%)',
                    `ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú nội bộ',
                    `ghi_chu_van_hanh` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú vận hành',
                    `trang_thai` TINYINT(1) DEFAULT 1 COMMENT 'Trạng thái: 0=Đóng/Khóa, 1=Đang mở, 2=Hết chỗ, 3=Gần đầy',
                    `ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                    `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                    PRIMARY KEY (`id`),
                    KEY `idx_id_tour` (`id_tour`),
                    KEY `idx_ngay_khoi_hanh` (`ngay_khoi_hanh`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lịch khởi hành'";
                $this->conn->exec($createTableSQL);
                error_log("Đã tạo bảng lich_khoi_hanh với đầy đủ các cột");
                return; // Đã tạo xong bảng, không cần thêm cột nữa
            }
            
            // Kiểm tra cột id có đúng không
            $idColumnInfo = $this->conn->query("SHOW COLUMNS FROM lich_khoi_hanh WHERE Field = 'id'")->fetch(PDO::FETCH_ASSOC);
            if ($idColumnInfo) {
                // Kiểm tra xem cột id có phải AUTO_INCREMENT và PRIMARY KEY không
                $isAutoIncrement = strpos($idColumnInfo['Extra'], 'auto_increment') !== false;
                $isPrimary = $idColumnInfo['Key'] === 'PRI';
                
                if (!$isAutoIncrement || !$isPrimary) {
                    // Sửa lại cột id
                    try {
                        // Kiểm tra xem có PRIMARY KEY nào khác không
                        $primaryKeys = $this->conn->query("SHOW KEYS FROM lich_khoi_hanh WHERE Key_name = 'PRIMARY'")->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Nếu không có PRIMARY KEY hoặc PRIMARY KEY là id
                        if (empty($primaryKeys) || (count($primaryKeys) == 1 && $primaryKeys[0]['Column_name'] === 'id')) {
                            // Nếu đã có PRIMARY KEY nhưng không phải id, xóa nó trước
                            if (!empty($primaryKeys) && $primaryKeys[0]['Column_name'] === 'id') {
                                // Chỉ cần sửa AUTO_INCREMENT
                                $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                    MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");
                                error_log("Đã sửa cột id thành AUTO_INCREMENT");
                            } else {
                                // Thêm PRIMARY KEY và AUTO_INCREMENT
                                $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                    MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT,
                                    ADD PRIMARY KEY (`id`)");
                                error_log("Đã sửa cột id thành AUTO_INCREMENT và PRIMARY KEY");
                            }
                        } else {
                            // Nếu đã có PRIMARY KEY khác, chỉ sửa AUTO_INCREMENT
                            // Nhưng cần cẩn thận - có thể cần xóa PRIMARY KEY cũ trước
                            try {
                                // Thử xóa PRIMARY KEY cũ nếu không phải id
                                $oldPrimaryKey = $primaryKeys[0]['Column_name'];
                                if ($oldPrimaryKey !== 'id') {
                                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` DROP PRIMARY KEY");
                                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                        MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT,
                                        ADD PRIMARY KEY (`id`)");
                                    error_log("Đã xóa PRIMARY KEY cũ và sửa cột id thành AUTO_INCREMENT và PRIMARY KEY");
                                } else {
                                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                        MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");
                                    error_log("Đã sửa cột id thành AUTO_INCREMENT");
                                }
                            } catch (PDOException $e2) {
                                // Nếu không thể xóa PRIMARY KEY, chỉ sửa AUTO_INCREMENT
                                $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                    MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");
                                error_log("Đã sửa cột id thành AUTO_INCREMENT (không thể thay đổi PRIMARY KEY)");
                            }
                        }
                    } catch (PDOException $e) {
                        error_log("Lỗi sửa cột id: " . $e->getMessage());
                        // Thử cách đơn giản hơn - chỉ sửa AUTO_INCREMENT
                        try {
                            $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                                MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT");
                            error_log("Đã sửa cột id thành AUTO_INCREMENT (fallback)");
                        } catch (PDOException $e3) {
                            error_log("Lỗi fallback sửa cột id: " . $e3->getMessage());
                        }
                    }
                }
            } else {
                // Nếu không có cột id, thêm cột id làm PRIMARY KEY và AUTO_INCREMENT
                try {
                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` 
                        ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
                    error_log("Đã thêm cột id làm PRIMARY KEY và AUTO_INCREMENT");
                } catch (PDOException $e) {
                    error_log("Lỗi thêm cột id: " . $e->getMessage());
                }
            }
            
            // Lấy danh sách các cột hiện có
            $existingColumns = $this->conn->query("SHOW COLUMNS FROM lich_khoi_hanh")->fetchAll(PDO::FETCH_COLUMN);
            $existingColumns = array_map('strtolower', $existingColumns);
            
            // Danh sách các cột cần thiết với định nghĩa
            $requiredColumns = [
                'id' => "`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST",
                'id_tour' => "`id_tour` INT(11) NULL DEFAULT NULL COMMENT 'ID tour' AFTER `id`",
                'ngay_khoi_hanh' => "`ngay_khoi_hanh` DATE NULL DEFAULT NULL COMMENT 'Ngày khởi hành' AFTER `id_tour`",
                'ngay_ket_thuc' => "`ngay_ket_thuc` DATE NULL DEFAULT NULL COMMENT 'Ngày kết thúc tour' AFTER `ngay_khoi_hanh`",
                'gio_khoi_hanh' => "`gio_khoi_hanh` TIME NULL DEFAULT NULL COMMENT 'Giờ khởi hành' AFTER `ngay_ket_thuc`",
                'gio_tap_trung' => "`gio_tap_trung` TIME NULL DEFAULT NULL COMMENT 'Giờ tập trung' AFTER `gio_khoi_hanh`",
                'diem_tap_trung' => "`diem_tap_trung` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Điểm tập trung' AFTER `gio_tap_trung`",
                'so_cho' => "`so_cho` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ tối đa' AFTER `diem_tap_trung`",
                'so_cho_da_dat' => "`so_cho_da_dat` INT(11) NULL DEFAULT 0 COMMENT 'Số chỗ đã có người đặt' AFTER `so_cho`",
                'so_cho_con_lai' => "`so_cho_con_lai` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống' AFTER `so_cho_da_dat`",
                'so_cho_con_trong' => "`so_cho_con_trong` INT(11) NULL DEFAULT NULL COMMENT 'Số chỗ còn trống (cũ)' AFTER `so_cho_con_lai`",
                'gia_nguoi_lon' => "`gia_nguoi_lon` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá người lớn' AFTER `so_cho_con_lai`",
                'gia_tre_em' => "`gia_tre_em` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá trẻ em' AFTER `gia_nguoi_lon`",
                'gia_tre_nho' => "`gia_tre_nho` DECIMAL(15,2) NULL DEFAULT NULL COMMENT 'Giá trẻ nhỏ' AFTER `gia_tre_em`",
                'phuong_tien' => "`phuong_tien` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Phương tiện di chuyển' AFTER `gia_tre_nho`",
                'uu_dai_giam_gia' => "`uu_dai_giam_gia` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Ưu đãi giảm giá (%)' AFTER `phuong_tien`",
                'ghi_chu' => "`ghi_chu` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú nội bộ' AFTER `uu_dai_giam_gia`",
                'ghi_chu_van_hanh' => "`ghi_chu_van_hanh` TEXT NULL DEFAULT NULL COMMENT 'Ghi chú vận hành' AFTER `ghi_chu`",
                'chuongtrinh' => "`chuongtrinh` TEXT NULL DEFAULT NULL COMMENT 'Lịch trình tour' AFTER `ghi_chu_van_hanh`",
                'trang_thai' => "`trang_thai` TINYINT(1) DEFAULT 1 COMMENT 'Trạng thái: 0=Đóng/Khóa, 1=Đang mở, 2=Hết chỗ, 3=Gần đầy' AFTER `chuongtrinh`",
                'ngay_tao' => "`ngay_tao` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo' AFTER `trang_thai`",
                'ngay_cap_nhat' => "`ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật' AFTER `ngay_tao`"
            ];
            
            // Thứ tự các cột theo thứ tự mong muốn
            $columnOrder = ['id', 'id_tour', 'ngay_khoi_hanh', 'ngay_ket_thuc', 'gio_khoi_hanh', 'gio_tap_trung', 
                          'diem_tap_trung', 'so_cho', 'so_cho_da_dat', 'so_cho_con_lai', 'so_cho_con_trong',
                          'gia_nguoi_lon', 'gia_tre_em', 'gia_tre_nho', 'phuong_tien', 'uu_dai_giam_gia', 
                          'ghi_chu', 'ghi_chu_van_hanh', 'chuongtrinh', 'trang_thai', 'ngay_tao', 'ngay_cap_nhat'];
            
            // Kiểm tra và tạo các cột còn thiếu
            foreach ($columnOrder as $idx => $columnName) {
                if (!in_array(strtolower($columnName), $existingColumns)) {
                    try {
                        // Tìm cột gần nhất đã tồn tại trước đó để đặt AFTER
                        $afterColumn = null;
                        for ($i = $idx - 1; $i >= 0; $i--) {
                            if (in_array(strtolower($columnOrder[$i]), $existingColumns)) {
                                $afterColumn = $columnOrder[$i];
                                break;
                            }
                        }
                        
                        $columnDef = $requiredColumns[$columnName];
                        
                        // Xử lý đặc biệt cho cột id (primary key)
                        if ($columnName === 'id') {
                            // Nếu không có cột nào trước đó, thêm ở đầu
                            $this->conn->exec("ALTER TABLE `lich_khoi_hanh` ADD COLUMN " . $columnDef);
                        } else {
                            // Thêm cột với AFTER clause nếu có cột trước đó
                            if ($afterColumn) {
                                // Tách phần định nghĩa cột (bỏ phần AFTER)
                                $defParts = explode(' AFTER ', $columnDef);
                                $columnDefOnly = $defParts[0];
                                $this->conn->exec("ALTER TABLE `lich_khoi_hanh` ADD COLUMN {$columnDefOnly} AFTER `{$afterColumn}`");
                            } else {
                                // Nếu không có cột nào trước đó, thêm ở đầu (sau id nếu có)
                                if (in_array('id', $existingColumns)) {
                                    $defParts = explode(' AFTER ', $columnDef);
                                    $columnDefOnly = $defParts[0];
                                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` ADD COLUMN {$columnDefOnly} AFTER `id`");
                                } else {
                                    $defParts = explode(' AFTER ', $columnDef);
                                    $columnDefOnly = $defParts[0];
                                    $this->conn->exec("ALTER TABLE `lich_khoi_hanh` ADD COLUMN {$columnDefOnly} FIRST");
                                }
                            }
                        }
                        error_log("Đã thêm cột {$columnName} vào bảng lich_khoi_hanh");
                        // Cập nhật danh sách cột hiện có để lần lặp tiếp theo có thể dùng
                        $existingColumns[] = strtolower($columnName);
                    } catch (PDOException $e) {
                        // Nếu lỗi do cột đã tồn tại (có thể do race condition), bỏ qua
                        if (strpos($e->getMessage(), 'Duplicate column name') === false && 
                            strpos($e->getMessage(), 'already exists') === false) {
                            error_log("Lỗi thêm cột {$columnName}: " . $e->getMessage());
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            // Log lỗi nhưng không throw để không làm gián đoạn ứng dụng
            error_log("Lỗi kiểm tra/tạo cột lich_khoi_hanh: " . $e->getMessage());
        }
    }

    /**
     * Lấy tất cả lịch khởi hành
     */
    public function getAllDeparturePlans($filters = [])
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi, g.songay
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE 1=1";
        $params = [];
        
        // Filter theo tên tour
        if (!empty($filters['ten_tour'])) {
            $sql .= " AND g.tengoi LIKE :ten_tour";
            $params[':ten_tour'] = '%' . $filters['ten_tour'] . '%';
        }
        
        $sql .= " ORDER BY dp.ngay_khoi_hanh DESC, dp.gio_khoi_hanh ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch khởi hành theo ID
     */
    public function getDeparturePlanByID($id)
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi, g.songay
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE dp.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy lịch khởi hành theo tour ID
     */
    public function getDeparturePlansByTourID($tourId)
    {
        $sql = "SELECT dp.*, g.tengoi, g.id_goi, g.songay
                FROM lich_khoi_hanh dp
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE dp.id_tour = :tour_id 
                ORDER BY dp.ngay_khoi_hanh DESC, dp.gio_khoi_hanh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo lịch khởi hành mới
     */
    public function createDeparturePlan(array $data)
    {
        try {
            // Tính toán so_cho_con_lai nếu có so_cho và so_cho_da_dat
            $so_cho = $data['so_cho'] ?? null;
            $so_cho_da_dat = $data['so_cho_da_dat'] ?? 0;
            $so_cho_con_lai = $data['so_cho_con_lai'] ?? null;
            if ($so_cho !== null && $so_cho_con_lai === null) {
                $so_cho_con_lai = max(0, $so_cho - $so_cho_da_dat);
            }
            // Fallback: nếu có so_cho_con_trong thì dùng nó
            if ($so_cho_con_lai === null && isset($data['so_cho_con_trong'])) {
                $so_cho_con_lai = $data['so_cho_con_trong'];
            }

            $sql = "INSERT INTO lich_khoi_hanh (
                        id_tour, ngay_khoi_hanh, ngay_ket_thuc, gio_khoi_hanh, gio_tap_trung,
                        diem_tap_trung, so_cho, so_cho_da_dat, so_cho_con_lai, so_cho_con_trong,
                        gia_nguoi_lon, gia_tre_em, gia_tre_nho,
                        phuong_tien, uu_dai_giam_gia, ghi_chu, ghi_chu_van_hanh, chuongtrinh,
                        trang_thai, ngay_tao, ngay_cap_nhat
                    ) VALUES (
                        :id_tour, :ngay_khoi_hanh, :ngay_ket_thuc, :gio_khoi_hanh, :gio_tap_trung,
                        :diem_tap_trung, :so_cho, :so_cho_da_dat, :so_cho_con_lai, :so_cho_con_trong,
                        :gia_nguoi_lon, :gia_tre_em, :gia_tre_nho,
                        :phuong_tien, :uu_dai_giam_gia, :ghi_chu, :ghi_chu_van_hanh, :chuongtrinh,
                        :trang_thai, NOW(), NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_tour' => $data['id_tour'] ?? null,
                ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                ':gio_tap_trung' => $data['gio_tap_trung'] ?? null,
                ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                ':so_cho' => $so_cho,
                ':so_cho_da_dat' => $so_cho_da_dat,
                ':so_cho_con_lai' => $so_cho_con_lai,
                ':so_cho_con_trong' => $data['so_cho_con_trong'] ?? null,
                ':gia_nguoi_lon' => isset($data['gia_nguoi_lon']) && $data['gia_nguoi_lon'] !== '' ? (float)$data['gia_nguoi_lon'] : null,
                ':gia_tre_em' => isset($data['gia_tre_em']) && $data['gia_tre_em'] !== '' ? (float)$data['gia_tre_em'] : null,
                ':gia_tre_nho' => isset($data['gia_tre_nho']) && $data['gia_tre_nho'] !== '' ? (float)$data['gia_tre_nho'] : null,
                ':phuong_tien' => $data['phuong_tien'] ?? null,
                ':uu_dai_giam_gia' => isset($data['uu_dai_giam_gia']) && $data['uu_dai_giam_gia'] !== '' ? (float)$data['uu_dai_giam_gia'] : null,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
            ]);

            $this->lastError = null;
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $errorMsg = "Lỗi tạo lịch khởi hành: " . $e->getMessage();
            $errorMsg .= " | Code: " . $e->getCode();
            $errorMsg .= " | SQL: " . $sql;
            $errorMsg .= " | Data: " . print_r($data, true);
            error_log($errorMsg);
            
            // Lưu lỗi để có thể lấy sau
            $this->lastError = $e->getMessage();
            
            // Nếu lỗi do cột không tồn tại, thử tạo lại cột và retry
            if (strpos($e->getMessage(), 'phuong_tien') !== false || $e->getCode() == '42S22') {
                try {
                    $this->ensurePhuongTienColumnExists();
                    // Retry insert với các trường mới
                    $so_cho_retry = $data['so_cho'] ?? null;
                    $so_cho_da_dat_retry = $data['so_cho_da_dat'] ?? 0;
                    $so_cho_con_lai_retry = $data['so_cho_con_lai'] ?? null;
                    if ($so_cho_retry !== null && $so_cho_con_lai_retry === null) {
                        $so_cho_con_lai_retry = max(0, $so_cho_retry - $so_cho_da_dat_retry);
                    }
                    if ($so_cho_con_lai_retry === null && isset($data['so_cho_con_trong'])) {
                        $so_cho_con_lai_retry = $data['so_cho_con_trong'];
                    }

                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([
                        ':id_tour' => $data['id_tour'] ?? null,
                        ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                        ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                        ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                        ':gio_tap_trung' => $data['gio_tap_trung'] ?? null,
                        ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                        ':so_cho' => $so_cho_retry,
                        ':so_cho_da_dat' => $so_cho_da_dat_retry,
                        ':so_cho_con_lai' => $so_cho_con_lai_retry,
                        ':so_cho_con_trong' => $data['so_cho_con_trong'] ?? null,
                        ':gia_nguoi_lon' => isset($data['gia_nguoi_lon']) && $data['gia_nguoi_lon'] !== '' ? (float)$data['gia_nguoi_lon'] : null,
                        ':gia_tre_em' => isset($data['gia_tre_em']) && $data['gia_tre_em'] !== '' ? (float)$data['gia_tre_em'] : null,
                        ':gia_tre_nho' => isset($data['gia_tre_nho']) && $data['gia_tre_nho'] !== '' ? (float)$data['gia_tre_nho'] : null,
                        ':phuong_tien' => $data['phuong_tien'] ?? null,
                        ':uu_dai_giam_gia' => isset($data['uu_dai_giam_gia']) && $data['uu_dai_giam_gia'] !== '' ? (float)$data['uu_dai_giam_gia'] : null,
                        ':ghi_chu' => $data['ghi_chu'] ?? null,
                        ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                        ':chuongtrinh' => $data['chuongtrinh'] ?? null,
                        ':trang_thai' => $data['trang_thai'] ?? 1,
                    ]);
                    $this->lastError = null;
                    return $this->conn->lastInsertId();
                } catch (PDOException $e2) {
                    $this->lastError = $e2->getMessage();
                    error_log("Lỗi retry tạo lịch khởi hành: " . $e2->getMessage());
                    return false;
                }
            }
            
            return false;
        }
    }

    /**
     * Cập nhật lịch khởi hành
     */
    public function updateDeparturePlan($id, array $data)
    {
        try {
            // Tính toán so_cho_con_lai nếu có so_cho và so_cho_da_dat
            $so_cho = isset($data['so_cho']) && $data['so_cho'] !== '' ? (int)$data['so_cho'] : null;
            $so_cho_da_dat = isset($data['so_cho_da_dat']) && $data['so_cho_da_dat'] !== '' ? (int)$data['so_cho_da_dat'] : null;
            $so_cho_con_lai = isset($data['so_cho_con_lai']) && $data['so_cho_con_lai'] !== '' ? (int)$data['so_cho_con_lai'] : null;
            if ($so_cho !== null && $so_cho_da_dat !== null && $so_cho_con_lai === null) {
                $so_cho_con_lai = max(0, $so_cho - $so_cho_da_dat);
            }

            $sql = "UPDATE lich_khoi_hanh SET
                        id_tour = :id_tour,
                        ngay_khoi_hanh = :ngay_khoi_hanh,
                        ngay_ket_thuc = :ngay_ket_thuc,
                        gio_khoi_hanh = :gio_khoi_hanh,
                        gio_tap_trung = :gio_tap_trung,
                        diem_tap_trung = :diem_tap_trung,
                        so_cho = :so_cho,
                        so_cho_da_dat = :so_cho_da_dat,
                        so_cho_con_lai = :so_cho_con_lai,
                        so_cho_con_trong = :so_cho_con_trong,
                        gia_nguoi_lon = :gia_nguoi_lon,
                        gia_tre_em = :gia_tre_em,
                        gia_tre_nho = :gia_tre_nho,
                        phuong_tien = :phuong_tien,
                        uu_dai_giam_gia = :uu_dai_giam_gia,
                        ghi_chu = :ghi_chu,
                        ghi_chu_van_hanh = :ghi_chu_van_hanh,
                        chuongtrinh = :chuongtrinh,
                        trang_thai = :trang_thai,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':id_tour' => $data['id_tour'] ?? null,
                ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                ':gio_tap_trung' => $data['gio_tap_trung'] ?? null,
                ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                ':so_cho' => $so_cho,
                ':so_cho_da_dat' => $so_cho_da_dat,
                ':so_cho_con_lai' => $so_cho_con_lai,
                ':so_cho_con_trong' => $data['so_cho_con_trong'] ?? null,
                ':gia_nguoi_lon' => isset($data['gia_nguoi_lon']) && $data['gia_nguoi_lon'] !== '' ? (float)$data['gia_nguoi_lon'] : null,
                ':gia_tre_em' => isset($data['gia_tre_em']) && $data['gia_tre_em'] !== '' ? (float)$data['gia_tre_em'] : null,
                ':gia_tre_nho' => isset($data['gia_tre_nho']) && $data['gia_tre_nho'] !== '' ? (float)$data['gia_tre_nho'] : null,
                ':phuong_tien' => $data['phuong_tien'] ?? null,
                ':uu_dai_giam_gia' => isset($data['uu_dai_giam_gia']) && $data['uu_dai_giam_gia'] !== '' ? (float)$data['uu_dai_giam_gia'] : null,
                ':ghi_chu' => $data['ghi_chu'] ?? null,
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                ':chuongtrinh' => $data['chuongtrinh'] ?? null,
                ':trang_thai' => $data['trang_thai'] ?? 1,
            ]);
            
            // Nếu lỗi do cột không tồn tại, thử tạo lại cột và retry
            if (!$result && (strpos($stmt->errorInfo()[2] ?? '', 'phuong_tien') !== false)) {
                try {
                    $this->ensurePhuongTienColumnExists();
                    // Retry update với các trường mới - cần prepare lại statement
                    $so_cho_retry = isset($data['so_cho']) && $data['so_cho'] !== '' ? (int)$data['so_cho'] : null;
                    $so_cho_da_dat_retry = isset($data['so_cho_da_dat']) && $data['so_cho_da_dat'] !== '' ? (int)$data['so_cho_da_dat'] : null;
                    $so_cho_con_lai_retry = isset($data['so_cho_con_lai']) && $data['so_cho_con_lai'] !== '' ? (int)$data['so_cho_con_lai'] : null;
                    if ($so_cho_retry !== null && $so_cho_da_dat_retry !== null && $so_cho_con_lai_retry === null) {
                        $so_cho_con_lai_retry = max(0, $so_cho_retry - $so_cho_da_dat_retry);
                    }

                    $stmt_retry = $this->conn->prepare($sql);
                    return $stmt_retry->execute([
                        ':id' => $id,
                        ':id_tour' => $data['id_tour'] ?? null,
                        ':ngay_khoi_hanh' => $data['ngay_khoi_hanh'] ?? null,
                        ':ngay_ket_thuc' => $data['ngay_ket_thuc'] ?? null,
                        ':gio_khoi_hanh' => $data['gio_khoi_hanh'] ?? null,
                        ':gio_tap_trung' => $data['gio_tap_trung'] ?? null,
                        ':diem_tap_trung' => $data['diem_tap_trung'] ?? null,
                        ':so_cho' => $so_cho_retry,
                        ':so_cho_da_dat' => $so_cho_da_dat_retry,
                        ':so_cho_con_lai' => $so_cho_con_lai_retry,
                        ':so_cho_con_trong' => $data['so_cho_con_trong'] ?? null,
                        ':gia_nguoi_lon' => isset($data['gia_nguoi_lon']) && $data['gia_nguoi_lon'] !== '' ? (float)$data['gia_nguoi_lon'] : null,
                        ':gia_tre_em' => isset($data['gia_tre_em']) && $data['gia_tre_em'] !== '' ? (float)$data['gia_tre_em'] : null,
                        ':gia_tre_nho' => isset($data['gia_tre_nho']) && $data['gia_tre_nho'] !== '' ? (float)$data['gia_tre_nho'] : null,
                        ':phuong_tien' => $data['phuong_tien'] ?? null,
                        ':uu_dai_giam_gia' => isset($data['uu_dai_giam_gia']) && $data['uu_dai_giam_gia'] !== '' ? (float)$data['uu_dai_giam_gia'] : null,
                        ':ghi_chu' => $data['ghi_chu'] ?? null,
                        ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'] ?? null,
                        ':chuongtrinh' => $data['chuongtrinh'] ?? null,
                        ':trang_thai' => $data['trang_thai'] ?? 1,
                    ]);
                } catch (PDOException $e2) {
                    error_log("Lỗi retry cập nhật lịch khởi hành: " . $e2->getMessage());
                    return false;
                }
            }
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                $errorMsg = "Lỗi cập nhật lịch khởi hành: " . ($errorInfo[2] ?? 'Unknown error');
                $errorMsg .= " | Code: " . ($errorInfo[0] ?? 'N/A');
                $errorMsg .= " | SQL: " . $sql;
                error_log($errorMsg);
            }
            
            return $result;
        } catch (PDOException $e) {
            $errorMsg = "Lỗi cập nhật lịch khởi hành: " . $e->getMessage();
            $errorMsg .= " | Code: " . $e->getCode();
            $errorMsg .= " | SQL: " . ($sql ?? 'N/A');
            error_log($errorMsg);
            return false;
        }
    }

    /**
     * Xóa lịch khởi hành
     */
    public function deleteDeparturePlan($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM lich_khoi_hanh WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa lịch khởi hành: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle trạng thái lịch khởi hành
     */
    public function toggleStatus($id)
    {
        try {
            $sql = "UPDATE lich_khoi_hanh 
                    SET trang_thai = NOT trang_thai, ngay_cap_nhat = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi toggle trạng thái: " . $e->getMessage());
            return false;
        }
    }
}

