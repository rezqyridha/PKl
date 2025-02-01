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

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$categoryController = new CategoryController($db);
$categories = $categoryController->getAllCategories();
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
            title: 'Good job!',
            text: 'Product successfully added!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({
            title: 'Deleted!',
            text: 'Product successfully deleted!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({
            title: 'Updated!',
            text: 'Product successfully updated!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'add_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to add product!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'delete_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to delete product!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to update product!',
            icon: 'error'
        });";
    }

    echo "});</script>";
    unset($_SESSION['alert']); // Hapus session alert setelah ditampilkan
}
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manage Categories</h1>
                    <a href="add_category.php" class="btn btn-primary">Add New Category</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $index => $category): ?>
                                            <tr>
                                                <td><?= $index + 1; ?></td>
                                                <td><?= htmlspecialchars($category['nama_kategori']); ?></td>
                                                <td>
                                                    <a href="edit_category.php?id=<?= $category['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $category['id_kategori']; ?>)">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No categories available.</td>
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


