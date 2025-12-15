<?php
/**
 * TourModel - ĐÃ XÓA GIAPHONGDON
 * Updated: 2025
 * @author Tienhien109
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

            // Cho phép lưu songay_text (mô tả dạng "3 ngày 2 đêm") nếu bảng có cột này
            $hasSongayText = $this->hasColumn('goidulich', 'songay_text');

            $columns = [
                'khuyenmai', 'khuyenmai_phantram', 'khuyenmai_tungay', 'khuyenmai_denngay', 'khuyenmai_mota',
                'nuocngoai', 'quocgia', 'mato', 'tengoi',
                'noixuatphat', 'giagoi', 'giatreem', 'giatrenho',
                'chitietgoi', 'chuongtrinh', 'luuy',
                'songay'
            ];

            if ($hasSongayText) {
                $columns[] = 'songay_text';
            }

            $columns[] = 'hinhanh';
            $columns[] = 'ngaydang';

            $placeholders = array_map(function ($col) {
                return ':' . $col;
            }, $columns);

            // ngaydang dùng NOW(), không bind
            $placeholders[array_search('ngaydang', $columns)] = 'NOW()';

            $sql = "INSERT INTO goidulich (" . implode(', ', $columns) . ")
                    VALUES (" . implode(', ', $placeholders) . ")";

            $params = [
                ':khuyenmai'          => $data['khuyenmai']  ?? 0,
                ':khuyenmai_phantram' => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_phantram'] ?? 0) : 0,
                ':khuyenmai_tungay'   => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_tungay'] ?? null) : null,
                ':khuyenmai_denngay'  => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_denngay'] ?? null) : null,
                ':khuyenmai_mota'     => ($data['khuyenmai'] == 1) ? ($data['khuyenmai_mota'] ?? null) : null,
                ':nuocngoai'    => $data['nuocngoai']  ?? 0,
                ':quocgia'      => $data['quocgia']    ?? 'Việt Nam',
                ':mato'         => $data['mato'] ?? null,
                ':tengoi'       => $data['tengoi'],
                ':noixuatphat'  => $data['noixuatphat'],
                ':giagoi'       => $data['giagoi'],
                ':giatreem'     => $data['giatreem'],
                ':giatrenho'    => $data['giatrenho'],
                ':chitietgoi'   => $data['chitietgoi'],
                ':chuongtrinh'  => $data['chuongtrinh'] ?? '',
                ':luuy'         => $data['luuy'],
                ':songay'       => $data['songay'],
                ':hinhanh'      => $hinhanh,
            ];

            if ($hasSongayText) {
                $params[':songay_text'] = $data['songay_text'] ?? null;
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Tạm thời hiển thị lỗi SQL để debug khi tạo tour thất bại
            error_log("LỖI SQL (createTour): " . $e->getMessage());
            echo "<pre style='color:red'>LỖI SQL (createTour): " . htmlspecialchars($e->getMessage()) . "</pre>";
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

    /**
     * Kiểm tra mã tour đã tồn tại chưa
     * @param string $mato Mã tour cần kiểm tra
     * @param int|null $excludeId ID tour cần loại trừ (khi update)
     * @return bool true nếu đã tồn tại, false nếu chưa tồn tại
     */
    public function isMatoExists($mato, $excludeId = null)
    {
        if (empty($mato)) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) FROM goidulich WHERE mato = :mato";
        $params = [':mato' => $mato];
        
        if ($excludeId !== null) {
            $sql .= " AND id_goi != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }

    /**
     * Kiểm tra tên tour đã tồn tại chưa
     * @param string $tengoi Tên tour cần kiểm tra
     * @param int|null $excludeId ID tour cần loại trừ (khi update)
     * @return bool true nếu đã tồn tại, false nếu chưa tồn tại
     */
    public function isTengoiExists($tengoi, $excludeId = null)
    {
        if (empty($tengoi)) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) FROM goidulich WHERE tengoi = :tengoi";
        $params = [':tengoi' => $tengoi];
        
        if ($excludeId !== null) {
            $sql .= " AND id_goi != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $count = $stmt->fetchColumn();
        
        return $count > 0;
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
            $hasSongayText = $this->hasColumn('goidulich', 'songay_text');

            $setParts = [
                'khuyenmai           = :khuyenmai',
                'khuyenmai_phantram  = :khuyenmai_phantram',
                'khuyenmai_tungay    = :khuyenmai_tungay',
                'khuyenmai_denngay   = :khuyenmai_denngay',
                'khuyenmai_mota      = :khuyenmai_mota',
                'nuocngoai    = :nuocngoai',
                'quocgia      = :quocgia',
                'mato         = :mato',
                'tengoi       = :tengoi',
                'noixuatphat  = :noixuatphat',
                'giagoi       = :giagoi',
                'giatreem     = :giatreem',
                'giatrenho    = :giatrenho',
                'chitietgoi   = :chitietgoi',
                'chuongtrinh  = :chuongtrinh',
                'luuy         = :luuy',
                'songay       = :songay',
            ];

            if ($hasSongayText) {
                $setParts[] = 'songay_text  = :songay_text';
            }

            $sql = "UPDATE goidulich SET " . implode(",\n                        ", $setParts) . " WHERE id_goi = :id";

            $params = [
                ':khuyenmai'          => isset($data['khuyenmai']) ? (int)$data['khuyenmai'] : 0,
                ':khuyenmai_phantram' => isset($data['khuyenmai_phantram']) ? (float)$data['khuyenmai_phantram'] : 0,
                ':khuyenmai_tungay'   => !empty($data['khuyenmai_tungay']) ? $data['khuyenmai_tungay'] : null,
                ':khuyenmai_denngay'  => !empty($data['khuyenmai_denngay']) ? $data['khuyenmai_denngay'] : null,
                ':khuyenmai_mota'     => !empty($data['khuyenmai_mota']) ? $data['khuyenmai_mota'] : null,
                ':nuocngoai'    => isset($data['nuocngoai']) ? (int)$data['nuocngoai'] : 0,
                ':quocgia'      => $data['quocgia'] ?? 'Việt Nam',
                ':mato'         => $data['mato'] ?? null,
                ':tengoi'       => $data['tengoi'] ?? '',
                ':noixuatphat'  => $data['noixuatphat'] ?? '',
                ':giagoi'       => isset($data['giagoi']) ? (float)$data['giagoi'] : 0,
                ':giatreem'     => isset($data['giatreem']) ? (float)$data['giatreem'] : 0,
                ':giatrenho'    => isset($data['giatrenho']) ? (float)$data['giatrenho'] : 0,
                ':chitietgoi'   => $data['chitietgoi'] ?? '',
                ':chuongtrinh'  => $data['chuongtrinh'] ?? '',
                ':luuy'         => $data['luuy'] ?? '',
                ':songay'       => $data['songay'] ?? '',
                ':id'           => $id,
            ];

            if ($hasSongayText) {
                $params[':songay_text'] = $data['songay_text'] ?? null;
            }

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Lỗi updateTour execute: " . print_r($errorInfo, true));
            }
            
            return $result;
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
     * Kiểm tra bảng có cột hay không (cache tạm trong request)
     */
    private function hasColumn(string $table, string $column): bool
    {
        static $cache = [];
        $key = $table . ':' . $column;
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $sql = "SELECT COUNT(*) FROM information_schema.columns 
                WHERE table_schema = DATABASE() 
                  AND table_name = :table 
                  AND column_name = :column";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':table' => $table, ':column' => $column]);
        $cache[$key] = $stmt->fetchColumn() > 0;
        return $cache[$key];
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
      