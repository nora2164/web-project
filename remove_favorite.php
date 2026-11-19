<?php
require __DIR__ . '/includes/bootstrap.php';

require_post_with_csrf();

$bookId    = (int) ($_POST['book_id'] ?? 0);
$returnUrl = safe_return_url($_POST['return'] ?? null, 'favorites.php');
$userId    = require_login($returnUrl);

// user_id in the WHERE clause: users can only remove their own favorites.
$delete = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND book_id = ?');
$delete->execute([$userId, $bookId]);

flash('success', 'تمت إزالة الملخص من المفضلة.');
redirect($returnUrl);
