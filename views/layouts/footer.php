<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; <?= date("Y"); ?> Manajemen Penjualan Madu. All Rights Reserved.</span>
        </div>
    </div>
</footer>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Easing -->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- SB Admin 2 JavaScript -->
<script src="../../assets/js/sb-admin-2.min.js"></script>

<!-- DataTables -->
<script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- File JavaScript Eksternal untuk Grafik -->
<script src="../../assets/js/chart-dashboard.js"></script>

<!-- Inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable(); // Menginisialisasi DataTables
    });
</script>

<!-- Sidebar Collapse with Persistent State -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const sidebarToggle = document.getElementById('sidebarToggle');

        // Terapkan status dari localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-toggled');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                body.classList.toggle('sidebar-toggled');

                // Simpan status di localStorage
                localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-toggled'));
            });
        }
    });
</script>