
<?php
require('../../fpdf/fpdf.php');
require_once '../../config/database.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Query untuk menampilkan data pengeluaran restock
$query = "SELECT p.nama_produk, r.tanggal_restock, r.jumlah_ditambahkan, r.harga_per_unit, r.total_biaya, s.nama 
          FROM restock r 
          JOIN produk p ON r.id_produk = p.id_produk 
          JOIN supplier s ON r.id_supplier = s.id_supplier 
          ORDER BY r.tanggal_restock DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF
{
    // Header halaman
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(10, 10); // Header lebih tinggi tanpa logo
        $this->Cell(0, 10, 'CV BERKAH ILMU', 0, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, 'Alamat: Jalan Bumi Pertiwi I Ujung RT. 032 RW.001 Kelurahan Pemurus Baru, Banjarmasin 70249', 0, 1, 'C');

        $this->Ln(5);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Garis pemisah
        $this->Ln(5);

        // Tambahkan judul di atas tabel
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Laporan Stok Produk', 0, 1, 'C');
        $this->Ln(5);
    }

    // Footer halaman
    function Footer()
    {
        $this->SetY(-65);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Banjarmasin, ' . date('d-m-Y'), 0, 1, 'R');
        $this->Cell(0, 10, 'Direktur CV Berkah Ilmu', 0, 1, 'R');
        $this->Ln(5);
        $this->Cell(0, 10, 'Husni Naparin', 0, 1, 'R');

        $this->SetY(-11);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
    }

    // Membuat tabel dengan logika stok kosong menjadi 0
    function ImprovedTable($header, $data)
    {
        $this->SetLeftMargin(15);
        $this->SetRightMargin(15);

        $w = array(10, 75, 40, 35, 20); // Lebar kolom disesuaikan
        $this->SetFont('Arial', 'B', 10);

        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 10, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        $this->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($data as $row) {
            $stok = !empty($row['stok']) ? $row['stok'] : 0; // Jika stok kosong, isi dengan 0
            $this->Cell($w[0], 10, $no++, 1, 0, 'C');
            $this->Cell($w[1], 10, $row['nama_produk'], 1, 0, 'L');
            $this->Cell($w[2], 10, $row['nama_satuan'], 1, 0, 'C');
            $this->Cell($w[3], 10, 'Rp ' . number_format($row['harga'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell($w[4], 10, $stok . ' Botol', 1, 1, 'C');
        }
    }
}
ob_clean();

// Buat PDF baru
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Header tabel
$header = array('No', 'Nama Produk', 'Satuan', 'Harga', 'Stok');
$pdf->ImprovedTable($header, $data);

// Output PDF di tab baru
$pdf->Output('I', 'laporan_produk.pdf');
