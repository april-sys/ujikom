<?php
require_once __DIR__ . '/includes/init.php';
require_guest();

$data   = ['nama' => '', 'username' => ''];
$errors = [];

if (is_post()) {
    if (!csrf_valid()) {
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $input  = ambil_input_registrasi();
        $data   = ['nama' => $input['nama'], 'username' => $input['username']];
        $errors = validasi_registrasi($input);

        if (!$errors && cari_user($input['username'])) {
            $errors['username'] = 'Username sudah dipakai.';
        }

        if (!$errors) {
            try {
                registrasi_user($input['username'], $input['nama'], $input['password']);
                set_flash('success', 'Registrasi berhasil. Silakan login.');
                redirect('login.php');
            } catch (PDOException $e) {
                if (is_duplicate_error($e)) {
                    $errors['username'] = 'Username sudah dipakai.';
                } else {
                    $errors['umum'] = 'Terjadi kesalahan saat menyimpan data. Coba lagi.';
                }
            }
        }
    }
}

$judul = 'Registrasi';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card">
            <div class="card-header">Registrasi</div>
            <div class="card-body">
                <?php if (isset($errors['umum'])): ?>
                    <div class="alert alert-danger"><?= e($errors['umum']) ?></div>
                <?php endif; ?>
                <form method="post" action="registrasi.php" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama lengkap</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'nama') ?>" id="nama" name="nama" maxlength="50" value="<?= e($data['nama']) ?>">
                        <?= pesan_error($errors, 'nama') ?>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'username') ?>" id="username" name="username" maxlength="20" autocomplete="username" value="<?= e($data['username']) ?>">
                        <?= pesan_error($errors, 'username') ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control<?= kelas_invalid($errors, 'password') ?>" id="password" name="password" autocomplete="new-password">
                        <?= pesan_error($errors, 'password') ?>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi" class="form-label">Konfirmasi password</label>
                        <input type="password" class="form-control<?= kelas_invalid($errors, 'konfirmasi') ?>" id="konfirmasi" name="konfirmasi" autocomplete="new-password">
                        <?= pesan_error($errors, 'konfirmasi') ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
                <p class="mt-3 mb-0 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
