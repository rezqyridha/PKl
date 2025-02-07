<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/CustomerController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$customerController = new CustomerController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: customers.php?error=invalid_request");
    exit();
}

$customer = $customerController->getCustomerById($id);
if (!$customer) {
    header("Location: customers.php?error=not_found");
    exit();
}

// Load data provinsi
$provinsi = [
    "Aceh",
    "Bali",
    "Banten",
    "Bengkulu",
    "DI Yogyakarta",
    "DKI Jakarta",
    "Gorontalo",
    "Jambi",
    "Jawa Barat",
    "Jawa Tengah",
    "Jawa Timur",
    "Kalimantan Barat",
    "Kalimantan Selatan",
    "Kalimantan Tengah",
    "Kalimantan Timur",
    "Kalimantan Utara",
    "Kepulauan Bangka Belitung",
    "Kepulauan Riau",
    "Lampung",
    "Maluku",
    "Maluku Utara",
    "Nusa Tenggara Barat",
    "Nusa Tenggara Timur",
    "Papua",
    "Papua Barat",
    "Riau",
    "Sulawesi Barat",
    "Sulawesi Selatan",
    "Sulawesi Tengah",
    "Sulawesi Tenggara",
    "Sulawesi Utara",
    "Sumatera Barat",
    "Sumatera Selatan",
    "Sumatera Utara"
];
?>

<?php
// Pastikan session dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tampilkan alert jika ada error
if (isset($_GET['error']) && $_GET['error'] == 'no_change') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Tidak Ada Perubahan!',
                text: 'Data yang Anda masukkan sama dengan yang sudah ada.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        });
    </script>";
}
?>



<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Pelanggan</h1>
                    <a href="customers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/customer_actions.php?action=edit&id=<?= $customer['id_pelanggan'] ?>" method="POST">
                            <div class="form-group">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                    value="<?= htmlspecialchars($customer['nama_pelanggan']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kontak">Kontak</label>
                                <input type="text" class="form-control" id="kontak" name="kontak"
                                    value="<?= htmlspecialchars($customer['kontak']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" required><?= htmlspecialchars($customer['alamat']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <input type="text" class="form-control" id="kota" name="kota"
                                    value="<?= htmlspecialchars($customer['kota']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <select class="form-control" id="provinsi" name="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php
                                    // Loop through the $provinsi array and create an option for each province
                                    foreach ($provinsi as $prov) {
                                        $selected = ($customer['provinsi'] == $prov) ? "selected" : "";
                                        echo "<option value=\"$prov\" $selected>$prov</option>";
                                    }
                                    ?>
                                </select>
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