<?php
require_once '../config/database.php';
require_once 'CustomerController.php';

session_start();

$database = new Database();
$db = $database->getConnection();
$customerController = new CustomerController($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {
    $data = [
        'nama_pelanggan' => $_POST['nama_pelanggan'] ?? '',
        'kontak' => $_POST['kontak'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
        'kota' => $_POST['kota'] ?? '',
        'provinsi' => $_POST['provinsi'] ?? ''
    ];

    $result = $customerController->addCustomer($data);

    if ($result) {
        header("Location: ../views/admin/customers.php?success=added");
    } else {
        header("Location: ../views/admin/customers.php?error=add_failed");
    }
    exit();
}

if ($action == 'edit') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $data = [
        'nama_pelanggan' => $_POST['nama_pelanggan'] ?? '',
        'kontak' => $_POST['kontak'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
        'kota' => $_POST['kota'] ?? '',
        'provinsi' => $_POST['provinsi'] ?? ''
    ];

    $result = $customerController->editCustomer($id, $data);

    if ($result) {
        header("Location: ../views/admin/customers.php?success=updated");
    } else {
        header("Location: ../views/admin/customers.php?error=update_failed");
    }
    exit();
}

if ($action == 'delete') {
    $customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($customerId > 0) {
        $result = $customerController->deleteCustomer($customerId);
        if ($result) {
            header("Location: ../views/admin/customers.php?success=deleted");
        } else {
            header("Location: ../views/admin/customers.php?error=delete_failed");
        }
    } else {
        header("Location: ../views/admin/customers.php?error=invalid_id");
    }
    exit();
}
?>
