<?php
require_once '../config/database.php';
require_once 'ProductController.php';

session_start(); // Pastikan session dimulai

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

if ($action == 'add') {
    // Menangani aksi tambah produk
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

    if (!empty($name) && !empty($description) && !empty($category) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
        ];

        $result = $productController->addProduct($data);

        if ($result) {
            $_SESSION['alert'] = 'added'; // Set session untuk alert
            header("Location: ../views/admin/products.php");
            exit();
        } else {
            $_SESSION['alert'] = 'add_failed';
            header("Location: ../views/admin/products.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'invalid_input';
        header("Location: ../views/admin/products.php");
        exit();
    }
} elseif ($action == 'edit') {
    // Menangani aksi edit produk
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/products.php");
        exit();
    }

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

    if (!empty($name) && !empty($description) && !empty($category) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
        ];

        $result = $productController->editProduct($productId, $data);

        if ($result) {
            $_SESSION['alert'] = 'updated';
            header("Location: ../views/admin/products.php");
            exit();
        } else {
            $_SESSION['alert'] = 'update_failed';
            header("Location: ../views/admin/products.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'invalid_input';
        header("Location: ../views/admin/products.php");
        exit();
    }
} elseif ($action == 'delete') {
    // Menangani aksi delete produk
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/products.php");
        exit();
    }

    $result = $productController->deleteProduct($productId);

    if ($result) {
        $_SESSION['alert'] = 'deleted';
        header("Location: ../views/admin/products.php");
        exit();
    } else {
        $_SESSION['alert'] = 'delete_failed';
        header("Location: ../views/admin/products.php");
        exit();
    }
} else {
    // Jika aksi tidak valid
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/products.php");
    exit();
}
?>
