<?php
require __DIR__ . '/includes/bootstrap.php';

// POST + CSRF token, so another website can't log the user out with a hidden link.
require_post_with_csrf();

$_SESSION = [];
session_regenerate_id(true);

flash('success', 'تم تسجيل الخروج. نراك قريباً!');
redirect('index.php');
