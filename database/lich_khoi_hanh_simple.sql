-- ============================================================
-- UC-Departure-Plan: Bảng Lịch Khởi Hành (Version Đơn Giản)
-- Chỉ tạo bảng cơ bản, không có view/procedure
-- ============================================================

CREATE TABLE IF NOT EXISTS `lich_khoi_hanh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tour` int(11) DEFAULT NULL COMMENT 'ID tour',
  `ngay_khoi_hanh` date NOT NULL COMMENT 'Ngày khởi hành',
  `gio_khoi_hanh` time NOT NULL COMMENT 'Giờ khởi hành',
  `diem_tap_trung` varchar(255) NOT NULL COMMENT 'Điểm tập trung',
  `so_cho_du_kien` int(11) DEFAULT NULL COMMENT 'Số chỗ dự kiến',
  `ghi_chu_van_hanh` text DEFAULT NULL COMMENT 'Ghi chú vận hành',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1=Hoạt động, 0=Tạm dừng',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_tour` (`id_tour`),
  KEY `idx_ngay_khoi_hanh` (`ngay_khoi_hanh`),
  CONSTRAINT `fk_lich_khoi_hanh_tour` FOREIGN KEY (`id_tour`) REFERENCES `goidulich` (`id_goi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

