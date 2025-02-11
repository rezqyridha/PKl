<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);

// Query untuk mendapatkan data penjualan per wilayah
$query = "SELECT pl.kota, pl.provinsi, COUNT(j.id_penjualan) AS total_transaksi, SUM(j.total_harga) AS total_pendapatan
          FROM penjualan j
          JOIN pelanggan pl ON j.id_pelanggan = pl.id_pelanggan
          GROUP BY pl.kota, pl.provinsi
          ORDER BY total_pendapatan DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menyiapkan data untuk ringkasan
$total_transaksi = array_sum(array_column($data, 'total_transaksi'));
$total_pendapatan = array_sum(array_column($data, 'total_pendapatan'));

// Menyiapkan data untuk grafik
$wilayah = array_map(function ($row) {
    return $row['kota'] . ', ' . $row['provinsi'];
}, $data);
$total_pendapatan_per_wilayah = array_column($data, 'total_pendapatan');
?>


<div id="wrapper">
    <?php
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>
            <div class="container-fluid mt-4">

                <!-- Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-info">Total Transaksi</h6>
                                <h5 class="font-weight-bold"><?= $total_transaksi; ?> Transaksi</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-warning">Total Pendapatan</h6>
                                <h5 class="font-weight-bold">Rp <?= number_format($total_pendapatan, 0, '', '.'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Detail Penjualan per Wilayah</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kota</th>
                                    <th>Provinsi</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Pendapatan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['kota']); ?></td>
                                        <td><?= htmlspecialchars($row['provinsi']); ?></td>
                                        <td><?= $row['total_transaksi']; ?> Penjualan</td>
                                        <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
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
                        <h5 class="card-title text-primary">Grafik Pendapatan per Wilayah</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPendapatanWilayah"></canvas>
                    </div>
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

        const ctxWilayah = document.getElementById('chartPendapatanWilayah').getContext('2d');
        new Chart(ctxWilayah, {
            type: 'bar',
            data: {
                labels: <?= json_encode($wilayah); ?>,
                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: <?= json_encode($total_pendapatan_per_wilayah); ?>,
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
            window.open('print_sales_by_region.php', '_blank');
        }
    </script>