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

    public function __construct($db)
    {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
        $this->salesModel = new SalesModel($db);
        $this->userModel = new UserModel($db);
    }

    public function getDashboardData($startDate = null, $endDate = null)
    {
        // Ambil data stok produk dan total karyawan
        $data = [
            'total_stock' => $this->productModel->getTotalStock(), // Ambil total stok produk
            'total_employees' => $this->userModel->getTotalEmployees() // Total karyawan
        ];

        // Jika ada filter tanggal, ambil data penjualan berdasarkan rentang tanggal
        if ($startDate && $endDate) {
            $salesData = $this->salesModel->getSalesByDate($startDate, $endDate);
            // Menghitung total penjualan dari data yang diambil
            $totalSales = array_sum(array_column($salesData, 'total_harga'));
            $data['sales_data'] = $salesData;
            $data['total_sales'] = $totalSales;  // Menambahkan total penjualan
        } else {
            // Menggunakan tanggal hari ini untuk penjualan hari ini
            $salesData = $this->salesModel->getSalesByDate(date('Y-m-d'), date('Y-m-d'));
            // Menghitung total penjualan dari data yang diambil
            $totalSalesToday = array_sum(array_column($salesData, 'total_harga'));
            $data['total_sales_today'] = $totalSalesToday;  // Menambahkan total penjualan hari ini
        }

        return $data;
    }
}
