<?php

class RestockModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }


    public function getAllRestock()
    {
        try {
            $query = "
                SELECT restock.id_restock, produk.nama_produk, satuan.nama_satuan, supplier.nama AS nama,
                       restock.tanggal_restock, restock.jumlah_ditambahkan, restock.harga_per_unit, restock.total_biaya,
                       restock.created_at, restock.updated_at
                FROM restock
                LEFT JOIN produk ON restock.id_produk = produk.id_produk
                LEFT JOIN satuan ON produk.id_satuan = satuan.id_satuan
                LEFT JOIN supplier ON restock.id_supplier = supplier.id_supplier
                ORDER BY restock.tanggal_restock DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllRestock: " . $e->getMessage());
            return [];
        }
    }

    public function getRestockById($id)
    {
        try {
            $query = "SELECT * FROM restock WHERE id_restock = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRestockById: " . $e->getMessage());
            return null;
        }
    }

    public function addRestock($data)
    {
        try {
            $query = "INSERT INTO restock (id_produk, id_supplier, tanggal_restock, jumlah_ditambahkan, harga_per_unit, total_biaya)
                      VALUES (:id_produk, :id_supplier, :tanggal_restock, :jumlah_ditambahkan, :harga_per_unit, :total_biaya)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id_produk', $data['id_produk'], PDO::PARAM_INT);
            $stmt->bindParam(':id_supplier', $data['id_supplier'], PDO::PARAM_INT);
            $stmt->bindParam(':tanggal_restock', $data['tanggal_restock']);
            $stmt->bindParam(':jumlah_ditambahkan', $data['jumlah_ditambahkan'], PDO::PARAM_INT);
            $stmt->bindParam(':harga_per_unit', $data['harga_per_unit']);
            $stmt->bindParam(':total_biaya', $data['total_biaya']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in addRestock: " . $e->getMessage());
            return false;
        }
    }

    public function updateRestock($id, $data)
    {
        try {
            $query = "UPDATE restock 
                      SET id_produk = :id_produk, id_supplier = :id_supplier, tanggal_restock = :tanggal_restock, 
                          jumlah_ditambahkan = :jumlah_ditambahkan, harga_per_unit = :harga_per_unit, total_biaya = :total_biaya
                      WHERE id_restock = :id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id_produk', $data['id_produk'], PDO::PARAM_INT);
            $stmt->bindParam(':id_supplier', $data['id_supplier'], PDO::PARAM_INT);
            $stmt->bindParam(':tanggal_restock', $data['tanggal_restock']);
            $stmt->bindParam(':jumlah_ditambahkan', $data['jumlah_ditambahkan'], PDO::PARAM_INT);
            $stmt->bindParam(':harga_per_unit', $data['harga_per_unit']);
            $stmt->bindParam(':total_biaya', $data['total_biaya']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in updateRestock: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRestock($id)
    {
        try {
            $query = "DELETE FROM restock WHERE id_restock = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteRestock: " . $e->getMessage());
            return false;
        }
    }
}
