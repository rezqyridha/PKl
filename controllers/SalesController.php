<?php

require_once __DIR__ . '/../models/SalesModel.php';

class SalesController
{
    private $db;
    private $salesModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->salesModel = new SalesModel($this->db);
    }

    // Menampilkan semua penjualan
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

            $sale = $this->salesModel->getSaleById($id);
            if (!$sale) {
                return ["pesan" => "Penjualan dengan ID tersebut tidak ditemukan."];
            }

            return $sale;
        } catch (Exception $e) {
            error_log("Kesalahan saat mengambil data penjualan: " . $e->getMessage());
            return ["pesan" => "Terjadi kesalahan. Silakan coba lagi nanti."];
        }
    }

    // Menambahkan penjualan baru
    public function addSale($data)
    {
        try {
            return $this->salesModel->addSale($data);
        } catch (Exception $e) {
            error_log("Kesalahan saat menambahkan penjualan: " . $e->getMessage());
            return false;
        }
    }

    // Mengedit data penjualan
    public function updateSales($id, $data)
    {
        try {
            $currentSale = $this->salesModel->getSaleById($id);
            if (!$currentSale) {
                throw new Exception("Data penjualan tidak ditemukan.");
            }

            // Cek apakah data yang diupdate sama persis
            if (
                $currentSale['id_produk'] == $data['id_produk'] &&
                $currentSale['id_pelanggan'] == $data['id_pelanggan'] &&
                $currentSale['tanggal_penjualan'] == $data['tanggal_penjualan'] &&
                $currentSale['jumlah_terjual'] == $data['jumlah_terjual'] &&
                $currentSale['total_harga'] == $data['total_harga']
            ) {
                return 'no_change'; // Tidak ada perubahan
            }

            return $this->salesModel->updateSale($id, $data) ? 'success' : 'failed';
        } catch (Exception $e) {
            error_log("Kesalahan saat memperbarui data penjualan: " . $e->getMessage());
            return 'error';
        }
    }


    // Menghapus data penjualan
    public function deleteSale($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("ID penjualan tidak valid.");
            }

            $sale = $this->salesModel->getSaleById($id);
            if (!$sale) {
                return false; // Penjualan tidak ditemukan
            }

            return $this->salesModel->deleteSale($id);
        } catch (Exception $e) {
            error_log("Kesalahan saat menghapus penjualan: " . $e->getMessage());
            return false;
        }
    }

    // Handler untuk menambahkan penjualan melalui form
    public function handleAddSale()
    {
        $id_produk = $_POST['id_produk'] ?? '';
        $id_pelanggan = $_POST['id_pelanggan'] ?? '';
        $tanggal_penjualan = $_POST['tanggal_penjualan'] ?? '';
        $jumlah_terjual = $_POST['jumlah_terjual'] ?? '';
        $total_harga = $_POST['total_harga'] ?? '';

        if (!empty($id_produk) && !empty($id_pelanggan) && !empty($tanggal_penjualan) && !empty($jumlah_terjual) && !empty($total_harga)) {
            $data = [
                'id_produk' => $id_produk,
                'id_pelanggan' => $id_pelanggan,
                'tanggal_penjualan' => $tanggal_penjualan,
                'jumlah_terjual' => $jumlah_terjual,
                'total_harga' => $total_harga
            ];

            if ($this->addSale($data)) {
                $_SESSION['alert'] = 'added';
                header("Location: ../views/admin/sales.php");
                exit();
            } else {
                $_SESSION['alert'] = 'add_failed';
                header("Location: ../views/admin/sales.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = 'invalid_input';
            header("Location: ../views/admin/sales.php");
            exit();
        }
    }
}
