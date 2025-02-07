document.addEventListener("DOMContentLoaded", function () {
    var canvas = document.getElementById("salesChart");

    if (canvas) {
        var ctx = canvas.getContext("2d");

        // Ambil data dari API
        fetch("../../api/get_chart_data.php")
            .then((response) => response.json())
            .then((data) => {
                if (data.length === 0) {
                    console.warn("Data kosong, pastikan ada data di database.");
                    return;
                }

                // Ubah nama hari ke bahasa Indonesia
                const hariIndo = {
                    Monday: "Senin",
                    Tuesday: "Selasa",
                    Wednesday: "Rabu",
                    Thursday: "Kamis",
                    Friday: "Jumat",
                    Saturday: "Sabtu",
                    Sunday: "Minggu",
                };

                var myChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: data.map((item) => hariIndo[item.hari]), // Ubah ke bahasa Indonesia
                        datasets: [
                            {
                                label: "Total Produk Terjual",
                                data: data.map((item) => item.total_produk),
                                backgroundColor: "rgba(78, 115, 223, 0.5)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                borderWidth: 2,
                                fill: true,
                                pointRadius: 5, // Ukuran titik diperbesar agar lebih terlihat
                                pointHoverRadius: 7,
                            },
                            {
                                label: "Total Pendapatan (Rp)",
                                data: data.map((item) => item.total_penjualan),
                                backgroundColor: "rgba(28, 200, 138, 0.5)",
                                borderColor: "rgba(28, 200, 138, 1)",
                                borderWidth: 2,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "top",
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        return (
                                            tooltipItem.dataset.label +
                                            ": " +
                                            tooltipItem.raw.toLocaleString(
                                                "id-ID"
                                            )
                                        );
                                    },
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 100000, // Skala lebih kecil
                                    callback: function (value) {
                                        return value.toLocaleString("id-ID"); // Format angka ribuan
                                    },
                                },
                            },
                        },
                    },
                });
            })
            .catch((error) =>
                console.error("Error fetching chart data:", error)
            );
    } else {
        console.warn("Canvas 'salesChart' tidak ditemukan di halaman.");
    }

    // **Grafik Stok Produk Terlaris**
    var ctxStock = document.getElementById("stockChart");
    if (ctxStock) {
        var stockChart = new Chart(ctxStock.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: ["Madu Hutan", "Madu Kelulut", "Madu Pahit"], // Label produk
                datasets: [
                    {
                        data: [50, 30, 20], // Contoh jumlah stok (bisa diganti dari API)
                        backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc"],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
            },
        });
    } else {
        console.warn("Canvas 'stockChart' tidak ditemukan di halaman.");
    }

    //Total Penjualan Hari ini
    fetch("../../api/get_total_penjualan.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.total_hari_ini !== undefined) {
                document.getElementById("totalPenjualan").innerText =
                    "Rp " +
                    parseFloat(data.total_hari_ini).toLocaleString("id-ID");
            } else {
                console.warn("Data total penjualan hari ini tidak ditemukan.");
            }
        })
        .catch((error) =>
            console.error("Error fetching total penjualan hari ini:", error)
        );

    //Produk Hampir Habis
    fetch("../../api/get_produk_hampir_habis.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.produk_hampir_habis !== undefined) {
                document.getElementById("produkHampirHabis").innerText =
                    data.produk_hampir_habis + " Produk";
            } else {
                console.warn("Data produk hampir habis tidak ditemukan.");
            }
        })
        .catch((error) =>
            console.error("Error fetching produk hampir habis:", error)
        );
});
