<?php
class BaseModel {
   protected $conn;
   public function __construct() {
       $this->conn = connectDB();
   }
}
