-- ============================================================
-- UC-User-Management: Bảng Quản Lý Người Dùng
-- Quản lý thông tin người dùng (khách hàng) của hệ thống
-- ============================================================

-- Tạo bảng users (người dùng)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID người dùng',
  `ho_ten` varchar(255) NOT NULL COMMENT 'Họ và tên',
  `email` varchar(255) NOT NULL COMMENT 'Email (unique)',
  `so_dien_thoai` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại',
  `dia_chi` text DEFAULT NULL COMMENT 'Địa chỉ',
  `ngay_sinh` date DEFAULT NULL COMMENT 'Ngày sinh',
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT NULL COMMENT 'Giới tính',
  `cmnd_cccd` varchar(20) DEFAULT NULL COMMENT 'CMND/CCCD',
  `mat_khau` varchar(255) DEFAULT NULL COMMENT 'Mật khẩu (hashed)',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Ảnh đại diện',
  `trang_thai` tinyint(1) DEFAULT 1 COMMENT '1=Hoạt động, 0=Khóa',
  `ghi_chu` text DEFAULT NULL COMMENT 'Ghi chú',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_so_dien_thoai` (`so_dien_thoai`),
  KEY `idx_trang_thai` (`trang_thai`),
  KEY `idx_ngay_tao` (`ngay_tao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý người dùng';

-- ============================================================
-- Dữ liệu mẫu (Sample Data)
-- ============================================================

/*
INSERT INTO `users` (`ho_ten`, `email`, `so_dien_thoai`, `dia_chi`, `ngay_sinh`, `gioi_tinh`, `cmnd_cccd`, `trang_thai`) VALUES
('Nguyễn Văn A', 'nguyenvana@email.com', '0912345678', '123 Đường ABC, Quận 1, TP.HCM', '1990-01-15', 'Nam', '123456789012', 1),
('Trần Thị B', 'tranthib@email.com', '0987654321', '456 Đường XYZ, Quận 2, TP.HCM', '1995-05-20', 'Nữ', '987654321098', 1),
('Lê Văn C', 'levanc@email.com', '0901234567', '789 Đường DEF, Quận 3, Hà Nội', '1988-12-10', 'Nam', '111222333444', 1);
*/

-- ============================================================
-- View: Danh sách người dùng đầy đủ
-- ============================================================

CREATE OR REPLACE VIEW `v_users_full` AS
SELECT 
    u.id,
    u.ho_ten,
    u.email,
    u.so_dien_thoai,
    u.dia_chi,
    u.ngay_sinh,
    u.gioi_tinh,
    u.cmnd_cccd,
    u.avatar,
    CASE 
        WHEN u.trang_thai = 1 THEN 'Hoạt động'
        ELSE 'Đã khóa'
    END AS trang_thai_text,
    u.trang_thai,
    u.ghi_chu,
    u.ngay_tao,
    u.ngay_cap_nhat,
    TIMESTAMPDIFF(YEAR, u.ngay_sinh, CURDATE()) AS tuoi
FROM 
    users u
ORDER BY 
    u.ngay_tao DESC;

-- ============================================================
-- View: Thống kê người dùng
-- ============================================================

CREATE OR REPLACE VIEW `v_users_stats` AS
SELECT 
    COUNT(*) AS tong_nguoi_dung,
    SUM(CASE WHEN trang_thai = 1 THEN 1 ELSE 0 END) AS dang_hoat_dong,
    SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END) AS da_khoa,
    SUM(CASE WHEN ngay_tao >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS moi_trong_30_ngay
FROM 
    users;

-- ============================================================
-- Notes
-- ============================================================
-- 1. Email là unique, không được trùng
-- 2. Mật khẩu nên được hash bằng password_hash() trước khi lưu
-- 3. Trạng thái: 1 = Hoạt động, 0 = Đã khóa
-- 4. Có thể liên kết với bảng hoadon để xem lịch sử đặt tour

