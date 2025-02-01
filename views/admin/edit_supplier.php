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
$supplierController = new SupplierController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$supplier = $supplierController->getSupplierById($id_supplier);



$notification = "";

if (!$supplier) {
    header("Location: suppliers.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supplier = $_POST['nama_supplier'] ?? '';
    $kontak_supplier = $_POST['kontak_supplier'] ?? '';
    $alamat_supplier = $_POST['alamat_supplier'] ?? '';

    if (!empty($nama_supplier)) {
        $result = $supplierController->editSupplier($id_supplier, [
            'nama' => $nama_supplier,
            'kontak' => $kontak_supplier,
            'alamat' => $alamat_supplier,
        ]);
        if ($result) {
            header("Location: suppliers.php?success=edit");
            exit();
        } else {
            $notification = "Gagal mengubah supplier.";
        }
    } else {
        $notification = "Nama supplier wajib diisi.";
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
                    <h1 class="h3 text-gray-800">Edit Supplier</h1>
                    <a href="suppliers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if (!empty($notification)): ?>
                    <script>
                        alert("<?= htmlspecialchars($notification); ?>");
                    </script>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Supplier</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nama_supplier">Nama Supplier</label>
                                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= htmlspecialchars($supplier['nama_supplier']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kontak_supplier">Kontak</label>
                                <input type="text" class="form-control" id="kontak_supplier" name="kontak_supplier" value="<?= htmlspecialchars($supplier['kontak_supplier']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat_supplier">Alamat</label>
                                <textarea class="form-control" id="alamat_supplier" name="alamat_supplier"><?= htmlspecialchars($supplier['alamat_supplier']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>
