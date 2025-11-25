-- ============================================================
-- UC-Departure-Plan: Bảng Lịch Khởi Hành
-- Tạo bảng lich_khoi_hanh để quản lý lịch khởi hành của các tour
-- ============================================================

-- Xóa bảng nếu đã tồn tại (chỉ dùng khi cần reset)
-- DROP TABLE IF EXISTS `lich_khoi_hanh`;

-- Tạo bảng lich_khoi_hanh
CREATE TABLE IF NOT EXISTS `lich_khoi_hanh` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID lịch khởi hành',
  `id_tour` int(11) DEFAULT NULL COMMENT 'ID tour (foreign key đến goidulich)',
  `ngay_khoi_hanh` date NOT NULL COMMENT 'Ngày khởi hành',
  `gio_khoi_hanh` time NOT NULL COMMENT 'Giờ khởi hành',
  `diem_tap_trung` varchar(255) NOT NULL COMMENT 'Điểm tập trung',
  `so_cho_du_kien` int(11) DEFAULT NULL COMMENT 'Số chỗ dự kiến (nếu cần)',
  `ghi_chu_van_hanh` text DEFAULT NULL COMMENT 'Ghi chú vận hành',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT 'Trạng thái: 1=Hoạt động, 0=Tạm dừng',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`),
  KEY `idx_id_tour` (`id_tour`),
  KEY `idx_ngay_khoi_hanh` (`ngay_khoi_hanh`),
  KEY `idx_trang_thai` (`trang_thai`),
  KEY `idx_ngay_gio_khoi_hanh` (`ngay_khoi_hanh`, `gio_khoi_hanh`),
  CONSTRAINT `fk_lich_khoi_hanh_tour` FOREIGN KEY (`id_tour`) REFERENCES `goidulich` (`id_goi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lịch khởi hành tour';

-- ============================================================
-- Dữ liệu mẫu (Sample Data)
-- ============================================================

-- Chèn dữ liệu mẫu (chỉ chạy nếu đã có tour trong bảng goidulich)
-- Lưu ý: Thay đổi id_tour phù hợp với dữ liệu tour thực tế của bạn

/*
INSERT INTO `lich_khoi_hanh` (`id_tour`, `ngay_khoi_hanh`, `gio_khoi_hanh`, `diem_tap_trung`, `so_cho_du_kien`, `ghi_chu_van_hanh`, `trang_thai`) VALUES
(1, '2025-12-01', '08:00:00', 'Sân bay Nội Bài, Hà Nội', 30, 'Tập trung tại cổng số 2, check-in trước 1 giờ', 1),
(1, '2025-12-15', '08:00:00', 'Sân bay Nội Bài, Hà Nội', 30, 'Tập trung tại cổng số 2, check-in trước 1 giờ', 1),
(2, '2025-12-10', '07:30:00', 'Khách sạn Grand Plaza, TP.HCM', 25, 'Xe đón tại sảnh khách sạn lúc 7:30', 1),
(2, '2025-12-25', '07:30:00', 'Khách sạn Grand Plaza, TP.HCM', 25, 'Xe đón tại sảnh khách sạn lúc 7:30', 1),
(3, '2025-12-05', '09:00:00', 'Bến xe Mỹ Đình, Hà Nội', 40, 'Xe khởi hành đúng giờ, không chờ khách muộn', 1);
*/

-- ============================================================
-- Các View hữu ích (Optional)
-- ============================================================

-- View: Lịch khởi hành với thông tin tour đầy đủ
CREATE OR REPLACE VIEW `v_lich_khoi_hanh_full` AS
SELECT 
    lkh.id,
    lkh.id_tour,
    g.tengoi AS ten_tour,
    g.vitri AS dia_diem_tour,
    g.giagoi,
    lkh.ngay_khoi_hanh,
    lkh.gio_khoi_hanh,
    CONCAT(DATE_FORMAT(lkh.ngay_khoi_hanh, '%d/%m/%Y'), ' ', TIME_FORMAT(lkh.gio_khoi_hanh, '%H:%i')) AS ngay_gio_khoi_hanh,
    lkh.diem_tap_trung,
    lkh.so_cho_du_kien,
    lkh.ghi_chu_van_hanh,
    CASE 
        WHEN lkh.trang_thai = 1 THEN 'Hoạt động'
        ELSE 'Tạm dừng'
    END AS trang_thai_text,
    lkh.trang_thai,
    lkh.ngay_tao,
    lkh.ngay_cap_nhat
FROM 
    lich_khoi_hanh lkh
LEFT JOIN 
    goidulich g ON lkh.id_tour = g.id_goi
ORDER BY 
    lkh.ngay_khoi_hanh DESC, lkh.gio_khoi_hanh ASC;

-- View: Lịch khởi hành sắp tới (trong 30 ngày tới)
CREATE OR REPLACE VIEW `v_lich_khoi_hanh_sap_toi` AS
SELECT 
    lkh.*,
    g.tengoi AS ten_tour,
    g.vitri AS dia_diem_tour,
    DATEDIFF(lkh.ngay_khoi_hanh, CURDATE()) AS so_ngay_con_lai
FROM 
    lich_khoi_hanh lkh
LEFT JOIN 
    goidulich g ON lkh.id_tour = g.id_goi
WHERE 
    lkh.trang_thai = 1
    AND lkh.ngay_khoi_hanh >= CURDATE()
    AND lkh.ngay_khoi_hanh <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
ORDER BY 
    lkh.ngay_khoi_hanh ASC, lkh.gio_khoi_hanh ASC;

-- ============================================================
-- Các Stored Procedure hữu ích (Optional)
-- ============================================================

-- Procedure: Lấy lịch khởi hành theo tour ID
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_get_lich_khoi_hanh_by_tour`(IN p_id_tour INT)
BEGIN
    SELECT 
        lkh.*,
        g.tengoi AS ten_tour
    FROM 
        lich_khoi_hanh lkh
    LEFT JOIN 
        goidulich g ON lkh.id_tour = g.id_goi
    WHERE 
        lkh.id_tour = p_id_tour
        AND lkh.trang_thai = 1
    ORDER BY 
        lkh.ngay_khoi_hanh ASC, lkh.gio_khoi_hanh ASC;
END //
DELIMITER ;

-- Procedure: Đếm số lịch khởi hành theo tour
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_count_lich_khoi_hanh_by_tour`(IN p_id_tour INT, OUT p_count INT)
BEGIN
    SELECT COUNT(*) INTO p_count
    FROM lich_khoi_hanh
    WHERE id_tour = p_id_tour AND trang_thai = 1;
END //
DELIMITER ;

-- ============================================================
-- Các Trigger (Optional)
-- ============================================================

-- Trigger: Log khi tạo lịch khởi hành mới
/*
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `trg_lich_khoi_hanh_insert`
AFTER INSERT ON `lich_khoi_hanh`
FOR EACH ROW
BEGIN
    INSERT INTO log_actions (table_name, action, record_id, created_at)
    VALUES ('lich_khoi_hanh', 'INSERT', NEW.id, NOW());
END //
DELIMITER ;
*/

-- ============================================================
-- Các Query hữu ích
-- ============================================================

-- Query 1: Lấy tất cả lịch khởi hành đang hoạt động
-- SELECT * FROM v_lich_khoi_hanh_full WHERE trang_thai = 1 ORDER BY ngay_khoi_hanh ASC;

-- Query 2: Lấy lịch khởi hành sắp tới
-- SELECT * FROM v_lich_khoi_hanh_sap_toi;

-- Query 3: Đếm số lịch khởi hành theo tour
-- SELECT id_tour, COUNT(*) as so_lich_khoi_hanh 
-- FROM lich_khoi_hanh 
-- WHERE trang_thai = 1 
-- GROUP BY id_tour;

-- Query 4: Lấy lịch khởi hành trong khoảng thời gian
-- SELECT * FROM lich_khoi_hanh 
-- WHERE ngay_khoi_hanh BETWEEN '2025-12-01' AND '2025-12-31'
-- AND trang_thai = 1
-- ORDER BY ngay_khoi_hanh, gio_khoi_hanh;

-- Query 5: Tìm lịch khởi hành còn chỗ trống
-- SELECT lkh.*, g.tengoi,
--        (lkh.so_cho_du_kien - COALESCE(COUNT(h.id_hoadon), 0)) as so_cho_con_lai
-- FROM lich_khoi_hanh lkh
-- LEFT JOIN goidulich g ON lkh.id_tour = g.id_goi
-- LEFT JOIN hoadon h ON h.id_goi = lkh.id_tour AND h.ngayvao = lkh.ngay_khoi_hanh
-- WHERE lkh.trang_thai = 1
-- GROUP BY lkh.id
-- HAVING so_cho_con_lai > 0 OR lkh.so_cho_du_kien IS NULL;

-- ============================================================
-- Index Optimization
-- ============================================================

-- Thêm index composite cho truy vấn thường dùng
-- CREATE INDEX idx_tour_ngay_trangthai ON lich_khoi_hanh(id_tour, ngay_khoi_hanh, trang_thai);

-- ============================================================
-- Notes
-- ============================================================
-- 1. Bảng này liên kết với bảng goidulich qua foreign key
-- 2. Khi xóa tour, lịch khởi hành sẽ được set id_tour = NULL (ON DELETE SET NULL)
-- 3. Khi cập nhật id tour, lịch khởi hành sẽ tự động cập nhật (ON UPDATE CASCADE)
-- 4. Các index được tạo để tối ưu truy vấn theo tour, ngày khởi hành và trạng thái
-- 5. View và Stored Procedure là optional, có thể bỏ qua nếu không cần
