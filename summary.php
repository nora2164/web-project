<?php
require __DIR__ . '/includes/bootstrap.php';

$bookId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
$stmt->execute([$bookId]);
$book = $stmt->fetch();

if (!$book) {
    redirect('summaries.php');
}

$userId     = current_user_id();
$isFavorite = in_array($bookId, favorite_book_ids($pdo, $userId), true);

// The logged-in user's own rating (0 = not rated yet).
$userRating = 0;
if ($userId !== null) {
    $stmt = $pdo->prepare('SELECT rating FROM ratings WHERE book_id = ? AND user_id = ?');
    $stmt->execute([$bookId, $userId]);
    $userRating = (int) ($stmt->fetchColumn() ?: 0);
}

$avgRating   = (float) $book['average_rating'];
$ratingCount = (int) $book['rating_count'];
$returnUrl   = 'summary.php?id=' . $bookId;

$pageTitle  = $book['title'];
$activePage = '';
require __DIR__ . '/includes/header.php';
?>

<article class="panel">
    <div class="summary-header">
        <div>
            <h1><?= e($book['title']) ?></h1>
            <div class="muted">بواسطة: <?= e($book['author']) ?> · <?= e($book['category']) ?></div>
        </div>
        <div class="summary-meta">
            <span class="reading-time">⏱ <?= e($book['reading_time']) ?></span>
            <span class="pill"><?= stars($avgRating) ?> <?= round($avgRating, 1) ?> / 5 (<?= $ratingCount ?> تقييم)</span>
            <?php include __DIR__ . '/includes/favorite_button.php'; ?>
        </div>
    </div>

    <section class="summary-content">
        <h2>📋 ملخص الكتاب</h2>
        <p><?= nl2br(e($book['summary'])) ?></p>
    </section>

    <?php if (!empty($book['key_points'])): ?>
        <section class="key-points">
            <h2>🎯 النقاط الرئيسية</h2>
            <div><?= nl2br(e($book['key_points'])) ?></div>
        </section>
    <?php endif; ?>

    <section class="rating-box">
        <h3>⭐ تقييم الكتاب</h3>

        <?php if ($userId === null): ?>
            <p class="muted">
                <a href="login.php?next=<?= urlencode($returnUrl) ?>">سجّل دخولك</a> لتقييم هذا الكتاب.
            </p>
        <?php else: ?>
            <p class="muted">
                <?php if ($userRating > 0): ?>
                    تقييمك الحالي: <strong><?= $userRating ?> / 5</strong> — يمكنك تعديله في أي وقت.
                <?php else: ?>
                    قيّم هذا الكتاب من 1 إلى 5 نجوم:
                <?php endif; ?>
            </p>
            <form method="POST" action="add_rating.php" class="star-form">
                <?= csrf_field() ?>
                <input type="hidden" name="book_id" value="<?= $bookId ?>">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="submit" name="rating" value="<?= $i ?>"
                            class="star-rating-btn <?= $i <= $userRating ? 'rated' : '' ?>"
                            data-rating="<?= $i ?>" aria-label="<?= $i ?> نجوم">
                        <?= $i <= $userRating ? '⭐' : '☆' ?>
                    </button>
                <?php endfor; ?>
            </form>
        <?php endif; ?>
    </section>

    <div class="summary-footer">
        <a href="summaries.php" class="btn btn-secondary">عودة للملخصات</a>
    </div>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
