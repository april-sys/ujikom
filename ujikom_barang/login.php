<?php
require_once __DIR__ . '/includes/init.php';
require_guest();

$username = '';
$errors   = [];

if (is_post()) {
    if (!csrf_valid()) {
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $input    = ambil_input_login();
        $username = $input['username'];
        $errors   = validasi_login($input);

        if (!$errors) {
            try {
                if (login_user($input['username'], $input['password'])) {
                    redirect('dashboard.php');
                }
                $errors['umum'] = 'Username atau password salah.';
            } catch (PDOException $e) {
                $errors['umum'] = 'Terjadi kesalahan pada server. Coba lagi.';
            }
        }
    }
}

$judul = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <?php if (isset($errors['umum'])): ?>
                    <div class="alert alert-danger"><?= e($errors['umum']) ?></div>
                <?php endif; ?>
                <form method="post" action="login.php" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control<?= kelas_invalid($errors, 'username') ?>" id="username" name="username" maxlength="20" autocomplete="username" value="<?= e($username) ?>">
                        <?= pesan_error($errors, 'username') ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control<?= kelas_invalid($errors, 'password') ?>" id="password" name="password" autocomplete="current-password">
                        <?= pesan_error($errors, 'password') ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 mb-0 text-center">Belum punya akun? <a href="registrasi.php">Registrasi</a></p>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
