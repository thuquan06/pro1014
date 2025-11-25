<?php
/**
 * Sửa Lịch trình - Redirect to create.php
 * File này sử dụng chung giao diện với create.php
 */

// Set biến $lichtrinh để create.php biết đây là chế độ edit
$lichtrinh = $lichTrinh;

// Include file create.php (đã có logic xử lý cả create và edit)
include './views/admin/tours/lichtrinh/create.php';
?>
