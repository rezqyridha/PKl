<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard_karyawan.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Karyawan</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($page === 'dashboard') ? 'active' : ''; ?>">
        <a class="nav-link" href="../employee/dashboard.php">
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
        <a class="nav-link" href="products.php">
            <i class="fas fa-cubes"></i>
            <span>Stok Produk</span>
        </a>
    </li>

    <!-- Nav Item - Restock -->
    <li class="nav-item <?= ($page === 'restock') ? 'active' : ''; ?>">
        <a class="nav-link" href="restock.php">
            <i class="fas fa-boxes"></i>
            <span>Restock Produk</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Laporan</div>

    <!-- Nav Item - Laporan Produk -->
    <li class="nav-item <?= ($page === 'report_stock') ? 'active' : ''; ?>">
        <a class="nav-link" href="report_stock.php">
            <i class="fas fa-file-alt"></i>
            <span>Laporan Stok Produk</span>
        </a>
    </li>

    <!-- Nav Item - Laporan Restock -->
    <li class="nav-item <?= ($page === 'report_restock') ? 'active' : ''; ?>">
        <a class="nav-link" href="report_restock.php">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan Restock</span>
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

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>