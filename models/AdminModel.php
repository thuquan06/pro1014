<?php
// File: models/AdminModel.php
class AdminModel {
    private $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    // Phiên bản dùng MD5 (đồng bộ code cũ)
    public function checkLogin($username, $md5Password) {
        $sql = "SELECT * FROM admin WHERE UserName = :u AND Password = :p LIMIT 1";
        $stm = $this->conn->prepare($sql);
        $stm->execute([':u'=>$username, ':p'=>$md5Password]);
        $row = $stm->fetch();
        return $row ?: false;
    }

    /* Nếu muốn chuyển sang password_hash():
    public function findByUsername($username) {
        $sql = "SELECT * FROM admin WHERE UserName = :u LIMIT 1";
        $stm = $this->conn->prepare($sql);
        $stm->execute([':u'=>$username]);
        return $stm->fetch() ?: false;
    }
    */
}
