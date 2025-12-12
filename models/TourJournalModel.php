<?php
/**
 * TourJournalModel - Quản lý Nhật ký tour của HDV
 * UC-Guide-Journal: Nhật ký tour với diễn biến, sự cố, thời tiết, điểm nhấn và ảnh
 */
class TourJournalModel extends BaseModel
{
    /**
     * Lấy tất cả nhật ký theo phân công
     */
    public function getJournalsByAssignmentID($assignmentId, $filters = [])
    {
        $sql = "SELECT nj.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM nhat_ky_tour nj
                LEFT JOIN phan_cong_hdv pc ON nj.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE nj.id_phan_cong = :id_phan_cong";
        $params = [':id_phan_cong' => $assignmentId];

        // Filter theo ngày
        if (!empty($filters['from_date'])) {
            $sql .= " AND nj.ngay >= :from_date";
            $params[':from_date'] = $filters['from_date'];
        }

        if (!empty($filters['to_date'])) {
            $sql .= " AND nj.ngay <= :to_date";
            $params[':to_date'] = $filters['to_date'];
        }

        $sql .= " ORDER BY nj.ngay DESC, nj.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse JSON images
        foreach ($results as &$result) {
            $result['hinh_anh'] = $this->parseJsonArray($result['hinh_anh'] ?? '[]');
        }
        
        return $results;
    }

    /**
     * Lấy nhật ký theo ID
     */
    public function getJournalByID($id)
    {
        $sql = "SELECT nj.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM nhat_ky_tour nj
                LEFT JOIN phan_cong_hdv pc ON nj.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE nj.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $result['hinh_anh'] = $this->parseJsonArray($result['hinh_anh'] ?? '[]');
        }
        
        return $result;
    }

    /**
     * Lấy tất cả nhật ký của một guide
     */
    public function getJournalsByGuideID($guideId, $filters = [])
    {
        $sql = "SELECT nj.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM nhat_ky_tour nj
                LEFT JOIN phan_cong_hdv pc ON nj.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE pc.id_hdv = :id_hdv";
        $params = [':id_hdv' => $guideId];

        // Filter theo ngày
        if (!empty($filters['from_date'])) {
            $sql .= " AND nj.ngay >= :from_date";
            $params[':from_date'] = $filters['from_date'];
        }

        if (!empty($filters['to_date'])) {
            $sql .= " AND nj.ngay <= :to_date";
            $params[':to_date'] = $filters['to_date'];
        }

        // Filter theo phân công
        if (!empty($filters['id_phan_cong'])) {
            $sql .= " AND nj.id_phan_cong = :id_phan_cong";
            $params[':id_phan_cong'] = $filters['id_phan_cong'];
        }

        $sql .= " ORDER BY nj.ngay DESC, nj.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse JSON images
        foreach ($results as &$result) {
            $result['hinh_anh'] = $this->parseJsonArray($result['hinh_anh'] ?? '[]');
        }
        
        return $results;
    }

    /**
     * Tạo nhật ký mới
     */
    public function createJournal(array $data)
    {
        try {
            $sql = "INSERT INTO nhat_ky_tour (
                        id_phan_cong, ngay, dien_bien, su_co, 
                        thoi_tiet, diem_nhan, hinh_anh
                    ) VALUES (
                        :id_phan_cong, :ngay, :dien_bien, :su_co,
                        :thoi_tiet, :diem_nhan, :hinh_anh
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_phan_cong' => $data['id_phan_cong'] ?? null,
                ':ngay' => $data['ngay'] ?? date('Y-m-d'),
                ':dien_bien' => $data['dien_bien'] ?? null,
                ':su_co' => $data['su_co'] ?? null,
                ':thoi_tiet' => $data['thoi_tiet'] ?? null,
                ':diem_nhan' => $data['diem_nhan'] ?? null,
                ':hinh_anh' => $this->prepareJsonArray($data['hinh_anh'] ?? []),
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo nhật ký: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật nhật ký
     */
    public function updateJournal($id, array $data)
    {
        try {
            $sql = "UPDATE nhat_ky_tour SET
                        ngay = :ngay,
                        dien_bien = :dien_bien,
                        su_co = :su_co,
                        thoi_tiet = :thoi_tiet,
                        diem_nhan = :diem_nhan,
                        hinh_anh = :hinh_anh,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':ngay' => $data['ngay'] ?? date('Y-m-d'),
                ':dien_bien' => $data['dien_bien'] ?? null,
                ':su_co' => $data['su_co'] ?? null,
                ':thoi_tiet' => $data['thoi_tiet'] ?? null,
                ':diem_nhan' => $data['diem_nhan'] ?? null,
                ':hinh_anh' => $this->prepareJsonArray($data['hinh_anh'] ?? []),
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật nhật ký: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa nhật ký
     */
    public function deleteJournal($id)
    {
        try {
            // Lấy thông tin nhật ký để xóa ảnh
            $journal = $this->getJournalByID($id);
            if ($journal && !empty($journal['hinh_anh'])) {
                foreach ($journal['hinh_anh'] as $image) {
                    if (!empty($image)) {
                        // Sử dụng hàm deleteFile từ commons/function.php
                        if (function_exists('deleteFile')) {
                            deleteFile($image);
                        } else {
                            // Fallback nếu hàm chưa được load
                            $fullPath = (defined('PATH_ROOT') ? PATH_ROOT : dirname(__DIR__) . '/') . $image;
                            if (file_exists($fullPath)) {
                                @unlink($fullPath);
                            }
                        }
                    }
                }
            }

            $stmt = $this->conn->prepare("DELETE FROM nhat_ky_tour WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa nhật ký: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả nhật ký (cho admin)
     */
    public function getAllJournals($filters = [])
    {
        $sql = "SELECT nj.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv, hdv.email AS email_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour, g.id_goi AS id_tour
                FROM nhat_ky_tour nj
                LEFT JOIN phan_cong_hdv pc ON nj.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE 1=1";
        $params = [];

        // Filter theo HDV
        if (!empty($filters['id_hdv'])) {
            $sql .= " AND pc.id_hdv = :id_hdv";
            $params[':id_hdv'] = $filters['id_hdv'];
        }

        // Filter theo tour
        if (!empty($filters['id_tour'])) {
            $sql .= " AND g.id_goi = :id_tour";
            $params[':id_tour'] = $filters['id_tour'];
        }

        // Filter theo lịch khởi hành
        if (!empty($filters['id_lich_khoi_hanh'])) {
            $sql .= " AND pc.id_lich_khoi_hanh = :id_lich_khoi_hanh";
            $params[':id_lich_khoi_hanh'] = $filters['id_lich_khoi_hanh'];
        }

        // Filter theo ngày
        if (!empty($filters['from_date'])) {
            $sql .= " AND nj.ngay >= :from_date";
            $params[':from_date'] = $filters['from_date'];
        }

        if (!empty($filters['to_date'])) {
            $sql .= " AND nj.ngay <= :to_date";
            $params[':to_date'] = $filters['to_date'];
        }

        $sql .= " ORDER BY nj.ngay DESC, nj.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse JSON images
        foreach ($results as &$result) {
            $result['hinh_anh'] = $this->parseJsonArray($result['hinh_anh'] ?? '[]');
        }
        
        return $results;
    }

    /**
     * Kiểm tra xem guide có quyền truy cập nhật ký này không
     */
    public function checkGuideAccess($journalId, $guideId)
    {
        $sql = "SELECT COUNT(*) as count
                FROM nhat_ky_tour nj
                INNER JOIN phan_cong_hdv pc ON nj.id_phan_cong = pc.id
                WHERE nj.id = :journal_id AND pc.id_hdv = :guide_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':journal_id' => $journalId,
            ':guide_id' => $guideId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Chuẩn bị mảng thành JSON string
     */
    private function prepareJsonArray($data)
    {
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_UNESCAPED_UNICODE);
            }
            return json_encode([$data], JSON_UNESCAPED_UNICODE);
        }
        if (is_array($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return json_encode([], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Parse JSON string thành mảng
     */
    public function parseJsonArray($jsonString)
    {
        if (empty($jsonString)) {
            return [];
        }
        $decoded = json_decode($jsonString, true);
        return $decoded ?: [];
    }
}

