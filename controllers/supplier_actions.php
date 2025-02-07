<?php
require_once '../config/database.php';
require_once '../controllers/SupplierController.php';


session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_supplier = isset($_GET['id']) ? intval($_GET['id']) : 0;

$database = new Database();
$db = $database->getConnection();
$supplierController = new SupplierController($db);

// Menangani aksi tambah supplier
if ($action === 'add') {
    $nama = $_POST['nama'] ?? '';
    $kontak = $_POST['kontak'] ?? null;
    $alamat = $_POST['alamat'] ?? null;

    if (!empty($nama)) {
        $data = ['nama' => $nama, 'kontak' => $kontak, 'alamat' => $alamat];
        $result = $supplierController->addSupplier($data);
        $_SESSION['alert'] = $result ? 'added' : 'add_failed';
    } else {
        $_SESSION['alert'] = 'invalid_input';
    }

    header("Location: ../views/admin/suppliers.php");
    exit();
}

// Menangani aksi edit supplier
elseif ($action === 'edit') {
    $id = $_GET['id'] ?? 0;
    $nama = $_POST['nama'] ?? '';
    $kontak = $_POST['kontak'] ?? null;
    $alamat = $_POST['alamat'] ?? null;

    if (!empty($nama)) {
        $data = ['nama' => $nama, 'kontak' => $kontak, 'alamat' => $alamat];
        $result = $supplierController->editSupplier($id, $data);
        $_SESSION['alert'] = $result ? 'updated' : 'no_change';
    } else {
        $_SESSION['alert'] = 'update_failed';
    }

    header("Location: ../views/admin/suppliers.php");
    exit();
}

// Menangani aksi hapus supplier
elseif ($action === 'delete') {
    if ($id_supplier === 0) {
        echo json_encode(["success" => false, "message" => "ID Supplier tidak valid."]);
        exit();
    }

    $result = $supplierController->deleteSupplier($id_supplier);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Supplier berhasil dihapus."]);
    } else {
        error_log("Gagal menghapus Supplier ID: " . $id_supplier);
        echo json_encode(["success" => false, "message" => "Gagal menghapus supplier."]);
    }
    exit();
}
