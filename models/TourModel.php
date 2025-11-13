<?php
/**
 * TourModel - ĐÃ XÓA GIAPHONGDON
 * Updated: 2025
 */
class TourModel extends BaseModel
{
    public function getAllTours()
    {
        $sql = "SELECT * FROM goidulich ORDER BY id_goi ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTour(array $data, $file = null)
    {
        try {
            // Ưu tiên đường dẫn đã upload sẵn ở Controller
            $hinhanh = $data['hinhanh'] ?? null;

            // Nếu Controller chưa upload, Model mới thử upload
            if (!$hinhanh && $file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
                $hinhanh = uploadFile($file, 'uploads/tours/');
            }

            // Bảo vệ NOT NULL
            if (!$hinhanh) {
                throw new PDOException("Ảnh (hinhanh) đang NULL vì chưa upload được.");
            }

            $sql = "INSERT INTO goidulich (
                        khuyenmai, nuocngoai, quocgia, ten_tinh, tengoi,
                        noixuatphat, vitri, giagoi, giatreem, giatrenho,
                        chitietgoi, chuongtrinh, luuy,
                        songay, giodi, ngayxuatphat, ngayve, phuongtien, hinhanh, ngaydang
                    ) VALUES (
                        :khuyenmai, :nuocngoai, :quocgia, :ten_tinh, :tengoi,
                        :noixuatphat, :vitri, :giagoi, :giatreem, :giatrenho,
                        :chitietgoi, :chuongtrinh, :luuy,
                        :songay, :giodi, :ngayxuatphat, :ngayve, :phuongtien, :hinhanh, NOW()
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':khuyenmai'    => $data['khuyenmai']  ?? 0,
                ':nuocngoai'    => $data['nuocngoai']  ?? 0,
                ':quocgia'      => $data['quocgia']    ?? 'Việt Nam',
                ':ten_tinh'     => $data['ten_tinh']   ?? null,
                ':tengoi'       => $data['tengoi'],
                ':noixuatphat'  => $data['noixuatphat'],
                ':vitri'        => $data['vitri'],
                ':giagoi'       => $data['giagoi'],
                ':giatreem'     => $data['giatreem'],
                ':giatrenho'    => $data['giatrenho'],
                ':chitietgoi'   => $data['chitietgoi'],
                ':chuongtrinh'  => $data['chuongtrinh'],
                ':luuy'         => $data['luuy'],
                ':songay'       => $data['songay'],
                ':giodi'        => $data['giodi'],
                ':ngayxuatphat' => $data['ngayxuatphat'],
                ':ngayve'       => $data['ngayve'],
                ':phuongtien'   => $data['phuongtien'],
                ':hinhanh'      => $hinhanh,
            ]);

            return true;
        } catch (PDOException $e) {
            echo "<pre style='color:red'>LỖI SQL: ".$e->getMessage()."</pre>";
            return false;
        }
    }

    public function getTourByID($id)
    {
        $sql = "SELECT * FROM goidulich WHERE id_goi = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTour($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM goidulich WHERE id_goi = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Cập nhật thông tin tour (KHÔNG đổi ảnh)
    public function updateTour($id, array $data)
    {
        try {
            $sql = "UPDATE goidulich SET
                        khuyenmai    = :khuyenmai,
                        nuocngoai    = :nuocngoai,
                        quocgia      = :quocgia,
                        ten_tinh     = :ten_tinh,
                        tengoi       = :tengoi,
                        noixuatphat  = :noixuatphat,
                        vitri        = :vitri,
                        giagoi       = :giagoi,
                        giatreem     = :giatreem,
                        giatrenho    = :giatrenho,
                        chitietgoi   = :chitietgoi,
                        chuongtrinh  = :chuongtrinh,
                        luuy         = :luuy,
                        songay       = :songay,
                        giodi        = :giodi,
                        ngayxuatphat = :ngayxuatphat,
                        ngayve       = :ngayve,
                        phuongtien   = :phuongtien
                    WHERE id_goi = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':khuyenmai'    => $data['khuyenmai']  ?? 0,
                ':nuocngoai'    => $data['nuocngoai']  ?? 0,
                ':quocgia'      => $data['quocgia']    ?? 'Việt Nam',
                ':ten_tinh'     => $data['ten_tinh']   ?? null,
                ':tengoi'       => $data['tengoi'],
                ':noixuatphat'  => $data['noixuatphat'],
                ':vitri'        => $data['vitri'],
                ':giagoi'       => $data['giagoi'],
                ':giatreem'     => $data['giatreem'],
                ':giatrenho'    => $data['giatrenho'],
                ':chitietgoi'   => $data['chitietgoi'],
                ':chuongtrinh'  => $data['chuongtrinh'],
                ':luuy'         => $data['luuy'],
                ':songay'       => $data['songay'],
                ':giodi'        => $data['giodi'],
                ':ngayxuatphat' => $data['ngayxuatphat'],
                ':ngayve'       => $data['ngayve'],
                ':phuongtien'   => $data['phuongtien'],
                ':id'           => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật riêng ảnh tour
    public function updateTourImage($id, string $hinhanh)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE goidulich SET hinhanh = :hinhanh WHERE id_goi = :id"
            );
            return $stmt->execute([
                ':hinhanh' => $hinhanh,
                ':id'      => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateTourImage: " . $e->getMessage());
            return false;
        }
    }

    public function toggleStatus($id) {
        $sql = "UPDATE goidulich SET trangthai = IF(trangthai = 1, 0, 1) WHERE id_goi = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }
}