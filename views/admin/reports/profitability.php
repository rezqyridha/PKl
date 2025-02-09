<?php
require_once '../../../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Query untuk mendapatkan data profitabilitas per produk
$query = "SELECT p.nama_produk, 
                 SUM(j.jumlah_terjual) AS total_terjual, 
                 SUM(j.total_harga) AS total_pendapatan, 
                 (SUM(j.total_harga) - (SUM(r.jumlah_ditambahkan * r.harga_per_unit))) AS profit 
          FROM penjualan j 
          JOIN produk p ON j.id_produk = p.id_produk 
          LEFT JOIN restock r ON r.id_produk = p.id_produk 
          GROUP BY p.id_produk 
          ORDER BY profit DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Siapkan data untuk ringkasan dan grafik
$nama_produk = array_column($data, 'nama_produk');
$total_pendapatan = array_column($data, 'total_pendapatan');
$total_profit = array_column($data, 'profit');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Profitabilitas Produk</title>
    <!-- Tambahkan CSS -->
    <link href="../../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-money-bill-wave"></i> Laporan Profitabilitas</li>
            </ol>
        </nav>

        <!-- Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <h6 class="text-info">Produk Paling Menguntungkan</h6>
                        <h5 class="font-weight-bold"><?= $data[0]['nama_produk'] ?? 'N/A'; ?> (Rp <?= number_format($data[0]['profit'] ?? 0, 0, '', '.'); ?>)</h5>
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
                                <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
                                <td>Rp <?= number_format($row['profit'], 0, '', '.'); ?></td>
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
                <h5 class="card-title text-primary">Grafik Profitabilitas Produk</h5>
            </div>
            <div class="card-body">
                <canvas id="chartProfit"></canvas>
            </div>
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

        function exportExcel() {
            alert('Ekspor ke Excel sedang dikembangkan.');
        }

        function exportPDF() {
            alert('Ekspor ke PDF sedang dikembangkan.');
        }
    </script>

</body>

</html>