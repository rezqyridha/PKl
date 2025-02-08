<?php

/**
 * Kelas Database dengan konfigurasi aman dan performa yang ditingkatkan
 * Menggunakan variabel lingkungan (.env) untuk menyimpan kredensial database.
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';


class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        // Memuat variabel dari .env
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_PERSISTENT => true, // Menggunakan koneksi persistent untuk performa
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Mode error yang lebih baik
                ]
            );
        } catch (PDOException $exception) {
            // Menyimpan pesan error ke log file, bukan menampilkannya ke pengguna
            error_log($exception->getMessage(), 3, __DIR__ . "/../logs/error.log");
            echo "Database connection error.";
        }

        return $this->conn;
    }
}
