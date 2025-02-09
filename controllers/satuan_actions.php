<?php
require_once '../config/database.php';
require_once 'SatuanController.php';

session_start(); // Pastikan session dimulai

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$satuanController = new SatuanController($db);

// Menangani aksi tambah satuan
if ($action == 'add') {
    $nama_satuan = $_POST['nama_satuan'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    if (!empty($nama_satuan)) {
        $result = $satuanController->create($nama_satuan, $deskripsi);
        $_SESSION['alert'] = $result ? 'added' : 'add_failed';
    } else {
        $_SESSION['alert'] = 'invalid_input';
    }
    header("Location: ../views/admin/satuan.php");
    exit();
}

// Menangani aksi edit satuan
elseif ($action == 'edit') {
    $id = intval($_GET['id'] ?? 0);

    if ($id == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/satuan.php");
        exit();
    }

    $nama_satuan = $_POST['nama_satuan'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    if (!empty($nama_satuan)) {
        $result = $satuanController->edit($id, $nama_satuan, $deskripsi);
        $_SESSION['alert'] = $result ? 'updated' : 'no_change';
    } else {
        $_SESSION['alert'] = 'update_failed';
    }
    header("Location: ../views/admin/satuan.php");
    exit();
}

// Menangani aksi hapus satuan
elseif ($action == 'delete') {
    $id = intval($_GET['id'] ?? 0);

    if ($id == 0) {
        echo json_encode(["success" => false, "message" => "ID satuan tidak valid."]);
        exit();
    }

    $result = $satuanController->delete($id);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Satuan berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus satuan."]);
    }
    exit();
} else {
    // Jika aksi tidak valid
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/satuan.php");
    exit();
}
