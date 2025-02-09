<?php
require_once '../../../config/database.php';

$database = new Database();
$conn = $database->getConnection();

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan per Wilayah</title>

    <!-- CSS -->
    <link href="../../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- JavaScript -->
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
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-map-marker-alt"></i> Laporan Penjualan per Wilayah</li>
            </ol>
        </nav>

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
                                <td><?= $row['total_transaksi']; ?></td>
                                <td>Rp <?= number_format($row['total_pendapatan'], 0, '', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="btn btn-success mt-3" onclick="exportExcel()"><i class="fas fa-file-excel"></i> Ekspor ke Excel</button>
                <button class="btn btn-danger mt-3" onclick="exportPDF()"><i class="fas fa-file-pdf"></i> Ekspor ke PDF</button>
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

        function exportExcel() {
            alert('Ekspor ke Excel sedang dikembangkan.');
        }

        function exportPDF() {
            alert('Ekspor ke PDF sedang dikembangkan.');
        }
    </script>

</body>

</html>