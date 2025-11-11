<?php
// File: models/TourModel.php (File mới)

class TourModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB(); // Sử dụng hàm connectDB() từ /commons/function.php
    }

    /**
     * Lấy tất cả các tour
     * (Logic từ manage-packages.php)
     */
    public function getAllTours()
    {
        try {
            $sql = "SELECT * from goidulich";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (getAllTours): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy một tour bằng ID
     * (Logic từ update-package.php)
     */
    public function getTourByID($id)
    {
        try {
            $sql = "SELECT * from goidulich where id_goi = :id";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (getTourByID): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả các tỉnh
     * (Logic từ create-package.php)
     */
    public function getAllProvinces()
    {
        try {
            // Lưu ý: File create-package.php của bạn kết nối mysqli
            // Tôi đã chuyển nó sang PDO để nhất quán với base của bạn
            $sql = "SELECT * FROM tinh ORDER BY ten_tinh";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (getAllProvinces): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tạo tour mới
     * (Logic từ create-package.php & create-package-world.php)
     * $data là mảng $_POST đã được xử lý
     */
    public function createTour($data)
    {
        try {
            // Gộp logic từ cả 2 file create (trong nước và quốc tế)
            // File create-package-world.php thiếu cột ten_tinh
            $sql = "INSERT INTO goidulich
                    (khuyenmai, nuocngoai, quocgia, ten_tinh, tengoi, noixuatphat, vitri, giagoi, giatreem, giatrenho, giaphongdon, chitietgoi, chuongtrinh, luuy, songay, giodi, ngayxuatphat, ngayve, phuongtien, hinhanh) 
                    VALUES 
                    (:khuyenmai, :nuocngoai, :quocgia, :ten_tinh, :tengoi, :noixuatphat, :vitri, :giagoi, :giatreem, :giatrenho, :giaphongdon, :chitietgoi, :chuongtrinh, :luuy, :songay, :giodi, :ngayxuatphat, :ngayve, :phuongtien, :hinhanh)";
            
            $query = $this->conn->prepare($sql);

            $query->bindParam(':khuyenmai', $data['khuyenmai']);
            $query->bindParam(':nuocngoai', $data['nuocngoai']);
            $query->bindParam(':quocgia', $data['quocgia']);
            $query->bindParam(':ten_tinh', $data['ten_tinh']); // File create-package-world bị thiếu cột này
            $query->bindParam(':tengoi', $data['tengoi']);
            $query->bindParam(':noixuatphat', $data['noixuatphat']);
            $query->bindParam(':vitri', $data['vitri']);
            $query->bindParam(':giagoi', $data['giagoi']);
            $query->bindParam(':giatreem', $data['giatreem']);
            $query->bindParam(':giatrenho', $data['giatrenho']);
            $query->bindParam(':giaphongdon', $data['giaphongdon']);
            $query->bindParam(':chitietgoi', $data['chitietgoi']);
            $query->bindParam(':chuongtrinh', $data['chuongtrinh']);
            $query->bindParam(':luuy', $data['luuy']);
            $query->bindParam(':songay', $data['songay']);
            $query->bindParam(':giodi', $data['giodi']);
            $query->bindParam(':ngayxuatphat', $data['ngayxuatphat']);
            $query->bindParam(':ngayve', $data['ngayve']);
            $query->bindParam(':phuongtien', $data['phuongtien']);
            $query->bindParam(':hinhanh', $data['hinhanh']);
            
            $query->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (createTour): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật tour
     * (Logic từ update-package.php)
     */
    public function updateTour($id, $data)
    {
        try {
            $sql = "UPDATE goidulich SET 
                        khuyenmai = :khuyenmai, 
                        nuocngoai = :nuocngoai, 
                        quocgia = :quocgia, 
                        ten_tinh = :ten_tinh, 
                        tengoi = :tengoi, 
                        noixuatphat = :noixuatphat, 
                        vitri = :vitri, 
                        giagoi = :giagoi, 
                        giatreem = :giatreem, 
                        giatrenho = :giatrenho, 
                        giaphongdon = :giaphongdon, 
                        sonhan = :sonhan, 
                        chitietgoi = :chitietgoi, 
                        chuongtrinh = :chuongtrinh, 
                        luuy = :luuy, 
                        songay = :songay, 
                        giodi = :giodi, 
                        ngayxuatphat = :ngayxuatphat, 
                        ngayve = :ngayve, 
                        phuongtien = :phuongtien  
                    WHERE id_goi = :id_goi";
            
            $query = $this->conn->prepare($sql);

            $query->bindParam(':khuyenmai', $data['khuyenmai']);
            $query->bindParam(':nuocngoai', $data['nuocngoai']);
            $query->bindParam(':quocgia', $data['quocgia']);
            $query->bindParam(':ten_tinh', $data['ten_tinh']);
            $query->bindParam(':tengoi', $data['tengoi']);
            $query->bindParam(':noixuatphat', $data['noixuatphat']);
            $query->bindParam(':vitri', $data['vitri']);
            $query->bindParam(':giagoi', $data['giagoi']);
            $query->bindParam(':giatreem', $data['giatreem']);
            $query->bindParam(':giatrenho', $data['giatrenho']);
            $query->bindParam(':giaphongdon', $data['giaphongdon']);
            $query->bindParam(':sonhan', $data['sonhan']);
            $query->bindParam(':chitietgoi', $data['chitietgoi']);
            $query->bindParam(':chuongtrinh', $data['chuongtrinh']);
            $query->bindParam(':luuy', $data['luuy']);
            $query->bindParam(':songay', $data['songay']);
            $query->bindParam(':giodi', $data['giodi']);
            $query->bindParam(':ngayxuatphat', $data['ngayxuatphat']);
            $query->bindParam(':ngayve', $data['ngayve']);
            $query->bindParam(':phuongtien', $data['phuongtien']);
            $query->bindParam(':id_goi', $id, PDO::PARAM_INT);
            
            return $query->execute();

        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (updateTour): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật chỉ ảnh tour
     * (Logic từ change-image.php)
     */
    public function updateTourImage($id, $imageName)
    {
        try {
            $sql = "UPDATE goidulich SET hinhanh = :hinhanh WHERE id_goi = :id_goi";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':hinhanh', $imageName, PDO::PARAM_STR);
            $query->bindParam(':id_goi', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (updateTourImage): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa một tour
     * (Logic từ delete-tour.php)
     */
    public function deleteTour($id)
    {
        try {
            // Chuyển đổi từ mysqli sang PDO
            $sql = "DELETE FROM goidulich WHERE id_goi = :id";
            $query = $this->conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Lỗi Model Tour (deleteTour): " . $e->getMessage());
            return false;
        }
    }
}