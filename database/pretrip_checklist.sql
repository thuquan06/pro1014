-- ============================================================
-- UC-Pretrip-Checklist: Bảng Checklist Trước Ngày Đi
-- Tạo bảng pretrip_checklist để quản lý checklist trước ngày đi
-- ============================================================

-- Xóa bảng nếu đã tồn tại (chỉ dùng khi cần reset)
-- DROP TABLE IF EXISTS `pretrip_checklist`;

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

-- ============================================================
-- Cấu trúc JSON của checklist_items
-- ============================================================
/*
{
  "tai_lieu": {
    "label": "Tài liệu",
    "checked": false
  },
  "bang_ten": {
    "label": "Bảng tên",
    "checked": false
  },
  "dung_cu_y_te": {
    "label": "Dụng cụ y tế",
    "checked": false
  },
  "ve": {
    "label": "Vé",
    "checked": false
  },
  "phong": {
    "label": "Phòng",
    "checked": false
  },
  "xe": {
    "label": "Xe/Phương tiện",
    "checked": false
  },
  "huong_dan_vien": {
    "label": "Hướng dẫn viên",
    "checked": false
  },
  "thuc_an": {
    "label": "Thức ăn/Đồ uống",
    "checked": false
  }
}
*/

-- ============================================================
-- Dữ liệu mẫu (Sample Data)
-- ============================================================
/*
INSERT INTO `pretrip_checklist` (`id_lich_khoi_hanh`, `checklist_items`, `trang_thai`, `ghi_chu`) VALUES
(1, '{"tai_lieu":{"label":"Tài liệu","checked":true},"bang_ten":{"label":"Bảng tên","checked":true},"dung_cu_y_te":{"label":"Dụng cụ y tế","checked":false},"ve":{"label":"Vé","checked":true},"phong":{"label":"Phòng","checked":true},"xe":{"label":"Xe/Phương tiện","checked":false},"huong_dan_vien":{"label":"Hướng dẫn viên","checked":true},"thuc_an":{"label":"Thức ăn/Đồ uống","checked":false}}', 0, 'Cần chuẩn bị thêm dụng cụ y tế'),
(2, '{"tai_lieu":{"label":"Tài liệu","checked":true},"bang_ten":{"label":"Bảng tên","checked":true},"dung_cu_y_te":{"label":"Dụng cụ y tế","checked":true},"ve":{"label":"Vé","checked":true},"phong":{"label":"Phòng","checked":true},"xe":{"label":"Xe/Phương tiện","checked":true},"huong_dan_vien":{"label":"Hướng dẫn viên","checked":true},"thuc_an":{"label":"Thức ăn/Đồ uống","checked":true}}', 1, 'Tất cả đã sẵn sàng');
*/

-- ============================================================
-- View hữu ích (Optional)
-- ============================================================

-- View: Checklist với thông tin đầy đủ
CREATE OR REPLACE VIEW `v_pretrip_checklist_full` AS
SELECT 
    c.id,
    c.id_lich_khoi_hanh,
    dp.ngay_khoi_hanh,
    dp.gio_khoi_hanh,
    dp.diem_tap_trung,
    g.tengoi AS ten_tour,
    g.id_goi AS id_tour,
    c.checklist_items,
    c.trang_thai,
    CASE 
        WHEN c.trang_thai = 1 THEN 'Ready'
        ELSE 'Chưa Ready'
    END AS trang_thai_text,
    c.ghi_chu,
    c.ngay_tao,
    c.ngay_cap_nhat
FROM 
    pretrip_checklist c
LEFT JOIN 
    lich_khoi_hanh dp ON c.id_lich_khoi_hanh = dp.id
LEFT JOIN 
    goidulich g ON dp.id_tour = g.id_goi
ORDER BY 
    c.ngay_cap_nhat DESC;

-- ============================================================
-- Query hữu ích
-- ============================================================

-- Query 1: Lấy checklist chưa ready
-- SELECT * FROM v_pretrip_checklist_full WHERE trang_thai = 0;

-- Query 2: Lấy checklist đã ready
-- SELECT * FROM v_pretrip_checklist_full WHERE trang_thai = 1;

-- Query 3: Đếm số checklist theo trạng thái
-- SELECT trang_thai, COUNT(*) as so_luong 
-- FROM pretrip_checklist 
-- GROUP BY trang_thai;

