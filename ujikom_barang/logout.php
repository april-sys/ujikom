<?php
require_once __DIR__ . '/includes/init.php';

if (is_post() && csrf_valid()) {
    logout_user();
    set_flash('success', 'Anda sudah logout.');
    redirect('login.php');
}

redirect(is_logged_in() ? 'dashboard.php' : 'login.php');
