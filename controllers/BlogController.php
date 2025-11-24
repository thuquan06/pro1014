<?php
/**
 * BlogController - PHIÊN BẢN HOÀN CHỈNH
 * 
 * Tính năng:
 * - ✅ Validation đầy đủ (Validator class)
 * - ✅ Sanitize input (XSS prevention)
 * - ✅ File upload an toàn (MIME type, size, extension check)
 * - ✅ Authentication check (requireLogin)
 * - ✅ Error handling với session messages
 * - ✅ Tự động xóa ảnh cũ khi update/delete
 * 
 * @version 1.0
 * @date 2025-11-24
 */
class BlogController 
{
    private $model;
    private $VIEW;

    public function __construct()
    {
        $this->model = new BlogModel();

        // Tự động xác định đường dẫn view
        $ROOT = dirname(__DIR__);  
        $this->VIEW = $ROOT . "/views/";
    }

    /**
     * Hiển thị danh sách tất cả blog
     * Route: ?act=blog-list
     */

    public function list()
    {
        // Yêu cầu đăng nhập
        requireLogin();
        
        try {
            $blogs = $this->model->getAll();
            
            // Tạo content
            $content = render('admin/blog/list', ['blogs' => $blogs]);
            
            // Nhúng vào layout
            require $this->VIEW . "admin/layout.php";
            
        } catch (Exception $e) {
            error_log("Blog list error: " . $e->getMessage());
            $_SESSION['error'] = "Lỗi khi tải danh sách blog";
            header("Location: " . BASE_URL . "?act=admin");
            exit;
        }
    }

    /**
     * Hiển thị form tạo blog mới
     * Route: ?act=blog-create
     */

    public function edit()
    {
        requireLogin();
        
        try {
            // Validate ID
            $id = filter_var($_GET["id"] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                $_SESSION['error'] = "ID không hợp lệ";
                header("Location: " . BASE_URL . "?act=blog-list");
                exit;
            }

            $blog = $this->model->getById($id);
            
            if (!$blog) {
                $_SESSION['error'] = "Không tìm thấy blog với ID: " . $id;
                header("Location: " . BASE_URL . "?act=blog-list");
                exit;
            }

            $content = render('admin/blog/edit', ['blog' => $blog]);
            require $this->VIEW . "admin/layout.php";
            
        } catch (Exception $e) {
            error_log("Blog edit form error: " . $e->getMessage());
            $_SESSION['error'] = "Lỗi khi tải form sửa blog";
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }
    }

    /**
     * Xử lý cập nhật blog
     * Route: ?act=blog-update
     */

    public function update()
    {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

        // Validate input
        $validator = new Validator($_POST);
        $validator->required('id_blog', 'ID blog là bắt buộc')
                  ->integer('id_blog', 'ID blog phải là số nguyên')
                  ->required('chude', 'Chủ đề là bắt buộc')
                  ->minLength('chude', 5, 'Chủ đề phải có ít nhất 5 ký tự')
                  ->maxLength('chude', 255, 'Chủ đề không được quá 255 ký tự')
                  ->required('tomtat', 'Tóm tắt là bắt buộc')
                  ->minLength('tomtat', 10, 'Tóm tắt phải có ít nhất 10 ký tự')
                  ->required('noidung', 'Nội dung là bắt buộc')
                  ->minLength('noidung', 50, 'Nội dung phải có ít nhất 50 ký tự')
                  ->required('nguoiviet', 'Người viết là bắt buộc');

        if ($validator->fails()) {
            $_SESSION['error'] = $validator->firstError();
            header("Location: " . BASE_URL . "?act=blog-edit&id=" . ($_POST['id_blog'] ?? 0));
            exit;
        }

        $validated = $validator->validated();
        $id = $validated['id_blog'];

        $data = [
            ':id'       => $id,
            ':chude'    => sanitizeInput($validated['chude']),
            ':tomtat'   => sanitizeInput($validated['tomtat']),
            ':noidung'  => $validated['noidung'], // Keep HTML for CKEditor
            ':nguoiviet'=> sanitizeInput($validated['nguoiviet']),
            ':hinhanh'  => $_POST['old_hinhanh'] ?? ''
        ];

        // Handle file upload
        if (!empty($_FILES['hinhanh']['name']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
            // Validate file
            $fileValidation = Validator::validateFile($_FILES['hinhanh'], [
                'maxSize' => 10485760, // 10MB
                'allowedTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
                'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                'required' => false
            ]);

            if ($fileValidation['valid']) {
                // Upload new image
                $uploadedPath = uploadFile($_FILES['hinhanh'], 'uploads/blog/', [
                    'maxSize' => 10485760,
                    'allowedTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
                    'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
                ]);
                
                if ($uploadedPath) {
                    // Delete old image if exists and is different
                    $oldImage = $data[':hinhanh'];
                    if (!empty($oldImage) && $oldImage !== $uploadedPath) {
                        @deleteFile($oldImage); // @ to suppress warning if file not exists
                    }
                    $data[':hinhanh'] = $uploadedPath;
                } else {
                    $_SESSION['error'] = 'Lỗi khi upload file. Vui lòng thử lại.';
                    header("Location: " . BASE_URL . "?act=blog-edit&id=" . $id);
                    exit;
                }
            } else {
                $_SESSION['error'] = $fileValidation['error'];
                header("Location: " . BASE_URL . "?act=blog-edit&id=" . $id);
                exit;
            }
        }

        $this->model->update($data);
        $_SESSION['success'] = 'Cập nhật blog thành công';
        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }

    public function create()
    {
        requireLogin();
        
        try {
            $content = render('admin/blog/create');
            require $this->VIEW . "admin/layout.php";
        } catch (Exception $e) {
            error_log("Blog create form error: " . $e->getMessage());
            $_SESSION['error'] = "Lỗi khi tải form tạo blog";
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }
    }

    /**
     * Xử lý tạo blog mới
     * Route: ?act=blog-store
     */

    public function store()
    {
        requireLogin();
        
        // Chỉ chấp nhận POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

        try {
            // Validate input
            $validator = new Validator($_POST);
            $validator->required('chude', 'Chủ đề là bắt buộc')
                      ->minLength('chude', 5, 'Chủ đề phải có ít nhất 5 ký tự')
                      ->maxLength('chude', 255, 'Chủ đề không được quá 255 ký tự')
                      ->required('tomtat', 'Tóm tắt là bắt buộc')
                      ->minLength('tomtat', 10, 'Tóm tắt phải có ít nhất 10 ký tự')
                      ->required('noidung', 'Nội dung là bắt buộc')
                      ->minLength('noidung', 50, 'Nội dung phải có ít nhất 50 ký tự')
                      ->required('nguoiviet', 'Người viết là bắt buộc');

            if ($validator->fails()) {
                $_SESSION['error'] = $validator->firstError();
                $_SESSION['old_input'] = $_POST; // Giữ lại input cũ
                header("Location: " . BASE_URL . "?act=blog-create");
                exit;
            }

            $validated = $validator->validated();

            // Prepare data for database
            $data = [
                ':chude'     => htmlspecialchars($validated['chude'], ENT_QUOTES, 'UTF-8'),
                ':tomtat'    => htmlspecialchars($validated['tomtat'], ENT_QUOTES, 'UTF-8'),
                ':noidung'   => $validated['noidung'], // Giữ nguyên HTML cho CKEditor
                ':nguoiviet' => htmlspecialchars($validated['nguoiviet'], ENT_QUOTES, 'UTF-8'),
                ':hinhanh'   => null
            ];

            // Xử lý upload file (nếu có)
            if (!empty($_FILES['hinhanh']['name']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
                $fileValidation = Validator::validateFile($_FILES['hinhanh'], [
                    'maxSize' => 10485760, // 10MB
                    'allowedTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
                    'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    'required' => false
                ]);

                if ($fileValidation['valid']) {
                    $uploadedPath = uploadFile($_FILES['hinhanh'], 'uploads/blog/', [
                        'maxSize' => 10485760,
                        'allowedTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
                        'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
                    ]);
                    
                    if ($uploadedPath) {
                        $data[':hinhanh'] = $uploadedPath;
                    } else {
                        throw new Exception('Upload file thất bại. Vui lòng thử lại.');
                    }
                } else {
                    $_SESSION['error'] = $fileValidation['error'];
                    $_SESSION['old_input'] = $_POST;
                    header("Location: " . BASE_URL . "?act=blog-create");
                    exit;
                }
            }

            // Lưu vào database
            $result = $this->model->insert($data);
            
            if ($result) {
                unset($_SESSION['old_input']); // Xóa old input
                $_SESSION['success'] = 'Tạo blog thành công!';
                error_log("Blog created successfully by: " . ($_SESSION['alogin'] ?? 'unknown'));
            } else {
                throw new Exception('Lưu blog thất bại');
            }
            
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
            
        } catch (Exception $e) {
            error_log("Blog store error: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header("Location: " . BASE_URL . "?act=blog-create");
            exit;
        }
    }

    /**
     * Hiển thị form sửa blog
     * Route: ?act=blog-edit&id=X
     */

    public function delete()
    {
        requireLogin();
        
        // Validate ID
        $id = filter_var($_GET["id"] ?? 0, FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            $_SESSION['error'] = "ID không hợp lệ";
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

        // Get blog to delete image
        $blog = $this->model->getById($id);
        if ($blog && !empty($blog['hinhanh'])) {
            // Delete image file
            @deleteFile($blog['hinhanh']); // @ to suppress warning if file not exists
        }

        // Delete blog from database
        if ($this->model->delete($id)) {
            $_SESSION['success'] = 'Xóa blog thành công';
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa blog';
        }
        
        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }
}
