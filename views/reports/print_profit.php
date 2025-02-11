<?php
require('../../fpdf/fpdf.php');
require_once '../../config/database.php';

// Koneksi ke database
$database = new Database();
$conn = $database->getConnection();

// Query untuk mendapatkan data profitabilitas per produk
$query = "SELECT p.nama_produk, 
       SUM(j.jumlah_terjual) AS total_terjual, 
       SUM(j.total_harga) AS total_pendapatan, 
       (SUM(j.total_harga) - 
        (SELECT IFNULL(SUM(r.jumlah_ditambahkan * r.harga_per_unit), 0) 
         FROM restock r 
         WHERE r.id_produk = p.id_produk)) AS profit
FROM penjualan j 
JOIN produk p ON j.id_produk = p.id_produk 
GROUP BY p.id_produk 
ORDER BY profit DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total profit keseluruhan
$total_profit = array_sum(array_column($data, 'profit'));

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
        $this->Cell(0, 10, 'Laporan Profitabilitas per Produk', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {

        $this->SetY(-65);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Banjarmasin, ' . date('d-m-Y'), 0, 1, 'R');
        $this->Cell(0, 10, 'Direktur CV Berkah Ilmu', 0, 1, 'R');
        $this->Ln(5);
        $this->Cell(0, 10, 'Husni Naparin', 0, 1, 'R');

        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
    }

    function ImprovedTable($header, $data)
    {
        $w = array(10, 80, 50, 50); // Lebar kolom
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
            $this->Cell($w[2], 10, 'Rp ' . number_format($row['total_pendapatan'], 0, ',', '.'), 1, 0, 'R');
            $this->Cell($w[3], 10, 'Rp ' . number_format($row['profit'], 0, ',', '.'), 1, 1, 'R');
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = array('No', 'Nama Produk', 'Total Pendapatan (Rp)', 'Total Profit (Rp)');
$pdf->ImprovedTable($header, $data);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total Profit Keseluruhan: Rp ' . number_format($total_profit, 0, ',', '.'), 0, 1, 'R');

$pdf->Output('I', 'laporan_profit_per_produk.pdf');
