<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Database connected successfully!";
} else {
    echo "Failed to connect to database.";
}
?>
