<?php
require __DIR__ . '/includes/bootstrap.php';

require_post_with_csrf();

$bookId    = (int) ($_POST['book_id'] ?? 0);
$rating    = (int) ($_POST['rating'] ?? 0);
$returnUrl = 'summary.php?id=' . $bookId;
$userId    = require_login($returnUrl);

if ($rating < 1 || $rating > 5) {
    flash('error', 'تقييم غير صالح. الرجاء اختيار تقييم بين 1 و 5 نجوم.');
    redirect($returnUrl);
}

$exists = $pdo->prepare('SELECT 1 FROM books WHERE id = ?');
$exists->execute([$bookId]);
if (!$exists->fetchColumn()) {
    redirect('summaries.php');
}

$pdo->beginTransaction();

$existing = $pdo->prepare('SELECT id FROM ratings WHERE user_id = ? AND book_id = ?');
$existing->execute([$userId, $bookId]);
$ratingId = $existing->fetchColumn();

if ($ratingId) {
    $pdo->prepare('UPDATE ratings SET rating = ? WHERE id = ?')->execute([$rating, $ratingId]);
} else {
    $pdo->prepare('INSERT INTO ratings (user_id, book_id, rating) VALUES (?, ?, ?)')->execute([$userId, $bookId, $rating]);
}

refresh_book_rating($pdo, $bookId);
$pdo->commit();

flash('success', 'شكراً لتقييمك! تم حفظ التقييم بنجاح.');
redirect($returnUrl);
