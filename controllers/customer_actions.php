<?php
require_once '../config/database.php';
require_once 'CustomerController.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$customerController = new CustomerController($db);

// Menangani aksi tambah pelanggan
if ($action == 'add') {
    $nama_pelanggan = isset($_POST['nama_pelanggan']) ? $_POST['nama_pelanggan'] : '';
    $kontak = isset($_POST['kontak']) ? $_POST['kontak'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $kota = isset($_POST['kota']) ? $_POST['kota'] : '';
    $provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : '';

    // Validasi input
    if (!empty($nama_pelanggan) && !empty($kontak) && !empty($alamat) && !empty($kota) && !empty($provinsi)) {
        $data = [
            'nama_pelanggan' => $nama_pelanggan,
            'kontak' => $kontak,
            'alamat' => $alamat,
            'kota' => $kota,
            'provinsi' => $provinsi
        ];

        // Menambahkan pelanggan menggunakan CustomerController
        $result = $customerController->addCustomer($data);
        if ($result) {
            $_SESSION['alert'] = 'added';
            header("Location: ../views/admin/customers.php");
            exit();
        } else {
            $_SESSION['alert'] = 'add_failed';
            header("Location: ../views/admin/customers.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'invalid_input';
        header("Location: ../views/admin/customers.php");
        exit();
    }
}

// Menangani aksi edit pelanggan
elseif ($action == 'edit') {
    $customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($customerId == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/customers.php");
        exit();
    }

    $nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
    $kontak = $_POST['kontak'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $kota = $_POST['kota'] ?? '';
    $provinsi = $_POST['provinsi'] ?? '';

    if (!empty($nama_pelanggan) && !empty($kontak) && !empty($alamat) && !empty($kota) && !empty($provinsi)) {
        $data = [
            'nama_pelanggan' => $nama_pelanggan,
            'kontak' => $kontak,
            'alamat' => $alamat,
            'kota' => $kota,
            'provinsi' => $provinsi,
        ];

        $result = $customerController->editCustomer($customerId, $data);

        if ($result) {
            $_SESSION['alert'] = 'updated';
        } else {
            $_SESSION['alert'] = 'no_change';
        }

        header("Location: ../views/admin/customers.php");
        exit();
    } else {
        $_SESSION['alert'] = 'update_failed';
        header("Location: ../views/admin/customers.php");
        exit();
    }
}


// Menangani aksi hapus pelanggan
elseif ($action == 'delete') {
    $customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($customerId == 0) {
        echo json_encode(["success" => false, "message" => "ID Pelanggan tidak valid."]);
        exit();
    }

    $result = $customerController->deleteCustomer($customerId);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Pelanggan berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus pelanggan."]);
    }
    exit();
} else {
    // Jika aksi tidak valid
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/customers.php");
    exit();
}
