<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../config/database.php';

class ProductController
{
    private $db;
    private $productModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->productModel = new ProductModel($this->db);
    }

    public function showAllProducts()
    {
        try {
            // Memanggil model untuk mengambil produk
            return $this->productModel->getAllProducts();  // Memanggil fungsi yang sudah ada di model
        } catch (Exception $e) {
            error_log("Terjadi kesalahan saat mengambil produk: " . $e->getMessage());
            return [];  // Kembalikan array kosong jika ada error
        }
    }


    public function getAllProducts()
    {
        try {
            $query = "
                SELECT produk.*, kategori.nama_kategori 
                FROM produk
                LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Kesalahan database saat mengambil semua produk: " . $e->getMessage());
            return ["pesan" => "Terjadi kesalahan saat mengambil data produk."];
        }
    }

    public function getProductById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID produk tidak valid.");
            }

            $query = "SELECT * FROM produk WHERE id_produk = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                return ["pesan" => "Produk dengan ID tersebut tidak ditemukan."];
            }

            return $product;
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil produk: " . $e->getMessage());
            return ["pesan" => "Terjadi kesalahan. Silakan coba lagi nanti."];
        }
    }


    public function getAllCategories()
    {
        return $this->productModel->getAllCategories();  // Mengambil kategori dari model
    }

    public function getAllSatuan()
    {
        return $this->productModel->getAllSatuan();  // Mengambil satuan dari model
    }

    public function addProduct($data)
    {
        return $this->productModel->addProduct($data);  // Memanggil fungsi addProduct di model
    }

    public function editProduct($id, $data)
    {
        return $this->productModel->updateProduct($id, $data);
    }

    public function deleteProduct($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID produk tidak valid.");
            }

            // Cek apakah produk ada sebelum dihapus
            $queryCheck = "SELECT * FROM produk WHERE id_produk = :id";
            $stmtCheck = $this->db->prepare($queryCheck);
            $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            $product = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                return false; // Produk tidak ditemukan
            }

            // Query untuk menghapus produk
            $queryDelete = "DELETE FROM produk WHERE id_produk = :id";
            $stmtDelete = $this->db->prepare($queryDelete);
            $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmtDelete->execute(); // Mengembalikan true jika berhasil
        } catch (Exception $e) {
            error_log("Gagal menghapus produk: " . $e->getMessage());
            return false;
        }
    }


    // Tambahkan metode untuk menangani action add
    public function handleAddProduct()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $satuan = isset($_POST['satuan']) ? $_POST['satuan'] : ''; // Ambil data satuan
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

        if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price) && !empty($stock)) {
            $data = [
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'satuan' => $satuan, // Sertakan satuan
                'price' => $price,
                'stock' => $stock,
            ];

            // Menambahkan produk
            $result = $this->addProduct($data);

            if ($result) {
                $_SESSION['alert'] = 'added'; // Set session untuk alert
                header("Location: ../views/admin/products.php");
                exit();
            } else {
                $_SESSION['alert'] = 'add_failed';
                header("Location: ../views/admin/products.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = 'invalid_input'; // Set alert jika input tidak lengkap
            header("Location: ../views/admin/products.php");
            exit();
        }
    }
}
