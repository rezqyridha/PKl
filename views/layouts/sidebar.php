<?php
$page = $page ?? ''; // Beri nilai default kosong jika $page belum didefinisikan
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/dashboard.php ">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Manajemen Madu</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item <?= ($page == 'dashboard') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Laporan -->
    <div class="sidebar-heading">Laporan</div>

    <!-- Analisis Penjualan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSalesReports"
            aria-expanded="true" aria-controls="collapseSalesReports">
            <i class="fas fa-chart-line"></i>
            <span>Analisis Penjualan</span>
        </a>
        <div id="collapseSalesReports" class="collapse" aria-labelledby="headingSalesReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="../reports/sales_by_product.php"><i class="fas fa-chart-bar"></i> Penjualan per Produk</a>
                <a class="collapse-item" href="../reports/sales_by_region.php"><i class="fas fa-map-marker-alt"></i> Penjualan per Wilayah</a>
                <a class="collapse-item" href="../reports/profitability.php"><i class="fas fa-dollar-sign"></i> Profitabilitas Produk</a>
            </div>
        </div>
    </li>

    <!-- Stok & Pengeluaran -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStockReports"
            aria-expanded="true" aria-controls="collapseStockReports">
            <i class="fas fa-warehouse"></i>
            <span>Stok & Pengeluaran</span>
        </a>
        <div id="collapseStockReports" class="collapse" aria-labelledby="headingStockReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="../reports/product_stock_report.php"><i class="fas fa-box"></i> Stok Produk Saat Ini</a>
                <a class="collapse-item" href="../reports/restock_expenses.php"><i class="fas fa-file-invoice-dollar"></i> Pengeluaran Restock</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Kelola Data -->
    <div class="sidebar-heading">Kelola Data</div>

    <li class="nav-item <?= ($page == 'products') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/products.php">
            <i class="fas fa-box"></i>
            <span>Kelola Produk</span>
        </a>
    </li>
    <li class="nav-item <?= ($page == 'categories') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/categories.php">
            <i class="fas fa-tags"></i>
            <span>Kelola Kategori</span>
        </a>
    </li>
    <li class="nav-item <?= ($page == 'satuan') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/satuan.php">
            <i class="fas fa-balance-scale"></i>
            <span>Kelola Satuan</span>
        </a>
    </li>
    <li class="nav-item <?= ($page == 'suppliers') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/suppliers.php">
            <i class="fas fa-truck"></i>
            <span>Kelola Supplier</span>
        </a>
    </li>
    <li class="nav-item <?= ($page == 'customers') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/customers.php">
            <i class="fas fa-users"></i>
            <span>Kelola Pelanggan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-user-cog"></i>
            <span>Kelola Pengguna</span>
        </a>
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

</ul>