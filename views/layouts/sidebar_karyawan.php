<?php
$base_url = "/madu/views/";
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../employee/dashboard_karyawan.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Karyawan</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($page === 'dashboard') ? 'active' : ''; ?>">
        <a class="nav-link" href="../employee/dashboard_karyawan.php">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Manajemen Produk</div>

    <!-- Nav Item - Produk -->
    <li class="nav-item <?= ($page === 'products') ? 'active' : ''; ?>">
        <a class="nav-link" href="../employee/products.php">
            <i class="fas fa-cubes"></i>
            <span>Stok Produk</span>
        </a>
    </li>

    <!-- Nav Item - Restock -->
    <li class="nav-item <?= ($page === 'restock') ? 'active' : ''; ?>">
        <a class="nav-link" href="../employee/restock.php">
            <i class="fas fa-boxes"></i>
            <span>Restock Produk</span>
        </a>
    </li>

    <!-- Manajemen Penjualan -->
    <li class="nav-item <?= ($page == 'sales') ? 'active' : '' ?>">
        <a class="nav-link" href="../employee/sales.php">
            <i class="fas fa-shopping-cart"></i>
            <span>Manajemen Penjualan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Laporan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="true" aria-controls="collapseReports">
            <i class="fas fa-chart-line"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan Tersedia:</h6>
                <a class="collapse-item" href="<?= $base_url ?>reports/sales_by_product.php"><i class="fas fa-chart-bar"></i> Penjualan per Produk</a>
                <a class="collapse-item" href="<?= $base_url ?>reports/restock_expenses.php"><i class="fas fa-file-invoice-dollar"></i> Pengeluaran Restock</a>
                <a class="collapse-item" href="<?= $base_url ?>reports/product_stock_report.php"><i class="fas fa-boxes"></i> Stok Produk</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="../auth/logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>