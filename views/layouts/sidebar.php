<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Managemen & Penjualan Madu</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="../admin/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Manage Menu -->
    <div class="sidebar-heading">
        Kelola
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKelola"
            aria-expanded="true" aria-controls="collapseKelola">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengelolaan</span>
        </a>
        <div id="collapseKelola" class="collapse" aria-labelledby="headingKelola" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pengelolaan</h6>
                <a class="collapse-item" href=" ../admin/products.php">Kelola Produk</a>
                <a class="collapse-item" href="../admin/sales.php">Kelola Penjualan</a>
                <a class="collapse-item" href="../admin/restock.php">Kelola Restock</a>
                <a class="collapse-item" href="../admin/categories.php">Kelola Kategori</a>
                <a class="collapse-item" href="../admin/customers.php">Kelola Pelanggan</a>
                <a class="collapse-item" href="../admin/suppliers.php">Kelola Supplier</a>
            </div>
        </div>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="../auth/logout.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
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