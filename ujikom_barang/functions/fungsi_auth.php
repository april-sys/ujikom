<?php
// Fungsi autentikasi dan proteksi halaman

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('warning', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

function require_guest(): void
{
    if (is_logged_in()) {
        redirect('dashboard.php');
    }
}

function cari_user(string $username): ?array
{
    $st = db()->prepare('SELECT id, username, nama, password FROM users WHERE username = :username LIMIT 1');
    $st->execute([':username' => $username]);
    $baris = $st->fetch();
    return $baris ?: null;
}

function registrasi_user(string $username, string $nama, string $password): void
{
    $st = db()->prepare('INSERT INTO users (username, nama, password) VALUES (:username, :nama, :password)');
    $st->execute([
        ':username' => $username,
        ':nama'     => $nama,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
}

function login_user(string $username, string $password): bool
{
    $user = cari_user($username);

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = (int) $user['id'];
    $_SESSION['user_nama'] = $user['nama'];
    return true;
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();

    // Mulai session baru supaya pesan flash bisa ditampilkan
    session_start();
    session_regenerate_id(true);
}
