-- ============================================================
-- UC-Assign-Services: Bảng Dịch vụ và Gán dịch vụ
-- Gán dịch vụ nội bộ/đối tác (xe, KS, nhà hàng, vé tham quan)
-- Theo dõi trạng thái "đã xác nhận/chờ"
-- ============================================================

-- Bảng 1: Dịch vụ (Services)
CREATE TABLE IF NOT EXISTS `dich_vu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID dịch vụ',
  `ten_dich_vu` varchar(255) NOT NULL COMMENT 'Tên dịch vụ',
  `loai_dich_vu` varchar(50) NOT NULL COMMENT 'Loại: xe, khach_san, nha_hang, ve_tham_quan',
  `mo_ta` text DEFAULT NULL COMMENT 'Mô tả dịch vụ',
  `nha_cung_cap` varchar(255) DEFAULT NULL COMMENT 'Nhà cung cấp/Đối tác',
  `lien_he` varchar(255) DEFAULT NULL COMMENT 'Thông tin liên hệ',
  `gia` decimal(15,2) DEFAULT NULL COMMENT 'Giá dịch vụ',
  `don_vi` varchar(50) DEFAULT NULL COMMENT 'Đơn vị tính: chuyến, đêm, bữa, vé',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1=Hoạt động, 0=Tạm dừng',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_loai_dich_vu` (`loai_dich_vu`),
  KEY `idx_trang_thai` (`trang_thai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng dịch vụ';

-- Bảng 2: Gán dịch vụ cho lịch khởi hành
CREATE TABLE IF NOT EXISTS `gan_dich_vu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID gán dịch vụ',
  `id_lich_khoi_hanh` int(11) NOT NULL COMMENT 'ID lịch khởi hành',
  `id_dich_vu` int(11) NOT NULL COMMENT 'ID dịch vụ',
  `so_luong` int(11) DEFAULT 1 COMMENT 'Số lượng',
  `ngay_su_dung` date DEFAULT NULL COMMENT 'Ngày sử dụng dịch vụ',
  `gia_thuc_te` decimal(15,2) DEFAULT NULL COMMENT 'Giá thực tế (có thể khác giá mặc định)',
  `trang_thai` varchar(50) DEFAULT 'cho' COMMENT 'Trạng thái: cho (chờ), da_xac_nhan (đã xác nhận), huy (hủy)',
  `ngay_xac_nhan` datetime DEFAULT NULL COMMENT 'Ngày xác nhận',
  `nguoi_xac_nhan` varchar(255) DEFAULT NULL COMMENT 'Người xác nhận',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_lich_khoi_hanh` (`id_lich_khoi_hanh`),
  KEY `idx_id_dich_vu` (`id_dich_vu`),
  KEY `idx_trang_thai` (`trang_thai`),
  CONSTRAINT `fk_gan_dich_vu_departure` FOREIGN KEY (`id_lich_khoi_hanh`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_gan_dich_vu_service` FOREIGN KEY (`id_dich_vu`) REFERENCES `dich_vu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng gán dịch vụ';

