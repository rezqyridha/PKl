<?php
require_once __DIR__ . '/../models/SalesModel.php';
require_once __DIR__ . '/../config/database.php';

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
        return $this->salesModel->getAllSales();
    }

    // Menambahkan penjualan
    public function addSale($data)
    {
        return $this->salesModel->addSale($data);
    }

    // Mengambil penjualan berdasarkan ID
    public function getSaleById($id)
    {
        return $this->salesModel->getSaleById($id);
    }

    // Mengedit penjualan
    public function updateSale($id, $data)
    {
        return $this->salesModel->updateSale($id, $data);
    }

    // Menghapus penjualan
    public function deleteSale($id)
    {
        return $this->salesModel->deleteSale($id);
    }

    // Fungsi untuk mengambil penjualan berdasarkan rentang tanggal
    public function getSalesByDate($startDate, $endDate)
    {
        return $this->salesModel->getSalesByDate($startDate, $endDate);
    }
}
