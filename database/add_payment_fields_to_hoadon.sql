-- Thêm các cột thanh toán vào bảng hoadon
ALTER TABLE `hoadon` 
ADD COLUMN `phuong_thuc_thanh_toan` VARCHAR(50) DEFAULT NULL COMMENT 'Phương thức thanh toán: momo, bank_transfer, cash' AFTER `ghichu`,
ADD COLUMN `trang_thai_thanh_toan` TINYINT(1) DEFAULT 0 COMMENT '0: Chưa thanh toán, 1: Đã thanh toán, 2: Đang xử lý' AFTER `phuong_thuc_thanh_toan`,
ADD COLUMN `ma_giao_dich_momo` VARCHAR(255) DEFAULT NULL COMMENT 'Mã giao dịch từ MoMo' AFTER `trang_thai_thanh_toan`,
ADD COLUMN `so_tien_thanh_toan` DECIMAL(15,2) DEFAULT NULL COMMENT 'Số tiền thanh toán' AFTER `ma_giao_dich_momo`,
ADD COLUMN `ngay_thanh_toan` DATETIME DEFAULT NULL COMMENT 'Ngày thanh toán' AFTER `so_tien_thanh_toan`,
ADD COLUMN `qr_code_url` TEXT DEFAULT NULL COMMENT 'URL mã QR thanh toán' AFTER `ngay_thanh_toan`,
ADD COLUMN `payment_link` TEXT DEFAULT NULL COMMENT 'Link thanh toán MoMo' AFTER `qr_code_url`,
ADD INDEX `idx_trang_thai_thanh_toan` (`trang_thai_thanh_toan`),
ADD INDEX `idx_ma_giao_dich_momo` (`ma_giao_dich_momo`);

