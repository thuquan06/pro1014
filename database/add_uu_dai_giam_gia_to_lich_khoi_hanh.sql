-- ============================================
-- Thêm cột ưu đãi giảm giá vào bảng lich_khoi_hanh
-- Chạy file này trong phpMyAdmin hoặc MySQL client
-- ============================================

-- Thêm cột uu_dai_giam_gia vào bảng lich_khoi_hanh
ALTER TABLE `lich_khoi_hanh` 
ADD COLUMN `uu_dai_giam_gia` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Ưu đãi giảm giá (%)' 
AFTER `phuong_tien`;

-- Kiểm tra cấu trúc bảng sau khi thêm
-- DESCRIBE lich_khoi_hanh;

