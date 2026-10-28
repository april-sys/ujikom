<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$ringkasan = hitung_ringkasan();
$terbaru   = ambil_barang_terbaru(5);

$judul = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<h1 class="h4 mb-3">Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="stat-label">Jumlah barang</div>
                <div class="stat-nilai"><?= number_format($ringkasan['jumlah_barang'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="stat-label">Total stok</div>
                <div class="stat-nilai"><?= number_format($ringkasan['total_stok'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="stat-label">Total nilai</div>
                <div class="stat-nilai"><?= e(rupiah($ringkasan['total_nilai'])) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Barang terbaru</span>
        <a href="data_barang.php" class="small">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Stok</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$terbaru): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data barang. <a href="form_barang.php">Tambah barang</a></td></tr>
            <?php else: ?>
                <?php foreach ($terbaru as $b): ?>
                    <tr>
                        <td><?= e($b['kode']) ?></td>
                        <td><?= e($b['nama']) ?></td>
                        <td><?= e($b['kategori']) ?></td>
                        <td class="text-end"><?= e(rupiah($b['harga'])) ?></td>
                        <td class="text-end"><?= number_format((int) $b['stok'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
