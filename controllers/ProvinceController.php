<?php
class ProvinceController extends BaseController {

    private $provinceModel;

    public function __construct() {
        $this->provinceModel = new ProvinceModel();

        // CHECK LOGIN
        if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
            header("Location: ?act=login");
            exit();
        }
    }

    // ============================
    // LIST + SEARCH + PAGINATION
    // ============================
    public function index() {
    $keyword = $_GET['keyword'] ?? "";
    $page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit   = 10;

    $result = $this->provinceModel->getWithPagination($keyword, $page, $limit);

    return $this->loadView("admin/province/list", [
        'title'       => "Danh sách tỉnh/thành phố",
        'keyword'     => $keyword,
        'provinces'   => $result['data'],   // 🔥 PHẢI ĐỔI TÊN NÀY
        'total'       => $result['total'],
        'page'        => $page,
        'limit'       => $limit,
        'totalPages'  => ceil($result['total'] / $limit),
        'startIndex'  => ($page - 1) * $limit
    ]);
}

    // CREATE FORM
    public function create() {
        return $this->loadView("admin/province/create");
    }

    // STORE
    public function store() {
        $name = trim($_POST['ten_tinh']);

        if ($name == "") {
            $_SESSION['error'] = "Tên tỉnh không được để trống.";
            return $this->redirect("?act=province-create");
        }

        if ($this->provinceModel->exists($name)) {
            $_SESSION['error'] = "Tỉnh này đã tồn tại.";
            return $this->redirect("?act=province-create");
        }

        $this->provinceModel->create(['ten_tinh' => $name]);
        $_SESSION['success'] = "Thêm tỉnh thành công!";

        return $this->redirect("?act=province-list");
    }

    // EDIT
    public function edit() {
    $id = intval($_GET['id']);
    $record = $this->provinceModel->getById($id);

    // lấy số tour đang dùng
    $usageCount = $this->provinceModel->checkUsage($id);

    return $this->loadView("admin/province/edit", [
        'record'      => $record,
        'usageCount'  => $usageCount
    ]);
}


    // UPDATE
    public function update() {
        $id   = intval($_POST['id']);
        $name = trim($_POST['ten_tinh']);

        if ($name == "") {
            $_SESSION['error'] = "Tên tỉnh không được để trống.";
            return $this->redirect("?act=province-edit&id=".$id);
        }

        $this->provinceModel->update($id, ['ten_tinh' => $name]);
        $_SESSION['success'] = "Cập nhật thành công!";

        return $this->redirect("?act=province-list");
    }

    // DELETE
    public function delete() {
        $id = intval($_GET['id']);
        $this->provinceModel->delete($id);

        $_SESSION['success'] = "Xóa thành công!";
        return $this->redirect("?act=province-list");
    }

    
}
