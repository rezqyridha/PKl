<?php
class CategoryController {
    private $db;

    
    public function __construct($db) {
        $this->db = $db;
    }

    
    public function getAllCategories() {
        $query = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY id_kategori ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getCategoryById($id) {
        $query = "SELECT id_kategori, nama_kategori FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function addCategory($name) {
        // Validasi apakah nama kategori sudah ada
        if ($this->isCategoryExists($name)) {
            return false; // Jika kategori sudah ada
        }

        $query = "INSERT INTO kategori (nama_kategori) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        return $stmt->execute();
    }

    
    public function editCategory($id, $name) {
        // Validasi apakah nama kategori sudah ada (kecuali kategori saat ini)
        $query = "SELECT COUNT(*) AS total FROM kategori WHERE nama_kategori = :name AND id_kategori != :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return false; // Nama kategori sudah ada
        }

        $query = "UPDATE kategori SET nama_kategori = :name WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function deleteCategory($id) {
        // Pastikan tidak ada produk yang terkait dengan kategori sebelum menghapus
        $query = "SELECT COUNT(*) AS total FROM produk WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return false; // Tidak bisa menghapus kategori karena masih ada produk terkait
        }

        $query = "DELETE FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function countCategories() {
        $query = "SELECT COUNT(*) AS total FROM kategori";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    
    public function isCategoryExists($name) {
        $query = "SELECT COUNT(*) AS total FROM kategori WHERE nama_kategori = :name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
}
?>
