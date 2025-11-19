<?php
class TourVersionModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB();
    }
    
    /**
     * Lấy tất cả versions của 1 tour
     */
    public function layDanhSachVersions($idGoi) {
        $sql = "SELECT * FROM tour_versions 
                WHERE id_goi = :id_goi 
                ORDER BY is_default DESC, priority DESC, ngay_batdau DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy 1 version theo ID
     */
    public function layMotVersion($id) {
        $sql = "SELECT * FROM tour_versions WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy version đang active cho ngày cụ thể
     */
    public function layVersionTheoNgay($idGoi, $ngay) {
        $sql = "SELECT * FROM tour_versions 
                WHERE id_goi = :id_goi 
                AND is_active = 1
                AND :ngay BETWEEN ngay_batdau AND ngay_ketthuc
                ORDER BY priority DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi, ':ngay' => $ngay]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy version mặc định
     */
    public function layVersionMacDinh($idGoi) {
        $sql = "SELECT * FROM tour_versions 
                WHERE id_goi = :id_goi AND is_default = 1 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $idGoi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm version mới
     */
    public function themVersion($data) {
        $sql = "INSERT INTO tour_versions 
                (id_goi, ten_phienban, loai_phienban, mo_ta, 
                 ngay_batdau, ngay_ketthuc, 
                 gia_nguoilon, gia_treem, gia_embe,
                 is_active, is_default, priority, created_by) 
                VALUES 
                (:id_goi, :ten_phienban, :loai_phienban, :mo_ta,
                 :ngay_batdau, :ngay_ketthuc,
                 :gia_nguoilon, :gia_treem, :gia_embe,
                 :is_active, :is_default, :priority, :created_by)";
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            ':id_goi'        => $data['id_goi'],
            ':ten_phienban'  => $data['ten_phienban'],
            ':loai_phienban' => $data['loai_phienban'],
            ':mo_ta'         => $data['mo_ta'] ?? null,
            ':ngay_batdau'   => $data['ngay_batdau'],
            ':ngay_ketthuc'  => $data['ngay_ketthuc'],
            ':gia_nguoilon'  => $data['gia_nguoilon'] ?? null,
            ':gia_treem'     => $data['gia_treem'] ?? null,
            ':gia_embe'      => $data['gia_embe'] ?? null,
            ':is_active'     => $data['is_active'] ?? 1,
            ':is_default'    => $data['is_default'] ?? 0,
            ':priority'      => $data['priority'] ?? 0,
            ':created_by'    => $data['created_by'] ?? null
        ]);
        
        if ($result) {
            $versionId = $this->conn->lastInsertId();
            
            // Ghi log
            $this->ghiLog($versionId, 'create', 'Tạo phiên bản mới', null, $data, $data['created_by']);
            
            return $versionId;
        }
        
        return false;
    }
    
    /**
     * Cập nhật version
     */
    public function suaVersion($id, $data) {
        // Lấy data cũ để log
        $dataCu = $this->layMotVersion($id);
        
        $sql = "UPDATE tour_versions SET
                ten_phienban = :ten_phienban,
                loai_phienban = :loai_phienban,
                mo_ta = :mo_ta,
                ngay_batdau = :ngay_batdau,
                ngay_ketthuc = :ngay_ketthuc,
                gia_nguoilon = :gia_nguoilon,
                gia_treem = :gia_treem,
                gia_embe = :gia_embe,
                is_active = :is_active,
                is_default = :is_default,
                priority = :priority
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            ':ten_phienban'  => $data['ten_phienban'],
            ':loai_phienban' => $data['loai_phienban'],
            ':mo_ta'         => $data['mo_ta'] ?? null,
            ':ngay_batdau'   => $data['ngay_batdau'],
            ':ngay_ketthuc'  => $data['ngay_ketthuc'],
            ':gia_nguoilon'  => $data['gia_nguoilon'] ?? null,
            ':gia_treem'     => $data['gia_treem'] ?? null,
            ':gia_embe'      => $data['gia_embe'] ?? null,
            ':is_active'     => $data['is_active'] ?? 1,
            ':is_default'    => $data['is_default'] ?? 0,
            ':priority'      => $data['priority'] ?? 0,
            ':id'            => $id
        ]);
        
        if ($result) {
            // Ghi log
            $this->ghiLog($id, 'update', 'Cập nhật phiên bản', $dataCu, $data, $data['updated_by'] ?? null);
        }
        
        return $result;
    }
    
    /**
     * Xóa version
     */
    public function xoaVersion($id, $userId = null) {
        $dataCu = $this->layMotVersion($id);
        
        $stmt = $this->conn->prepare("DELETE FROM tour_versions WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);
        
        if ($result) {
            $this->ghiLog($id, 'delete', 'Xóa phiên bản', $dataCu, null, $userId);
        }
        
        return $result;
    }
    
    /**
     * Clone version
     */
    public function cloneVersion($idVersion, $tenMoi, $userId = null) {
        $versionCu = $this->layMotVersion($idVersion);
        
        if (!$versionCu) {
            return false;
        }
        
        // Tạo version mới từ version cũ
        $data = [
            'id_goi'        => $versionCu['id_goi'],
            'ten_phienban'  => $tenMoi,
            'loai_phienban' => $versionCu['loai_phienban'],
            'mo_ta'         => $versionCu['mo_ta'],
            'ngay_batdau'   => $versionCu['ngay_batdau'],
            'ngay_ketthuc'  => $versionCu['ngay_ketthuc'],
            'gia_nguoilon'  => $versionCu['gia_nguoilon'],
            'gia_treem'     => $versionCu['gia_treem'],
            'gia_embe'      => $versionCu['gia_embe'],
            'is_active'     => 0, // Tắt mặc định
            'is_default'    => 0,
            'priority'      => $versionCu['priority'],
            'created_by'    => $userId
        ];
        
        $newVersionId = $this->themVersion($data);
        
        if ($newVersionId) {
            // Copy lịch trình
            $this->copyLichTrinh($idVersion, $newVersionId);
            
            // Copy chính sách
            $this->copyChinhSach($idVersion, $newVersionId);
            
            // Ghi log
            $this->ghiLog($newVersionId, 'clone', "Clone từ version #{$idVersion}", null, $data, $userId);
        }
        
        return $newVersionId;
    }
    
    /**
     * Copy lịch trình từ version này sang version khác
     */
    private function copyLichTrinh($fromVersionId, $toVersionId) {
        $sql = "INSERT INTO tour_version_lichtrinh (id_version, id_lichtrinh, thu_tu)
                SELECT :to_version, id_lichtrinh, thu_tu
                FROM tour_version_lichtrinh
                WHERE id_version = :from_version";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':from_version' => $fromVersionId,
            ':to_version'   => $toVersionId
        ]);
    }
    
    /**
     * Copy chính sách từ version này sang version khác
     */
    private function copyChinhSach($fromVersionId, $toVersionId) {
        $sql = "INSERT INTO tour_version_chinhsach (id_version, id_chinhsach, thu_tu)
                SELECT :to_version, id_chinhsach, thu_tu
                FROM tour_version_chinhsach
                WHERE id_version = :from_version";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':from_version' => $fromVersionId,
            ':to_version'   => $toVersionId
        ]);
    }
    
    /**
     * Ghi log lịch sử thay đổi
     */
    public function ghiLog($idVersion, $hanhDong, $noiDung, $dataCu = null, $dataMoi = null, $userId = null) {
        $sql = "INSERT INTO tour_version_history 
                (id_version, hanh_dong, noi_dung, data_cu, data_moi, created_by)
                VALUES 
                (:id_version, :hanh_dong, :noi_dung, :data_cu, :data_moi, :created_by)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_version' => $idVersion,
            ':hanh_dong'  => $hanhDong,
            ':noi_dung'   => $noiDung,
            ':data_cu'    => $dataCu ? json_encode($dataCu) : null,
            ':data_moi'   => $dataMoi ? json_encode($dataMoi) : null,
            ':created_by' => $userId
        ]);
    }
    
    /**
     * Lấy lịch sử thay đổi của version
     */
    public function layLichSu($idVersion) {
        $sql = "SELECT * FROM tour_version_history 
                WHERE id_version = :id_version 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_version' => $idVersion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đặt version làm mặc định
     */
    public function datMacDinh($idVersion, $userId = null) {
        $version = $this->layMotVersion($idVersion);
        
        if (!$version) {
            return false;
        }
        
        // Bỏ mặc định cho các version khác
        $sql = "UPDATE tour_versions SET is_default = 0 WHERE id_goi = :id_goi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_goi' => $version['id_goi']]);
        
        // Đặt version này làm mặc định
        $sql = "UPDATE tour_versions SET is_default = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([':id' => $idVersion]);
        
        if ($result) {
            $this->ghiLog($idVersion, 'update', 'Đặt làm version mặc định', null, null, $userId);
        }
        
        return $result;
    }
}
?>