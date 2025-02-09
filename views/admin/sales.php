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
$sales = $salesController->showAllSales();

// Menampilkan SweetAlert berdasarkan session alert
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    switch ($_SESSION['alert']) {
        case 'added':
            echo "Swal.fire({ title: 'Berhasil!', text: 'Penjualan berhasil ditambahkan!', icon: 'success' });";
            break;
        case 'updated':
            echo "Swal.fire({ title: 'Berhasil!', text: 'Penjualan berhasil diubah!', icon: 'success' });";
            break;
        case 'deleted':
            echo "Swal.fire({ title: 'Terhapus!', text: 'Penjualan berhasil dihapus!', icon: 'success' });";
            break;
        case 'no_change':
            echo "Swal.fire({ title: 'Tidak Ada Perubahan!', text: 'Data yang Anda masukkan sama dengan yang sudah ada.',
                icon: 'info' });";
            break;
        case 'insufficient_stock':
            echo "Swal.fire({ title: 'Gagal!', text: 'Stok tidak mencukupi untuk penjualan ini.', icon: 'error' });";
            break;
        case 'add_failed':
            echo "Swal.fire({ title: 'Gagal!', text: 'Gagal menambahkan penjualan!', icon: 'error' });";
            break;
        case 'update_failed':
            echo "Swal.fire({ title: 'Gagal!', text: 'Gagal memperbarui penjualan!', icon: 'error' });";
            break;
        case 'delete_failed':
            echo "Swal.fire({ title: 'Gagal!', text: 'Gagal menghapus penjualan!', icon: 'error' });";
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
    include '../layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Kelola Penjualan</h1>
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
                                        <th>Pelanggan</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Total Harga (Rp)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($sales)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($sales as $sale): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($sale['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($sale['nama_pelanggan']); ?></td>
                                                <td><?php
                                                    $tanggal = DateTime::createFromFormat('Y-m-d', $sale['tanggal_penjualan']);
                                                    echo $tanggal ? $tanggal->format('d-m-Y') : '-';
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($sale['jumlah_terjual'] ?? '0'); ?> unit</td>
                                                <td>Rp <?= number_format((float) ($sale['total_harga'] ?? 0), 0, ',', '.'); ?></td>
                                                <td>
                                                    <a href="edit_sales.php?id=<?= $sale['id_penjualan']; ?>"
                                                        class="btn btn-info btn-circle">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle"
                                                        onclick="confirmDelete(<?= $sale['id_penjualan']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada penjualan yang tersedia.</td>
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

<!-- Script Delete -->
<script>
    function confirmDelete(saleId) {
        Swal.fire({
            title: "Yakin ingin menghapus penjualan ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/sales_actions.php?action=delete&id=${saleId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Penjualan telah dihapus.",
                                icon: "success"
                            }).then(() => {
                                location.reload(); // Reload halaman
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Terjadi kesalahan saat menghapus penjualan.",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan dalam komunikasi dengan server.",
                            icon: "error"
                        });
                    });
            }
        });
    }
</script>