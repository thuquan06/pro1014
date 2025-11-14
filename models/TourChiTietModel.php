<?php
/**
 * TourChiTietModel - Quản lý chi tiết tour (TIẾNG VIỆT)
 * - Lịch trình theo ngày
 * - Gallery ảnh
 * - Chính sách hủy/đổi
 * - Loại tour & Tags
 */
class TourChiTietModel extends BaseModel
{
    // ==================== LỊCH TRÌNH ====================
    
    /**
     * Lấy lịch trình của tour theo ngày
     */
    public function layLichTrinh($idGoi) {
        $sql = "SELECT * FROM lichtrinhtheoday 
                WHERE id_goi = :id_goi 
                ORDER BY ngay_thu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm lịch trình 1 ngày
     */
    public function themLichTrinh($data) {
        $sql = "INSERT INTO lichtrinhtheoday 
                (id_goi, ngay_thu, tieude, mota, hoatdong, buaan, noinghi) 
                VALUES 
                (:id_goi, :ngay_thu, :tieude, :mota, :hoatdong, :buaan, :noinghi)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi'    => $data['id_goi'],
            ':ngay_thu'  => $data['ngay_thu'],
            ':tieude'    => $data['tieude'],
            ':mota'      => $data['mota'],
            ':hoatdong'  => $data['hoatdong'] ?? null,
            ':buaan'     => $data['buaan'] ?? null,
            ':noinghi'   => $data['noinghi'] ?? null
        ]);
    }
    
    /**
     * Cập nhật lịch trình 1 ngày
     */
    public function suaLichTrinh($id, $data) {
        $sql = "UPDATE lichtrinhtheoday SET
                tieude = :tieude,
                mota = :mota,
                hoatdong = :hoatdong,
                buaan = :buaan,
                noinghi = :noinghi
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tieude'    => $data['tieude'],
            ':mota'      => $data['mota'],
            ':hoatdong'  => $data['hoatdong'] ?? null,
            ':buaan'     => $data['buaan'] ?? null,
            ':noinghi'   => $data['noinghi'] ?? null,
            ':id'        => $id
        ]);
    }
    
    /**
     * Xóa lịch trình 1 ngày
     */
    public function xoaLichTrinh($id) {
        $stmt = $this->conn->prepare("DELETE FROM lichtrinhtheoday WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Lấy chi tiết 1 ngày
     */
    public function layMotNgay($id) {
        $sql = "SELECT * FROM lichtrinhtheoday WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ==================== HÌNH ẢNH (GALLERY) ====================
    
    /**
     * Lấy tất cả ảnh của tour
     */
    public function layDanhSachAnh($idGoi) {
        $sql = "SELECT * FROM tour_hinhanh 
                WHERE id_goi = :id_goi 
                ORDER BY anh_daodien DESC, thutu_hienthi ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm ảnh vào gallery
     */
    public function themAnh($idGoi, $duongDanAnh, $motaAnh = '', $anhDaiDien = 0) {
        $sql = "INSERT INTO tour_hinhanh 
                (id_goi, duongdan_anh, mota_anh, anh_daodien) 
                VALUES 
                (:id_goi, :duongdan_anh, :mota_anh, :anh_daodien)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi'        => $idGoi,
            ':duongdan_anh'  => $duongDanAnh,
            ':mota_anh'      => $motaAnh,
            ':anh_daodien'   => $anhDaiDien
        ]);
    }
    
    /**
     * Đặt ảnh làm ảnh đại diện
     */
    public function datAnhDaiDien($idGoi, $idAnh) {
        // Reset tất cả ảnh về không phải đại diện
        $sql1 = "UPDATE tour_hinhanh SET anh_daodien = 0 WHERE id_goi = :id_goi";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([':id_goi' => $idGoi]);
        
        // Set ảnh được chọn làm đại diện
        $sql2 = "UPDATE tour_hinhanh SET anh_daodien = 1 WHERE id = :id";
        $stmt2 = $this->conn->prepare($sql2);
        return $stmt2->execute([':id' => $idAnh]);
    }
    
    /**
     * Xóa ảnh
     */
    public function xoaAnh($id) {
        // Lấy thông tin ảnh trước
        $sql = "SELECT duongdan_anh FROM tour_hinhanh WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $anh = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($anh) {
            // Xóa file vật lý
            deleteFile($anh['duongdan_anh']);
            
            // Xóa record trong DB
            $stmt2 = $this->conn->prepare("DELETE FROM tour_hinhanh WHERE id = :id");
            return $stmt2->execute([':id' => $id]);
        }
        return false;
    }
    
    /**
     * Lấy ảnh đại diện của tour
     */
    public function layAnhDaiDien($idGoi) {
        $sql = "SELECT * FROM tour_hinhanh 
                WHERE id_goi = :id_goi AND anh_daodien = 1 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ==================== CHÍNH SÁCH ====================
    
    /**
     * Lấy chính sách của tour
     */
    public function layChinhSach($idGoi, $loai = null) {
        if ($loai) {
            $sql = "SELECT * FROM chinhsach_tour 
                    WHERE id_goi = :id_goi AND loai_chinhsach = :loai 
                    ORDER BY so_ngay_truoc DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi, ':loai' => $loai]);
        } else {
            $sql = "SELECT * FROM chinhsach_tour 
                    WHERE id_goi = :id_goi 
                    ORDER BY loai_chinhsach, so_ngay_truoc DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_goi' => $idGoi]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm chính sách
     */
    public function themChinhSach($data) {
        $sql = "INSERT INTO chinhsach_tour 
                (id_goi, loai_chinhsach, so_ngay_truoc, phantram_hoantien, noidung) 
                VALUES 
                (:id_goi, :loai_chinhsach, :so_ngay_truoc, :phantram_hoantien, :noidung)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi'            => $data['id_goi'],
            ':loai_chinhsach'    => $data['loai_chinhsach'],
            ':so_ngay_truoc'     => $data['so_ngay_truoc'],
            ':phantram_hoantien' => $data['phantram_hoantien'] ?? 0,
            ':noidung'           => $data['noidung']
        ]);
    }
    
    /**
     * Xóa chính sách
     */
    public function xoaChinhSach($id) {
        $stmt = $this->conn->prepare("DELETE FROM chinhsach_tour WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // ==================== LOẠI TOUR ====================
    
    /**
     * Lấy tất cả loại tour
     */
    public function layTatCaLoaiTour() {
        $sql = "SELECT * FROM loaitour ORDER BY thutu_hienthi ASC, ten_loai ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy loại tour của 1 tour
     */
    public function layLoaiTourCuaTour($idGoi) {
        $sql = "SELECT l.* FROM loaitour l
                INNER JOIN tour_loai_map m ON l.id = m.id_loai
                WHERE m.id_goi = :id_goi
                ORDER BY l.ten_loai ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Gán loại cho tour
     */
    public function ganLoaiTour($idGoi, $idLoai) {
        $sql = "INSERT IGNORE INTO tour_loai_map (id_goi, id_loai) 
                VALUES (:id_goi, :id_loai)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi'  => $idGoi,
            ':id_loai' => $idLoai
        ]);
    }
    
    /**
     * Xóa loại tour
     */
    public function xoaLoaiTour($idGoi, $idLoai) {
        $sql = "DELETE FROM tour_loai_map 
                WHERE id_goi = :id_goi AND id_loai = :id_loai";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi'  => $idGoi,
            ':id_loai' => $idLoai
        ]);
    }
    
    // ==================== TAGS ====================
    
    /**
     * Lấy tất cả tags
     */
    public function layTatCaTags() {
        $sql = "SELECT * FROM tour_tags ORDER BY ten_tag ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tags của 1 tour
     */
    public function layTagsCuaTour($idGoi) {
        $sql = "SELECT t.* FROM tour_tags t
                INNER JOIN tour_tag_map m ON t.id = m.id_tag
                WHERE m.id_goi = :id_goi
                ORDER BY t.ten_tag ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Gán tag cho tour
     */
    public function ganTag($idGoi, $idTag) {
        $sql = "INSERT IGNORE INTO tour_tag_map (id_goi, id_tag) 
                VALUES (:id_goi, :id_tag)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi' => $idGoi,
            ':id_tag' => $idTag
        ]);
    }
    
    /**
     * Xóa tag khỏi tour
     */
    public function xoaTag($idGoi, $idTag) {
        $sql = "DELETE FROM tour_tag_map 
                WHERE id_goi = :id_goi AND id_tag = :id_tag";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_goi' => $idGoi,
            ':id_tag' => $idTag
        ]);
    }
    
    /**
     * Tạo tag mới
     */
    public function taoTag($tenTag) {
        $slug = $this->taoSlug($tenTag);
        $sql = "INSERT INTO tour_tags (ten_tag, slug) VALUES (:ten_tag, :slug)";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt->execute([':ten_tag' => $tenTag, ':slug' => $slug])) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // ==================== HELPER ====================
    
    /**
     * Tạo slug từ tiếng Việt
     */
    private function taoSlug($str) {
        // Chuyển sang chữ thường
        $str = mb_strtolower($str, 'UTF-8');
        
        // Bỏ dấu tiếng Việt
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        
        // Chỉ giữ chữ, số và dấu gạch ngang
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/[\s]+/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        
        return trim($str, '-');
    }
    
    /**
     * Lấy tổng hợp thông tin chi tiết tour
     */
    public function layChiTietDayDu($idGoi) {
        return [
            'lichtrinh'  => $this->layLichTrinh($idGoi),
            'hinhanh'    => $this->layDanhSachAnh($idGoi),
            'chinhsach'  => $this->layChinhSach($idGoi),
            'loaitour'   => $this->layLoaiTourCuaTour($idGoi),
            'tags'       => $this->layTagsCuaTour($idGoi)
        ];
    }

    
}