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

    /**
     * Menampilkan semua produk
     * @return array Daftar produk
     */
    public function showAllProducts()
    {
        try {
            return $this->productModel->getAllProducts();
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil semua produk: " . $e->getMessage());
            return [];
        }
    }


    public function getTotalStock()
    {
        try {
            return $this->productModel->getTotalStock();
        } catch (Exception $e) {
            error_log("Error in getTotalStock: " . $e->getMessage());
            return 0;
        }
    }


    /**
     * Mengambil produk berdasarkan ID
     * @param int $id
     * @return array|null Data produk atau null jika tidak ditemukan
     */
    public function getProductById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID produk tidak valid.");
            }
            return $this->productModel->getProductById($id);
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil produk ID $id: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Memperbarui stok produk
     * @param int $productId
     * @param int|null $newStock Stok baru, bisa null jika tidak ingin diubah
     * @return bool True jika berhasil, False jika gagal
     */
    public function updateStock($productId, $newStock = null)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                throw new Exception("ID produk tidak valid.");
            }

            // Jika stok kosong, set sebagai NULL
            $newStock = ($newStock === '' || is_null($newStock)) ? null : (int)$newStock;

            $result = $this->productModel->updateStock($productId, $newStock);

            if ($result) {
                error_log("Stok produk ID $productId berhasil diperbarui.");
            } else {
                error_log("Gagal memperbarui stok produk ID $productId.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Exception saat memperbarui stok produk ID $productId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Menambahkan produk baru
     * @param array $data Data produk
     * @return bool True jika berhasil, False jika gagal
     */
    public function addProduct($data)
    {
        // Validasi stok sebelum diteruskan ke model
        $data['stock'] = ($data['stock'] === '' || is_null($data['stock'])) ? null : (int)$data['stock'];

        return $this->productModel->addProduct($data);
    }

    /**
     * Memperbarui data produk berdasarkan ID
     * @param int $id
     * @param array $data Data produk
     * @return bool True jika berhasil, False jika gagal
     */
    public function editProduct($id, $data)
    {
        // Validasi stok sebelum diteruskan ke model
        $data['stock'] = ($data['stock'] === '' || is_null($data['stock'])) ? null : (int)$data['stock'];

        $result = $this->productModel->updateProduct($id, $data);

        if ($result) {
            error_log("Produk ID $id berhasil diperbarui.");
        } else {
            error_log("Gagal memperbarui produk ID $id.");
        }

        return $result;
    }

    /**
     * Menghapus produk berdasarkan ID
     * @param int $id
     * @return bool True jika berhasil, False jika gagal
     */
    public function deleteProduct($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID produk tidak valid.");
            }

            $product = $this->productModel->getProductById($id);

            if (!$product) {
                error_log("Produk ID $id tidak ditemukan.");
                return false;
            }

            $result = $this->productModel->deleteProduct($id);
            if ($result) {
                error_log("Produk ID $id berhasil dihapus.");
            } else {
                error_log("Gagal menghapus produk ID $id.");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Kesalahan saat menghapus produk ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil semua kategori produk
     * @return array Daftar kategori produk
     */
    public function getAllCategories()
    {
        return $this->productModel->getAllCategories();
    }

    /**
     * Mengambil semua satuan produk
     * @return array Daftar satuan produk
     */
    public function getAllSatuan()
    {
        return $this->productModel->getAllSatuan();
    }
}
