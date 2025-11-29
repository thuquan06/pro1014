-- Script sửa lỗi: Field 'id_hoadon' doesn't have a default value
-- Chạy script này trong phpMyAdmin hoặc MySQL client để sửa bảng hoadon

-- Bước 1: Kiểm tra cấu trúc hiện tại (chạy lệnh này trước để xem)
-- DESCRIBE hoadon;

-- Bước 2: Sửa cột id_hoadon để có AUTO_INCREMENT
ALTER TABLE `hoadon` 
MODIFY `id_hoadon` int(11) NOT NULL AUTO_INCREMENT;

-- Bước 3: Đảm bảo có PRIMARY KEY
ALTER TABLE `hoadon` 
ADD PRIMARY KEY (`id_hoadon`);

-- Bước 4: Kiểm tra lại (chạy lệnh này sau để xác nhận)
-- DESCRIBE hoadon;
-- SHOW CREATE TABLE hoadon;


