<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$cari   = (isset($_GET['cari']) && is_string($_GET['cari'])) ? trim($_GET['cari']) : '';
$daftar = ambil_semua_barang($cari);

$judul = 'Data Barang';
require __DIR__ . '/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">Data Barang</h1>
    <a href="form_barang.php" class="btn btn-primary">Tambah Barang</a>
</div>

<form method="get" action="data_barang.php" class="row g-2 mb-3">
    <div class="col-12 col-sm">
        <input type="text" class="form-control" name="cari" placeholder="Cari kode, nama, atau kategori" value="<?= e($cari) ?>" aria-label="Cari barang">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Cari</button>
    </div>
    <?php if ($cari !== ''): ?>
        <div class="col-auto">
            <a href="data_barang.php" class="btn btn-outline-secondary">Reset</a>
        </div>
    <?php endif; ?>
</form>

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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$daftar): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <?= $cari !== '' ? 'Tidak ada barang yang cocok dengan pencarian.' : 'Belum ada data barang.' ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($daftar as $i => $b): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($b['kode']) ?></td>
                        <td><?= e($b['nama']) ?></td>
                        <td><?= e($b['kategori']) ?></td>
                        <td class="text-end"><?= e(rupiah($b['harga'])) ?></td>
                        <td class="text-end"><?= number_format((int) $b['stok'], 0, ',', '.') ?></td>
                        <td class="kolom-aksi">
                            <a href="form_barang.php?id=<?= (int) $b['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalHapus"
                                    data-id="<?= (int) $b['id'] ?>"
                                    data-nama="<?= e($b['nama']) ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="hapus_barang.php" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="judulModalHapus">Hapus barang</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Barang <strong class="nama-barang"></strong> akan dihapus permanen. Lanjutkan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
