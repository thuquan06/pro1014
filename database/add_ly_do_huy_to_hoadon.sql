-- Thêm trường ly_do_huy vào bảng hoadon
-- Chạy script này để thêm trường lý do hủy

ALTER TABLE `hoadon` 
ADD COLUMN `ly_do_huy` TEXT NULL DEFAULT NULL COMMENT 'Lý do hủy đơn hàng' AFTER `huy`;

-- Kiểm tra kết quả
-- DESCRIBE hoadon;

