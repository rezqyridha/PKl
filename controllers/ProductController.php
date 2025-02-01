<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../config/database.php';

class ProductController {
    private $db;
    private $productModel;

    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new ProductModel($this->db);
    }

    public function showAllProducts() {
        $products = $this->productModel->getAllProducts();
        if (!$products) {
            echo "Tidak ada data produk ditemukan.";
            exit;
        }
        return $products;
    }
    
    public function getAllProducts() {
        $query = "
            SELECT produk.*, kategori.nama_kategori 
            FROM produk
            LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

    public function getAllCategories() {
        $query = "SELECT id_kategori, nama_kategori FROM kategori";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($data) {
        return $this->productModel->addProduct($data);
    }

    public function editProduct($id, $data) {
        return $this->productModel->updateProduct($id, $data);
    }

    public function deleteProduct($id) {
        return $this->productModel->deleteProduct($id);
    }

    // Tambahkan metode untuk menangani action add
    public function handleAddProduct() {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

        if (!empty($name) && !empty($description) && !empty($category) && !empty($price) && !empty($stock)) {
            $data = [
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'price' => $price,
                'stock' => $stock,
            ];

            $result = $this->addProduct($data);

            if ($result) {
                // Redirect ke halaman products.php dengan notifikasi sukses
                header("Location: ../views/admin/products.php?success=1");
                exit();
            } else {
                // Redirect ke halaman products.php dengan notifikasi error
                header("Location: ../views/admin/products.php?error=add_failed");
                exit();
            }
        } else {
            // Redirect ke halaman products.php dengan notifikasi invalid input
            header("Location: ../views/admin/products.php?error=invalid_input");
            exit();
        }
    }
}
