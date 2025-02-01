<?php
class ProdukSupplierModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Mendapatkan semua data relasi produk dan supplier
    public function getAllProductSuppliers() {
        $query = "SELECT ps.*, p.nama_produk, s.nama_supplier 
                  FROM produk_supplier ps
                  INNER JOIN produk p ON ps.id_produk = p.id_produk
                  INNER JOIN supplier s ON ps.id_supplier = s.id_supplier";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mendapatkan data relasi produk-supplier berdasarkan ID produk
    public function getSuppliersByProductId($productId) {
        $query = "SELECT ps.*, s.nama_supplier 
                  FROM produk_supplier ps
                  INNER JOIN supplier s ON ps.id_supplier = s.id_supplier
                  WHERE ps.id_produk = :productId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menambahkan relasi produk-supplier
    public function addProductSupplier($productId, $supplierId) {
        $query = "INSERT INTO produk_supplier (id_produk, id_supplier) VALUES (:productId, :supplierId)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':supplierId', $supplierId);
        return $stmt->execute();
    }

    // Menghapus relasi produk-supplier berdasarkan ID produk
    public function deleteByProductId($productId) {
        $query = "DELETE FROM produk_supplier WHERE id_produk = :productId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        return $stmt->execute();
    }
}
?>
