<?php
require_once '../../config/database.php';
require_once '../../controllers/SalesController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$salesController = new SalesController($db);

// Mendapatkan semua penjualan
$sales = $salesController->showAllSales();

// Tampilkan alert berdasarkan session
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    // Menampilkan alert sukses
    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({
            title: 'Berhasil!',
            text: 'Penjualan berhasil ditambahkan!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({
            title: 'Berhasil!',
            text: 'Penjualan berhasil diubah!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({
            title: 'Terhapus!',
            text: 'Penjualan berhasil dihapus!',
            icon: 'success'
        });";
    }

    // Menampilkan alert gagal
    elseif ($_SESSION['alert'] == 'add_failed') {
        echo "Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menambahkan penjualan!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({
            title: 'Gagal!',
            text: 'Gagal mengubah penjualan!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'delete_failed') {
        echo "Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menghapus penjualan!',
            icon: 'error'
        });";
    }

    echo "});</script>";

    // Menghapus session alert setelah ditampilkan
    unset($_SESSION['alert']);
}
?>

<div id="wrapper">
    <!-- Sidebar -->
    <?php include '../layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Daftar Penjualan</h1>
                    <a href="add_sale.php" class="btn btn-primary">Tambah Penjualan Baru</a>
                </div>

                <!-- Sales Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales as $key => $sale): ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= htmlspecialchars($sale['nama_produk']); ?></td>
                                            <td><?= htmlspecialchars($sale['nama_pelanggan']); ?></td>
                                            <td><?= htmlspecialchars($sale['tanggal_penjualan']); ?></td>
                                            <td><?= htmlspecialchars($sale['jumlah_terjual']); ?> unit</td> <!-- Menambahkan satuan unit -->
                                            <td>Rp <?= number_format($sale['total_harga'],); ?></td>
                                            <td>
                                                <a href="edit_sale.php?id=<?= $sale['id_penjualan']; ?>" class="btn btn-info btn-circle">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                <button class="btn btn-danger btn-circle" onclick="confirmDelete(<?= $sale['id_penjualan']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>