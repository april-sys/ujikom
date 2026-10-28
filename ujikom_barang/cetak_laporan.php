<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$fpdf = __DIR__ . '/libs/fpdf/fpdf.php';
if (!is_file($fpdf)) {
    set_flash('danger', 'Library FPDF belum dipasang. Letakkan fpdf.php di folder libs/fpdf.');
    redirect('laporan.php');
}
require_once $fpdf;

// FPDF memakai encoding Windows-1252, jadi teks dikonversi dulu
function pdf_teks(string $teks): string
{
    $hasil = @iconv('UTF-8', 'windows-1252//TRANSLIT', $teks);
    return $hasil === false ? $teks : $hasil;
}

function pdf_potong(string $teks, int $maks): string
{
    return pdf_teks(mb_strimwidth($teks, 0, $maks, '...', 'UTF-8'));
}

class LaporanPDF extends FPDF
{
    // No, Kode, Nama, Kategori, Harga, Stok, Nilai (total 190 mm)
    public $lebar = [10, 30, 45, 30, 28, 15, 32];

    function Header()
    {
        if ($this->PageNo() === 1) {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 8, pdf_teks('Laporan Data Barang'), 0, 1, 'C');
            $this->SetFont('Arial', '', 9);
            $this->Cell(0, 5, pdf_teks('Dicetak: ' . date('d-m-Y H:i')), 0, 1, 'C');
            $this->Ln(5);
        }

        $judul = ['No', 'Kode', 'Nama', 'Kategori', 'Harga', 'Stok', 'Nilai'];
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(230, 230, 230);
        foreach ($judul as $i => $teks) {
            $this->Cell($this->lebar[$i], 7, $teks, 1, 0, 'C', true);
        }
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
    }
}

$daftar = ambil_semua_barang();
$total  = hitung_total_daftar($daftar);

$pdf = new LaporanPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetTitle('Laporan Data Barang');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

$w = $pdf->lebar;

if (!$daftar) {
    $pdf->Cell(array_sum($w), 8, 'Belum ada data barang.', 1, 1, 'C');
} else {
    foreach ($daftar as $i => $b) {
        $pdf->Cell($w[0], 6, (string) ($i + 1), 1, 0, 'C');
        $pdf->Cell($w[1], 6, pdf_potong($b['kode'], 14), 1, 0, 'L');
        $pdf->Cell($w[2], 6, pdf_potong($b['nama'], 26), 1, 0, 'L');
        $pdf->Cell($w[3], 6, pdf_potong($b['kategori'], 16), 1, 0, 'L');
        $pdf->Cell($w[4], 6, rupiah($b['harga']), 1, 0, 'R');
        $pdf->Cell($w[5], 6, number_format((int) $b['stok'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell($w[6], 6, rupiah(hitung_nilai_barang($b['harga'], $b['stok'])), 1, 1, 'R');
    }

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell($w[0] + $w[1] + $w[2] + $w[3] + $w[4], 7, 'Total', 1, 0, 'R', true);
    $pdf->Cell($w[5], 7, number_format($total['total_stok'], 0, ',', '.'), 1, 0, 'R', true);
    $pdf->Cell($w[6], 7, rupiah($total['total_nilai']), 1, 1, 'R', true);

    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'Jumlah jenis barang: ' . $total['jumlah_barang'], 0, 1, 'L');
}

$pdf->Output('I', 'laporan-barang.pdf');
