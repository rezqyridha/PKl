<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    if ($productController->deleteProduct($productId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the product.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
