<?php
require_once __DIR__ . '/../models/RestockModel.php';
require_once __DIR__ . '/../controllers/ProductController.php';

class RestockController
{
    private $restockModel;
    private $productController;

    public function __construct($db)
    {
        $this->restockModel = new RestockModel($db);
        $this->productController = new ProductController($db);
    }

    public function showAllRestock()
    {
        try {
            return $this->restockModel->getAllRestock();
        } catch (Exception $e) {
            error_log("Error in showAllRestock: " . $e->getMessage());
            return [];
        }
    }

    public function getRestockById($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }
            return $this->restockModel->getRestockById($id);
        } catch (Exception $e) {
            error_log("Error in getRestockById: " . $e->getMessage());
            return null;
        }
    }

    public function getTotalRestockToday()
    {
        try {
            $today = date('Y-m-d');
            $query = "SELECT COUNT(*) AS total FROM restock WHERE DATE(tanggal_restock) = ?";
            $stmt = $this->restockModel->getDb()->prepare($query); // Pastikan properti `db` tersedia di RestockModel
            $stmt->execute([$today]);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalRestockToday: " . $e->getMessage());
            return 0;
        }
    }


    public function addRestock($data)
    {
        try {
            if ($this->validateRestockData($data)) {
                $result = $this->restockModel->addRestock($data);
                if ($result) {
                    // Update stok hanya setelah data berhasil disimpan
                    $this->updateProductStock($data['id_produk'], $data['jumlah_ditambahkan']);
                }
                return $result;
            } else {
                error_log("Validasi gagal pada addRestock: " . json_encode($data));
                return false;
            }
        } catch (Exception $e) {
            error_log('Error in addRestock: ' . $e->getMessage());
            return false;
        }
    }

    public function updateRestock($id, $data)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }

            if ($this->validateRestockData($data)) {
                $oldRestock = $this->getRestockById($id);
                if (!$oldRestock) {
                    throw new Exception("Data restock tidak ditemukan.");
                }

                // Periksa apakah ID produk berubah
                if ($oldRestock['id_produk'] !== $data['id_produk']) {
                    // Kembalikan stok lama
                    $this->updateProductStock($oldRestock['id_produk'], -$oldRestock['jumlah_ditambahkan']);
                    // Tambahkan stok ke produk baru
                    $this->updateProductStock($data['id_produk'], $data['jumlah_ditambahkan']);
                } else {
                    // Hitung selisih jumlah ditambahkan dan perbarui stok
                    $quantityDifference = $data['jumlah_ditambahkan'] - $oldRestock['jumlah_ditambahkan'];
                    if ($quantityDifference !== 0) {
                        $this->updateProductStock($data['id_produk'], $quantityDifference);
                    }
                }

                // Hitung total biaya dari jumlah yang ditambahkan dan harga beli
                $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];
                return $this->restockModel->updateRestock($id, $data);
            } else {
                error_log("Validasi gagal pada updateRestock: " . json_encode($data));
                return false;
            }
        } catch (Exception $e) {
            error_log('Error in updateRestock: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteRestock($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }

            $restock = $this->getRestockById($id);
            if ($restock) {
                // Kurangi stok produk sebelum menghapus data restock
                $this->updateProductStock($restock['id_produk'], -$restock['jumlah_ditambahkan']);
            }

            return $this->restockModel->deleteRestock($id);
        } catch (Exception $e) {
            error_log('Error in deleteRestock: ' . $e->getMessage());
            return false;
        }
    }

    private function updateProductStock($productId, $quantityChange)
    {
        $product = $this->productController->getProductById($productId);
        if ($product) {
            $newStock = max(0, $product['stok'] + $quantityChange);
            $this->productController->updateStock($productId, $newStock);
            error_log("Stok produk ID $productId diperbarui menjadi: $newStock");
        } else {
            error_log("Produk dengan ID $productId tidak ditemukan. Tidak bisa memperbarui stok.");
        }
    }

    private function validateRestockData($data)
    {
        return isset($data['id_produk'], $data['id_supplier'], $data['tanggal_restock'], $data['jumlah_ditambahkan'], $data['harga_per_unit']) &&
            is_numeric(str_replace('.', '', $data['jumlah_ditambahkan'])) &&
            is_numeric(str_replace('.', '', $data['harga_per_unit'])) &&
            !empty($data['tanggal_restock']);
    }
}
