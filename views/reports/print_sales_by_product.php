<?php
require('../../fpdf/fpdf.php');
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Query tanpa filter role
$query = "SELECT p.nama_produk, s.nama_satuan, 
                 SUM(j.jumlah_terjual) AS total_terjual, 
                 SUM(j.total_harga) AS total_pendapatan 
          FROM penjualan j 
          JOIN produk p ON j.id_produk = p.id_produk 
          JOIN satuan s ON p.id_satuan = s.id_satuan 
          GROUP BY p.id_produk 
          ORDER BY total_terjual DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'CV BERKAH ILMU', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, 'Alamat: Jalan Bumi Pertiwi I Ujung RT. 032 RW.001 Kelurahan Pemurus Baru, Banjarmasin 70249', 0, 1, 'C');
        $this->Ln(5);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);

        // Tambahkan judul di atas tabel
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Laporan Penjualan Per Produk', 0, 1, 'C');
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

    function ImprovedTable($header, $data)
    {
        $this->SetLeftMargin(15);
        $this->SetRightMargin(15); {
            $w = array(10, 70, 30, 30, 40); // Lebar kolom
            $this->SetFont('Arial', 'B', 10);
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($w[$i], 10, $header[$i], 1, 0, 'C');
            }
            $this->Ln();

            $this->SetFont('Arial', '', 10);
            $no = 1;
            foreach ($data as $row) {
                $this->Cell($w[0], 10, $no++, 1, 0, 'C');
                $this->Cell($w[1], 10, $row['nama_produk'], 1, 0, 'L');
                $this->Cell($w[2], 10, $row['nama_satuan'], 1, 0, 'C');
                $this->Cell($w[3], 10, ($row['total_terjual'] ?? 0) . ' Botol', 1, 0, 'C');
                $this->Cell($w[4], 10, 'Rp ' . number_format($row['total_pendapatan'] ?? 0, 0, ',', '.'), 1, 1, 'R');
            }
        }
    }
}



ob_clean(); // Menghapus output buffer agar tidak ada output sebelum PDF

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = array('No', 'Nama Produk', 'Satuan', 'Total Terjual', 'Total Pendapatan (Rp)');
$pdf->ImprovedTable($header, $data);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total Produk Terjual: ' . array_sum(array_column($data, 'total_terjual')) . ' Botol', 0, 1, 'R');
$pdf->Cell(0, 10, 'Total Pendapatan: Rp ' . number_format(array_sum(array_column($data, 'total_pendapatan')), 0, ',', '.'), 0, 1, 'R');

$pdf->Output('I', 'laporan_penjualan_per_produk.pdf');
