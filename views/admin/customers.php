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

// Notifikasi berdasarkan query string
$notification = "";
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'added') {
        $notification = "Pelanggan berhasil ditambahkan.";
    } elseif ($_GET['success'] == 'updated') {
        $notification = "Pelanggan berhasil diperbarui.";
    } elseif ($_GET['success'] == 'deleted') {
        $notification = "Pelanggan berhasil dihapus.";
    }
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] == 'add_failed') {
        $notification = "Gagal menambahkan pelanggan.";
    } elseif ($_GET['error'] == 'update_failed') {
        $notification = "Gagal memperbarui pelanggan.";
    } elseif ($_GET['error'] == 'delete_failed') {
        $notification = "Gagal menghapus pelanggan.";
    }
}
?>


<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manage Customers</h1>
                    <a href="add_customer.php" class="btn btn-primary">Add New Customer</a>
                </div>

                <?php if (!empty($notification)): ?>
                    <script>
                        alert("<?= htmlspecialchars($notification); ?>");
                    </script>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Customer List</h6>
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
                                                <a href="edit_customer.php?id=<?= $customer['id_pelanggan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="../../controllers/customer_actions.php?action=delete&id=<?= $customer['id_pelanggan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">Delete</a>
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


