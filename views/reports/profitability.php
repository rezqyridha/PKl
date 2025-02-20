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

// Query untuk mendapatkan data profitabilitas per produk
$query = "SELECT p.nama_produk, s.nama_satuan, 
       SUM(j.jumlah_terjual) AS total_terjual, 
       SUM(j.total_harga) AS total_pendapatan, 
       (SUM(j.total_harga) - 
        (SELECT IFNULL(SUM(r.jumlah_ditambahkan * r.harga_per_unit), 0) 
         FROM restock r 
         WHERE r.id_produk = p.id_produk)) AS profit
FROM penjualan j 
JOIN produk p ON j.id_produk = p.id_produk 
JOIN satuan s ON p.id_satuan = s.id_satuan 
GROUP BY p.id_produk, s.nama_satuan
ORDER BY profit DESC;
";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Siapkan data untuk ringkasan dan grafik
$nama_produk = array_column($data, 'nama_produk');
$total_pendapatan = array_column($data, 'total_pendapatan');
$total_profit = array_column($data, 'profit');
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
                                <h6 class="text-info">Produk Paling Menguntungkan</h6>
                                <h5 class="font-weight-bold"><?= $data[0]['nama_produk'] ?? 'N/A'; ?> <?= $data[0]['nama_satuan'] ?? ''; ?> (Keuntungan Rp <?= number_format($data[0]['profit'] ?? 0, 0, '', '.'); ?>)</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-warning">Total Profit Keseluruhan</h6>
                                <h5 class="font-weight-bold">Rp <?= number_format(array_sum($total_profit), 0, '', '.'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Detail Profitabilitas per Produk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan</th>
                                    <th>Total Terjual</th>
                                    <th>Total Pendapatan (Rp)</th>
                                    <th>Total Profit (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_satuan']); ?></td>
                                        <td><?= $row['total_terjual']; ?> Botol</td>
                                        <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
                                        <td>Rp <?= number_format($row['profit'], 0, '', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button class="btn btn-danger mt-3" onclick=" exportPDF()"><i class="fas fa-file-pdf"></i> Ekspor ke PDF</button>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Grafik Profitabilitas Produk</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartProfit"></canvas>
                    </div>
                </div>
            </div>
            <?php include '../layouts/footer.php'; ?>
        </div>
    </div>

    <!-- Script Chart dan DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        const ctxProfit = document.getElementById('chartProfit').getContext('2d');
        new Chart(ctxProfit, {
            type: 'bar',
            data: {
                labels: <?= json_encode($nama_produk); ?>,
                datasets: [{
                    label: 'Total Profit (Rp)',
                    data: <?= json_encode($total_profit); ?>,
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
            window.open('print_profit.php', '_blank');
        }
    </script>