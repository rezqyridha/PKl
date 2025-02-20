<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../auth/login.php");
    exit();
}


$database = new Database();
$conn = $database->getConnection();

$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);

// Query untuk mendapatkan data pengeluaran restock dengan satuan
$query = "SELECT p.nama_produk, r.tanggal_restock, r.jumlah_ditambahkan, r.harga_per_unit, 
                 r.total_biaya, sa.nama_satuan, s.nama AS nama_supplier
          FROM restock r 
          JOIN produk p ON r.id_produk = p.id_produk 
          JOIN satuan sa ON p.id_satuan = sa.id_satuan 
          JOIN supplier s ON r.id_supplier = s.id_supplier 
          ORDER BY r.tanggal_restock DESC";


$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total pengeluaran
$total_pengeluaran = array_sum(array_column($data, 'total_biaya'));

// Menyiapkan data untuk grafik
$nama_produk = array_column($data, 'nama_produk');
$total_biaya = array_column($data, 'total_biaya');
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
            <?php include '../layouts/header.php'; ?>
            <div class="container-fluid mt-4">
                <!-- Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-info">Total Pengeluaran</h6>
                                <h5 class="font-weight-bold">Rp <?= number_format($total_pengeluaran, 0, '', '.'); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-warning">Produk dengan Pengeluaran Tertinggi</h6>
                                <h5 class="font-weight-bold"><?= $data[0]['nama_produk'] ?? 'N/A'; ?> <?= $data[0]['nama_satuan'] ?? 'N/A'; ?> (Pengeluaran Sebesar Rp <?= number_format($data[0]['total_biaya'] ?? 0, 0, '', '.'); ?>)</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Detail Pengeluaran Restock</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Restock</th>
                                    <th>Jumlah Ditambahkan</th>
                                    <th>Harga per Unit (Rp)</th>
                                    <th>Total Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_satuan']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_supplier']); ?></td>
                                        <td><?= date(($row['tanggal_restock'])); ?></td>
                                        <td><?= $row['jumlah_ditambahkan'] ?: 0; ?> Botol</td>
                                        <td>Rp <?= number_format($row['harga_per_unit'], 0, '', '.'); ?></td>
                                        <td>Rp <?= number_format($row['total_biaya'], 0, '', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button class="btn btn-danger mt-3" onclick="exportPDF()"><i class="fas fa-file-pdf"></i> Ekspor ke PDF</button>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Grafik Pengeluaran per Produk</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPengeluaran"></canvas>
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

        const ctxPengeluaran = document.getElementById('chartPengeluaran').getContext('2d');
        new Chart(ctxPengeluaran, {
            type: 'bar',
            data: {
                labels: <?= json_encode($nama_produk); ?>,
                datasets: [{
                    label: 'Total Biaya (Rp)',
                    data: <?= json_encode($total_biaya); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
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
            window.open('print_restock_expenses.php', '_blank');
        }
    </script>