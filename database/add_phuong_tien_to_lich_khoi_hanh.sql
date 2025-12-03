-- ============================================
-- Thêm cột phương tiện vào bảng lich_khoi_hanh
-- Chạy file này trong phpMyAdmin hoặc MySQL client
-- ============================================

-- Thêm cột phuong_tien vào bảng lich_khoi_hanh
ALTER TABLE `lich_khoi_hanh` 
ADD COLUMN `phuong_tien` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Phương tiện di chuyển (xe khách, máy bay, tàu hỏa, v.v.)' 
AFTER `so_cho_con_trong`;

-- Kiểm tra cấu trúc bảng sau khi thêm
-- DESCRIBE lich_khoi_hanh;

