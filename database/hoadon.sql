-- Tạo bảng hoadon (hóa đơn/booking)
CREATE TABLE IF NOT EXISTS `hoadon` (
  `id_hoadon` int(11) NOT NULL AUTO_INCREMENT,
  `id_goi` int(11) DEFAULT NULL COMMENT 'ID gói du lịch',
  `id_ks` int(11) DEFAULT NULL COMMENT 'ID khách sạn',
  `email_nguoidung` varchar(255) NOT NULL COMMENT 'Email khách hàng',
  `nguoilon` int(11) DEFAULT 1 COMMENT 'Số người lớn',
  `treem` int(11) DEFAULT 0 COMMENT 'Số trẻ em',
  `trenho` int(11) DEFAULT 0 COMMENT 'Số trẻ nhỏ',
  `embe` int(11) DEFAULT 0 COMMENT 'Số em bé',
  `phongdon` tinyint(1) DEFAULT 0 COMMENT 'Có phòng đơn không',
  `ngayvao` date DEFAULT NULL COMMENT 'Ngày vào/khởi hành',
  `ngayra` date DEFAULT NULL COMMENT 'Ngày ra/kết thúc',
  `sophong` int(11) DEFAULT 1 COMMENT 'Số phòng',
  `ghichu` text DEFAULT NULL COMMENT 'Ghi chú',
  `trangthai` tinyint(1) DEFAULT 0 COMMENT '0: Chờ xác nhận, 1: Đã xác nhận, 2: Hoàn thành',
  `huy` tinyint(1) DEFAULT 0 COMMENT 'Đã hủy',
  `ngaydat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày đặt',
  `ngaycapnhat` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
  PRIMARY KEY (`id_hoadon`),
  KEY `idx_id_goi` (`id_goi`),
  KEY `idx_email` (`email_nguoidung`),
  KEY `idx_trangthai` (`trangthai`),
  KEY `idx_ngaydat` (`ngaydat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng hóa đơn/booking tour';


