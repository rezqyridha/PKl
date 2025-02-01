<?php
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../config/database.php';

class CustomerController {
    private $db;
    private $customerModel;

    public function __construct($db) {
        $this->db = $db;
        $this->customerModel = new CustomerModel($this->db); // Pastikan CustomerModel sudah terdefinisi
    }

    public function getCustomerById($id) {
        return $this->customerModel->getCustomerById($id);
    }
    

    public function getAllCustomers() {
        return $this->customerModel->getAllCustomers();
    }

    public function addCustomer($data) {
        return $this->customerModel->addCustomer($data);
    }
    

    public function editCustomer($id, $data) {
        return $this->customerModel->updateCustomer($id, $data);
    }
    

    public function deleteCustomer($id) {
        return $this->customerModel->deleteCustomer($id);
    }
}
?>
