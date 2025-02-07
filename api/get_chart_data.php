<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT DAYNAME(tanggal_penjualan) as hari, 
                 SUM(jumlah_terjual) as total_produk, 
                 SUM(total_harga) as total_penjualan 
          FROM penjualan 
          GROUP BY hari 
          ORDER BY FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

$stmt = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
