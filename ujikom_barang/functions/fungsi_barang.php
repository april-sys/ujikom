<?php
// Fungsi CRUD data barang

function ambil_semua_barang(string $cari = ''): array
{
    if ($cari !== '') {
        $like = '%' . addcslashes($cari, '%_\\') . '%';
        $st = db()->prepare(
            'SELECT * FROM barang
             WHERE kode LIKE :k1 OR nama LIKE :k2 OR kategori LIKE :k3
             ORDER BY kode ASC'
        );
        $st->execute([':k1' => $like, ':k2' => $like, ':k3' => $like]);
    } else {
        $st = db()->query('SELECT * FROM barang ORDER BY kode ASC');
    }

    return $st->fetchAll();
}

function ambil_barang_terbaru(int $jumlah = 5): array
{
    $jumlah = max(1, $jumlah);
    return db()->query('SELECT * FROM barang ORDER BY id DESC LIMIT ' . $jumlah)->fetchAll();
}

function ambil_barang(int $id): ?array
{
    $st = db()->prepare('SELECT * FROM barang WHERE id = :id LIMIT 1');
    $st->execute([':id' => $id]);
    $baris = $st->fetch();
    return $baris ?: null;
}

function kode_sudah_ada(string $kode, int $kecualiId = 0): bool
{
    $st = db()->prepare('SELECT COUNT(*) FROM barang WHERE kode = :kode AND id <> :id');
    $st->execute([':kode' => $kode, ':id' => $kecualiId]);
    return (int) $st->fetchColumn() > 0;
}

function tambah_barang(array $d): void
{
    $st = db()->prepare(
        'INSERT INTO barang (kode, nama, kategori, harga, stok)
         VALUES (:kode, :nama, :kategori, :harga, :stok)'
    );
    $st->execute([
        ':kode'     => $d['kode'],
        ':nama'     => $d['nama'],
        ':kategori' => $d['kategori'],
        ':harga'    => (int) $d['harga'],
        ':stok'     => (int) $d['stok'],
    ]);
}

function ubah_barang(int $id, array $d): void
{
    $st = db()->prepare(
        'UPDATE barang
         SET kode = :kode, nama = :nama, kategori = :kategori, harga = :harga, stok = :stok
         WHERE id = :id'
    );
    $st->execute([
        ':kode'     => $d['kode'],
        ':nama'     => $d['nama'],
        ':kategori' => $d['kategori'],
        ':harga'    => (int) $d['harga'],
        ':stok'     => (int) $d['stok'],
        ':id'       => $id,
    ]);
}

function hapus_barang(int $id): bool
{
    $st = db()->prepare('DELETE FROM barang WHERE id = :id');
    $st->execute([':id' => $id]);
    return $st->rowCount() > 0;
}
