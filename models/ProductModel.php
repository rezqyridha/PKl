<?php
class ProductModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducts()
    {
        $query = "
        SELECT produk.*, kategori.nama_kategori, satuan.nama_satuan
        FROM produk
        LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori
        LEFT JOIN satuan ON produk.id_satuan = satuan.id_satuan
    ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM kategori";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSatuan()
    {
        $query = "SELECT * FROM satuan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProductById($id)
    {
        $query = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($data)
    {
        try {
            // Query untuk menambahkan produk baru
            $query = "INSERT INTO produk (nama_produk, deskripsi, id_kategori, id_satuan, harga, stok)
                    VALUES (:name, :description, :category, :satuan, :price, :stock)";
            $stmt = $this->db->prepare($query);

            // Bind parameter
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':satuan', $data['satuan']); // Pastikan data satuan dikirim
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':stock', $data['stock']);

            // Eksekusi query
            if ($stmt->execute()) {
                return true; // Produk berhasil ditambahkan
            } else {
                return false; // Gagal menambahkan produk
            }
        } catch (PDOException $e) {
            error_log("Gagal menambahkan produk: " . $e->getMessage());
            return false; // Tangani error dan kembalikan false
        }
    }



    public function updateProduct($id, $data)
    {
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


    public function deleteProduct($id)
    {
        $query = "DELETE FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT * FROM produk WHERE id_kategori = :categoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalStock()
    {
        $query = "SELECT SUM(stok) AS total_stock FROM produk";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_stock'] ?? 0;
    }
}
