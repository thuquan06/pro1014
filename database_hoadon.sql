-- ============================================
-- SQL Migration: Bảng hoadon (Hóa đơn/Booking)
-- Database: starvel
-- Created: 2025
-- ============================================

-- Tạo bảng hoadon nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `hoadon` (
  `id_hoadon` int(11) NOT NULL AUTO_INCREMENT,
  `id_goi` int(11) DEFAULT NULL COMMENT 'ID gói du lịch',
  `id_ks` int(11) DEFAULT NULL COMMENT 'ID khách sạn (nếu có)',
  `email_nguoidung` varchar(100) NOT NULL COMMENT 'Email người đặt',
  `nguoilon` int(11) NOT NULL DEFAULT 1 COMMENT 'Số người lớn',
  `treem` int(11) NOT NULL DEFAULT 0 COMMENT 'Số trẻ em (6-11 tuổi)',
  `trenho` int(11) NOT NULL DEFAULT 0 COMMENT 'Số trẻ nhỏ (2-5 tuổi)',
  `embe` int(11) NOT NULL DEFAULT 0 COMMENT 'Số em bé (dưới 2 tuổi)',
  `phongdon` int(3) NOT NULL DEFAULT 0 COMMENT 'Số phòng đơn',
  `ngayvao` date DEFAULT NULL COMMENT 'Ngày check-in',
  `ngayra` date DEFAULT NULL COMMENT 'Ngày check-out',
  `sophong` int(3) NOT NULL DEFAULT 1 COMMENT 'Tổng số phòng',
  `ghichu` varchar(100) DEFAULT NULL COMMENT 'Ghi chú đặc biệt',
  `huy` varchar(100) NOT NULL DEFAULT '0' COMMENT 'Trạng thái hủy: 0=chưa hủy, 1=đã hủy',
  `ngaydat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày đặt hóa đơn',
  `ngaycapnhat` timestamp NULL DEFAULT NULL COMMENT 'Ngày cập nhật gần nhất',
  `trangthai` int(11) NOT NULL DEFAULT 0 COMMENT 'Trạng thái: 0=Chờ xác nhận, 1=Đã xác nhận, 2=Hoàn thành, 3=Đã hủy',
  PRIMARY KEY (`id_hoadon`),
  KEY `idx_email` (`email_nguoidung`),
  KEY `idx_trangthai` (`trangthai`),
  KEY `idx_ngaydat` (`ngaydat`),
  KEY `fk_hoadon_goi` (`id_goi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý hóa đơn/booking';

-- Thêm foreign key constraint (nếu bảng goidulich tồn tại)
-- Bỏ comment dòng dưới nếu muốn sử dụng foreign key
-- ALTER TABLE `hoadon` 
--   ADD CONSTRAINT `fk_hoadon_goi` FOREIGN KEY (`id_goi`) REFERENCES `goidulich` (`id_goi`) ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- Dữ liệu mẫu (Sample data) - Có thể xóa nếu không cần
-- ============================================

INSERT INTO `hoadon` (`id_goi`, `id_ks`, `email_nguoidung`, `nguoilon`, `treem`, `trenho`, `embe`, `phongdon`, `ngayvao`, `ngayra`, `sophong`, `ghichu`, `trangthai`, `ngaydat`) VALUES
(1, NULL, 'nguyen.van.a@example.com', 2, 1, 0, 0, 0, '2025-12-01', '2025-12-05', 1, 'Cần phòng view biển', 0, NOW()),
(1, NULL, 'tran.thi.b@example.com', 2, 2, 1, 0, 1, '2025-12-10', '2025-12-15', 2, '', 1, NOW()),
(2, NULL, 'le.van.c@example.com', 4, 0, 0, 1, 0, '2025-12-20', '2025-12-25', 2, 'Tour gia đình', 2, NOW());

-- ============================================
-- Ghi chú:
-- - Trường trangthai: 0=Chờ xác nhận, 1=Đã xác nhận, 2=Hoàn thành, 3=Đã hủy
-- - Trường huy: 0=Chưa hủy, 1=Đã hủy (dùng để đánh dấu hóa đơn bị hủy)
-- - Trường ngaycapnhat: Tự động cập nhật khi có thay đổi
-- - Indexes được tạo cho các trường thường xuyên query
-- ============================================
