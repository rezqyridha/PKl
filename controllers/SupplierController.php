<?php
require_once __DIR__ . '/../models/SupplierModel.php';
require_once __DIR__ . '/../config/database.php';

class SupplierController {
    private $db;
    private $supplierModel;

    public function __construct($db) {
        $this->db = $db;
        $this->supplierModel = new SupplierModel($this->db);
    }

    public function getAllSuppliers() {
        return $this->supplierModel->getAllSuppliers();
    }

    public function getSupplierById($id) {
    return $this->supplierModel->getSupplierById($id);
    }


    public function addSupplier($data) {
        return $this->supplierModel->addSupplier($data);
    }

    public function editSupplier($id, $data) {
        return $this->supplierModel->editSupplier($id, $data);
    }

    public function deleteSupplier($id) {
        return $this->supplierModel->deleteSupplier($id);
    }
}
