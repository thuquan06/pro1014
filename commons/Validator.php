<?php
/**
 * Validator Class - Comprehensive Input Validation
 * Cung cấp các phương thức validation thông dụng và an toàn
 * 
 * @version 1.0
 * @author Security Team
 */
class Validator {
    
    private $errors = [];
    private $data = [];
    
    /**
     * Constructor
     * @param array $data Dữ liệu cần validate (thường là $_POST hoặc $_GET)
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Validate required field
     * @param string $field Tên trường
     * @param string $message Thông báo lỗi tùy chỉnh
     * @return self
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = $message ?? "Trường {$field} là bắt buộc";
        }
        return $this;
    }
    
    /**
     * Validate email format
     * @param string $field Tên trường
     * @param string $message Thông báo lỗi
     * @return self
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = $message ?? "Email không hợp lệ";
            }
        }
        return $this;
    }
    
    /**
     * Validate minimum length
     * @param string $field
     * @param int $min
     * @param string $message
     * @return self
     */
    public function minLength($field, $min, $message = null) {
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) < $min) {
            $this->errors[$field] = $message ?? "Trường {$field} phải có ít nhất {$min} ký tự";
        }
        return $this;
    }
    
    /**
     * Validate maximum length
     * @param string $field
     * @param int $max
     * @param string $message
     * @return self
     */
    public function maxLength($field, $max, $message = null) {
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) > $max) {
            $this->errors[$field] = $message ?? "Trường {$field} không được vượt quá {$max} ký tự";
        }
        return $this;
    }
    
    /**
     * Validate numeric value
     * @param string $field
     * @param string $message
     * @return self
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "Trường {$field} phải là số";
        }
        return $this;
    }
    
    /**
     * Validate integer value
     * @param string $field
     * @param string $message
     * @return self
     */
    public function integer($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = $message ?? "Trường {$field} phải là số nguyên";
        }
        return $this;
    }
    
    /**
     * Validate minimum numeric value
     * @param string $field
     * @param float $min
     * @param string $message
     * @return self
     */
    public function min($field, $min, $message = null) {
        if (isset($this->data[$field]) && is_numeric($this->data[$field])) {
            if ($this->data[$field] < $min) {
                $this->errors[$field] = $message ?? "Trường {$field} phải lớn hơn hoặc bằng {$min}";
            }
        }
        return $this;
    }
    
    /**
     * Validate maximum numeric value
     * @param string $field
     * @param float $max
     * @param string $message
     * @return self
     */
    public function max($field, $max, $message = null) {
        if (isset($this->data[$field]) && is_numeric($this->data[$field])) {
            if ($this->data[$field] > $max) {
                $this->errors[$field] = $message ?? "Trường {$field} phải nhỏ hơn hoặc bằng {$max}";
            }
        }
        return $this;
    }
    
    /**
     * Validate date format
     * @param string $field
     * @param string $format Default: Y-m-d
     * @param string $message
     * @return self
     */
    public function date($field, $format = 'Y-m-d', $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $d = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$d || $d->format($format) !== $this->data[$field]) {
                $this->errors[$field] = $message ?? "Ngày tháng không hợp lệ (định dạng: {$format})";
            }
        }
        return $this;
    }
    
    /**
     * Validate regex pattern
     * @param string $field
     * @param string $pattern
     * @param string $message
     * @return self
     */
    public function pattern($field, $pattern, $message = null) {
        if (isset($this->data[$field]) && !preg_match($pattern, $this->data[$field])) {
            $this->errors[$field] = $message ?? "Trường {$field} không hợp lệ";
        }
        return $this;
    }
    
    /**
     * Validate alphanumeric
     * @param string $field
     * @param string $message
     * @return self
     */
    public function alphanumeric($field, $message = null) {
        if (isset($this->data[$field]) && !ctype_alnum($this->data[$field])) {
            $this->errors[$field] = $message ?? "Trường {$field} chỉ được chứa chữ và số";
        }
        return $this;
    }
    
    /**
     * Validate URL
     * @param string $field
     * @param string $message
     * @return self
     */
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? "URL không hợp lệ";
        }
        return $this;
    }
    
    /**
     * Validate value is in array
     * @param string $field
     * @param array $values
     * @param string $message
     * @return self
     */
    public function in($field, array $values, $message = null) {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values, true)) {
            $this->errors[$field] = $message ?? "Giá trị {$field} không hợp lệ";
        }
        return $this;
    }
    
    /**
     * Custom validation with callback
     * @param string $field
     * @param callable $callback
     * @param string $message
     * @return self
     */
    public function custom($field, callable $callback, $message = null) {
        if (isset($this->data[$field])) {
            if (!$callback($this->data[$field])) {
                $this->errors[$field] = $message ?? "Trường {$field} không hợp lệ";
            }
        }
        return $this;
    }
    
    /**
     * Check if validation passed
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Check if validation failed
     * @return bool
     */
    public function fails() {
        return !$this->passes();
    }
    
    /**
     * Get all errors
     * @return array
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     * @return string|null
     */
    public function firstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Get error for specific field
     * @param string $field
     * @return string|null
     */
    public function error($field) {
        return $this->errors[$field] ?? null;
    }
    
    /**
     * Get validated data (sanitized)
     * @return array
     */
    public function validated() {
        $validated = [];
        foreach ($this->data as $key => $value) {
            if (!isset($this->errors[$key])) {
                $validated[$key] = is_string($value) ? sanitizeInput($value) : $value;
            }
        }
        return $validated;
    }
    
    /**
     * Add custom error
     * @param string $field
     * @param string $message
     * @return self
     */
    public function addError($field, $message) {
        $this->errors[$field] = $message;
        return $this;
    }
    
    /**
     * Static helper - validate file upload
     * @param array $file File từ $_FILES
     * @param array $options ['maxSize' => bytes, 'allowedTypes' => [], 'allowedExtensions' => []]
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function validateFile($file, $options = []) {
        $defaults = [
            'maxSize' => 5242880, // 5MB default
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'required' => true
        ];
        
        $options = array_merge($defaults, $options);
        
        // Check if file exists
        if (!isset($file) || !isset($file['error'])) {
            return [
                'valid' => !$options['required'],
                'error' => $options['required'] ? 'File là bắt buộc' : null
            ];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File quá lớn (vượt quá giới hạn server)',
                UPLOAD_ERR_FORM_SIZE => 'File quá lớn (vượt quá giới hạn form)',
                UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần',
                UPLOAD_ERR_NO_FILE => 'Không có file nào được upload',
                UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
                UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file',
                UPLOAD_ERR_EXTENSION => 'Upload bị chặn bởi extension'
            ];
            
            return [
                'valid' => false,
                'error' => $errorMessages[$file['error']] ?? 'Lỗi upload không xác định'
            ];
        }
        
        // Check file size
        if ($file['size'] > $options['maxSize']) {
            return [
                'valid' => false,
                'error' => 'File quá lớn (tối đa: ' . self::formatBytes($options['maxSize']) . ')'
            ];
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $options['allowedTypes'])) {
            return [
                'valid' => false,
                'error' => 'Loại file không được phép (chỉ chấp nhận: ' . implode(', ', $options['allowedExtensions']) . ')'
            ];
        }
        
        // Check extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $options['allowedExtensions'])) {
            return [
                'valid' => false,
                'error' => 'Phần mở rộng file không được phép'
            ];
        }
        
        // Additional security check for images
        if (strpos($mimeType, 'image/') === 0) {
            $imageInfo = @getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return [
                    'valid' => false,
                    'error' => 'File không phải là ảnh hợp lệ'
                ];
            }
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * Format bytes to human readable
     * @param int $bytes
     * @return string
     */
    private static function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
