<?php
// Header + navbar. Set $judul di halaman sebelum memuat file ini.
$judul   = $judul ?? 'Inventaris Barang';
$halaman = basename($_SERVER['SCRIPT_NAME']);
$flash   = get_flash();

$aktif = function (array $files) use ($halaman) {
    return in_array($halaman, $files, true) ? ' active' : '';
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul) ?> - Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<header class="app-header">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-1">
        <span class="app-title">Inventaris Barang</span>
        <?php if (is_logged_in()): ?>
            <span class="app-user">Masuk sebagai <?= e($_SESSION['user_nama'] ?? '') ?></span>
        <?php endif; ?>
    </div>
</header>

<nav class="navbar navbar-expand-md navbar-dark navbar-app">
    <div class="container">
        <span class="navbar-text d-md-none">Menu</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menuUtama" aria-controls="menuUtama"
                aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuUtama">
            <?php if (is_logged_in()): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link<?= $aktif(['dashboard.php']) ?>" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link<?= $aktif(['data_barang.php']) ?>" href="data_barang.php">Data Barang</a></li>
                    <li class="nav-item"><a class="nav-link<?= $aktif(['form_barang.php']) ?>" href="form_barang.php">Tambah Barang</a></li>
                    <li class="nav-item"><a class="nav-link<?= $aktif(['laporan.php']) ?>" href="laporan.php">Laporan</a></li>
                </ul>
                <form method="post" action="logout.php" class="my-2 my-md-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            <?php else: ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link<?= $aktif(['login.php']) ?>" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link<?= $aktif(['registrasi.php']) ?>" href="registrasi.php">Registrasi</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['tipe']) ?>" role="alert"><?= e($flash['pesan']) ?></div>
<?php endif; ?>
