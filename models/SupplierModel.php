<?php
class SupplierModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllSuppliers()
    {
        $query = "SELECT * FROM supplier ORDER BY id_supplier ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierById($id)
    {
        $query = "SELECT * FROM supplier WHERE id_supplier = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSupplier($data)
    {
        try {
            // Pastikan nama tidak kosong karena tidak boleh NULL
            if (empty($data['nama'])) {
                throw new Exception("Nama supplier wajib diisi.");
            }

            // Jika kontak atau alamat kosong, ubah menjadi NULL
            $kontak = !empty($data['kontak']) ? $data['kontak'] : null;
            $alamat = !empty($data['alamat']) ? $data['alamat'] : null;

            $query = "INSERT INTO supplier (nama, kontak, alamat) 
                      VALUES (:nama, :kontak, :alamat)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':kontak', $kontak, PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $alamat, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Gagal menambahkan supplier: " . $e->getMessage());
            return false;
        }
    }

    public function updateSupplier($id, $data)
    {
        try {
            // Pastikan nama tidak kosong karena tidak boleh NULL
            if (empty($data['nama'])) {
                throw new Exception("Nama supplier wajib diisi.");
            }

            // Jika kontak atau alamat kosong, ubah menjadi NULL
            $kontak = !empty($data['kontak']) ? $data['kontak'] : null;
            $alamat = !empty($data['alamat']) ? $data['alamat'] : null;

            $query = "UPDATE supplier 
                      SET nama = :nama, kontak = :kontak, alamat = :alamat 
                      WHERE id_supplier = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama', $data['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':kontak', $kontak, PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $alamat, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->rowCount() > 0; // Pastikan ada perubahan data
        } catch (Exception $e) {
            error_log("Gagal memperbarui supplier: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSupplier($id)
    {
        $query = "DELETE FROM supplier WHERE id_supplier = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
