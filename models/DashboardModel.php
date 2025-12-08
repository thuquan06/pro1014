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

    private function countTableByDate(string $table, string $dateField, string $startDate, string $endDate): int {
        try {
            $sql = "SELECT COUNT(*) FROM `$table` WHERE `$dateField` >= :start AND `$dateField` < :end";
            $stm = $this->conn->prepare($sql);
            $stm->execute([':start' => $startDate, ':end' => $endDate]);
            return (int)$stm->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting $table by date: " . $e->getMessage());
            return 0;
        }
    }

    private function getTableName(string $type): ?string {
        $tables = [
            'booking' => $this->firstExisting(['booking', 'bookings']),
            'hoadon' => $this->firstExisting(['hoadon', 'invoices', 'bill']),
            'tour' => $this->firstExisting(['goidulich', 'goi_tour', 'tour', 'tours']),
            'blog' => $this->firstExisting(['blog', 'blogs', 'posts']),
        ];
        return $tables[$type] ?? null;
    }

    public function getStatisticsByPeriod(string $period = 'day'): array {
        try {
            $now = new DateTime();
            $startDate = null;
            $endDate = $now->format('Y-m-d H:i:s');

            switch ($period) {
                case 'day':
                    $startDate = $now->format('Y-m-d 00:00:00');
                    break;
                case 'week':
                    $startDate = (clone $now)->modify('-7 days')->format('Y-m-d 00:00:00');
                    break;
                case 'month':
                    $startDate = (clone $now)->modify('-30 days')->format('Y-m-d 00:00:00');
                    break;
                default:
                    $startDate = $now->format('Y-m-d 00:00:00');
            }

            $tblBooking = $this->getTableName('booking');
            $tblHoaDon = $this->getTableName('hoadon');
            $tblTour = $this->getTableName('tour');
            $tblBlog = $this->getTableName('blog');

            $stats = [
                'booking' => 0,
                'hoadon' => 0,
                'tour' => 0,
                'blog' => 0,
                'revenue' => 0,
            ];

            // Thống kê booking
            if ($tblBooking) {
                $stats['booking'] = $this->countTableByDate($tblBooking, 'ngay_dat', $startDate, $endDate);
                
                // Tính doanh thu từ booking
                try {
                    $sql = "SELECT SUM(tong_tien) FROM `$tblBooking` 
                            WHERE ngay_dat >= :start AND ngay_dat < :end 
                            AND trang_thai IN (3, 4)"; // Đã thanh toán hoặc hoàn thành
                    $stm = $this->conn->prepare($sql);
                    $stm->execute([':start' => $startDate, ':end' => $endDate]);
                    $revenue = $stm->fetchColumn();
                    $stats['revenue'] = (float)($revenue ?? 0);
                } catch (PDOException $e) {
                    error_log("Error calculating revenue: " . $e->getMessage());
                }
            }

            // Thống kê hóa đơn
            if ($tblHoaDon) {
                $stats['hoadon'] = $this->countTableByDate($tblHoaDon, 'ngaydat', $startDate, $endDate);
            }

            // Thống kê tour
            if ($tblTour) {
                $stats['tour'] = $this->countTableByDate($tblTour, 'ngay_tao', $startDate, $endDate);
            }

            // Thống kê blog
            if ($tblBlog) {
                $stats['blog'] = $this->countTableByDate($tblBlog, 'ngay_tao', $startDate, $endDate);
            }

            return $stats;
        } catch (Exception $e) {
            error_log("DashboardModel getStatisticsByPeriod error: " . $e->getMessage());
            return [
                'booking' => 0,
                'hoadon' => 0,
                'tour' => 0,
                'blog' => 0,
                'revenue' => 0,
            ];
        }
    }

    public function getStatistics(): array {
        try {
            // DB hiện tại
            $db = $this->conn->query("SELECT DATABASE()")->fetchColumn();

            // Map các bảng có thể khác tên
            $tblHoaDon    = $this->firstExisting(['hoadon','invoices','bill']);
            $tblTour      = $this->firstExisting(['goidulich','goi_tour','tour','tours']);
            $tblLichKhoiHanh = $this->firstExisting(['lich_khoi_hanh','departure_plans','schedules']);
            $tblBooking   = $this->firstExisting(['booking','bookings']);
            $tblChuyenBay = $this->firstExisting(['chuyenbay','flights']);
            $tblKhachSan  = $this->firstExisting(['khachsan','hotels']);
            $tblDuThuyen  = $this->firstExisting(['duthuyen','cruise']);
            $tblXe        = $this->firstExisting(['xe','cars','vehicles']);
            $tblBlog      = $this->firstExisting(['blog','blogs','posts']);

            $stats = [
                'db'   => $db,
                'cnt'  => 0,
                'cnt1' => $tblHoaDon    ? $this->countTable($tblHoaDon)    : 0,
                'cnt2' => $tblLichKhoiHanh ? $this->countTable($tblLichKhoiHanh) : 0, // Lịch trình
                'cnt3' => $tblTour      ? $this->countTable($tblTour)      : 0,
                'goi'  => $tblTour      ? $this->countTable($tblTour)      : 0,
                'cnt5' => $tblBooking   ? $this->countTable($tblBooking)   : 0, // Booking
                'cb'   => $tblChuyenBay ? $this->countTable($tblChuyenBay) : 0,
                'ks'   => $tblKhachSan  ? $this->countTable($tblKhachSan)  : 0,
                'dt'   => $tblDuThuyen  ? $this->countTable($tblDuThuyen)  : 0,
                'xe'   => $tblXe        ? $this->countTable($tblXe)        : 0,
                'blog' => $tblBlog      ? $this->countTable($tblBlog)      : 0,

                // debug nhẹ để soi nhanh nếu cần
                '_tables' => [
                    'hoadon' => $tblHoaDon, 'lich_khoi_hanh' => $tblLichKhoiHanh,
                    'tour' => $tblTour, 'booking' => $tblBooking, 'chuyenbay' => $tblChuyenBay,
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
