<?php
/**
 * TourController - Quản lý các tour du lịch
 * @author Tienhien109
 */
class TourController extends BaseController
{
    private $tourModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->tourModel->createTour($_POST, $_FILES['hinhanh']);

            if ($result) {
                redirect('?act=tour-list');
            } else {
                echo "<script>alert('Thêm tour thất bại!');</script>";
            }
        } else {
           $this->loadView('tour/create', [], 'layout');
        }
    }
}
