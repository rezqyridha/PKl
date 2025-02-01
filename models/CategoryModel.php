<?php
class CategoryModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM kategori ORDER BY id_kategori ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($name) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function editCategory($id, $name) {
        $query = "UPDATE kategori SET nama_kategori = :name WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
