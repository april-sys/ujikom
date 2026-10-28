<?php
require_once __DIR__ . '/includes/init.php';
require_login();

if (!is_post()) {
    redirect('data_barang.php');
}

if (!csrf_valid()) {
    set_flash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
    redirect('data_barang.php');
}

$id = filter_var(post_str('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id === false) {
    set_flash('danger', 'ID barang tidak valid.');
    redirect('data_barang.php');
}

try {
    if (hapus_barang($id)) {
        set_flash('success', 'Barang berhasil dihapus.');
    } else {
        set_flash('danger', 'Barang tidak ditemukan atau sudah dihapus.');
    }
} catch (PDOException $e) {
    set_flash('danger', 'Terjadi kesalahan saat menghapus data.');
}

redirect('data_barang.php');
