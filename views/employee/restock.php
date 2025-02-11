<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/RestockController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$restockController = new RestockController($db);
$restocks = $restockController->showAllRestock(); // Mengambil data restock menggunakan getAllRestock()

// Menampilkan notifikasi menggunakan SweetAlert
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    $alertMessages = [
        'restock_success' => ['title' => 'Berhasil!', 'text' => 'Restock berhasil ditambahkan dan stok diperbarui.', 'icon' => 'success'],
        'deleted' => ['title' => 'Terhapus!', 'text' => 'Data restock berhasil dihapus dan stok diperbarui.', 'icon' => 'success'],
        'updated' => ['title' => 'Berhasil!', 'text' => 'Data restock berhasil diperbarui!', 'icon' => 'success'],
        'no_change' => ['title' => 'Tidak Ada Perubahan!', 'text' => 'Data yang Anda masukkan sama dengan yang sudah ada.', 'icon' => 'info'],
        'update_failed' => ['title' => 'Gagal!', 'text' => 'Gagal memperbarui data restock.', 'icon' => 'error']
    ];

    if (isset($alertMessages[$_SESSION['alert']])) {
        $alert = $alertMessages[$_SESSION['alert']];
        echo "Swal.fire({ title: '{$alert['title']}', text: '{$alert['text']}', icon: '{$alert['icon']}' });";
    }

    echo "});</script>";
    unset($_SESSION['alert']); // Hapus session alert setelah ditampilkan
}
?>

<div id="wrapper">
    <?php
    $page = 'restock';
    include '../layouts/sidebar_karyawan.php';
    ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manajemen Restock</h1>
                    <a href="add_restock.php" class="btn btn-primary">Tambah Restock Baru</a>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Restock</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Satuan</th>
                                        <th>Tanggal Restock</th>
                                        <th>Jumlah Ditambahkan</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($restocks)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($restocks as $restock): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($restock['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($restock['nama_satuan']); ?></td>
                                                <td><?= htmlspecialchars($restock['tanggal_restock']); ?></td>
                                                <td><?= htmlspecialchars($restock['jumlah_ditambahkan']); ?> Botol</td>
                                                <td><?= htmlspecialchars($restock['nama']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data restock yang tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>