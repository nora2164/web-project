<?php
require __DIR__ . '/includes/bootstrap.php';

require_post_with_csrf();

$bookId    = (int) ($_POST['book_id'] ?? 0);
$returnUrl = safe_return_url($_POST['return'] ?? null, 'summaries.php');
$userId    = require_login($returnUrl);

$exists = $pdo->prepare('SELECT 1 FROM books WHERE id = ?');
$exists->execute([$bookId]);
if (!$exists->fetchColumn()) {
    flash('error', 'الكتاب غير موجود.');
    redirect($returnUrl);
}

$already = $pdo->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND book_id = ?');
$already->execute([$userId, $bookId]);

if ($already->fetchColumn()) {
    flash('success', 'الملخص موجود في مفضلتك مسبقاً.');
} else {
    // New favorites go to the end of the user's list.
    $maxOrder = $pdo->prepare('SELECT COALESCE(MAX(display_order), 0) FROM favorites WHERE user_id = ?');
    $maxOrder->execute([$userId]);

    $insert = $pdo->prepare('INSERT INTO favorites (user_id, book_id, display_order) VALUES (?, ?, ?)');
    $insert->execute([$userId, $bookId, (int) $maxOrder->fetchColumn() + 1]);
    flash('success', 'تمت إضافة الملخص إلى المفضلة بنجاح.');
}

redirect($returnUrl);
