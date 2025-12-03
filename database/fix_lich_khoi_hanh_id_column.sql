-- ============================================
-- Sửa cột id trong bảng lich_khoi_hanh
-- Chạy file này trong phpMyAdmin để sửa lỗi AUTO_INCREMENT
-- ============================================

-- Kiểm tra và sửa cột id thành AUTO_INCREMENT và PRIMARY KEY
-- Nếu cột id chưa có AUTO_INCREMENT
ALTER TABLE `lich_khoi_hanh` 
MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT;

-- Đảm bảo id là PRIMARY KEY (nếu chưa có)
-- Lưu ý: Nếu đã có PRIMARY KEY khác, bạn cần xóa nó trước:
-- ALTER TABLE `lich_khoi_hanh` DROP PRIMARY KEY;
-- Sau đó chạy:
-- ALTER TABLE `lich_khoi_hanh` ADD PRIMARY KEY (`id`);

-- Hoặc chạy cả hai cùng lúc (nếu chưa có PRIMARY KEY):
-- ALTER TABLE `lich_khoi_hanh` 
-- MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT,
-- ADD PRIMARY KEY (`id`);

-- Kiểm tra kết quả:
-- DESCRIBE lich_khoi_hanh;

