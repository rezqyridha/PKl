<?php
require_once '../config/database.php';
require_once '../controllers/SupplierController.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

$database = new Database();
$db = $database->getConnection();
$controller = new SupplierController($db);

if ($aksi === 'tambah') {
    $nama = $_POST['nama'] ?? '';
    $kontak = $_POST['kontak'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    $data = ['nama' => $nama, 'kontak' => $kontak, 'alamat' => $alamat];
    $hasil = $controller->addSupplier($data);

    header("Location: ../views/admin/suppliers.php?" . ($hasil ? 'sukses=1' : 'gagal=tambah_gagal'));
} elseif ($aksi === 'ubah') {
    $id = $_GET['id'] ?? 0;
    $nama = $_POST['nama'] ?? '';
    $kontak = $_POST['kontak'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    $data = ['nama' => $nama, 'kontak' => $kontak, 'alamat' => $alamat];
    $hasil = $controller->editSupplier($id, $data);

    header("Location: ../views/admin/suppliers.php?" . ($hasil ? 'sukses=diubah' : 'gagal=ubah_gagal'));
} elseif ($aksi === 'hapus') {
    $id = $_GET['id'] ?? 0;
    $hasil = $controller->deleteSupplier($id);

    header("Location: ../views/admin/suppliers.php?" . ($hasil ? 'sukses=dihapus' : 'gagal=hapus_gagal'));
}
?>
