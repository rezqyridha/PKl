<?php

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();


$query = "SELECT COALESCE(SUM(total_harga), 0) AS total_hari_ini FROM penjualan WHERE DATE(tanggal_penjualan) = CURDATE()";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($row);
