<?php
/**
 * BlogController - ĐÃ CẬP NHẬT
 * - Thêm validation đầy đủ
 * - Sanitize input
 * - File upload an toàn
 * - Error handling
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

    public function list()
    {
        requireLogin(); // Require authentication
        $blogs = $this->model->getAll();

        // Tạo content
        $content = render('admin/blog/list', ['blogs' => $blogs]);

        // Nhúng vào layout
        require $this->VIEW . "admin/layout.php";
    }

    public function edit()
    {
        requireLogin();
        
        // Validate ID
        $id = filter_var($_GET["id"] ?? 0, FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            $_SESSION['error'] = "ID không hợp lệ";
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

        $blog = $this->model->getById($id);
        
        if (!$blog) {
            $_SESSION['error'] = "Không tìm thấy blog";
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

        $content = render('admin/blog/edit', ['blog' => $blog]);
        require $this->VIEW . "admin/layout.php";
    }

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
        if (!empty($_FILES['hinhanh']['name'])) {
            $fileValidation = Validator::validateFile($_FILES['hinhanh'], [
                'maxSize' => 5242880, // 5MB
                'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                'required' => false
            ]);

            if ($fileValidation['valid']) {
                $uploadedPath = uploadFile($_FILES['hinhanh'], 'uploads/blog/');
                if ($uploadedPath) {
                    // Delete old image if exists
                    if (!empty($data[':hinhanh']) && file_exists(PATH_ROOT . $data[':hinhanh'])) {
                        deleteFile($data[':hinhanh']);
                    }
                    $data[':hinhanh'] = $uploadedPath;
                } else {
                    $_SESSION['error'] = 'Lỗi khi upload file';
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
        $content = render('admin/blog/create');
        require $this->VIEW . "admin/layout.php";
    }

    public function store()
    {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?act=blog-list");
            exit;
        }

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
            header("Location: " . BASE_URL . "?act=blog-create");
            exit;
        }

        $validated = $validator->validated();

        $data = [
            ':chude'     => sanitizeInput($validated['chude']),
            ':tomtat'    => sanitizeInput($validated['tomtat']),
            ':noidung'   => $validated['noidung'], // Keep HTML for CKEditor
            ':nguoiviet' => sanitizeInput($validated['nguoiviet']),
            ':hinhanh'   => null
        ];

        // Handle file upload
        if (!empty($_FILES['hinhanh']['name'])) {
            $fileValidation = Validator::validateFile($_FILES['hinhanh'], [
                'maxSize' => 5242880, // 5MB
                'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                'required' => false
            ]);

            if ($fileValidation['valid']) {
                $uploadedPath = uploadFile($_FILES['hinhanh'], 'uploads/blog/');
                if ($uploadedPath) {
                    $data[':hinhanh'] = $uploadedPath;
                } else {
                    $_SESSION['error'] = 'Lỗi khi upload file';
                    header("Location: " . BASE_URL . "?act=blog-create");
                    exit;
                }
            } else {
                $_SESSION['error'] = $fileValidation['error'];
                header("Location: " . BASE_URL . "?act=blog-create");
                exit;
            }
        }

        $this->model->insert($data);
        $_SESSION['success'] = 'Tạo blog thành công';
        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }

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
            deleteFile($blog['hinhanh']);
        }

        $this->model->delete($id);
        $_SESSION['success'] = 'Xóa blog thành công';
        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }
}
