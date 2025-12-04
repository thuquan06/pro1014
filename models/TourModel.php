<?php
/**
 * TourModel - ĐÃ XÓA GIAPHONGDON
 * Updated: 2025
 */
class TourModel extends BaseModel
{
    public function getAllTours()
    {
        $sql = "SELECT * FROM goidulich ORDER BY id_goi ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTour(array $data, $file = null)
    {
        try {
            // Ưu tiên đường dẫn đã upload sẵn ở Controller
            $hinhanh = $data['hinhanh'] ?? null;

            // Nếu Controller chưa upload, Model mới thử upload
            if (!$hinhanh && $file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
                $hinhanh = uploadFile($file, 'uploads/tours/');
            }

            // Bảo vệ NOT NULL
            if (!$hinhanh) {
                throw new PDOException("Ảnh (hinhanh) đang NULL vì chưa upload được.");
            }

            $sql = "INSERT INTO goidulich (
                        khuyenmai, khuyenmai_phantram, khuyenmai_tungay, khuyenmai_denngay, khuyenmai_mota,
                        nuocngoai, quocgia, ten_tinh, mato, tengoi,
                        noixuatphat, vitri, giagoi, giatreem, giatrenho,
                        chitietgoi, chuongtrinh, luuy,
                        songay, hinhanh, ngaydang
                    ) VALUES (
                        :khuyenmai, :khuyenmai_phantram, :khuyenmai_tungay, :khuyenmai_denngay, :khuyenmai_mota,
                        :nuocngoai, :quocgia, :ten_tinh, :mato, :tengoi,
                        :noixuatphat, :vitri, :giagoi, :giatreem, :giatrenho,
                        :chitietgoi, :chuongtrinh, :luuy,
                        :songay, :hinhanh, NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':khuyenmai'          => $data['khuyenmai']  ?? 0,
                ':khuyenmai_phantram' => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_phantram'] ?? 0) : 0,
                ':khuyenmai_tungay'   => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_tungay'] ?? null) : null,
                ':khuyenmai_denngay'  => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_denngay'] ?? null) : null,
                ':khuyenmai_mota'     => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_mota'] ?? null) : null,
                ':nuocngoai'    => $data['nuocngoai']  ?? 0,
                ':quocgia'      => $data['quocgia']    ?? 'Việt Nam',
                ':ten_tinh'     => $data['ten_tinh']   ?? null,
                ':mato'         => $data['mato'] ?? null,
                ':tengoi'       => $data['tengoi'],
                ':noixuatphat'  => $data['noixuatphat'],
                ':vitri'        => $data['vitri'],
                ':giagoi'       => $data['giagoi'],
                ':giatreem'     => $data['giatreem'],
                ':giatrenho'    => $data['giatrenho'],
                ':chitietgoi'   => $data['chitietgoi'],
                ':chuongtrinh'  => $data['chuongtrinh'],
                ':luuy'         => $data['luuy'],
                ':songay'       => $data['songay'],
                ':ngayxuatphat' => $data['ngayxuatphat'] ?? null,
                ':ngayve'       => $data['ngayve'] ?? null,
                ':phuongtien'   => $data['phuongtien'] ?? null,
                ':socho'        => $data['socho'] ?? null,
                ':hinhanh'      => $hinhanh,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "<pre style='color:red'>LỖI SQL: ".$e->getMessage()."</pre>";
            return false;
        }
    }

    public function getTourByID($id)
    {
        $sql = "SELECT * FROM goidulich WHERE id_goi = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTour($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM goidulich WHERE id_goi = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Cập nhật thông tin tour (KHÔNG đổi ảnh)
    public function updateTour($id, array $data)
    {
        try {
            $sql = "UPDATE goidulich SET
                        khuyenmai           = :khuyenmai,
                        khuyenmai_phantram  = :khuyenmai_phantram,
                        khuyenmai_tungay    = :khuyenmai_tungay,
                        khuyenmai_denngay   = :khuyenmai_denngay,
                        khuyenmai_mota      = :khuyenmai_mota,
                        nuocngoai    = :nuocngoai,
                        quocgia      = :quocgia,
                        ten_tinh     = :ten_tinh,
                        mato         = :mato,
                        tengoi       = :tengoi,
                        noixuatphat  = :noixuatphat,
                        vitri        = :vitri,
                        giagoi       = :giagoi,
                        giatreem     = :giatreem,
                        giatrenho    = :giatrenho,
                        chitietgoi   = :chitietgoi,
                        chuongtrinh  = :chuongtrinh,
                        luuy         = :luuy,
                        songay       = :songay
                    WHERE id_goi = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':khuyenmai'          => $data['khuyenmai']  ?? 0,
                ':khuyenmai_phantram' => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_phantram'] ?? 0) : 0,
                ':khuyenmai_tungay'   => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_tungay'] ?? null) : null,
                ':khuyenmai_denngay'  => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_denngay'] ?? null) : null,
                ':khuyenmai_mota'     => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_mota'] ?? null) : null,
                ':nuocngoai'    => $data['nuocngoai']  ?? 0,
                ':quocgia'      => $data['quocgia']    ?? 'Việt Nam',
                ':ten_tinh'     => $data['ten_tinh']   ?? null,
                ':mato'         => $data['mato'] ?? null,
                ':tengoi'       => $data['tengoi'],
                ':noixuatphat'  => $data['noixuatphat'],
                ':vitri'        => $data['vitri'],
                ':giagoi'       => $data['giagoi'],
                ':giatreem'     => $data['giatreem'],
                ':giatrenho'    => $data['giatrenho'],
                ':chitietgoi'   => $data['chitietgoi'],
                ':chuongtrinh'  => $data['chuongtrinh'],
                ':luuy'         => $data['luuy'],
                ':songay'       => $data['songay'],
                ':id'           => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật riêng ảnh tour
    public function updateTourImage($id, string $hinhanh)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE goidulich SET hinhanh = :hinhanh WHERE id_goi = :id"
            );
            return $stmt->execute([
                ':hinhanh' => $hinhanh,
                ':id'      => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateTourImage: " . $e->getMessage());
            return false;
        }
    }

    public function toggleStatus($id) {
        $sql = "UPDATE goidulich SET trangthai = IF(trangthai = 1, 0, 1) WHERE id_goi = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * Lưu dịch vụ cho tour
     */
    public function saveTourServices($tourId, array $serviceIds)
    {
        try {
            // Xóa các dịch vụ cũ (nếu có)
            $this->deleteTourServices($tourId);
            
            // Tạo bảng tour_dich_vu nếu chưa có
            $this->ensureTourServiceTableExists();
            
            // Thêm các dịch vụ mới
            if (!empty($serviceIds)) {
                $sql = "INSERT INTO tour_dich_vu (id_tour, id_dich_vu, ngay_tao) VALUES (:id_tour, :id_dich_vu, NOW())";
                $stmt = $this->conn->prepare($sql);
                
                foreach ($serviceIds as $serviceId) {
                    $serviceId = (int)$serviceId;
                    if ($serviceId > 0) {
                        $stmt->execute([
                            ':id_tour' => $tourId,
                            ':id_dich_vu' => $serviceId
                        ]);
                    }
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi saveTourServices: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa tất cả dịch vụ của tour
     */
    public function deleteTourServices($tourId)
    {
        try {
            $this->ensureTourServiceTableExists();
            $stmt = $this->conn->prepare("DELETE FROM tour_dich_vu WHERE id_tour = :id_tour");
            return $stmt->execute([':id_tour' => $tourId]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteTourServices: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy danh sách dịch vụ của tour
     */
    public function getTourServices($tourId)
    {
        try {
            $this->ensureTourServiceTableExists();
            $sql = "SELECT td.*, dv.ten_dich_vu, dv.loai_dich_vu, dv.gia, dv.don_vi, dv.nha_cung_cap
                    FROM tour_dich_vu td
                    LEFT JOIN dich_vu dv ON td.id_dich_vu = dv.id
                    WHERE td.id_tour = :id_tour
                    ORDER BY dv.loai_dich_vu, dv.ten_dich_vu";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_tour' => $tourId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getTourServices: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Đảm bảo bảng tour_dich_vu tồn tại
     */
    private function ensureTourServiceTableExists()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `tour_dich_vu` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_tour` INT(11) NOT NULL COMMENT 'ID tour',
                `id_dich_vu` INT(11) NOT NULL COMMENT 'ID dịch vụ',
                `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                UNIQUE KEY `unique_tour_service` (`id_tour`, `id_dich_vu`),
                KEY `idx_tour` (`id_tour`),
                KEY `idx_dich_vu` (`id_dich_vu`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quan hệ tour - dịch vụ'";
            
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Bảng đã tồn tại hoặc lỗi khác, bỏ qua
            error_log("Lỗi ensureTourServiceTableExists: " . $e->getMessage());
        }
    }
}