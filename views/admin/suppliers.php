<?php
require_once '../../config/database.php';
require_once '../../controllers/SupplierController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$controller = new SupplierController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);


$suppliers = $controller->getAllSuppliers();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({ 
        title: 'Berhasil!', 
        text: 'Supplier berhasil ditambahkan!', 
        icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({ 
        title: 'Terhapus!', 
        text: 'Supplier berhasil dihapus!', 
        icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({ 
        title: 'Berhasil!', 
        text: 'Supplier berhasil diperbarui!', 
        icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'no_change') {
        echo "Swal.fire({ 
        title: 'Tidak Ada Perubahan!', 
        text: 'Data yang Anda masukkan sama.', 
        icon: 'info' });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({ 
        title: 'Gagal!', 
        text: 'Gagal mengubah supplier!', 
        icon: 'error' });";
    }

    echo "});</script>";

    unset($_SESSION['alert']);
}
?>



<div id="wrapper">
    <?php
    $page = 'suppliers';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Kelola Supplier</h1>
                    <a href="add_supplier.php" class="btn btn-primary">Tambah Supplier</a>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Supplier</h6>
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($suppliers)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($supplier['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($supplier['kontak'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($supplier['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <a href="edit_supplier.php?id=<?= urlencode($supplier['id_supplier']); ?>" class="btn btn-info btn-circle">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle" onclick="confirmDelete(<?= $supplier['id_supplier']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada supplier yang tersedia.</td>
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

<script>
    function confirmDelete(supplierId) {
        Swal.fire({
            title: "Yakin ingin menghapus supplier ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/supplier_actions.php?action=delete&id=${supplierId}`, {
                        method: "GET"
                    })
                    .then(response => {
                        console.log("Response Status:", response.status);
                        return response.text(); // Ambil sebagai text untuk debugging
                    })
                    .then(text => {
                        console.log("Raw Response Text:", text); // Debugging
                        try {
                            let data = JSON.parse(text); // Konversi ke JSON
                            console.log("Response Data:", data);
                            if (data.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Supplier telah dihapus.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: data.message || "Terjadi kesalahan saat menghapus supplier.",
                                    icon: "error"
                                });
                            }
                        } catch (e) {
                            console.error("JSON Parse Error:", e);
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan dalam komunikasi dengan server. Periksa di Console (F12).",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error Fetching Data:", error);
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