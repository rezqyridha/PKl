<?php
require_once '../../config/database.php';
require_once '../../controllers/SupplierController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$supplierController = new SupplierController($db);

$notification = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supplier = $_POST['nama_supplier'] ?? '';
    $kontak_supplier = $_POST['kontak_supplier'] ?? '';
    $alamat_supplier = $_POST['alamat_supplier'] ?? '';

    if (!empty($nama_supplier)) {
        $result = $supplierController->addSupplier([
            'nama_supplier' => $nama_supplier,
            'kontak_supplier' => $kontak_supplier,
            'alamat_supplier' => $alamat_supplier,
        ]);

        if ($result) {
            // Setelah berhasil, kembali ke halaman supplier.php dengan notifikasi sukses
            header("Location: suppliers.php?success=1");
            exit();
        } else {
            $notification = "Gagal menambahkan supplier.";
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
                    <h1 class="h3 text-gray-800">Tambah Supplier</h1>
                    <a href="suppliers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if (!empty($notification)): ?>
                    <script>
                        alert("<?= htmlspecialchars($notification); ?>");
                    </script>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Supplier</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nama_supplier">Nama Supplier</label>
                                <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Masukkan nama supplier" required>
                            </div>
                            <div class="form-group">
                                <label for="kontak_supplier">Kontak</label>
                                <input type="text" class="form-control" id="kontak_supplier" name="kontak_supplier" placeholder="Masukkan kontak supplier">
                            </div>
                            <div class="form-group">
                                <label for="alamat_supplier">Alamat</label>
                                <textarea class="form-control" id="alamat_supplier" name="alamat_supplier" placeholder="Masukkan alamat supplier"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>
