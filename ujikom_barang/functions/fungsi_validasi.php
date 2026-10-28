<?php
// Fungsi validasi dan pengambilan input form

function ambil_input_registrasi(): array
{
    return [
        'nama'       => post_str('nama'),
        'username'   => post_str('username'),
        'password'   => post_raw('password'),
        'konfirmasi' => post_raw('konfirmasi'),
    ];
}

function validasi_registrasi(array $d): array
{
    $err = [];

    if ($d['nama'] === '') {
        $err['nama'] = 'Nama lengkap wajib diisi.';
    } elseif (mb_strlen($d['nama']) > 50) {
        $err['nama'] = 'Nama lengkap maksimal 50 karakter.';
    }

    if ($d['username'] === '') {
        $err['username'] = 'Username wajib diisi.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{4,20}$/', $d['username'])) {
        $err['username'] = 'Username 4-20 karakter, hanya huruf, angka, dan underscore.';
    }

    if ($d['password'] === '') {
        $err['password'] = 'Password wajib diisi.';
    } elseif (strlen($d['password']) < 6) {
        $err['password'] = 'Password minimal 6 karakter.';
    } elseif (strlen($d['password']) > 72) {
        $err['password'] = 'Password maksimal 72 karakter.';
    }

    if (!isset($err['password']) && $d['konfirmasi'] !== $d['password']) {
        $err['konfirmasi'] = 'Konfirmasi password tidak sama.';
    }

    return $err;
}

function ambil_input_login(): array
{
    return [
        'username' => post_str('username'),
        'password' => post_raw('password'),
    ];
}

function validasi_login(array $d): array
{
    $err = [];

    if ($d['username'] === '') {
        $err['username'] = 'Username wajib diisi.';
    }
    if ($d['password'] === '') {
        $err['password'] = 'Password wajib diisi.';
    }

    return $err;
}

function ambil_input_barang(): array
{
    return [
        'kode'     => strtoupper(post_str('kode')),
        'nama'     => post_str('nama'),
        'kategori' => post_str('kategori'),
        'harga'    => post_str('harga'),
        'stok'     => post_str('stok'),
    ];
}

function validasi_barang(array $d): array
{
    $err = [];

    if ($d['kode'] === '') {
        $err['kode'] = 'Kode barang wajib diisi.';
    } elseif (!preg_match('/^[A-Z0-9\-]{2,12}$/', $d['kode'])) {
        $err['kode'] = 'Kode 2-12 karakter, hanya huruf, angka, dan tanda hubung.';
    }

    if ($d['nama'] === '') {
        $err['nama'] = 'Nama barang wajib diisi.';
    } elseif (mb_strlen($d['nama']) > 100) {
        $err['nama'] = 'Nama barang maksimal 100 karakter.';
    }

    if ($d['kategori'] === '') {
        $err['kategori'] = 'Kategori wajib diisi.';
    } elseif (mb_strlen($d['kategori']) > 50) {
        $err['kategori'] = 'Kategori maksimal 50 karakter.';
    }

    if ($d['harga'] === '') {
        $err['harga'] = 'Harga wajib diisi.';
    } elseif (!ctype_digit($d['harga']) || strlen($d['harga']) > 9) {
        $err['harga'] = 'Harga harus angka bulat tanpa titik atau koma (maksimal 9 digit).';
    }

    if ($d['stok'] === '') {
        $err['stok'] = 'Stok wajib diisi.';
    } elseif (!ctype_digit($d['stok']) || strlen($d['stok']) > 7) {
        $err['stok'] = 'Stok harus angka bulat 0 atau lebih (maksimal 7 digit).';
    }

    return $err;
}
