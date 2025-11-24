# Hướng Dẫn Validation - PHP MVC Application

## Tổng Quan

Dự án đã được cập nhật với hệ thống validation toàn diện để đảm bảo bảo mật và tính toàn vẹn dữ liệu. Tài liệu này mô tả các tính năng validation đã được triển khai.

---

## 📋 Mục Lục

1. [Validator Class](#validator-class)
2. [Validation Functions](#validation-functions)
3. [File Upload Validation](#file-upload-validation)
4. [Rate Limiting](#rate-limiting)
5. [Password Security](#password-security)
6. [Examples](#examples)
7. [Best Practices](#best-practices)

---

## Validator Class

### Tính Năng

Validator class cung cấp các phương thức validation chuỗi (chainable) để dễ dàng validate dữ liệu form.

### Cách Sử Dụng

```php
require_once './commons/Validator.php';

// Khởi tạo validator với dữ liệu cần validate
$validator = new Validator($_POST);

// Chain các validation rules
$validator->required('email', 'Email là bắt buộc')
          ->email('email', 'Email không hợp lệ')
          ->required('username', 'Username là bắt buộc')
          ->minLength('username', 3, 'Username phải có ít nhất 3 ký tự')
          ->maxLength('username', 20, 'Username không được quá 20 ký tự');

// Kiểm tra validation
if ($validator->fails()) {
    $errors = $validator->errors(); // Lấy tất cả lỗi
    $firstError = $validator->firstError(); // Lấy lỗi đầu tiên
    // Xử lý lỗi...
}

// Lấy dữ liệu đã validate và sanitize
$validatedData = $validator->validated();
```

### Các Validation Rules Có Sẵn

#### 1. **required($field, $message = null)**
Kiểm tra trường có giá trị hay không.

```php
$validator->required('name', 'Tên là bắt buộc');
```

#### 2. **email($field, $message = null)**
Kiểm tra định dạng email hợp lệ.

```php
$validator->email('email', 'Email không hợp lệ');
```

#### 3. **minLength($field, $min, $message = null)**
Kiểm tra độ dài tối thiểu.

```php
$validator->minLength('password', 8, 'Mật khẩu phải có ít nhất 8 ký tự');
```

#### 4. **maxLength($field, $max, $message = null)**
Kiểm tra độ dài tối đa.

```php
$validator->maxLength('username', 50, 'Username không được quá 50 ký tự');
```

#### 5. **numeric($field, $message = null)**
Kiểm tra giá trị có phải số không.

```php
$validator->numeric('price', 'Giá phải là số');
```

#### 6. **integer($field, $message = null)**
Kiểm tra giá trị có phải số nguyên không.

```php
$validator->integer('age', 'Tuổi phải là số nguyên');
```

#### 7. **min($field, $min, $message = null)**
Kiểm tra giá trị số tối thiểu.

```php
$validator->min('price', 0, 'Giá phải lớn hơn hoặc bằng 0');
```

#### 8. **max($field, $max, $message = null)**
Kiểm tra giá trị số tối đa.

```php
$validator->max('quantity', 100, 'Số lượng không được quá 100');
```

#### 9. **date($field, $format = 'Y-m-d', $message = null)**
Kiểm tra định dạng ngày tháng.

```php
$validator->date('birthday', 'Y-m-d', 'Ngày sinh không hợp lệ');
```

#### 10. **pattern($field, $pattern, $message = null)**
Kiểm tra với regex pattern.

```php
$validator->pattern('phone', '/^[0-9]{10}$/', 'Số điện thoại không hợp lệ');
```

#### 11. **alphanumeric($field, $message = null)**
Kiểm tra chỉ chứa chữ và số.

```php
$validator->alphanumeric('username', 'Username chỉ được chứa chữ và số');
```

#### 12. **url($field, $message = null)**
Kiểm tra URL hợp lệ.

```php
$validator->url('website', 'Website không hợp lệ');
```

#### 13. **in($field, array $values, $message = null)**
Kiểm tra giá trị có trong mảng cho trước.

```php
$validator->in('status', ['active', 'inactive'], 'Trạng thái không hợp lệ');
```

#### 14. **custom($field, callable $callback, $message = null)**
Validation tùy chỉnh với callback.

```php
$validator->custom('username', function($value) {
    // Custom logic
    return strlen($value) > 5 && ctype_alnum($value);
}, 'Username không đáp ứng yêu cầu');
```

---

## Validation Functions

### 1. **sanitizeInput($input)**

Làm sạch input từ user để tránh XSS attacks.

```php
$cleanInput = sanitizeInput($_POST['username']);
```

**Chức năng:**
- Trim whitespace
- Loại bỏ null bytes
- Convert special characters sang HTML entities

### 2. **isValidEmail($email)**

Kiểm tra email hợp lệ.

```php
if (isValidEmail($email)) {
    // Email hợp lệ
}
```

### 3. **generateCSRFToken()**

Tạo CSRF token cho form.

```php
$token = generateCSRFToken();
// Trong form:
// <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
```

### 4. **verifyCSRFToken($token)**

Xác thực CSRF token.

```php
if (!verifyCSRFToken($_POST['csrf_token'])) {
    die('Invalid CSRF token');
}
```

---

## File Upload Validation

### Upload File An Toàn

```php
// Upload file với validation mặc định (image, max 5MB)
$filePath = uploadFile($_FILES['image'], 'uploads/images/');

if ($filePath) {
    // Upload thành công
    echo "File uploaded: " . $filePath;
} else {
    // Upload thất bại
    echo "Upload failed";
}
```

### Upload File Với Options Tùy Chỉnh

```php
$options = [
    'maxSize' => 10485760, // 10MB
    'allowedTypes' => ['application/pdf', 'image/jpeg', 'image/png'],
    'allowedExtensions' => ['pdf', 'jpg', 'jpeg', 'png']
];

$filePath = uploadFile($_FILES['document'], 'uploads/documents/', $options);
```

### Validate File Trước Khi Upload

```php
$validation = Validator::validateFile($_FILES['photo'], [
    'maxSize' => 5242880, // 5MB
    'allowedTypes' => ['image/jpeg', 'image/png'],
    'allowedExtensions' => ['jpg', 'jpeg', 'png'],
    'required' => true
]);

if ($validation['valid']) {
    $filePath = uploadFile($_FILES['photo'], 'uploads/photos/');
} else {
    echo "Error: " . $validation['error'];
}
```

### File Upload Security Features

✅ **MIME type validation** - Kiểm tra loại file thực tế, không chỉ extension  
✅ **File size limits** - Giới hạn kích thước file  
✅ **Extension whitelist** - Chỉ cho phép các extension an toàn  
✅ **Image validation** - Kiểm tra ảnh có hợp lệ không với `getimagesize()`  
✅ **Filename sanitization** - Loại bỏ ký tự đặc biệt khỏi tên file  
✅ **Unique filenames** - Tạo tên file unique để tránh ghi đè  
✅ **Secure permissions** - Set permissions 0644 cho file đã upload  

---

## Rate Limiting

### Giới Hạn Login Attempts

Rate limiting được tích hợp sẵn vào AdminController để ngăn brute force attacks.

#### **checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900)**

```php
$identifier = $username ?: $_SERVER['REMOTE_ADDR'];
$rateLimit = checkRateLimit($identifier, 5, 900); // 5 lần trong 15 phút

if (!$rateLimit['allowed']) {
    $waitMinutes = ceil($rateLimit['wait_time'] / 60);
    echo "Quá nhiều lần thử. Vui lòng đợi {$waitMinutes} phút.";
    exit;
}
```

#### **recordFailedAttempt($identifier, $maxAttempts = 5, $lockDuration = 900)**

```php
// Ghi nhận lần đăng nhập thất bại
recordFailedAttempt($username);
```

#### **resetRateLimit($identifier)**

```php
// Reset rate limit sau khi đăng nhập thành công
resetRateLimit($username);
```

### Rate Limiting Flow

```
1. User nhập login → checkRateLimit()
   ↓
2. Nếu allowed → Tiếp tục xử lý login
   ↓
3. Nếu login thất bại → recordFailedAttempt()
   ↓
4. Nếu login thành công → resetRateLimit()
```

---

## Password Security

### Password Hashing

Dự án sử dụng **password_hash()** với **BCRYPT** algorithm (thay thế MD5 không an toàn).

#### **Tạo Admin Với Password Hash**

```php
$adminModel = new AdminModel();
$adminModel->createAdmin('admin', 'password123', 'admin@example.com');
// Password sẽ tự động được hash bằng bcrypt
```

#### **Kiểm Tra Password**

```php
$admin = $adminModel->checkLogin($username, $password);
// password_verify() được sử dụng để kiểm tra
```

#### **Đổi Password**

```php
$adminModel->changePassword('admin', 'newPassword456');
// Password mới sẽ được hash trước khi lưu
```

---

## Examples

### Example 1: Validate Form Login

```php
public function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect(BASE_URL . '?act=login');
    }

    // Rate limiting
    $identifier = $_POST['username'] ?? $_SERVER['REMOTE_ADDR'];
    $rateLimit = checkRateLimit($identifier);
    
    if (!$rateLimit['allowed']) {
        $error = "Quá nhiều lần thử. Vui lòng đợi.";
        return $this->loadView('admin/login', compact('error'));
    }

    // Validation
    $validator = new Validator($_POST);
    $validator->required('username', 'Username là bắt buộc')
              ->minLength('username', 3)
              ->required('password', 'Password là bắt buộc');

    if ($validator->fails()) {
        recordFailedAttempt($identifier);
        return $this->loadView('admin/login', ['error' => $validator->firstError()]);
    }

    $validated = $validator->validated();
    
    // Check credentials
    $admin = $this->adminModel->checkLogin(
        $validated['username'], 
        $validated['password']
    );

    if ($admin) {
        resetRateLimit($identifier);
        $_SESSION['alogin'] = $admin['UserName'];
        redirect(BASE_URL . '?act=admin');
    } else {
        recordFailedAttempt($identifier);
        $error = "Username hoặc password không đúng";
        $this->loadView('admin/login', compact('error'));
    }
}
```

### Example 2: Validate & Upload Blog

```php
public function store() {
    requireLogin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect(BASE_URL . '?act=blog-list');
    }

    // Validate input
    $validator = new Validator($_POST);
    $validator->required('chude', 'Chủ đề là bắt buộc')
              ->minLength('chude', 5)
              ->maxLength('chude', 255)
              ->required('noidung', 'Nội dung là bắt buộc')
              ->minLength('noidung', 50);

    if ($validator->fails()) {
        $_SESSION['error'] = $validator->firstError();
        redirect(BASE_URL . '?act=blog-create');
    }

    $validated = $validator->validated();

    // Validate file upload
    if (!empty($_FILES['hinhanh']['name'])) {
        $fileValidation = Validator::validateFile($_FILES['hinhanh'], [
            'maxSize' => 5242880,
            'allowedTypes' => ['image/jpeg', 'image/png'],
            'allowedExtensions' => ['jpg', 'jpeg', 'png']
        ]);

        if (!$fileValidation['valid']) {
            $_SESSION['error'] = $fileValidation['error'];
            redirect(BASE_URL . '?act=blog-create');
        }

        $imagePath = uploadFile($_FILES['hinhanh'], 'uploads/blog/');
        if (!$imagePath) {
            $_SESSION['error'] = 'Lỗi upload ảnh';
            redirect(BASE_URL . '?act=blog-create');
        }

        $validated['hinhanh'] = $imagePath;
    }

    // Save to database
    $this->model->insert($validated);
    $_SESSION['success'] = 'Tạo blog thành công';
    redirect(BASE_URL . '?act=blog-list');
}
```

### Example 3: Validate Tour Creation

```php
public function storeTour() {
    $this->checkLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate tour data
        $validator = new Validator($_POST);
        $validator->required('tengoi', 'Tên tour là bắt buộc')
                  ->minLength('tengoi', 5)
                  ->maxLength('tengoi', 255)
                  ->required('giagoi', 'Giá là bắt buộc')
                  ->numeric('giagoi', 'Giá phải là số')
                  ->min('giagoi', 0, 'Giá phải lớn hơn 0')
                  ->required('songay', 'Số ngày là bắt buộc')
                  ->integer('songay', 'Số ngày phải là số nguyên')
                  ->min('songay', 1);

        if ($validator->fails()) {
            $error = $validator->firstError();
            $provinces = $this->provinceModel->getAll();
            return $this->loadView('admin/tours/create', 
                compact('provinces', 'error'), 'admin/layout');
        }

        // Validate image upload
        if (empty($_FILES['packageimage']['name'])) {
            $error = "Ảnh tour là bắt buộc";
            $provinces = $this->provinceModel->getAll();
            return $this->loadView('admin/tours/create', 
                compact('provinces', 'error'), 'admin/layout');
        }

        $fileValidation = Validator::validateFile($_FILES['packageimage']);
        if (!$fileValidation['valid']) {
            $error = $fileValidation['error'];
            $provinces = $this->provinceModel->getAll();
            return $this->loadView('admin/tours/create', 
                compact('provinces', 'error'), 'admin/layout');
        }

        $imagePath = uploadFile($_FILES['packageimage'], 'uploads/tours/');
        if (!$imagePath) {
            $error = "Lỗi upload ảnh";
            $provinces = $this->provinceModel->getAll();
            return $this->loadView('admin/tours/create', 
                compact('provinces', 'error'), 'admin/layout');
        }

        $validated = $validator->validated();
        $validated['hinhanh'] = $imagePath;
        $validated['khuyenmai'] = isset($_POST['khuyenmai']) ? 1 : 0;

        $this->tourModel->createTour($validated);
        $_SESSION['success'] = 'Tạo tour thành công';
        $this->redirect(BASE_URL . '?act=admin-tours');
    }
}
```

---

## Best Practices

### ✅ DO's

1. **Luôn validate input từ user**
   ```php
   $validator = new Validator($_POST);
   $validator->required('field')->minLength('field', 3);
   ```

2. **Sanitize output khi hiển thị**
   ```php
   echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
   // hoặc sử dụng sanitizeInput()
   ```

3. **Validate file uploads**
   ```php
   $validation = Validator::validateFile($_FILES['file']);
   if ($validation['valid']) {
       // Proceed
   }
   ```

4. **Sử dụng prepared statements** (PDO đã sử dụng)
   ```php
   $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
   $stmt->execute([':id' => $id]);
   ```

5. **Hash passwords với bcrypt**
   ```php
   $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
   ```

6. **Implement rate limiting cho sensitive actions**
   ```php
   checkRateLimit($identifier);
   ```

7. **Sử dụng CSRF tokens** (đã có functions)
   ```php
   generateCSRFToken(); // trong form
   verifyCSRFToken($_POST['csrf_token']); // khi xử lý
   ```

8. **Require login cho admin pages**
   ```php
   requireLogin();
   // hoặc
   $this->checkLogin();
   ```

### ❌ DON'Ts

1. **ĐỪNG tin tưởng user input**
   ```php
   // ❌ BAD
   $id = $_GET['id'];
   
   // ✅ GOOD
   $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
   ```

2. **ĐỪNG sử dụng MD5 cho passwords**
   ```php
   // ❌ BAD
   $password = md5($_POST['password']);
   
   // ✅ GOOD
   $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
   ```

3. **ĐỪNG bỏ qua file validation**
   ```php
   // ❌ BAD
   move_uploaded_file($_FILES['file']['tmp_name'], $destination);
   
   // ✅ GOOD
   $validation = Validator::validateFile($_FILES['file']);
   if ($validation['valid']) {
       uploadFile($_FILES['file'], 'uploads/');
   }
   ```

4. **ĐỪNG hiển thị error messages chi tiết cho user**
   ```php
   // ❌ BAD
   die("SQL Error: " . $e->getMessage());
   
   // ✅ GOOD
   error_log("SQL Error: " . $e->getMessage());
   die("Đã có lỗi xảy ra. Vui lòng thử lại sau.");
   ```

5. **ĐỪNG hardcode sensitive data**
   ```php
   // ❌ BAD
   $password = "admin123";
   
   // ✅ GOOD
   // Sử dụng env.php và .env file
   ```

---

## Security Checklist

- [x] ✅ Input validation (Validator class)
- [x] ✅ Output sanitization (sanitizeInput function)
- [x] ✅ File upload validation (validateFile method)
- [x] ✅ SQL injection prevention (PDO prepared statements)
- [x] ✅ XSS prevention (htmlspecialchars)
- [x] ✅ Password hashing (bcrypt với password_hash)
- [x] ✅ Rate limiting (checkRateLimit function)
- [x] ✅ Session security (httponly, secure, samesite cookies)
- [x] ✅ CSRF protection (generateCSRFToken, verifyCSRFToken)
- [x] ✅ Path traversal prevention (realpath checks trong deleteFile)
- [x] ✅ Authentication checks (requireLogin, checkLogin)
- [x] ✅ Error logging (error_log thay vì echo)

---

## Kết Luận

Hệ thống validation này cung cấp nền tảng vững chắc cho bảo mật ứng dụng. Hãy luôn:

1. Validate mọi input từ user
2. Sanitize output trước khi hiển thị
3. Sử dụng các functions có sẵn
4. Follow best practices
5. Keep security in mind

**Lưu ý:** Bảo mật là một quá trình liên tục. Hãy thường xuyên review và cập nhật code theo các tiêu chuẩn bảo mật mới nhất.

---

## Liên Hệ & Hỗ Trợ

Nếu có câu hỏi hoặc phát hiện lỗ hổng bảo mật, vui lòng liên hệ team phát triển.

**Version:** 1.0  
**Last Updated:** 2025-11-24
