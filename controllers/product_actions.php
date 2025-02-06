<?php
require_once '../config/database.php';
require_once 'ProductController.php';

session_start(); // Pastikan session dimulai

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

// Menangani aksi tambah produk
if ($action == 'add') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $satuan = isset($_POST['satuan']) ? $_POST['satuan'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

    // Validasi input
    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan, // Tambahkan satuan ke data
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
        $_SESSION['alert'] = 'invalid_input'; // Set alert jika input tidak lengkap
        header("Location: ../views/admin/products.php");
        exit();
    }
}

// Menangani aksi edit produk
elseif ($action == 'edit') {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/products.php");
        exit();
    }

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $satuan = isset($_POST['satuan']) ? $_POST['satuan'] : '';  // Ambil data satuan
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

    // Validasi input
    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan, // Sertakan satuan
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
}

// Menangani aksi hapus produk
elseif ($action == 'delete') {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId == 0) {
        echo json_encode(["success" => false, "message" => "ID produk tidak valid."]);
        exit();
    }

    $result = $productController->deleteProduct($productId);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Produk berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus produk."]);
    }
    exit();
} else {
    // Jika aksi tidak valid
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/products.php");
    exit();
}
