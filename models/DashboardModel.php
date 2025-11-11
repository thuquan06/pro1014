<?php
// models/DashboardModel.php (bản chắc chắn)
class DashboardModel {
    private $conn;
    public function __construct() { $this->conn = connectDB(); }

    private function tableExists(string $name): bool {
        $stm = $this->conn->prepare("SHOW TABLES LIKE :t");
        $stm->execute([':t' => $name]);
        return (bool)$stm->fetchColumn();
    }

    private function firstExisting(array $candidates): ?string {
        foreach ($candidates as $t) {
            if ($this->tableExists($t)) return $t;
        }
        return null;
    }

    private function countTable(string $table): int {
        $sql = "SELECT COUNT(*) FROM `$table`";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return (int)$stm->fetchColumn();
    }

    public function getStatistics(): array {
        try {
            // DB hiện tại
            $db = $this->conn->query("SELECT DATABASE()")->fetchColumn();

            // Map các bảng có thể khác tên
            $tblNguoiDung = $this->firstExisting(['nguoidung','users','user']);
            $tblHoaDon    = $this->firstExisting(['hoadon','invoices','bill']);
            $tblGopY      = $this->firstExisting(['gopy','feedback']);
            $tblTour      = $this->firstExisting(['goidulich','goi_tour','tour','tours']);
            $tblTroGiup   = $this->firstExisting(['trogiup','support','help']);
            $tblChuyenBay = $this->firstExisting(['chuyenbay','flights']);
            $tblKhachSan  = $this->firstExisting(['khachsan','hotels']);
            $tblDuThuyen  = $this->firstExisting(['duthuyen','cruise']);
            $tblXe        = $this->firstExisting(['xe','cars','vehicles']);
            $tblBlog      = $this->firstExisting(['blog','blogs','posts']);

            $stats = [
                'db'   => $db,
                'cnt'  => $tblNguoiDung ? $this->countTable($tblNguoiDung) : 0,
                'cnt1' => $tblHoaDon    ? $this->countTable($tblHoaDon)    : 0,
                'cnt2' => $tblGopY      ? $this->countTable($tblGopY)      : 0,
                'cnt3' => $tblTour      ? $this->countTable($tblTour)      : 0,
                'goi'  => $tblTour      ? $this->countTable($tblTour)      : 0,
                'cnt5' => $tblTroGiup   ? $this->countTable($tblTroGiup)   : 0,
                'cb'   => $tblChuyenBay ? $this->countTable($tblChuyenBay) : 0,
                'ks'   => $tblKhachSan  ? $this->countTable($tblKhachSan)  : 0,
                'dt'   => $tblDuThuyen  ? $this->countTable($tblDuThuyen)  : 0,
                'xe'   => $tblXe        ? $this->countTable($tblXe)        : 0,
                'blog' => $tblBlog      ? $this->countTable($tblBlog)      : 0,

                // debug nhẹ để soi nhanh nếu cần
                '_tables' => [
                    'nguoidung' => $tblNguoiDung, 'hoadon' => $tblHoaDon, 'gopy' => $tblGopY,
                    'tour' => $tblTour, 'trogiup' => $tblTroGiup, 'chuyenbay' => $tblChuyenBay,
                    'khachsan' => $tblKhachSan, 'duthuyen' => $tblDuThuyen, 'xe' => $tblXe, 'blog' => $tblBlog
                ],
            ];
            return $stats;
        } catch (PDOException $e) {
            error_log("DashboardModel error: ".$e->getMessage());
            return [];
        }
    }
}
