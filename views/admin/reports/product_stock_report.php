<?php
require_once '../../../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Query untuk mendapatkan data stok produk saat ini
$query = "SELECT p.nama_produk, p.stok, p.harga, k.nama_kategori, s.nama_satuan 
          FROM produk p 
          JOIN kategori k ON p.id_kategori = k.id_kategori 
          JOIN satuan s ON p.id_satuan = s.id_satuan 
          ORDER BY p.stok DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menyiapkan data untuk ringkasan
$total_produk = count($data);
$total_stok = array_sum(array_column($data, 'stok'));

// Menyiapkan data untuk grafik
$nama_produk = array_column($data, 'nama_produk');
$jumlah_stok = array_column($data, 'stok');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Produk Saat Ini</title>

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
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-box"></i> Laporan Stok Produk</li>
            </ol>
        </nav>

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
                            <th>Kategori</th>
                            <th>Satuan</th>
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
                                <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                                <td><?= htmlspecialchars($row['nama_satuan']); ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, '', '.'); ?></td>
                                <td><?= $row['stok']; ?></td>
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
                <h5 class="card-title text-primary">Grafik Stok per Produk</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStok"></canvas>
            </div>
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

        function exportExcel() {
            alert('Ekspor ke Excel sedang dikembangkan.');
        }

        function exportPDF() {
            alert('Ekspor ke PDF sedang dikembangkan.');
        }
    </script>

</body>

</html>