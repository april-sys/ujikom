<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$daftar = ambil_semua_barang();
$total  = hitung_total_daftar($daftar);

$judul = 'Laporan';
require __DIR__ . '/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">Laporan Data Barang</h1>
    <a href="cetak_laporan.php" class="btn btn-primary" target="_blank" rel="noopener">Cetak PDF</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Stok</th>
                    <th class="text-end">Nilai</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$daftar): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data barang.</td></tr>
            <?php else: ?>
                <?php foreach ($daftar as $i => $b): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($b['kode']) ?></td>
                        <td><?= e($b['nama']) ?></td>
                        <td><?= e($b['kategori']) ?></td>
                        <td class="text-end"><?= e(rupiah($b['harga'])) ?></td>
                        <td class="text-end"><?= number_format((int) $b['stok'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= e(rupiah(hitung_nilai_barang($b['harga'], $b['stok']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <?php if ($daftar): ?>
            <tfoot>
                <tr class="fw-semibold">
                    <td colspan="5" class="text-end">Total</td>
                    <td class="text-end"><?= number_format($total['total_stok'], 0, ',', '.') ?></td>
                    <td class="text-end"><?= e(rupiah($total['total_nilai'])) ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<p class="text-muted small mt-2">Jumlah jenis barang: <?= (int) $total['jumlah_barang'] ?></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
