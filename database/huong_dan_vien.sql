-- ============================================================
-- UC-Assign-Guide: Bảng Hướng Dẫn Viên và Phân Công
-- Phân công HDV theo kỹ năng/tuyến/ngôn ngữ; cảnh báo trùng lịch
-- ============================================================

-- Bảng 1: Hướng dẫn viên
CREATE TABLE IF NOT EXISTS `huong_dan_vien` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID hướng dẫn viên',
  `ho_ten` varchar(255) NOT NULL COMMENT 'Họ tên HDV',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `so_dien_thoai` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại',
  `cmnd_cccd` varchar(20) DEFAULT NULL COMMENT 'CMND/CCCD',
  `dia_chi` text DEFAULT NULL COMMENT 'Địa chỉ',
  `ky_nang` text DEFAULT NULL COMMENT 'Kỹ năng (JSON: ["Hiking", "Swimming", "Photography"])',
  `tuyen_chuyen` text DEFAULT NULL COMMENT 'Tuyến chuyên (JSON: ["Miền Bắc", "Miền Trung", "Miền Nam"])',
  `ngon_ngu` text DEFAULT NULL COMMENT 'Ngôn ngữ (JSON: ["Tiếng Việt", "English", "中文"])',
  `kinh_nghiem` int(11) DEFAULT 0 COMMENT 'Số năm kinh nghiệm',
  `danh_gia` decimal(3,2) DEFAULT 0.00 COMMENT 'Đánh giá trung bình (0-5)',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1=Hoạt động, 0=Tạm dừng',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_trang_thai` (`trang_thai`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng hướng dẫn viên';

-- Bảng 2: Phân công HDV cho lịch khởi hành
CREATE TABLE IF NOT EXISTS `phan_cong_hdv` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID phân công',
  `id_lich_khoi_hanh` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `id_hdv` int(11) NOT NULL COMMENT 'ID hướng dẫn viên',
  `vai_tro` varchar(50) DEFAULT 'HDV chính' COMMENT 'Vai trò: HDV chính, HDV phụ, Trợ lý',
  `ngay_bat_dau` date NOT NULL COMMENT 'Ngày bắt đầu',
  `ngay_ket_thuc` date NOT NULL COMMENT 'Ngày kết thúc',
  `luong` decimal(15,2) DEFAULT NULL COMMENT 'Lương/Thù lao',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1=Đã phân công, 0=Đã hủy',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú phân công',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_lich_khoi_hanh` (`id_lich_khoi_hanh`),
  KEY `idx_id_hdv` (`id_hdv`),
  KEY `idx_ngay_bat_dau` (`ngay_bat_dau`),
  KEY `idx_ngay_ket_thuc` (`ngay_ket_thuc`),
  KEY `idx_trang_thai` (`trang_thai`),
  CONSTRAINT `fk_phan_cong_hdv_departure` FOREIGN KEY (`id_lich_khoi_hanh`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_phan_cong_hdv_hdv` FOREIGN KEY (`id_hdv`) REFERENCES `huong_dan_vien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng phân công HDV';

-- Index để kiểm tra trùng lịch nhanh hơn
CREATE INDEX `idx_check_overlap` ON `phan_cong_hdv` (`id_hdv`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai`);

-- ============================================================
-- Dữ liệu mẫu (Sample Data)
-- ============================================================
/*
INSERT INTO `huong_dan_vien` (`ho_ten`, `email`, `so_dien_thoai`, `ky_nang`, `tuyen_chuyen`, `ngon_ngu`, `kinh_nghiem`, `danh_gia`) VALUES
('Nguyễn Văn A', 'nguyenvana@example.com', '0912345678', '["Hiking", "Swimming", "Photography"]', '["Miền Bắc", "Miền Trung"]', '["Tiếng Việt", "English"]', 5, 4.5),
('Trần Thị B', 'tranthib@example.com', '0987654321', '["Cooking", "Culture"]', '["Miền Nam"]', '["Tiếng Việt", "中文"]', 3, 4.2),
('Lê Văn C', 'levanc@example.com', '0901234567', '["Adventure", "Wildlife"]', '["Miền Trung", "Miền Nam"]', '["Tiếng Việt", "English", "Français"]', 7, 4.8);
*/

