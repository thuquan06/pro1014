<?php

/**
 * Kết nối CSDL qua PDO
 */
function connectDB() {
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

        // Cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Cài đặt timeout
        $conn->setAttribute(PDO::ATTR_TIMEOUT, 5);
    
        return $conn;
    } catch (PDOException $e) {
        $errorMsg = "Connection failed: " . $e->getMessage();
        $errorMsg .= " | Host: $host | Port: $port | Database: $dbname | User: " . DB_USERNAME;
        error_log($errorMsg);
        
        // Trong môi trường development, hiển thị chi tiết lỗi
        $isProduction = false; // Có thể lấy từ env.php nếu cần
        if (!$isProduction) {
            die("Lỗi kết nối database: " . htmlspecialchars($e->getMessage()) . 
                "<br>Host: $host | Port: $port | Database: $dbname" .
                "<br>Vui lòng kiểm tra:<br>" .
                "1. MySQL/MariaDB đã được khởi động chưa?<br>" .
                "2. Port $port có đúng không?<br>" .
                "3. Database '$dbname' đã được tạo chưa?<br>" .
                "4. Username và password có đúng không?");
        } else {
            die("Lỗi kết nối database. Vui lòng thử lại sau.");
        }
    }
}

/**
 * Upload file AN TOÀN
 * ĐÃ CẬP NHẬT: Validation đầy đủ với MIME type check, file size, extension whitelist
 * 
 * @param array $file File từ $_FILES
 * @param string $folderSave Thư mục lưu (ví dụ: 'uploads/tours/')
 * @param array $options Tùy chọn ['maxSize' => bytes, 'allowedTypes' => [], 'allowedExtensions' => []]
 * @return string|null Đường dẫn file nếu thành công, null nếu thất bại
 */
function uploadFile($file, $folderSave, $options = [])
{
    // Default options
    $defaults = [
        'maxSize' => 5242880, // 5MB
        'allowedTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
        'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
    ];
    $options = array_merge($defaults, $options);

    // Check if file exists and has no errors
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        error_log("Upload file error: " . ($file['error'] ?? 'No file provided'));
        return null;
    }

    $tmp_file = $file['tmp_name'];
    if (!is_uploaded_file($tmp_file)) {
        error_log("Upload file error: Not an uploaded file");
        return null;
    }

    // Validate file size
    if ($file['size'] > $options['maxSize']) {
        error_log("Upload file error: File too large (" . $file['size'] . " bytes)");
        return null;
    }

    // Validate MIME type using finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmp_file);
    finfo_close($finfo);

    if (!in_array($mimeType, $options['allowedTypes'])) {
        error_log("Upload file error: Invalid MIME type: " . $mimeType);
        return null;
    }

    // Validate file extension
    $originalName = basename($file['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    if (!in_array($extension, $options['allowedExtensions'])) {
        error_log("Upload file error: Invalid extension: " . $extension);
        return null;
    }

    // Additional security: Validate image files
    if (strpos($mimeType, 'image/') === 0) {
        $imageInfo = @getimagesize($tmp_file);
        if ($imageInfo === false) {
            error_log("Upload file error: Invalid image file");
            return null;
        }
    }

    // Sanitize filename - remove special characters
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
    
    // Create folder if not exists
    $folderPath = PATH_ROOT . $folderSave;
    if (!is_dir($folderPath)) {
        if (!mkdir($folderPath, 0755, true)) {
            error_log("Upload file error: Cannot create directory: " . $folderPath);
            return null;
        }
    }

    // Generate unique filename to prevent overwriting
    $fileName = uniqid() . '_' . $safeName;
    $pathSave = $folderPath . $fileName;

    // Move uploaded file
    if (move_uploaded_file($tmp_file, $pathSave)) {
        // Set proper permissions
        chmod($pathSave, 0644);
        
        error_log("Upload file success: " . $folderSave . $fileName);
        // Return relative path for database storage
        return $folderSave . $fileName;
    }

    error_log("Upload file error: move_uploaded_file failed");
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

/**
 * Rate limiting for login attempts
 * Giới hạn số lần đăng nhập thất bại
 * 
 * @param string $identifier Username hoặc IP
 * @param int $maxAttempts Số lần thử tối đa (default: 5)
 * @param int $timeWindow Thời gian window (giây) (default: 900 = 15 phút)
 * @return array ['allowed' => bool, 'remaining' => int, 'reset_time' => int]
 */
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900) {
    $key = 'rate_limit_' . md5($identifier);
    $now = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => $now,
            'locked_until' => 0
        ];
    }
    
    $data = $_SESSION[$key];
    
    // Check if currently locked
    if ($data['locked_until'] > $now) {
        return [
            'allowed' => false,
            'remaining' => 0,
            'reset_time' => $data['locked_until'],
            'wait_time' => $data['locked_until'] - $now
        ];
    }
    
    // Reset if time window has passed
    if ($now - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => $now,
            'locked_until' => 0
        ];
        $data = $_SESSION[$key];
    }
    
    $remaining = max(0, $maxAttempts - $data['attempts']);
    
    return [
        'allowed' => $data['attempts'] < $maxAttempts,
        'remaining' => $remaining,
        'reset_time' => $data['first_attempt'] + $timeWindow,
        'wait_time' => 0
    ];
}

/**
 * Record failed login attempt
 * Ghi nhận lần đăng nhập thất bại
 * 
 * @param string $identifier
 * @param int $maxAttempts
 * @param int $lockDuration Thời gian khóa (giây) khi vượt quá số lần thử
 */
function recordFailedAttempt($identifier, $maxAttempts = 5, $lockDuration = 900) {
    $key = 'rate_limit_' . md5($identifier);
    $now = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => $now,
            'locked_until' => 0
        ];
    }
    
    $_SESSION[$key]['attempts']++;
    
    // Lock if max attempts exceeded
    if ($_SESSION[$key]['attempts'] >= $maxAttempts) {
        $_SESSION[$key]['locked_until'] = $now + $lockDuration;
        error_log("Rate limit exceeded for: " . $identifier);
    }
}

/**
 * Reset rate limit (after successful login)
 * 
 * @param string $identifier
 */
function resetRateLimit($identifier) {
    $key = 'rate_limit_' . md5($identifier);
    unset($_SESSION[$key]);
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