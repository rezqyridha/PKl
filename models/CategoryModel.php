<?php
class CategoryModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM kategori ORDER BY id_kategori ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $query = "SELECT * FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($data)
    {
        $query = "INSERT INTO kategori (nama_kategori) VALUES (:nama)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama_kategori'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateCategory($id, $data)
    {
        $query = "UPDATE kategori SET nama_kategori = :nama WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama_kategori'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
