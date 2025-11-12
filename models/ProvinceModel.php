<?php
class ProvinceModel extends BaseModel {
    
    // Lấy danh sách tỉnh cho dropdown
    public function getAll() {
    try {
        $sql = "SELECT * FROM tinh ORDER BY ten_tinh";
        $query = $this->conn->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
        return [];
    }
}
}