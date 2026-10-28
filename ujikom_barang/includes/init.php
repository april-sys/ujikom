<?php
// Dimuat di awal setiap halaman: session, koneksi, dan semua fungsi
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/fungsi_umum.php';
require_once __DIR__ . '/../functions/fungsi_validasi.php';
require_once __DIR__ . '/../functions/fungsi_auth.php';
require_once __DIR__ . '/../functions/fungsi_barang.php';
require_once __DIR__ . '/../functions/fungsi_hitung.php';
