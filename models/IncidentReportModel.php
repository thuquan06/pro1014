<?php
/**
 * IncidentReportModel - Quản lý Báo cáo sự cố của HDV
 * UC-Guide-IncidentReport: Báo cáo sự cố (mất đồ, say xe, trễ giờ…) + cách xử lý; gắn mức độ
 */
class IncidentReportModel extends BaseModel
{
    /**
     * Lấy tất cả báo cáo sự cố theo phân công
     */
    public function getReportsByAssignmentID($assignmentId, $filters = [])
    {
        $sql = "SELECT bcs.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM bao_cao_su_co bcs
                LEFT JOIN phan_cong_hdv pc ON bcs.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE bcs.id_phan_cong = :id_phan_cong";
        $params = [':id_phan_cong' => $assignmentId];

        // Filter theo mức độ
        if (!empty($filters['muc_do'])) {
            $sql .= " AND bcs.muc_do = :muc_do";
            $params[':muc_do'] = $filters['muc_do'];
        }

        // Filter theo loại sự cố
        if (!empty($filters['loai_su_co'])) {
            $sql .= " AND bcs.loai_su_co = :loai_su_co";
            $params[':loai_su_co'] = $filters['loai_su_co'];
        }

        // Filter theo ngày
        if (!empty($filters['from_date'])) {
            $sql .= " AND bcs.ngay_xay_ra >= :from_date";
            $params[':from_date'] = $filters['from_date'];
        }

        if (!empty($filters['to_date'])) {
            $sql .= " AND bcs.ngay_xay_ra <= :to_date";
            $params[':to_date'] = $filters['to_date'];
        }

        $sql .= " ORDER BY bcs.ngay_xay_ra DESC, bcs.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy báo cáo sự cố theo ID
     */
    public function getReportByID($id)
    {
        $sql = "SELECT bcs.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM bao_cao_su_co bcs
                LEFT JOIN phan_cong_hdv pc ON bcs.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE bcs.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả báo cáo sự cố của một guide
     */
    public function getReportsByGuideID($guideId, $filters = [])
    {
        $sql = "SELECT bcs.*, 
                       pc.id_hdv, pc.id_lich_khoi_hanh,
                       hdv.ho_ten AS ten_hdv,
                       dp.ngay_khoi_hanh,
                       g.tengoi AS ten_tour
                FROM bao_cao_su_co bcs
                LEFT JOIN phan_cong_hdv pc ON bcs.id_phan_cong = pc.id
                LEFT JOIN huong_dan_vien hdv ON pc.id_hdv = hdv.id
                LEFT JOIN lich_khoi_hanh dp ON pc.id_lich_khoi_hanh = dp.id
                LEFT JOIN goidulich g ON dp.id_tour = g.id_goi
                WHERE pc.id_hdv = :id_hdv";
        $params = [':id_hdv' => $guideId];

        // Filter theo mức độ
        if (!empty($filters['muc_do'])) {
            $sql .= " AND bcs.muc_do = :muc_do";
            $params[':muc_do'] = $filters['muc_do'];
        }

        // Filter theo loại sự cố
        if (!empty($filters['loai_su_co'])) {
            $sql .= " AND bcs.loai_su_co = :loai_su_co";
            $params[':loai_su_co'] = $filters['loai_su_co'];
        }

        // Filter theo ngày
        if (!empty($filters['from_date'])) {
            $sql .= " AND bcs.ngay_xay_ra >= :from_date";
            $params[':from_date'] = $filters['from_date'];
        }

        if (!empty($filters['to_date'])) {
            $sql .= " AND bcs.ngay_xay_ra <= :to_date";
            $params[':to_date'] = $filters['to_date'];
        }

        // Filter theo phân công
        if (!empty($filters['id_phan_cong'])) {
            $sql .= " AND bcs.id_phan_cong = :id_phan_cong";
            $params[':id_phan_cong'] = $filters['id_phan_cong'];
        }

        $sql .= " ORDER BY bcs.ngay_xay_ra DESC, bcs.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo báo cáo sự cố mới
     */
    public function createReport(array $data)
    {
        try {
            $sql = "INSERT INTO bao_cao_su_co (
                        id_phan_cong, loai_su_co, mo_ta, 
                        cach_xu_ly, goi_y_xu_ly, muc_do, ngay_xay_ra, gio_xay_ra,
                        vi_tri_gps, hinh_anh, thong_tin_khach
                    ) VALUES (
                        :id_phan_cong, :loai_su_co, :mo_ta,
                        :cach_xu_ly, :goi_y_xu_ly, :muc_do, :ngay_xay_ra, :gio_xay_ra,
                        :vi_tri_gps, :hinh_anh, :thong_tin_khach
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_phan_cong' => $data['id_phan_cong'] ?? null,
                ':loai_su_co' => $data['loai_su_co'] ?? null,
                ':mo_ta' => $data['mo_ta'] ?? null,
                ':cach_xu_ly' => $data['cach_xu_ly'] ?? null,
                ':goi_y_xu_ly' => $data['goi_y_xu_ly'] ?? null,
                ':muc_do' => $data['muc_do'] ?? 'thap',
                ':ngay_xay_ra' => $data['ngay_xay_ra'] ?? date('Y-m-d'),
                ':gio_xay_ra' => $data['gio_xay_ra'] ?? null,
                ':vi_tri_gps' => $data['vi_tri_gps'] ?? null,
                ':hinh_anh' => $this->prepareJsonArray($data['hinh_anh'] ?? []),
                ':thong_tin_khach' => $data['thong_tin_khach'] ?? null,
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo báo cáo sự cố: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật báo cáo sự cố
     */
    public function updateReport($id, array $data)
    {
        try {
            $sql = "UPDATE bao_cao_su_co SET
                        loai_su_co = :loai_su_co,
                        mo_ta = :mo_ta,
                        cach_xu_ly = :cach_xu_ly,
                        goi_y_xu_ly = :goi_y_xu_ly,
                        muc_do = :muc_do,
                        ngay_xay_ra = :ngay_xay_ra,
                        gio_xay_ra = :gio_xay_ra,
                        vi_tri_gps = :vi_tri_gps,
                        hinh_anh = :hinh_anh,
                        thong_tin_khach = :thong_tin_khach,
                        ngay_cap_nhat = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':loai_su_co' => $data['loai_su_co'] ?? null,
                ':mo_ta' => $data['mo_ta'] ?? null,
                ':cach_xu_ly' => $data['cach_xu_ly'] ?? null,
                ':goi_y_xu_ly' => $data['goi_y_xu_ly'] ?? null,
                ':muc_do' => $data['muc_do'] ?? 'thap',
                ':ngay_xay_ra' => $data['ngay_xay_ra'] ?? date('Y-m-d'),
                ':gio_xay_ra' => $data['gio_xay_ra'] ?? null,
                ':vi_tri_gps' => $data['vi_tri_gps'] ?? null,
                ':hinh_anh' => $this->prepareJsonArray($data['hinh_anh'] ?? []),
                ':thong_tin_khach' => $data['thong_tin_khach'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật báo cáo sự cố: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa báo cáo sự cố
     */
    public function deleteReport($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM bao_cao_su_co WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa báo cáo sự cố: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra xem guide có quyền truy cập báo cáo này không
     */
    public function checkGuideAccess($reportId, $guideId)
    {
        $sql = "SELECT COUNT(*) as count
                FROM bao_cao_su_co bcs
                INNER JOIN phan_cong_hdv pc ON bcs.id_phan_cong = pc.id
                WHERE bcs.id = :report_id AND pc.id_hdv = :guide_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':report_id' => $reportId,
            ':guide_id' => $guideId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Lấy danh sách loại sự cố
     */
    public function getIncidentTypes()
    {
        return [
            'mat_do' => 'Mất đồ',
            'say_xe' => 'Say xe / Vấn đề sức khỏe nhẹ',
            'tre_gio' => 'Trễ giờ / Lạc đoàn',
            'thoi_tiet_xau' => 'Thời tiết xấu',
            'tai_nan' => 'Tai nạn',
            'benh_tat' => 'Bệnh tật',
            'thuc_an' => 'Vấn đề thức ăn',
            'khach_san' => 'Vấn đề khách sạn',
            'phuong_tien' => 'Sự cố giao thông / Phương tiện',
            'khach_dac_biet' => 'Sự cố khách đặc biệt',
            'khac' => 'Khác'
        ];
    }
    
    /**
     * Đánh dấu đã gửi báo cáo
     */
    public function markAsSent($id, $recipientEmail)
    {
        try {
            $sql = "UPDATE bao_cao_su_co SET
                        da_gui_bao_cao = 1,
                        ngay_gui_bao_cao = NOW(),
                        nguoi_nhan_bao_cao = :nguoi_nhan
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nguoi_nhan' => $recipientEmail
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi đánh dấu đã gửi báo cáo: " . $e->getMessage());
            return false;
        }
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

    /**
     * Lấy danh sách mức độ nghiêm trọng
     */
    public function getSeverityLevels()
    {
        return [
            'thap' => ['label' => 'Thấp', 'color' => '#10b981'],
            'trung_binh' => ['label' => 'Trung bình', 'color' => '#f59e0b'],
            'cao' => ['label' => 'Cao', 'color' => '#ef4444'],
            'nghiem_trong' => ['label' => 'Nghiêm trọng', 'color' => '#991b1b']
        ];
    }
}

