-- =====================================================
-- Thêm các trường khuyến mãi chi tiết vào bảng goidulich
-- Created: 2024-12-03
-- =====================================================

USE starvel;

-- Thêm các cột khuyến mãi chi tiết
ALTER TABLE goidulich
ADD COLUMN IF NOT EXISTS khuyenmai_phantram INT DEFAULT 0 COMMENT 'Phần trăm giảm giá (0-100)',
ADD COLUMN IF NOT EXISTS khuyenmai_tungay DATE DEFAULT NULL COMMENT 'Ngày bắt đầu khuyến mãi',
ADD COLUMN IF NOT EXISTS khuyenmai_denngay DATE DEFAULT NULL COMMENT 'Ngày kết thúc khuyến mãi',
ADD COLUMN IF NOT EXISTS khuyenmai_mota VARCHAR(255) DEFAULT NULL COMMENT 'Mô tả ngắn khuyến mãi';

-- Cập nhật comment cho cột khuyenmai hiện tại
ALTER TABLE goidulich
MODIFY COLUMN khuyenmai TINYINT(1) DEFAULT 0 COMMENT 'Có khuyến mãi hay không (0=Không, 1=Có)';

-- Hiển thị cấu trúc bảng sau khi update
DESCRIBE goidulich;
