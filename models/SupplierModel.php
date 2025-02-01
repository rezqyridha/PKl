<?php
class SupplierModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllSuppliers() {
        $query = "SELECT * FROM supplier";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierById($id) {
    $query = "SELECT * FROM supplier WHERE id_supplier = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    
    public function addSupplier($data) {
        $query = "INSERT INTO supplier (nama_supplier, kontak_supplier, alamat_supplier) 
                  VALUES (:nama, :kontak, :alamat)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama_supplier']);
        $stmt->bindParam(':kontak', $data['kontak_supplier']);
        $stmt->bindParam(':alamat', $data['alamat_supplier']);
        return $stmt->execute();
    }

    public function editSupplier($id, $data) {
        $query = "UPDATE supplier SET nama_supplier = :nama, kontak_supplier = :kontak, 
                  alamat_supplier = :alamat WHERE id_supplier = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nama', $data['nama_supplier']);
        $stmt->bindParam(':kontak', $data['kontak_supplier']);
        $stmt->bindParam(':alamat', $data['alamat_supplier']);
        return $stmt->execute();
    }

    public function deleteSupplier($id) {
        $query = "DELETE FROM supplier WHERE id_supplier = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

