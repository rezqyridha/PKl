<?php
require_once '../../config/database.php';

require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

// Query untuk mendapatkan data stok produk saat ini
$query = "SELECT p.nama_produk, p.stok, p.harga, k.nama_kategori, s.nama_satuan 
          FROM produk p 
          JOIN kategori k ON p.id_kategori = k.id_kategori 
          JOIN satuan s ON p.id_satuan = s.id_satuan 
          ORDER BY p.stok DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menyiapkan data untuk ringkasan
$total_produk = count($data);
$total_stok = array_sum(array_column($data, 'stok'));

// Menyiapkan data untuk grafik
$nama_produk = array_column($data, 'nama_produk');
$jumlah_stok = array_column($data, 'stok');
?>



<div id="wrapper">
    <?php
    if ($_SESSION['role'] === 'admin') {
        include '../layouts/sidebar.php';  // Sidebar untuk admin
    } else if ($_SESSION['role'] === 'karyawan') {
        include '../layouts/sidebar_karyawan.php';  // Sidebar untuk karyawan
    }
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php';
            ?>

            <div class="container-fluid mt-4">

                <!-- Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-info">Total Produk</h6>
                                <h5 class="font-weight-bold"><?= $total_produk; ?> Produk</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-warning">Total Stok</h6>
                                <h5 class="font-weight-bold"><?= $total_stok; ?> Unit</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Detail Stok Produk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan</th>
                                    <th>Kategori</th>
                                    <th>Harga (Rp)</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_satuan']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                                        <td>Rp <?= number_format($row['harga'], 0, '', '.'); ?></td>
                                        <td><?= $row['stok'] ?: 0; ?> Botol</td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button class="btn btn-danger " onclick="exportPDF()"><i class="fas fa-file-pdf"></i> Ekspor ke PDF</button>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Grafik Stok per Produk</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartStok"></canvas>
                    </div>
                </div>
                <?php include '../layouts/footer.php'; ?>
            </div>
        </div>



        <!-- Script DataTables dan Chart.js -->
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });

            const ctxStok = document.getElementById('chartStok').getContext('2d');
            new Chart(ctxStok, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($nama_produk); ?>,
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: <?= json_encode($jumlah_stok); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });

            function exportPDF() {
                window.open('print_product_stock.php', '_blank');
            }
        </script>