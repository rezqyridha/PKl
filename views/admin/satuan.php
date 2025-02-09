<?php
require_once '../../config/database.php';
require_once '../../controllers/SatuanController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Koneksi ke database dan mengambil data satuan
$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$satuanController = new SatuanController($db);
$satuanList = $satuanController->index();
?>

<?php
// Menampilkan SweetAlert berdasarkan session alert
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({ title: 'Berhasil!', text: 'Satuan berhasil ditambahkan!', icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({ title: 'Terhapus!', text: 'Satuan berhasil dihapus!', icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({ title: 'Berhasil!', text: 'Satuan berhasil diperbarui!', icon: 'success' });";
    } elseif ($_SESSION['alert'] == 'invalid_input') {
        echo "Swal.fire({ title: 'Gagal!', text: 'Input tidak valid!', icon: 'error' });";
    } elseif ($_SESSION['alert'] == 'invalid_id') {
        echo "Swal.fire({ title: 'Gagal!', text: 'ID tidak valid!', icon: 'error' });";
    } elseif ($_SESSION['alert'] == 'no_change') {
        echo "Swal.fire({ title: 'Tidak Ada Perubahan!', text: 'Data yang Anda masukkan sama dengan yang sudah ada.', icon: 'info', confirmButtonText: 'OK' });";
    }

    echo "});</script>";
    unset($_SESSION['alert']);
}
?>

<div id="wrapper">
    <!-- Sidebar -->
    <?php
    $page = 'satuan';
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
                    <h1 class="h3 text-gray-800">Kelola Satuan</h1>
                    <a href="add_satuan.php" class="btn btn-primary">Tambah Satuan Baru</a>
                </div>

                <!-- Satuan Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Satuan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Satuan</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($satuanList)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($satuanList as $satuan): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($satuan['nama_satuan']); ?></td>
                                                <td><?= htmlspecialchars($satuan['deskripsi']); ?></td>
                                                <td>
                                                    <a href="edit_satuan.php?id=<?= $satuan['id_satuan']; ?>" class="btn btn-info btn-circle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-circle" onclick="confirmDelete(<?= $satuan['id_satuan']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data satuan.</td>
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
    function confirmDelete(satuanId) {
        Swal.fire({
            title: "Yakin ingin menghapus satuan ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`../../controllers/satuan_actions.php?action=delete&id=${satuanId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: data.message || "Satuan berhasil dihapus.",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: data.message || "Gagal menghapus satuan.",
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