<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Koneksi ke database dan mengambil data produk
$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$productController = new ProductController($db);
$products = $productController->showAllProducts(); // Ambil semua produk
$categories = $productController->getAllCategories();
$satuan = $productController->getAllSatuan();
?>

<?php
// Pastikan session dimulai sebelum digunakan
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menampilkan SweetAlert berdasarkan session alert
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({
        title: 'Berhasil!',
        text: 'Produk berhasil ditambahkan!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({
        title: 'Terhapus!',
        text: 'Produk berhasil dihapus!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({
        title: 'Berhasil!',
        text: 'Produk berhasil diperbarui!',
        icon: 'success'
    });";
    } elseif ($_SESSION['alert'] == 'no_change') {
        echo "Swal.fire({
        title: 'Tidak Ada Perubahan!',
        text: 'Data yang Anda masukkan sama dengan yang sudah ada.',
        icon: 'info'
    });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({
        title: 'Gagal!',
        text: 'Gagal mengubah produk!',
        icon: 'error'
    });";
    } elseif ($_SESSION['alert'] == 'invalid_input') {
        echo "Swal.fire({
        title: 'Gagal!',
        text: 'Data yang anda masukkan tidak lengkap !',
        icon: 'error'
    });";
    }

    echo "});</script>";

    // Hapus session alert agar tidak muncul lagi saat halaman di-refresh
    unset($_SESSION['alert']);
}
?>



<div id="wrapper">
    <!-- Sidebar -->
    <?php
    $page = 'products';
    include '../layouts/sidebar.php';
    ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Kelola Produk</h1>
                    <a href="add_product.php" class="btn btn-primary">Tambah Produk Baru</a>
                </div>

                <!-- Products Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($products)): ?>
                                        <?php $no = 1; // Inisialisasi nomor urut 
                                        ?>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?= $no++; ?></td> <!-- Tampilkan nomor urut -->
                                                <td><?= htmlspecialchars($product['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($product['deskripsi']); ?></td>

                                                <!-- Menampilkan kategori produk -->
                                                <td>
                                                    <?php
                                                    $categoryName = '';
                                                    foreach ($categories as $category) {
                                                        if ($category['id_kategori'] == $product['id_kategori']) {
                                                            $categoryName = $category['nama_kategori'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($categoryName);
                                                    ?>
                                                </td>

                                                <!-- Menampilkan satuan produk -->
                                                <td>
                                                    <?php
                                                    $satuanName = '';
                                                    foreach ($satuan as $satuanItem) {
                                                        if ($satuanItem['id_satuan'] == $product['id_satuan']) {
                                                            $satuanName = $satuanItem['nama_satuan'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($satuanName);
                                                    ?>
                                                </td>
                                                <!-- Menampilkan harga produk -->
                                                <td>Rp <?= number_format($product['harga'] ?? 0, 0, ',', '.'); ?></td>
                                                <td><?= ($product['stok'] === null || $product['stok'] === 0) ? '0' : htmlspecialchars($product['stok']); ?> Botol</td>
                                                <td>
                                                    <a href="edit_product.php?id=<?= $product['id_produk']; ?>"
                                                        class="btn btn-info btn-circle">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle"
                                                        onclick="confirmDelete(<?= $product['id_produk']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada produk yang tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php include '../layouts/footer.php'; ?>
    </div>
    <!-- End Content Wrapper -->
</div>
<!-- End Wrapper -->


<!-- Script Delete -->
<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: "Yakin ingin menghapus data produk ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/product_actions.php?action=delete&id=${productId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json()) // Pastikan server mengembalikan JSON
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: data.message || "Data restock telah dihapus.",
                                icon: "success"
                            }).then(() => {
                                location.reload(); // Reload halaman setelah berhasil
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Terjadi kesalahan saat menghapus data restock.",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan dalam komunikasi dengan server.",
                            icon: "error"
                        });
                    });
            }
        });
    }
</script>