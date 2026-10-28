<?php
require_once __DIR__ . '/includes/init.php';
require_login();

$id     = 0;
$edit   = false;
$data   = ['kode' => '', 'nama' => '', 'kategori' => '', 'harga' => '', 'stok' => ''];
$errors = [];

// Mode edit: ?id=... harus valid dan datanya harus ada
if (isset($_GET['id'])) {
    $idParam = is_string($_GET['id']) ? $_GET['id'] : '';
    $id = filter_var($idParam, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($id === false) {
        set_flash('danger', 'ID barang tidak valid.');
        redirect('data_barang.php');
    }

    $barang = ambil_barang($id);
    if (!$barang) {
        set_flash('danger', 'Barang tidak ditemukan.');
        redirect('data_barang.php');
    }

    $edit = true;
    $data = [
        'kode'     => $barang['kode'],
        'nama'     => $barang['nama'],
        'kategori' => $barang['kategori'],
        'harga'    => $barang['harga'],
        'stok'     => $barang['stok'],
    ];
}

if (is_post()) {
    if (!csrf_valid()) {
        set_flash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
        redirect($edit ? 'form_barang.php?id=' . $id : 'form_barang.php');
    }

    $data   = ambil_input_barang();
    $errors = validasi_barang($data);

    if (!$errors && kode_sudah_ada($data['kode'], $id)) {
        $errors['kode'] = 'Kode barang sudah dipakai.';
    }

    if (!$errors) {
        try {
            if ($edit) {
                ubah_barang($id, $data);
                set_flash('success', 'Data barang berhasil diperbarui.');
            } else {
                tambah_barang($data);
                set_flash('success', 'Barang berhasil ditambahkan.');
            }
            redirect('data_barang.php');
        } catch (PDOException $e) {
            if (is_duplicate_error($e)) {
                $errors['kode'] = 'Kode barang sudah dipakai.';
            } else {
                $errors['umum'] = 'Terjadi kesalahan saat menyimpan data. Coba lagi.';
            }
        }
    }
}

$judul = $edit ? 'Edit Barang' : 'Tambah Barang';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><?= e($judul) ?></div>
            <div class="card-body">
                <?php if (isset($errors['umum'])): ?>
                    <div class="alert alert-danger"><?= e($errors['umum']) ?></div>
                <?php endif; ?>
                <form method="post" action="<?= $edit ? 'form_barang.php?id=' . (int) $id : 'form_barang.php' ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode barang</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'kode') ?>" id="kode" name="kode" maxlength="12" value="<?= e($data['kode']) ?>">
                        <?= pesan_error($errors, 'kode') ?>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama barang</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'nama') ?>" id="nama" name="nama" maxlength="100" value="<?= e($data['nama']) ?>">
                        <?= pesan_error($errors, 'nama') ?>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'kategori') ?>" id="kategori" name="kategori" maxlength="50" value="<?= e($data['kategori']) ?>">
                        <?= pesan_error($errors, 'kategori') ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="harga" class="form-label">Harga (Rp)</label>
                            <input type="text" inputmode="numeric" class="form-control<?= kelas_invalid($errors, 'harga') ?>" id="harga" name="harga" maxlength="9" value="<?= e($data['harga']) ?>">
                            <?= pesan_error($errors, 'harga') ?>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="text" inputmode="numeric" class="form-control<?= kelas_invalid($errors, 'stok') ?>" id="stok" name="stok" maxlength="7" value="<?= e($data['stok']) ?>">
                            <?= pesan_error($errors, 'stok') ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="data_barang.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
