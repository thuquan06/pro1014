<?php
class ProvinceModel extends BaseModel {

    protected $table = "tinh";  
    protected $primaryKey = "id_tinh";
    public function getAll($orderBy = 'ten_tinh', $order = 'ASC') {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProvinceModel::getAll() - " . $e->getMessage());
            return [];
        }
    }


    // ============================
    // LẤY 1 TỈNH THEO ID
    // ============================
    public function getById($id) {
        $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================
    // KIỂM TRA TÊN TỈNH TỒN TẠI
    // ============================
    public function exists($tenTinh) {
        $sql = "SELECT $this->primaryKey FROM $this->table WHERE ten_tinh = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tenTinh]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================
    // TẠO MỚI
    // ============================
    public function create($data) {
        $sql = "INSERT INTO $this->table (ten_tinh) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['ten_tinh']]);
    }

    // ============================
    // CẬP NHẬT
    // ============================
    public function update($id, $data) {
        $sql = "UPDATE $this->table SET ten_tinh = ? WHERE $this->primaryKey = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['ten_tinh'], $id]);
    }

    // ============================
    // XÓA 
    // ============================
    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE $this->primaryKey = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ============================
    // SEARCH + PAGINATION
    // ============================
    public function getWithPagination($keyword = "", $page = 1, $limit = 10) {
    $offset = ($page - 1) * $limit;

    // SEARCH
    if ($keyword !== "") {
        $sql = "SELECT * FROM tinh 
                WHERE ten_tinh LIKE :kw 
                ORDER BY ten_tinh ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $kw = '%' . $keyword . '%';
        $stmt->bindParam(':kw', $kw, PDO::PARAM_STR);
    } else {
        $sql = "SELECT * FROM tinh 
                ORDER BY ten_tinh ASC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
    }

    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL COUNT
    if ($keyword !== "") {
        $sqlCount = "SELECT COUNT(*) FROM tinh WHERE ten_tinh LIKE :kw";
        $stmt2 = $this->conn->prepare($sqlCount);
        $stmt2->bindParam(':kw', $kw, PDO::PARAM_STR);
    } else {
        $sqlCount = "SELECT COUNT(*) FROM tinh";
        $stmt2 = $this->conn->prepare($sqlCount);
    }

    $stmt2->execute();
    $total = $stmt2->fetchColumn();

    return [
        'data'  => $rows,
        'total' => $total
    ];
}
public function checkUsage($id) {
    try {
        // Nếu bảng goidulich có id_tinh thì dùng đoạn này:
        $sql = "SELECT COUNT(*) as total FROM goidulich WHERE ten_tinh = (SELECT ten_tinh FROM tinh WHERE id_tinh = :id)";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return intval($row['total']);
    } catch (PDOException $e) {
        return 0;
    }
}

}
