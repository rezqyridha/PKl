<?php
require_once dirname(__FILE__) . '/../models/ProductModel.php';
require_once dirname(__FILE__) . '/../models/SalesModel.php';
require_once dirname(__FILE__) . '/../models/UserModel.php';

class AdminController {
    private $db;
    private $productModel;
    private $salesModel;
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new ProductModel($db);
        $this->salesModel = new SalesModel($db);
        $this->userModel = new UserModel($db);
    }

    public function getDashboardData() {
        return [
            'total_stock' => $this->productModel->getTotalStock(), // Ambil total stok produk
            'total_sales_today' => $this->salesModel->getSalesByDate(date('Y-m-d')), // Penjualan hari ini
            'total_employees' => $this->userModel->getTotalEmployees() // Total karyawan
        ];
    }
    
}
?>
