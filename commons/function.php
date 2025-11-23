<?php

/**
 * Kết nối CSDL qua PDO
 */
function connectDB() {
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // Cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        error_log("Connection failed: " . $e->getMessage());
        die("Lỗi kết nối database. Vui lòng thử lại sau.");
    }
}

/**
 * Upload file AN TOÀN
 * ĐÃ CẬP NHẬT: Thêm validation đầy đủ
 * 
 * @param array $file File từ $_FILES
 * @param string $folderSave Thư mục lưu (ví dụ: 'uploads/tours/')
 * @return string|null Đường dẫn file nếu thành công, null nếu thất bại
 */
function uploadFile($file, $folderSave)
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null; // nếu không có file hợp lệ
    }

    $tmp_file = $file['tmp_name'];
    if (!is_uploaded_file($tmp_file)) {
        return null; // tránh lỗi "failed to open stream"
    }

    // Tạo thư mục nếu chưa tồn tại
    $folderPath = PATH_ROOT . $folderSave;
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    // Tạo tên file ngẫu nhiên để tránh trùng
    $fileName = uniqid() . '_' . basename($file['name']);
    $pathSave = $folderPath . $fileName;

    if (move_uploaded_file($tmp_file, $pathSave)) {
        // trả về đường dẫn tương đối để lưu vào DB
        return $folderSave . $fileName;
    }

    return null;
}


/**
 * Xóa file an toàn
 * ĐÃ CẬP NHẬT: Thêm validation
 * 
 * @param string $filePath Đường dẫn file cần xóa
 * @return bool True nếu xóa thành công
 */
function deleteFile($filePath) {
    if (empty($filePath)) {
        return false;
    }
    
    $fullPath = PATH_ROOT . $filePath;
    
    // Kiểm tra file có tồn tại không
    if (!file_exists($fullPath)) {
        error_log("Delete file warning: File not found '$fullPath'");
        return false;
    }
    
    // Kiểm tra có phải file không (không phải thư mục)
    if (!is_file($fullPath)) {
        error_log("Delete file error: Not a file '$fullPath'");
        return false;
    }
    
    // Kiểm tra đường dẫn không đi ra ngoài root (security)
    $realPath = realpath($fullPath);
    $realRoot = realpath(PATH_ROOT);
    
    if ($realPath === false || strpos($realPath, $realRoot) !== 0) {
        error_log("Delete file security error: Path traversal attempt '$filePath'");
        return false;
    }
    
    // Xóa file
    if (unlink($fullPath)) {
        error_log("Delete file success: " . $filePath);
        return true;
    }
    
    error_log("Delete file error: unlink failed '$fullPath'");
    return false;
}

/**
 * Sanitize input string
 * Làm sạch input từ user
 * 
 * @param string $input
 * @return string
 */
function sanitizeInput($input) {
    if (empty($input)) {
        return '';
    }
    
    // Trim whitespace
    $input = trim($input);
    
    // Remove null bytes
    $input = str_replace(chr(0), '', $input);
    
    // Convert special chars to HTML entities
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    return $input;
}

/**
 * Validate email
 * 
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate CSRF token
 * Tạo token chống CSRF
 * 
 * @return string
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * Kiểm tra token CSRF
 * 
 * @param string $token
 * @return bool
 */
function verifyCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    // Use hash_equals để tránh timing attack
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect helper
 * 
 * @param string $url
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function isLoggedIn() {
    return !empty($_SESSION['alogin']);
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(BASE_URL . '?act=login');
    }
}
function render($view, $data = [])
{
    // Lấy toàn bộ biến trong $data thành biến view
    extract($data);

    // Bắt đầu gom nội dung view
    ob_start();

    // Xác định đường dẫn gốc dự án
    $ROOT = dirname(__DIR__); // thư mục pro1014/

    // File view
    $file = $ROOT . "/views/" . $view . ".php";

    if (!file_exists($file)) {
        echo "<div class='errorWrap'>Không tìm thấy view: $view</div>";
        return '';
    }

    require $file;

    // Lấy HTML trả về
    return ob_get_clean();
}