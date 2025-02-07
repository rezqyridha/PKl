<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Manajemen Madu</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item <?= ($page == 'dashboard') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Kelola Data</div>

    <!-- Kelola Produk -->
    <li class="nav-item <?= ($page == 'products') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/products.php">
            <i class="fas fa-box-open"></i>
            <span>Kelola Produk</span>
        </a>
    </li>

    <!-- Kelola Kategori -->
    <li class="nav-item <?= ($page == 'categories') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/categories.php">
            <i class="fas fa-tags"></i>
            <span>Kelola Kategori</span>
        </a>
    </li>

    <!-- Kelola Supplier -->
    <li class="nav-item <?= ($page == 'suppliers') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/suppliers.php">
            <i class="fas fa-truck"></i>
            <span>Kelola Supplier</span>
        </a>
    </li>

    <!-- Kelola Restock -->
    <li class="nav-item <?= ($page == 'restock') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/restock.php">
            <i class="fas fa-truck-loading"></i>
            <span>Kelola Restock</span>
        </a>
    </li>

    <!-- Kelola Penjualan -->
    <li class="nav-item <?= ($page == 'sales') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/sales.php">
            <i class="fas fa-shopping-cart"></i>
            <span>Kelola Penjualan</span>
        </a>
    </li>

    <!-- Kelola Pelanggan -->
    <li class="nav-item <?= ($page == 'customers') ? 'active' : '' ?>">
        <a class="nav-link" href="../admin/customers.php">
            <i class="fas fa-user-friends"></i>
            <span>Kelola Pelanggan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Laporan</div>

    <!-- Laporan Dropdown -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="true" aria-controls="collapseReports">
            <i class="fas fa-chart-line"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan Analisis:</h6>
                <a class="collapse-item" href="../admin/reports/sales_by_product.php"><i class="fas fa-chart-bar"></i> Penjualan per Produk</a>
                <a class="collapse-item" href="../admin/reports/restock_expense.php"><i class="fas fa-file-invoice-dollar"></i> Pengeluaran Restock</a>
                <a class="collapse-item" href="../admin/reports/stock_report.php"><i class="fas fa-boxes"></i> Stok Produk</a>
                <a class="collapse-item" href="../admin/reports/user_activity.php"><i class="fas fa-user-clock"></i> Aktivitas Pengguna</a>
                <a class="collapse-item" href="../admin/reports/sales_by_region.php"><i class="fas fa-map-marked-alt"></i> Penjualan Wilayah</a>
                <a class="collapse-item" href="../admin/reports/best_selling_products.php"><i class="fas fa-star"></i> Produk Terlaris</a>
                <a class="collapse-item" href="../admin/reports/active_suppliers.php"><i class="fas fa-handshake"></i> Supplier Aktif</a>
                <a class="collapse-item" href="../admin/reports/profitability.php"><i class="fas fa-money-bill-wave"></i> Profitabilitas</a>
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

</ul>