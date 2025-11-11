<?php
// BaseModel sẽ chứa hàm kết nối CSDL chung
class BaseModel {
    protected $conn;

    public function __construct() {
        $this->conn = connectDB(); // Hàm này từ /commons/function.php
    }
}