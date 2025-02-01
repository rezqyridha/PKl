<?php
require_once '../models/SalesModel.php';

class SalesController {
    private $db;
    private $salesModel;

    public function __construct($db) {
        $this->db = $db;
        $this->salesModel = new SalesModel($db);
    }

    public function getAllSales() {
        return $this->salesModel->getAllSales();
    }

    public function getSaleById($id) {
        return $this->salesModel->getSaleById($id);
    }

    public function addSale($data) {
        // Logic to add new sales record
    }
}
?>
