<?php
// Fungsi perhitungan total

function hitung_nilai_barang($harga, $stok): float
{
    return (float) $harga * (int) $stok;
}

// Ringkasan untuk dashboard (dihitung langsung di database)
function hitung_ringkasan(): array
{
    $st = db()->query(
        'SELECT COUNT(*) AS jumlah_barang,
                COALESCE(SUM(stok), 0) AS total_stok,
                COALESCE(SUM(harga * stok), 0) AS total_nilai
         FROM barang'
    );
    $r = $st->fetch();

    return [
        'jumlah_barang' => (int) $r['jumlah_barang'],
        'total_stok'    => (int) $r['total_stok'],
        'total_nilai'   => (float) $r['total_nilai'],
    ];
}

// Total dari daftar barang yang sudah diambil (untuk laporan)
function hitung_total_daftar(array $daftar): array
{
    $total = ['jumlah_barang' => count($daftar), 'total_stok' => 0, 'total_nilai' => 0.0];

    foreach ($daftar as $b) {
        $total['total_stok']  += (int) $b['stok'];
        $total['total_nilai'] += hitung_nilai_barang($b['harga'], $b['stok']);
    }

    return $total;
}
