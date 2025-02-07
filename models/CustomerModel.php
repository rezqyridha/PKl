<?php
class CustomerModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCustomers()
    {
        $query = "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id)
    {
        $query = "SELECT * FROM pelanggan WHERE id_pelanggan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function addCustomer($data)
    {
        try {
            $query = "INSERT INTO pelanggan (nama_pelanggan, kontak, alamat, kota, provinsi)
                  VALUES (:nama_pelanggan, :kontak, :alamat, :kota, :provinsi)";
            $stmt = $this->db->prepare($query);

            // Bind parameter
            $stmt->bindParam(':nama_pelanggan', $data['nama_pelanggan']);
            $stmt->bindParam(':kontak', $data['kontak']);
            $stmt->bindParam(':alamat', $data['alamat']);
            $stmt->bindParam(':kota', $data['kota']);
            $stmt->bindParam(':provinsi', $data['provinsi']);

            // Debugging: Menampilkan data yang akan dimasukkan
            echo "<pre>";
            var_dump($data);
            echo "</pre>";

            // Eksekusi query
            if (!$stmt->execute()) {
                // Debugging: Menampilkan error jika gagal eksekusi query
                echo "<pre>";
                var_dump($stmt->errorInfo());
                echo "</pre>";
                return false; // Jika eksekusi gagal, return false
            }
            return true; // Berhasil ditambahkan
        } catch (PDOException $e) {
            // Debugging: Menampilkan pesan error exception
            echo "Gagal menambahkan pelanggan: " . $e->getMessage();
            return false;
        }
    }




    public function updateCustomer($id, $data)
    {
        try {
            $query = "UPDATE pelanggan 
                  SET nama_pelanggan = :nama_pelanggan, kontak = :kontak, alamat = :alamat, 
                      kota = :kota, provinsi = :provinsi
                  WHERE id_pelanggan = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nama_pelanggan', $data['nama_pelanggan']);
            $stmt->bindParam(':kontak', $data['kontak']);
            $stmt->bindParam(':alamat', $data['alamat']);
            $stmt->bindParam(':kota', $data['kota']);
            $stmt->bindParam(':provinsi', $data['provinsi']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            // Periksa apakah ada baris yang diperbarui
            if ($stmt->rowCount() > 0) {
                return true; // Data berubah
            } else {
                return false; // Tidak ada perubahan
            }
        } catch (PDOException $e) {
            error_log("Gagal mengupdate pelanggan: " . $e->getMessage());
            return false;
        }
    }



    public function deleteCustomer($id)
    {
        $query = "DELETE FROM pelanggan WHERE id_pelanggan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
