-- ============================================================
-- UC-Guide-IncidentReport: Bảng Báo cáo sự cố
-- Quản lý báo cáo sự cố của hướng dẫn viên (mất đồ, say xe, trễ giờ…)
-- ============================================================

-- Tạo bảng bao_cao_su_co (Báo cáo sự cố)
CREATE TABLE IF NOT EXISTS `bao_cao_su_co` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID báo cáo',
  `id_phan_cong` int(11) DEFAULT NULL COMMENT 'ID phân công HDV (FK: phan_cong_hdv.id)',
  `loai_su_co` varchar(50) DEFAULT NULL COMMENT 'Loại sự cố (mat_do, say_xe, tre_gio, thoi_tiet_xau, tai_nan, benh_tat, thuc_an, khach_san, phuong_tien, khach_dac_biet, khac)',
  `mo_ta` text DEFAULT NULL COMMENT 'Mô tả chi tiết về sự cố',
  `cach_xu_ly` text DEFAULT NULL COMMENT 'Cách xử lý sự cố',
  `goi_y_xu_ly` text DEFAULT NULL COMMENT 'Gợi ý xử lý (JSON)',
  `muc_do` enum('thap','trung_binh','cao','nghiem_trong') DEFAULT 'thap' COMMENT 'Mức độ nghiêm trọng',
  `ngay_xay_ra` date DEFAULT NULL COMMENT 'Ngày xảy ra sự cố',
  `gio_xay_ra` time DEFAULT NULL COMMENT 'Giờ xảy ra sự cố',
  `vi_tri_gps` varchar(255) DEFAULT NULL COMMENT 'Vị trí GPS (lat,lng)',
  `hinh_anh` text DEFAULT NULL COMMENT 'Danh sách hình ảnh (JSON array)',
  `thong_tin_khach` text DEFAULT NULL COMMENT 'Thông tin khách hàng liên quan',
  `da_gui_bao_cao` tinyint(1) DEFAULT 0 COMMENT 'Đã gửi báo cáo (0=Chưa, 1=Đã gửi)',
  `ngay_gui_bao_cao` datetime DEFAULT NULL COMMENT 'Ngày giờ gửi báo cáo',
  `nguoi_nhan_bao_cao` varchar(255) DEFAULT NULL COMMENT 'Email người nhận báo cáo',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`),
  KEY `idx_id_phan_cong` (`id_phan_cong`),
  KEY `idx_loai_su_co` (`loai_su_co`),
  KEY `idx_muc_do` (`muc_do`),
  KEY `idx_ngay_xay_ra` (`ngay_xay_ra`),
  KEY `idx_ngay_tao` (`ngay_tao`),
  CONSTRAINT `fk_bao_cao_su_co_phan_cong` FOREIGN KEY (`id_phan_cong`) REFERENCES `phan_cong_hdv` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý báo cáo sự cố của hướng dẫn viên';

-- ============================================================
-- Notes
-- ============================================================
-- 1. id_phan_cong: Liên kết với bảng phan_cong_hdv để biết phân công nào
-- 2. loai_su_co: Các loại sự cố phổ biến:
--    - mat_do: Mất đồ
--    - say_xe: Say xe / Vấn đề sức khỏe nhẹ
--    - tre_gio: Trễ giờ / Lạc đoàn
--    - thoi_tiet_xau: Thời tiết xấu
--    - tai_nan: Tai nạn
--    - benh_tat: Bệnh tật
--    - thuc_an: Vấn đề thức ăn
--    - khach_san: Vấn đề khách sạn
--    - phuong_tien: Sự cố giao thông / Phương tiện
--    - khach_dac_biet: Sự cố khách đặc biệt
--    - khac: Khác
-- 3. muc_do: Mức độ nghiêm trọng (thap, trung_binh, cao, nghiem_trong)
-- 4. hinh_anh: Lưu dưới dạng JSON array: ["image1.jpg", "image2.jpg"]
-- 5. goi_y_xu_ly: Lưu dưới dạng JSON với các gợi ý xử lý
-- 6. vi_tri_gps: Format "lat,lng" ví dụ: "10.762622,106.660172"

