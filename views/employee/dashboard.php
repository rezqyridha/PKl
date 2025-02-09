<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/EmployeeController.php';
require_once '../../controllers/ProductController.php';
require_once '../../controllers/RestockController.php';

session_start();

// Periksa apakah user adalah karyawan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan' || !isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil data dashboard
$database = new Database();
$db = $database->getConnection();
$conn = (new Database())->getConnection();
$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);


$employeeController = new EmployeeController($db);
$data = $employeeController->getDashboardData();

$productController = new ProductController($db);
$restockController = new RestockController($db);
$productModel = new ProductModel($conn);

// Contoh data yang bisa Anda gunakan di dashboard
$totalStock = $productController->getTotalStock();
$lowStockProducts = $productModel->getLowStockProducts();
$totalRestockToday = $restockController->getTotalRestockToday();

?>

<div id="wrapper">
    <!-- Sidebar -->
    <?php
    $page = 'dashboard';
    include '../layouts/sidebar_karyawan.php'; // Buat sidebar khusus untuk karyawan
    ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Judul Halaman -->
                <h1 class="h3 mb-4 text-gray-800">Dashboard Karyawan</h1>

                <!-- Statistik Umum -->
                <div class="row">

                    <!-- Total Stok -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Stok Produk
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= number_format($totalStock); ?> Pcs
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cubes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk Hampir Habis -->
                    <div class="col-xl-4 col-md-6 mb-4">
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

                    <!-- Total Restock Hari Ini -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card dashboard-widget border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Restock Hari Ini
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= number_format($totalRestockToday); ?> Pcs
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
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