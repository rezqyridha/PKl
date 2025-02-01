<?php
require_once '../../config/database.php';
require_once '../../controllers/AdminController.php';
require_once '../../models/UserModel.php';

session_start();

// Periksa apakah session valid
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
                <!-- Dashboard Content -->
                <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>
                <div class="row">
                    <!-- Total Stock -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Stock</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $data['total_stock'] ?> Pcs</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales Today -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Sales Today</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($data['total_sales_today'], 2) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Employees -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Employees</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $data['total_employees'] ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>