<?php
require_once __DIR__ . '/../models/SupplierModel.php';
require_once __DIR__ . '/../config/database.php';

class SupplierController
{
    private $db;
    private $supplierModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->supplierModel = new SupplierModel($this->db);
    }

    public function getAllSuppliers()
    {
        return $this->supplierModel->getAllSuppliers();
    }

    public function getSupplierById($id)
    {
        if ($id <= 0) {
            return false; // Jika ID tidak valid, kembalikan false
        }

        return $this->supplierModel->getSupplierById($id);
    }


    public function addSupplier($data)
    {
        if (empty($data['nama'])) {
            return false; // Nama tidak boleh kosong
        }

        return $this->supplierModel->addSupplier($data);
    }

    public function editSupplier($id, $data)
    {
        if (empty($data['nama'])) {
            return false; // Nama tidak boleh kosong
        }

        return $this->supplierModel->updateSupplier($id, $data);
    }

    public function deleteSupplier($id)
    {
        if ($id <= 0) {
            error_log("Gagal menghapus: ID tidak valid");
            return false;
        }

        $query = "DELETE FROM supplier WHERE id_supplier = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            error_log("Supplier ID: $id berhasil dihapus");
            return true;
        } else {
            error_log("Gagal menghapus Supplier ID: $id, Error: " . json_encode($stmt->errorInfo()));
            return false;
        }
    }
}
