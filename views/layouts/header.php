<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- CSS Custom File  -->
    <link rel="stylesheet" href="../../assets/css/custom.css">

    <!-- Include jQuery (pastikan dimuat terlebih dahulu sebelum DataTables) -->
    <script src="../../assets/vendor/jquery/jquery.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css">

    <!-- SB Admin 2 CSS -->
    <link rel="stylesheet" href="../../assets/css/sb-admin-2.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="../../assets/vendor/fontawesome-free/css/all.min.css">
</head>


<body>
    <!-- Topbar -->
    <?php
    // Ambil nama pengguna dari session
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    ?>
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Navbar -->
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                    aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <ul class="navbar-nav ml-auto">
            <!-- Notifikasi Pesanan Baru Next fitur
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-shopping-basket fa-fw"></i>
                    <span class="badge badge-danger badge-counter">5+</span> // Jumlah pesanan baru 
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="ordersDropdown">
                <h6 class="dropdown-header">Pesanan Baru</h6>
                <a class="dropdown-item d-flex align-items-center" href="../admin/orders.php">
                    <div class="dropdown-list-image mr-3">
                        <i class="fas fa-box-open text-primary"></i>
                    </div>
                    <div>
                        <span class="font-weight-bold">Ada 5 pesanan baru</span>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="../admin/orders.php">Lihat Semua Pesanan</a>
            </div>
            </li> -->

            <!-- User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        <?= isset($user['nama_lengkap']) ? $user['nama_lengkap'] : 'Guest'; ?>
                    </span>
                    <img class="img-profile rounded-circle" src="../../assets/img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../../views/auth/profile.php">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>