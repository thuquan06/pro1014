<?php
class BlogModel {

    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM blog ORDER BY id_blog DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM blog WHERE id_blog = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE blog SET 
                chude=:chude,
                tomtat=:tomtat,
                noidung=:noidung,
                hinhanh=:hinhanh,
                nguoiviet=:nguoiviet
            WHERE id_blog=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function insert($data)
{
    $sql = "INSERT INTO blog 
            (chude, tomtat, noidung, hinhanh, nguoiviet, ngaydang)
            VALUES (:chude, :tomtat, :noidung, :hinhanh, :nguoiviet, NOW())";

    $stmt = $this->conn->prepare($sql);
    return $stmt->execute($data);
}


    public function delete($id)
    {
        $sql = "DELETE FROM blog WHERE id_blog = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
