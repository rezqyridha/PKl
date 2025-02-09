<?php
require_once dirname(__FILE__) . '/../models/ProductModel.php';
require_once dirname(__FILE__) . '/../models/SalesModel.php';

class EmployeeController
{
    private $db;
    private $productModel;
    private $salesModel;

    /**
     * Konstruktor untuk inisialisasi koneksi database dan model terkait
     *
     * @param PDO $db Koneksi database
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
        $this->salesModel = new SalesModel($db);
    }

    /**
     * Mengambil data utama untuk dashboard karyawan
     *
     * @return array Data yang akan digunakan di dashboard
     */
    public function getDashboardData()
    {
        $data = [
            'total_stock' => $this->getTotalStock(),
            'total_sales_today' => $this->getTotalSalesToday(),
            'total_low_stock_products' => $this->getTotalLowStockProducts()
        ];

        return $data;
    }

    /**
     * Mengambil total stok semua produk
     *
     * @return int Total stok produk
     */
    private function getTotalStock()
    {
        return $this->productModel->getTotalStock() ?? 0;
    }

    /**
     * Mengambil jumlah produk yang stoknya hampir habis (≤ 5)
     *
     * @return int Jumlah produk hampir habis
     */
    private function getTotalLowStockProducts()
    {
        return $this->productModel->getLowStockCount();
    }

    /**
     * Mengambil total penjualan untuk hari ini
     *
     * @return float Total penjualan hari ini
     */
    private function getTotalSalesToday()
    {
        $salesData = $this->salesModel->getSalesByDate(date('Y-m-d'), date('Y-m-d'));
        return array_sum(array_column($salesData, 'total_harga')) ?? 0;
    }
}
