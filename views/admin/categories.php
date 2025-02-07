<?php
require_once '../../config/database.php';
require_once '../../controllers/CategoryController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$categoryController = new CategoryController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$categories = $categoryController->getAllCategories();
?>

<?php
// SweetAlert untuk Notifikasi CRUD
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";
    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire('Berhasil!', 'Kategori berhasil ditambahkan!', 'success');";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire('Berhasil!', 'Kategori berhasil diperbarui!', 'success');";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire('Berhasil!', 'Kategori berhasil dihapus!', 'success');";
    } elseif ($_SESSION['alert'] == 'failed') {
        echo "Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi!', 'error');";
    }
    echo "});</script>";
    unset($_SESSION['alert']);
}
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Kelola Kategori</h1>
                    <a href="add_category.php" class="btn btn-primary">Tambah Kategori</a>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($category['nama_kategori']); ?></td>
                                                <td>
                                                    <a href="edit_category.php?id=<?= $category['id_kategori']; ?>" class="btn btn-info btn-circle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle"
                                                        onclick="confirmDelete(<?= $category['id_kategori']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada kategori yang tersedia.</td>
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
    function confirmDelete(categoryId) {
        Swal.fire({
            title: "Yakin ingin menghapus kategori ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/category_actions.php?action=delete&id=${categoryId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Berhasil!", "Kategori telah dihapus.", "success")
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            Swal.fire("Error!", data.message || "Terjadi kesalahan.", "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Terjadi kesalahan dalam komunikasi dengan server.", "error");
                    });
            }
        });
    }
</script>