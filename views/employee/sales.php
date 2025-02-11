<?php
require_once '../../config/database.php';
require_once '../../controllers/SalesController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$salesController = new SalesController($db);
$sales = $salesController->showAllSales(); // Menampilkan semua data penjualan

// Menampilkan SweetAlert berdasarkan session alert
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    switch ($_SESSION['alert']) {
        case 'added':
            echo "Swal.fire({ title: 'Berhasil!', text: 'Penjualan berhasil ditambahkan!', icon: 'success' });";
            break;
        case 'add_failed':
            echo "Swal.fire({ title: 'Gagal!', text: 'Gagal menambahkan penjualan!', icon: 'error' });";
            break;
        case 'validation_error':
            echo "Swal.fire({ title: 'Gagal!', text: 'Data penjualan tidak valid!', icon: 'error' });";
            break;
        default:
            echo "Swal.fire({ title: 'Peringatan!', text: 'Aksi tidak valid!', icon: 'warning' });";
            break;
    }

    echo "});</script>";

    unset($_SESSION['alert']); // Hapus session alert setelah ditampilkan
}
?>

<div id="wrapper">
    <!-- Sidebar -->
    <?php
    $page = 'sales';
    include '../layouts/sidebar_karyawan.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manajemen Penjualan</h1>
                    <a href="add_sales.php" class="btn btn-primary">Tambah Penjualan Baru</a>
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
                                        <th>Satuan</th>
                                        <th> Pelanggan</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Total Harga (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($sales)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($sales as $sale): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($sale['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($sale['nama_satuan']); ?></td> <!-- Menampilkan satuan produk -->
                                                <td><?= htmlspecialchars($sale['nama_pelanggan']); ?></td>
                                                <td><?= date('d-m-Y', strtotime($sale['tanggal_penjualan'])); ?></td>
                                                <td><?= htmlspecialchars($sale['jumlah_terjual']); ?> Botol</td>
                                                <td>Rp <?= number_format($sale['total_harga'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada penjualan yang tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php include '../layouts/footer.php'; ?>
    </div>
    <!-- End Content Wrapper -->
</div>