<?php
class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM pengguna";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM pengguna WHERE id_pengguna = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getTotalEmployees()
    {
        $query = "SELECT COUNT(*) AS total_employees FROM pengguna WHERE role = 'karyawan'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_employees'] ?? 0;
    }


    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM pengguna WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Kembalikan data pengguna atau false jika tidak ditemukan
    }

    public function updateUserPartial($userId, $name = null, $email = null)
    {
        $fields = [];
        $params = [':userId' => $userId];

        if (!empty($name)) {
            $fields[] = "nama_lengkap = :name";
            $params[':name'] = $name;
        }
        if (!empty($email)) {
            $fields[] = "email = :email";
            $params[':email'] = $email;
        }

        if (count($fields) === 0) {
            return false; // Tidak ada field untuk diupdate
        }

        $query = "UPDATE pengguna SET " . implode(", ", $fields) . " WHERE id_pengguna = :userId";
        $stmt = $this->db->prepare($query); // Menggunakan $this->db, bukan $this->conn

        return $stmt->execute($params);
    }

    public function checkUsernameOrEmailExists($username, $email)
    {
        $query = "SELECT id_pengguna FROM pengguna WHERE username = :username OR email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function addUser($data)
    {
        $query = "INSERT INTO pengguna (username, password, nama_lengkap, email, kontak, role) 
              VALUES (:username, :password, :nama_lengkap, :email, :kontak, :role)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':kontak', $data['kontak']);
        $stmt->bindParam(':role', $data['role']);

        return $stmt->execute();
    }
}
