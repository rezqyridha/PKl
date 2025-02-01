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


<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manage Supplier</h1>
                    <a href="add_supplier.php" class="btn btn-primary">Tambah Supplier</a>
                </div>

                <?php if (!empty($notification)): ?>
                    <script>
                        alert("<?= htmlspecialchars($notification); ?>");
                    </script>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Supplier List</h6>
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
                                                <td><?= htmlspecialchars($supplier['nama_supplier']); ?></td>
                                                <td><?= htmlspecialchars($supplier['kontak_supplier']); ?></td>
                                                <td><?= htmlspecialchars($supplier['alamat_supplier']); ?></td>
                                                <td>
                                                    <a href="edit_supplier.php?id=<?= $supplier['id_supplier']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="../../controllers/supplier_actions.php?action=delete&id=<?= $supplier['id_supplier']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengelete supplier ini?');">Delete</a>
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


