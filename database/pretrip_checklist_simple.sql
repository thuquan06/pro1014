-- ============================================================
-- UC-Pretrip-Checklist: Bảng Checklist Trước Ngày Đi
-- Tạo bảng pretrip_checklist để quản lý checklist trước ngày đi
-- ============================================================

-- Tạo bảng pretrip_checklist
CREATE TABLE IF NOT EXISTS `pretrip_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID checklist',
  `id_lich_khoi_hanh` int(11) DEFAULT NULL COMMENT 'ID lịch khởi hành (foreign key đến lich_khoi_hanh)',
  `checklist_items` text DEFAULT NULL COMMENT 'Danh sách checklist items dạng JSON',
  `trang_thai` tinyint(1) DEFAULT 0 COMMENT 'Trạng thái: 1=Ready (tất cả đã tick), 0=Chưa ready',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú bổ sung',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`),
  KEY `idx_id_lich_khoi_hanh` (`id_lich_khoi_hanh`),
  KEY `idx_trang_thai` (`trang_thai`),
  CONSTRAINT `fk_pretrip_checklist_departure` FOREIGN KEY (`id_lich_khoi_hanh`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng checklist trước ngày đi';

