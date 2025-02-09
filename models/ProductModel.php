<?php
class ProductModel
{
    const STOK_MINIMUM = 10; // Konstanta untuk batas stok minimum
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /* ============================= */
    /*      FUNGSI CRUD PRODUK       */
    /* ============================= */

    /**
     * Mengambil semua produk dengan kategori dan satuan
     * @return array Daftar semua produk
     */
    public function getAllProducts()
    {
        $query = "
            SELECT produk.*, kategori.nama_kategori, satuan.nama_satuan
            FROM produk
            LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori
            LEFT JOIN satuan ON produk.id_satuan = satuan.id_satuan
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil produk berdasarkan ID
     * @param int $id
     * @return array|null Data produk atau null jika tidak ditemukan
     */
    public function getProductById($id)
    {
        $query = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Memperbarui stok produk berdasarkan ID
     * @param int $id
     * @param int $newStock
     * @return bool True jika berhasil, False jika gagal
     */
    public function updateStock($id, $newStock)
    {
        $query = "UPDATE produk SET stok = :stok WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':stok', $newStock);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Menambahkan produk baru
     * @param array $data Data produk yang akan ditambahkan
     * @return bool True jika berhasil, False jika gagal
     */
    public function addProduct($data)
    {
        try {
            $query = "INSERT INTO produk (nama_produk, deskripsi, id_kategori, id_satuan, harga, stok)
                      VALUES (:name, :description, :category, :satuan, :price, :stock)";
            $stmt = $this->db->prepare($query);

            // Bind parameter
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':satuan', $data['satuan']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':stock', $data['stock']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Gagal menambahkan produk: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Memperbarui data produk berdasarkan ID
     * @param int $id ID produk
     * @param array $data Data produk yang diperbarui
     * @return bool True jika berhasil, False jika gagal
     */
    public function updateProduct($id, $data)
    {
        try {
            $query = "UPDATE produk 
                      SET nama_produk = :name, deskripsi = :description, id_kategori = :category, 
                          id_satuan = :satuan, harga = :price, stok = :stock 
                      WHERE id_produk = :id";
            $stmt = $this->db->prepare($query);

            // Bind parameter
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':satuan', $data['satuan']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':stock', $data['stock']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Gagal memperbarui produk: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Menghapus produk berdasarkan ID
     * @param int $id ID produk
     * @return bool True jika berhasil, False jika gagal
     */
    public function deleteProduct($id)
    {
        $query = "DELETE FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /* ============================= */
    /*     FUNGSI UNTUK DASHBOARD     */
    /* ============================= */

    /**
     * Menghitung total stok semua produk
     * @return int Total stok
     */
    public function getTotalStock()
    {
        $query = "SELECT SUM(stok) AS total_stock FROM produk";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_stock'] ?? 0;
    }

    /**
     * Menghitung jumlah produk dengan stok rendah
     * @return int Total produk dengan stok rendah
     */
    public function getLowStockCount()
    {
        $query = "SELECT COUNT(*) AS total FROM produk WHERE stok <= " . self::STOK_MINIMUM;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Mengambil daftar produk dengan stok rendah
     * @return array Daftar produk dengan stok rendah
     */
    public function getLowStockProducts()
    {
        $query = "SELECT id_produk, nama_produk, stok FROM produk WHERE stok <= " . self::STOK_MINIMUM . " ORDER BY stok ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================= */
    /*     FUNGSI PENGOLAHAN DATA    */
    /* ============================= */

    /**
     * Mengambil produk berdasarkan kategori
     * @param int $categoryId ID kategori
     * @return array Daftar produk dalam kategori tertentu
     */
    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT * FROM produk WHERE id_kategori = :categoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua kategori produk
     * @return array Daftar kategori produk
     */
    public function getAllCategories()
    {
        $query = "SELECT * FROM kategori";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua satuan produk
     * @return array Daftar satuan produk
     */
    public function getAllSatuan()
    {
        $query = "SELECT * FROM satuan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
