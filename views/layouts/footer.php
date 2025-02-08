<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; <?= date("Y"); ?> Manajemen Penjualan Madu. All Rights Reserved.</span>
        </div>
    </div>
</footer>

<!-- Tambahkan SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="../../assets/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Easing Plugin -->
<script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- SB Admin 2 JavaScript -->
<script src="../../assets/js/sb-admin-2.min.js"></script>

<!-- DataTables JavaScript -->
<script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- File JavaScript Eksternal untuk Grafik -->
<script src="../../assets/js/chart-dashboard.js"></script>

<!-- Inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable(); // Pastikan tabel memiliki ID "dataTable"
    });
</script>