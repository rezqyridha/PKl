<?php
require_once __DIR__ . '/../models/RestockModel.php';

class RestockController
{
    private $restockModel;

    public function __construct($db)
    {
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
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }
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
            if ($this->validateRestockData($data)) {
                return $this->restockModel->addRestock($data);
            } else {
                error_log("Validasi gagal pada addRestock: " . json_encode($data));
                return false;
            }
        } catch (Exception $e) {
            error_log('Error in addRestock: ' . $e->getMessage());
            return false;
        }
    }

    // Memperbarui data restock
    public function updateRestock($id, $data)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }

            if ($this->validateRestockData($data)) {
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

    // Menghapus data restock
    public function deleteRestock($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID Restock tidak valid.");
            }
            return $this->restockModel->deleteRestock($id);
        } catch (Exception $e) {
            error_log('Error in deleteRestock: ' . $e->getMessage());
            return false;
        }
    }

    // ==========================
    // Fungsi Validasi Data Restock
    // ==========================
    private function validateRestockData($data)
    {
        return isset($data['id_produk'], $data['id_supplier'], $data['tanggal_restock'], $data['jumlah_ditambahkan'], $data['harga_per_unit']) &&
            is_numeric($data['id_produk']) && $data['id_produk'] > 0 &&
            is_numeric($data['id_supplier']) && $data['id_supplier'] > 0 &&
            !empty($data['tanggal_restock']) &&
            is_numeric($data['jumlah_ditambahkan']) && $data['jumlah_ditambahkan'] > 0 &&
            is_numeric($data['harga_per_unit']) && $data['harga_per_unit'] > 0;
    }
}
