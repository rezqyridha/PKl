<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/CustomerController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Koneksi ke database dan mengambil data pelanggan
$database = new Database();
$db = $database->getConnection();
$customerController = new CustomerController($db);
$customers = $customerController->getAllCustomers();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);
?>

<?php
// Cek apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tampilkan alert berdasarkan session
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({
        title: 'Berhasil!',
        text: 'Pelanggan berhasil ditambahkan!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({
        title: 'Terhapus!',
        text: 'Pelanggan berhasil dihapus!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({
        title: 'Berhasil!',
        text: 'Pelanggan berhasil diubah!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'add_failed') {
        echo "Swal.fire({
        title: 'Gagal!',
        text: 'Gagal menambahkan pelanggan!',
        icon: 'error'
    });";
    } elseif ($_SESSION['alert'] == 'delete_failed') {
        echo "Swal.fire({
        title: 'Gagal!',
        text: 'Gagal menghapus pelanggan!',
        icon: 'error'
    });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({
        title: 'Gagal!',
        text: 'Gagal mengubah pelanggan!',
        icon: 'error'
    });";
    }
    echo "});</script>";
    unset($_SESSION['alert']); // Hapus session alert setelah ditampilkan
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

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Kelola Pelanggan</h1>
                    <a href="add_customer.php" class="btn btn-primary">Tambah Pelanggan Baru</a>
                </div>

                <!-- Customer Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kontak</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Provinsi</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($customers)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($customers as $customer): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($customer['nama_pelanggan']); ?></td>
                                                <td><?= htmlspecialchars($customer['kontak']); ?></td>
                                                <td><?= htmlspecialchars($customer['alamat']); ?></td>
                                                <td><?= htmlspecialchars($customer['kota']); ?></td>
                                                <td><?= htmlspecialchars($customer['provinsi']); ?></td>
                                                <td>
                                                    <a href="edit_customer.php?id=<?= $customer['id_pelanggan']; ?>"
                                                        class="btn btn-info btn-circle ">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle"
                                                        onclick="confirmDelete(<?= $customer['id_pelanggan']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada pelanggan yang tersedia.</td>
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

<!-- Script Delete -->
<script>
    function confirmDelete(customerId) {
        Swal.fire({
            title: "Yakin ingin menghapus pelanggan ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim request ke product_actions.php (bukan file terpisah)
                fetch(`../../controllers/customer_actions.php?action=delete&id=${customerId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json()) // Pastikan PHP mengembalikan JSON
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Pelanggan telah dihapus.",
                                icon: "success"
                            }).then(() => {
                                location.reload(); // Reload halaman setelah berhasil
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Terjadi kesalahan saat menghapus produk.",
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