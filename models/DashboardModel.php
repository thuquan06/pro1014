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
            // Sử dụng DATE() để so sánh chỉ phần ngày, bỏ qua phần giờ
            $sql = "SELECT COUNT(*) FROM `$table` WHERE DATE(`$dateField`) >= DATE(:start) AND DATE(`$dateField`) <= DATE(:end)";
            $stm = $this->conn->prepare($sql);
            $stm->execute([':start' => $startDate, ':end' => $endDate]);
            return (int)$stm->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting $table by date: " . $e->getMessage());
            // Thử cách khác nếu DATE() không hoạt động
            try {
                $sql = "SELECT COUNT(*) FROM `$table` WHERE `$dateField` >= :start AND `$dateField` <= :end";
                $stm = $this->conn->prepare($sql);
                $stm->execute([':start' => $startDate, ':end' => $endDate]);
                return (int)$stm->fetchColumn();
            } catch (PDOException $e2) {
                error_log("Error counting $table by date (fallback): " . $e2->getMessage());
                return 0;
            }
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
            $endDate = null;

            switch ($period) {
                case 'day':
                    $startDate = $now->format('Y-m-d 00:00:00');
                    $endDate = $now->format('Y-m-d 23:59:59'); // Kết thúc vào cuối ngày
                    break;
                case 'week':
                    $startDate = (clone $now)->modify('-7 days')->format('Y-m-d 00:00:00');
                    $endDate = $now->format('Y-m-d 23:59:59');
                    break;
                case 'month':
                    $startDate = (clone $now)->modify('-30 days')->format('Y-m-d 00:00:00');
                    $endDate = $now->format('Y-m-d 23:59:59');
                    break;
                default:
                    $startDate = $now->format('Y-m-d 00:00:00');
                    $endDate = $now->format('Y-m-d 23:59:59');
            }
            
            // Debug log
            error_log("DashboardModel getStatisticsByPeriod - Period: $period, StartDate: $startDate, EndDate: $endDate");

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

            // Thống kê booking - thử nhiều tên trường có thể
            if ($tblBooking) {
                $bookingDateFields = ['ngay_dat', 'ngay_tao', 'created_at', 'thoi_gian_dat'];
                $bookingDateField = null;
                foreach ($bookingDateFields as $field) {
                    try {
                        $checkSql = "SHOW COLUMNS FROM `$tblBooking` LIKE '$field'";
                        $checkStm = $this->conn->prepare($checkSql);
                        $checkStm->execute();
                        if ($checkStm->fetch()) {
                            $bookingDateField = $field;
                            break;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }
                
                if ($bookingDateField) {
                    $stats['booking'] = $this->countTableByDate($tblBooking, $bookingDateField, $startDate, $endDate);
                    
                    // Tính doanh thu từ booking
                    try {
                        $revenueField = $this->conn->query("SHOW COLUMNS FROM `$tblBooking` LIKE 'tong_tien'")->fetch() ? 'tong_tien' : 
                                       ($this->conn->query("SHOW COLUMNS FROM `$tblBooking` LIKE 'tong_tien_thanh_toan'")->fetch() ? 'tong_tien_thanh_toan' : null);
                        if ($revenueField) {
                            $sql = "SELECT SUM($revenueField) FROM `$tblBooking` 
                                    WHERE DATE($bookingDateField) >= DATE(:start) AND DATE($bookingDateField) <= DATE(:end) 
                                    AND (trang_thai IN (3, 4) OR trang_thai IS NULL)"; // Đã thanh toán hoặc hoàn thành hoặc null
                            $stm = $this->conn->prepare($sql);
                            $stm->execute([':start' => $startDate, ':end' => $endDate]);
                            $revenue = $stm->fetchColumn();
                            $stats['revenue'] = (float)($revenue ?? 0);
                        }
                    } catch (PDOException $e) {
                        error_log("Error calculating revenue: " . $e->getMessage());
                    }
                }
            }

            // Thống kê hóa đơn - hóa đơn thường lấy từ booking với điều kiện đã thanh toán
            if ($tblHoaDon) {
                // Thử tìm trường ngày trong bảng hoadon
                $hoadonDateFields = ['ngaydat', 'ngay_tao', 'created_at', 'ngay_dat', 'thoi_gian_tao'];
                $foundField = null;
                foreach ($hoadonDateFields as $field) {
                    try {
                        $checkSql = "SHOW COLUMNS FROM `$tblHoaDon` LIKE '$field'";
                        $checkStm = $this->conn->prepare($checkSql);
                        $checkStm->execute();
                        if ($checkStm->fetch()) {
                            $foundField = $field;
                            break;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }
                
                if ($foundField) {
                    $stats['hoadon'] = $this->countTableByDate($tblHoaDon, $foundField, $startDate, $endDate);
                } elseif ($tblBooking) {
                    // Nếu không có bảng hoadon riêng, đếm từ booking đã thanh toán
                    try {
                        $sql = "SELECT COUNT(*) FROM `$tblBooking` 
                                WHERE (DATE(ngay_dat) >= DATE(:start) AND DATE(ngay_dat) <= DATE(:end) 
                                OR DATE(ngay_thanh_toan) >= DATE(:start) AND DATE(ngay_thanh_toan) <= DATE(:end))
                                AND trang_thai IN (3, 4)"; // Đã thanh toán hoặc hoàn thành
                        $stm = $this->conn->prepare($sql);
                        $stm->execute([':start' => $startDate, ':end' => $endDate]);
                        $stats['hoadon'] = (int)$stm->fetchColumn();
                    } catch (PDOException $e) {
                        error_log("Error counting hoadon from booking: " . $e->getMessage());
                    }
                }
            }

            // Thống kê tour - thử nhiều tên trường có thể
            if ($tblTour) {
                $tourDateFields = ['ngay_tao', 'created_at', 'thoi_gian_tao', 'ngay_dat'];
                foreach ($tourDateFields as $field) {
                    try {
                        $checkSql = "SHOW COLUMNS FROM `$tblTour` LIKE '$field'";
                        $checkStm = $this->conn->prepare($checkSql);
                        $checkStm->execute();
                        if ($checkStm->fetch()) {
                            $stats['tour'] = $this->countTableByDate($tblTour, $field, $startDate, $endDate);
                            break;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }
            }

            // Thống kê blog - thử nhiều tên trường có thể
            if ($tblBlog) {
                $blogDateFields = ['ngay_tao', 'created_at', 'thoi_gian_tao', 'ngay_dat'];
                foreach ($blogDateFields as $field) {
                    try {
                        $checkSql = "SHOW COLUMNS FROM `$tblBlog` LIKE '$field'";
                        $checkStm = $this->conn->prepare($checkSql);
                        $checkStm->execute();
                        if ($checkStm->fetch()) {
                            $stats['blog'] = $this->countTableByDate($tblBlog, $field, $startDate, $endDate);
                            break;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }
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
