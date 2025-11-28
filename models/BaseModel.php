<?php
class BaseModel {
   protected $conn;
   
   public function __construct() {
       try {
           $this->conn = connectDB();
       } catch (Exception $e) {
           error_log("BaseModel constructor error: " . $e->getMessage());
           throw new Exception("Không thể khởi tạo kết nối database. Vui lòng kiểm tra cấu hình.");
       }
   }
   
   /**
    * Kiểm tra kết nối database
    */
   protected function checkConnection() {
       if (!$this->conn) {
           throw new Exception("Database connection không tồn tại");
       }
       return true;
   }
}
