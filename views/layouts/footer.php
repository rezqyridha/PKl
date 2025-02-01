<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<!-- Include Bootstrap -->
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Include SB Admin JS -->
<script src="../../assets/js/sb-admin-2.min.js"></script>
<!-- Include DataTables -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Send delete request to the server
                fetch(`delete_product.php?id=${productId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your product has been deleted.",
                                icon: "success"
                            }).then(() => {
                                // Reload the page after successful deletion
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "There was an error deleting the product.",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Error!",
                            text: "There was an error communicating with the server.",
                            icon: "error"
                        });
                    });
            }
        });
    }
</script>