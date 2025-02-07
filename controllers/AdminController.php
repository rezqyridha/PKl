<?php
require_once dirname(__FILE__) . '/../models/ProductModel.php';
require_once dirname(__FILE__) . '/../models/SalesModel.php';
require_once dirname(__FILE__) . '/../models/UserModel.php';

class AdminController
{
    private $db;
    private $productModel;
    private $salesModel;
    private $userModel;

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
        $this->userModel = new UserModel($db);
    }

    /**
     * Mengambil data utama untuk dashboard admin
     *
     * @param string|null $startDate Tanggal mulai (opsional)
     * @param string|null $endDate Tanggal akhir (opsional)
     * @return array Data yang akan digunakan di dashboard
     */
    public function getDashboardData($startDate = null, $endDate = null)
    {
        $data = [
            'total_stock' => $this->getTotalStock(),
            'total_employees' => $this->getTotalEmployees(),
            'total_low_stock_products' => $this->getTotalLowStockProducts(),
            'total_sales_today' => $this->getTotalSalesToday()
        ];

        // Jika ada filter rentang tanggal, ambil total penjualan berdasarkan rentang tersebut
        if (!empty($startDate) && !empty($endDate)) {
            $salesData = $this->getSalesByDate($startDate, $endDate);
            $data['sales_data'] = $salesData;
            $data['total_sales'] = array_sum(array_column($salesData, 'total_harga')) ?? 0;
        }

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
     * Mengambil jumlah total karyawan
     *
     * @return int Total karyawan
     */
    private function getTotalEmployees()
    {
        return $this->userModel->getTotalEmployees() ?? 0;
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

    /**
     * Mengambil data penjualan berdasarkan rentang tanggal tertentu
     *
     * @param string $startDate Tanggal mulai (YYYY-MM-DD)
     * @param string $endDate Tanggal akhir (YYYY-MM-DD)
     * @return array Data penjualan berdasarkan tanggal
     */
    private function getSalesByDate($startDate, $endDate)
    {
        return $this->salesModel->getSalesByDate($startDate, $endDate) ?? [];
    }
}
