<?php
require_once __DIR__ . '/../models/RestockModel.php';

class RestockController
{
    private $restockModel;

    public function __construct($db)
    {
        // Inisialisasi model Restock dengan koneksi database
        $this->restockModel = new RestockModel($db);
    }

    // Menampilkan semua data restock
    public function showAllRestock()
    {
        try {
            return $this->restockModel->getAllRestock();
        } catch (Exception $e) {
            error_log("Error in showAllRestock: " . $e->getMessage());
            return [];
        }
    }

    // Menampilkan data restock berdasarkan ID
    public function getRestockById($id)
    {
        try {
            return $this->restockModel->getRestockById($id);
        } catch (Exception $e) {
            error_log("Error in getRestockById: " . $e->getMessage());
            return null;
        }
    }

    // Menambahkan data restock baru
    public function addRestock($data)
    {
        try {
            return $this->restockModel->addRestock($data);
        } catch (Exception $e) {
            error_log('Error in addRestock: ' . $e->getMessage());
            return false;
        }
    }

    // Memperbarui data restock
    public function updateRestock($id, $data)
    {
        try {
            if (
                !empty($data['id_produk']) &&
                !empty($data['id_supplier']) &&
                !empty($data['tanggal_restock']) &&
                !empty($data['jumlah_ditambahkan']) &&
                !empty($data['harga_beli'])
            ) {
                // Hitung total biaya dari jumlah yang ditambahkan dan harga beli
                $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_beli'];
                return $this->restockModel->updateRestock($id, $data);
            }
            return false;
        } catch (Exception $e) {
            error_log('Error in updateRestock: ' . $e->getMessage());
            return false;
        }
    }

    // Menghapus data restock
    public function deleteRestock($id)
    {
        try {
            return $this->restockModel->deleteRestock($id);
        } catch (Exception $e) {
            error_log('Error in deleteRestock: ' . $e->getMessage());
            return false;
        }
    }
}
