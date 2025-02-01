<?php
class CustomerModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCustomers() {
        $query = "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id) {
        $query = "SELECT * FROM pelanggan WHERE id_pelanggan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function addCustomer($data) {
        $query = "INSERT INTO pelanggan (nama_pelanggan, kontak, alamat, kota, provinsi, created_at, updated_at)
                  VALUES (:nama_pelanggan, :kontak, :alamat, :kota, :provinsi, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama_pelanggan', $data['nama_pelanggan']);
        $stmt->bindParam(':kontak', $data['kontak']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kota', $data['kota']);
        $stmt->bindParam(':provinsi', $data['provinsi']);
        return $stmt->execute();
    }
    

    public function updateCustomer($id, $data) {
        $query = "UPDATE pelanggan 
                  SET nama_pelanggan = :nama_pelanggan, kontak = :kontak, alamat = :alamat, 
                      kota = :kota, provinsi = :provinsi, updated_at = NOW()
                  WHERE id_pelanggan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama_pelanggan', $data['nama_pelanggan']);
        $stmt->bindParam(':kontak', $data['kontak']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kota', $data['kota']);
        $stmt->bindParam(':provinsi', $data['provinsi']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    

    public function deleteCustomer($id) {
        $query = "DELETE FROM pelanggan WHERE id_pelanggan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
