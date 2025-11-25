<?php
/**
 * UserModel - Quản lý người dùng (nguoidung)
 * UC-User-Management: Quản lý thông tin người dùng
 */
class UserModel extends BaseModel
{
    /**
     * Lấy tất cả người dùng
     */
    public function getAllUsers($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM nguoidung ORDER BY ngaytao DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo ID
     */
    public function getUserByID($id)
    {
        $sql = "SELECT * FROM nguoidung WHERE id_nguoidung = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo Email
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM nguoidung WHERE id_email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm người dùng
     */
    public function searchUsers($keyword)
    {
        $sql = "SELECT * FROM nguoidung 
                WHERE hoten LIKE :keyword 
                   OR id_email LIKE :keyword 
                   OR sdt_nd LIKE :keyword
                ORDER BY ngaytao DESC";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo người dùng mới
     */
    public function createUser(array $data)
    {
        try {
            $sql = "INSERT INTO nguoidung (
                        hoten, sdt_nd, id_email, matkhau,
                        hinhanh, ngaysinh, diachi, ngaytao, ngaycapnhat
                    ) VALUES (
                        :hoten, :sdt_nd, :id_email, :matkhau,
                        :hinhanh, :ngaysinh, :diachi, NOW(), NOW()
                    )";

            // Hash password nếu có
            $password = null;
            if (!empty($data['matkhau'])) {
                $password = password_hash($data['matkhau'], PASSWORD_BCRYPT);
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':hoten' => $data['hoten'] ?? '',
                ':sdt_nd' => $data['sdt_nd'] ?? '',
                ':id_email' => $data['id_email'] ?? '',
                ':matkhau' => $password,
                ':hinhanh' => $data['hinhanh'] ?? '',
                ':ngaysinh' => $data['ngaysinh'] ?? '',
                ':diachi' => $data['diachi'] ?? '',
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo người dùng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật người dùng
     */
    public function updateUser($id, array $data)
    {
        try {
            // Nếu có mật khẩu mới, hash nó
            $passwordUpdate = '';
            if (!empty($data['matkhau'])) {
                $hashedPassword = password_hash($data['matkhau'], PASSWORD_BCRYPT);
                $passwordUpdate = ', matkhau = :matkhau';
            }

            $sql = "UPDATE nguoidung SET
                        hoten = :hoten,
                        sdt_nd = :sdt_nd,
                        id_email = :id_email,
                        hinhanh = :hinhanh,
                        ngaysinh = :ngaysinh,
                        diachi = :diachi
                        {$passwordUpdate},
                        ngaycapnhat = NOW()
                    WHERE id_nguoidung = :id";

            $params = [
                ':id' => $id,
                ':hoten' => $data['hoten'] ?? '',
                ':sdt_nd' => $data['sdt_nd'] ?? '',
                ':id_email' => $data['id_email'] ?? '',
                ':hinhanh' => $data['hinhanh'] ?? '',
                ':ngaysinh' => $data['ngaysinh'] ?? '',
                ':diachi' => $data['diachi'] ?? '',
            ];

            if (!empty($data['matkhau'])) {
                $params[':matkhau'] = $hashedPassword;
            }

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật người dùng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM nguoidung WHERE id_nguoidung = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi xóa người dùng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Đếm tổng số người dùng
     */
    public function countUsers()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM nguoidung");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Đếm người dùng theo trạng thái (không có trong bảng, giữ lại để tương thích)
     */
    public function countUsersByStatus($status)
    {
        // Bảng không có trang_thai, trả về 0
        return 0;
    }
}
