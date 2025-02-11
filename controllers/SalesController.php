<?php
require_once __DIR__ . '/../models/SalesModel.php';
require_once __DIR__ . '/../controllers/ProductController.php';

class SalesController
{
    private $db;
    private $salesModel;
    private $productController;

    public function __construct($db)
    {
        $this->db = $db;
        $this->salesModel = new SalesModel($this->db);
        $this->productController = new ProductController($this->db); // Pastikan ProductController diinisialisasi
    }

    // Menampilkan semua data penjualan
    public function showAllSales()
    {
        try {
            return $this->salesModel->getAllSales();
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil data penjualan: " . $e->getMessage());
            return [];
        }
    }

    // Mengambil data penjualan berdasarkan ID
    public function getSaleById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID penjualan tidak valid.");
            }
            return $this->salesModel->getSaleById($id);
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil data penjualan ID $id: " . $e->getMessage());
            return null;
        }
    }

    // Mengurangi stok saat penjualan ditambahkan
    public function addSale($data)
    {
        try {
            if ($this->salesModel->addSale($data)) {
                $this->updateProductStock($data['id_produk'], -$data['jumlah_terjual']);
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Kesalahan saat menambahkan penjualan: " . $e->getMessage());
            return false;
        }
    }


    // Mengedit data penjualan dan menyesuaikan stok produk
    public function updateSales($id, $data)
    {
        try {
            $currentSale = $this->salesModel->getSaleById($id);
            if (!$currentSale) {
                throw new Exception("Data penjualan tidak ditemukan.");
            }

            $oldQuantity = $currentSale['jumlah_terjual'];
            $newQuantity = intval($data['jumlah_terjual']);
            $quantityDifference = $newQuantity - $oldQuantity;

            if ($quantityDifference !== 0) {
                $this->updateProductStock($data['id_produk'], -$quantityDifference);
            }

            return $this->salesModel->updateSale($id, $data) ? 'success' : 'failed';
        } catch (Exception $e) {
            error_log("Kesalahan saat memperbarui data penjualan: " . $e->getMessage());
            return 'error';
        }
    }





    // Menghapus data penjualan dan mengembalikan stok produk
    public function deleteSaleWithStockRestore($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID penjualan tidak valid.");
            }

            $sale = $this->getSaleById($id);
            if ($sale) {
                $this->updateProductStock($sale['id_produk'], $sale['jumlah_terjual']); // Kembalikan stok
                error_log("Stok produk ID {$sale['id_produk']} berhasil dikembalikan sebanyak {$sale['jumlah_terjual']}.");
                return $this->salesModel->deleteSale($id);
            } else {
                error_log("Penjualan dengan ID $id tidak ditemukan.");
                return false;
            }
        } catch (Exception $e) {
            error_log("Kesalahan saat menghapus penjualan: " . $e->getMessage());
            return false;
        }
    }

    // ==========================
    // Fungsi Pembaruan Stok Produk
    // ==========================
    private function updateProductStock($productId, $quantityChange)
    {
        $product = $this->productController->getProductById($productId);
        if ($product) {
            $newStock = max(0, $product['stok'] + $quantityChange);
            $this->productController->updateStock($productId, $newStock);
            error_log("Stok produk ID $productId diperbarui menjadi: $newStock.");
        }
    }


    // ==========================
    // Validasi Data Penjualan
    // ==========================
    private function validateSaleData($data)
    {
        return isset($data['id_produk'], $data['tanggal_penjualan'], $data['jumlah_terjual'], $data['total_harga']) &&
            is_numeric($data['jumlah_terjual']) && $data['jumlah_terjual'] > 0 &&
            is_numeric($data['total_harga']) && $data['total_harga'] > 0;
    }
}
