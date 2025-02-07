<?php

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();


$query = "SELECT COUNT(*) AS produk_hampir_habis FROM produk WHERE stok <= 5";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($row);
