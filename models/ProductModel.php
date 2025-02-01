<?php
class ProductModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllProducts() {
        $query = "SELECT * FROM produk";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$result) {
            echo "Query gagal atau tidak ada data ditemukan.";
            print_r($this->db->errorInfo());
            exit;
        }
    
        return $result;
    }
    
    public function getProductById($id) {
        $query = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($data) {
        $query = "INSERT INTO produk (nama_produk, deskripsi, id_kategori, harga, stok) 
                  VALUES (:name, :description, :category, :price, :stock)";
    
        $stmt = $this->db->prepare($query);
    
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':category', $data['category'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindParam(':stock', $data['stock'], PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateProduct($id, $data) {
        $query = "UPDATE produk 
                  SET nama_produk = :name, deskripsi = :description, id_kategori = :category, 
                      harga = :price, stok = :stock 
                  WHERE id_produk = :id";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':id', $id);
    
        return $stmt->execute();
    }
    

    public function deleteProduct($id) {
        $query = "DELETE FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getProductsByCategory($categoryId) {
        $query = "SELECT * FROM produk WHERE id_kategori = :categoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalStock() {
        $query = "SELECT SUM(stok) AS total_stock FROM produk";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_stock'] ?? 0;
    }
    
    
}
?>
