<?php
require('../../fpdf/fpdf.php');
require_once '../../config/database.php';

// Koneksi ke database
$database = new Database();
$conn = $database->getConnection();

// Query untuk mendapatkan data penjualan per wilayah
$query = "SELECT pl.kota, pl.provinsi, COUNT(j.id_penjualan) AS total_transaksi, SUM(j.total_harga) AS total_pendapatan
          FROM penjualan j
          JOIN pelanggan pl ON j.id_pelanggan = pl.id_pelanggan
          GROUP BY pl.kota, pl.provinsi
          ORDER BY total_pendapatan DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total transaksi dan pendapatan
$total_transaksi = array_sum(array_column($data, 'total_transaksi'));
$total_pendapatan = array_sum(array_column($data, 'total_pendapatan'));

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'CV BERKAH ILMU ', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, 'Alamat: Jalan Bumi Pertiwi I Ujung RT. 032 RW.001 Kelurahan Pemurus Baru, Banjarmasin 70249', 0, 1, 'C');
        $this->Ln(5);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);

        // Tambahkan judul di atas tabel
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Laporan Penjualan per Wilayah', 0, 1, 'C');
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
        $w = array(10, 60, 50, 30, 40); // Lebar kolom
        $this->SetFont('Arial', 'B', 10);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 10, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        $this->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($data as $row) {
            $this->Cell($w[0], 10, $no++, 1, 0, 'C');
            $this->Cell($w[1], 10, $row['kota'], 1, 0, 'L');
            $this->Cell($w[2], 10, $row['provinsi'], 1, 0, 'L');
            $this->Cell($w[3], 10, $row['total_transaksi'], 1, 0, 'C');
            $this->Cell($w[4], 10, 'Rp ' . number_format($row['total_pendapatan'], 0, ',', '.'), 1, 1, 'R');
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = array('No', 'Kota', 'Provinsi', 'Total Transaksi', 'Total Pendapatan (Rp)');
$pdf->ImprovedTable($header, $data);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total Transaksi: ' . $total_transaksi . ' Transaksi', 0, 1, 'R');
$pdf->Cell(0, 10, 'Total Pendapatan: Rp ' . number_format($total_pendapatan, 0, ',', '.'), 0, 1, 'R');

$pdf->Output('I', 'laporan_penjualan_per_wilayah.pdf');
