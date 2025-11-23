<?php

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
        $blogs = $this->model->getAll();

        // Tạo content
        $content = render('admin/blog/list', ['blogs' => $blogs]);

        // Nhúng vào layout
        require $this->VIEW . "admin/layout.php";
    }

    public function edit()
    {
        $id = $_GET["id"] ?? 0;
        $blog = $this->model->getById($id);

        $content = render('admin/blog/edit', ['blog' => $blog]);

        require $this->VIEW . "admin/layout.php";
    }

    public function update()
    {
        $id = $_POST["id_blog"];

        $data = [
            ':id'       => $id,
            ':chude'    => $_POST['chude'],
            ':tomtat'   => $_POST['tomtat'],
            ':noidung'  => $_POST['noidung'],
            ':nguoiviet'=> $_POST['nguoiviet'],
            ':hinhanh'  => $_POST['old_hinhanh']
        ];

        if (!empty($_FILES['hinhanh']['name'])) {
            $file = $_FILES['hinhanh'];
            $target = "uploads/blog/" . time() . "_" . $file['name'];
            move_uploaded_file($file['tmp_name'], $target);
            $data[':hinhanh'] = $target;
        }

        $this->model->update($data);
        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }

    public function create()
{
    $content = render('admin/blog/create');
    require $this->VIEW . "admin/layout.php";
}

public function store()
{
    // lấy dữ liệu
    $data = [
        ':chude'     => $_POST['chude'],
        ':tomtat'    => $_POST['tomtat'],
        ':noidung'   => $_POST['noidung'],
        ':nguoiviet' => $_POST['nguoiviet'],
        ':hinhanh'   => null
    ];

    // upload ảnh
    if (!empty($_FILES['hinhanh']['name'])) {
        $file = $_FILES["hinhanh"];
        $target = "uploads/blog/" . time() . "_" . $file["name"];
        move_uploaded_file($file["tmp_name"], $target);
        $data[':hinhanh'] = $target;
    }

    $this->model->insert($data);

    header("Location: " . BASE_URL . "?act=blog-list");
    exit;
}


    public function delete()
    {
        $id = $_GET["id"] ?? 0;
        $this->model->delete($id);

        header("Location: " . BASE_URL . "?act=blog-list");
        exit;
    }
}
