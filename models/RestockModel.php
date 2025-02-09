<?php

class RestockModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; // Inisialisasi koneksi database
    }

    // Mendapatkan semua data restock
    public function getAllRestock()
    {
        $query = "SELECT restock.id_restock, restock.id_produk, produk.nama_produk, restock.id_supplier, supplier.nama, 
                     restock.tanggal_restock, restock.jumlah_ditambahkan, restock.harga_per_unit, restock.total_biaya, 
                     restock.created_at, restock.updated_at
              FROM restock 
              LEFT JOIN produk ON restock.id_produk = produk.id_produk 
              LEFT JOIN supplier ON restock.id_supplier = supplier.id_supplier 
              ORDER BY restock.tanggal_restock DESC";

        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRestockToday()
    {
        $query = "SELECT COUNT(*) AS total_restock FROM restock WHERE DATE(tanggal_restock) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_restock'] ?? 0;
    }


    // Mendapatkan data restock berdasarkan ID
    public function getRestockById($id)
    {
        $query = "SELECT * FROM restock WHERE id_restock = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menambahkan data restock baru
    public function addRestock($data)
    {
        $query = "INSERT INTO restock (id_produk, id_supplier, tanggal_restock, jumlah_ditambahkan, harga_per_unit, total_biaya) 
                  VALUES (:id_produk, :id_supplier, :tanggal_restock, :jumlah_ditambahkan, :harga_per_unit, :total_biaya)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id_produk', $data['id_produk'], PDO::PARAM_INT);
        $stmt->bindParam(':id_supplier', $data['id_supplier'], PDO::PARAM_INT);
        $stmt->bindParam(':tanggal_restock', $data['tanggal_restock']);
        $stmt->bindParam(':jumlah_ditambahkan', $data['jumlah_ditambahkan'], PDO::PARAM_INT);
        $stmt->bindParam(':harga_per_unit', $data['harga_per_unit'], PDO::PARAM_STR);
        $stmt->bindParam(':total_biaya', $data['total_biaya'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Memperbarui data restock berdasarkan ID
    public function updateRestock($id, $data)
    {
        $query = "UPDATE restock 
                  SET id_produk = :id_produk, 
                      id_supplier = :id_supplier, 
                      tanggal_restock = :tanggal_restock, 
                      jumlah_ditambahkan = :jumlah_ditambahkan, 
                      harga_per_unit = :harga_per_unit, 
                      total_biaya = :total_biaya 
                  WHERE id_restock = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id_produk', $data['id_produk'], PDO::PARAM_INT);
        $stmt->bindParam(':id_supplier', $data['id_supplier'], PDO::PARAM_INT);
        $stmt->bindParam(':tanggal_restock', $data['tanggal_restock']);
        $stmt->bindParam(':jumlah_ditambahkan', $data['jumlah_ditambahkan'], PDO::PARAM_INT);
        $stmt->bindParam(':harga_per_unit', $data['harga_per_unit'], PDO::PARAM_STR);
        $stmt->bindParam(':total_biaya', $data['total_biaya'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Menghapus data restock berdasarkan ID
    public function deleteRestock($id)
    {
        $query = "DELETE FROM restock WHERE id_restock = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
