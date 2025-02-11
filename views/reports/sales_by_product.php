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

// Query untuk mengambil semua data penjualan
$query = "SELECT p.nama_produk, s.nama_satuan, 
          SUM(j.jumlah_terjual) AS total_terjual, 
          SUM(j.total_harga) AS total_pendapatan 
          FROM penjualan j 
          JOIN produk p ON j.id_produk = p.id_produk 
          JOIN satuan s ON p.id_satuan = s.id_satuan 
          GROUP BY p.id_produk 
          ORDER BY total_terjual DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
                                <h6 class="text-info">Produk Terlaris</h6>
                                <h5 class="font-weight-bold"><?= $data[0]['nama_produk'] ?? 'N/A'; ?> (<?= $data[0]['total_terjual'] ?? 0; ?> Botol)</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <h6 class="text-warning">Pendapatan Tertinggi</h6>
                                <h5 class="font-weight-bold">Rp <?= number_format(max(array_column($data, 'total_pendapatan')) ?: 0, 0, '', '.'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title text-primary">Detail Penjualan per Produk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Total Terjual</th>
                                    <th>Total Pendapatan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                        <td><?= $row['total_terjual']; ?> Botol</td>
                                        <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
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
                        <h5 class="card-title text-primary">Grafik Total Pendapatan per Produk</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>
            <?php include '../layouts/footer.php'; ?>
        </div>
    </div>
</div>

<!-- Script DataTables dan Chart.js -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    const ctxPendapatan = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctxPendapatan, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($data, 'nama_produk')); ?>,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: <?= json_encode(array_column($data, 'total_pendapatan')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
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
        window.open('print_sales_by_product.php', '_blank');
    }
</script>