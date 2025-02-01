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

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

// Load data provinsi
$provinsi = [
    "Aceh", "Bali", "Banten", "Bengkulu", "DI Yogyakarta", "DKI Jakarta",
    "Gorontalo", "Jambi", "Jawa Barat", "Jawa Tengah", "Jawa Timur", "Kalimantan Barat",
    "Kalimantan Selatan", "Kalimantan Tengah", "Kalimantan Timur", "Kalimantan Utara",
    "Kepulauan Bangka Belitung", "Kepulauan Riau", "Lampung", "Maluku", "Maluku Utara",
    "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Papua", "Papua Barat", "Riau",
    "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tengah", "Sulawesi Tenggara",
    "Sulawesi Utara", "Sumatera Barat", "Sumatera Selatan", "Sumatera Utara"
];
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Pelanggan Baru</h1>
                    <a href="customers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pelanggan</h6>
                    </div>
                    <div class="card-body">
                    <form action="../../controllers/customer_actions.php?action=add" method="POST">
    <div class="form-group">
        <label for="nama_pelanggan">Nama Pelanggan</label>
        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan nama pelanggan" required>
    </div>
    <div class="form-group">
        <label for="kontak">Kontak</label>
        <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Masukkan kontak pelanggan" required>
    </div>
    <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat pelanggan" required></textarea>
    </div>
    <div class="form-group">
        <label for="kota">Kota</label>
        <input type="text" class="form-control" id="kota" name="kota" placeholder="Masukkan kota pelanggan" required>
    </div>
    <div class="form-group">
        <label for="provinsi">Provinsi</label>
        <select class="form-control" id="provinsi" name="provinsi" required>
            <option value="">Pilih Provinsi</option>
            <option value="DKI Jakarta">DKI Jakarta</option>
            <option value="Jawa Barat">Jawa Barat</option>
            <option value="Jawa Timur">Jawa Timur</option>
            <option value="Sumatera Utara">Sumatera Utara</option>
            <option value="Kalimantan Selatan">Kalimantan Selatan</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Tambah Pelanggan</button>
</form>

                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>
