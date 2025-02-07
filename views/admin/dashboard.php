<?php
require_once '../../config/database.php';
require_once '../../controllers/AdminController.php';
require_once '../../models/UserModel.php';

session_start();

// Periksa apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil data pengguna
$database = new Database();
$db = $database->getConnection();
$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

// Ambil data dashboard dari AdminController
$adminController = new AdminController($db);
$data = $adminController->getDashboardData();
?>

<div id="wrapper">
    <!-- Sidebar -->
    <?php include '../layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Judul Halaman -->
                <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

                <!-- Statistik Umum -->
                <div class="row">

                    <!-- Total Stok -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-primary shadow h-100 py-2" data-target="products.php">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Stok
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= number_format($data['total_stock']); ?> Pcs
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cubes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Penjualan Hari Ini -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-success shadow h-100 py-2" data-target="sales.php">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Penjualan Hari Ini
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($data['total_sales_today'], 0, ',', '.'); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pesanan Belum Diproses -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-warning shadow h-100 py-2" data-target="#">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pesanan Belum Diproses
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            0 Pesanan
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk Hampir Habis -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-danger shadow h-100 py-2" data-target="low_stock_products.php">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Produk Hampir Habis
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= number_format($data['total_low_stock_products']); ?> Produk
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <!-- Grafik Penjualan -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" style="min-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Stok Terlaris -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">Stok Produk Terlaris</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="stockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include '../layouts/footer.php'; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".dashboard-widget").forEach(widget => {
            widget.addEventListener("click", function() {
                let target = this.getAttribute("data-target");
                if (target) {
                    window.location.href = target;
                }
            });
        });
    });
</script>