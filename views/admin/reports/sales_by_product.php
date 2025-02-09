<?php
require_once '../../../config/database.php';

$database = new Database();
$conn = $database->getConnection();


$query = "SELECT p.nama_produk, SUM(j.jumlah_terjual) AS total_terjual, SUM(j.total_harga) AS total_pendapatan 
          FROM penjualan j 
          JOIN produk p ON j.id_produk = p.id_produk ";
$query .= "GROUP BY j.id_produk ORDER BY total_terjual DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan per Produk</title>

    <!-- CSS Urutan yang Benar -->
    <link href="../../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- JavaScript Urutan yang Benar -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="sidebar-toggled">

    <div class="container-fluid mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white shadow-sm p-2">
                <li class="breadcrumb-item"><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-chart-bar"></i> Laporan Penjualan</li>
            </ol>
        </nav>

        <!-- Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <h6 class="text-info">Produk Terlaris</h6>
                        <h5 class="font-weight-bold"><?= $data[0]['nama_produk'] ?? 'N/A'; ?> (<?= $data[0]['total_terjual'] ?? 0; ?> Terjual)</h5>
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
                                <td><?= $row['total_terjual']; ?></td>
                                <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="btn btn-success mt-3" onclick=" exportExcel()"><i class="fas fa-file-excel"></i> Ekspor ke Excel</button>
                <button class="btn btn-danger mt-3" onclick=" exportPDF()"><i class="fas fa-file-pdf"></i> Ekspor ke PDF</button>
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

    <!-- Tambahkan Script DataTables -->
    <script src="../../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Script Chart dan DataTables -->
    <script>
        // Inisialisasi DataTables
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        // Chart.js untuk Grafik Pendapatan
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

        function exportExcel() {
            alert('Ekspor ke Excel sedang dikembangkan.');
        }

        function exportPDF() {
            alert('Ekspor ke PDF sedang dikembangkan.');
        }
    </script>

</body>

</html>