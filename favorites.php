<?php
require __DIR__ . '/includes/bootstrap.php';

$userId = require_login('favorites.php');

$stmt = $pdo->prepare(
    'SELECT b.*, f.id AS favorite_id
       FROM favorites f
       JOIN books b ON b.id = f.book_id
      WHERE f.user_id = ?
      ORDER BY f.display_order ASC, f.added_at DESC'
);
$stmt->execute([$userId]);
$favorites = $stmt->fetchAll();

$pageTitle    = 'المفضلة';
$activePage   = 'favorites.php';
$extraScripts = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>'
              . '<script>initializeFavoriteOrdering();</script>';
require __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h1 class="page-title">❤ ملخصاتي المفضلة</h1>

    <?php if ($favorites): ?>
        <div class="hint">💡 يمكنك سحب الملخصات من أيقونة ↕ وإفلاتها لإعادة ترتيبها حسب تفضيلك</div>
    <?php endif; ?>

    <div class="books-grid" id="favorites-container">
        <?php foreach ($favorites as $book): ?>
            <div class="book-card" data-favorite-id="<?= (int) $book['favorite_id'] ?>">
                <div class="book-cover">❤</div>
                <div class="book-info">
                    <div class="card-title-row">
                        <h3><?= e($book['title']) ?></h3>
                        <span class="drag-handle" title="اسحب لإعادة الترتيب">↕</span>
                    </div>
                    <div class="book-author">بواسطة: <?= e($book['author']) ?></div>
                    <div class="book-category"><?= e($book['category']) ?></div>
                    <div class="reading-time">⏱ <?= e($book['reading_time']) ?></div>
                    <p class="book-excerpt"><?= e(excerpt($book['summary'])) ?></p>
                    <div class="card-actions">
                        <a href="summary.php?id=<?= (int) $book['id'] ?>" class="btn btn-success btn-sm">قراءة الملخص</a>
                        <form method="POST" action="remove_favorite.php" class="inline-form"
                              onsubmit="return confirm('هل تريد إزالة هذا الملخص من المفضلة؟')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="book_id" value="<?= (int) $book['id'] ?>">
                            <input type="hidden" name="return" value="favorites.php">
                            <button type="submit" class="btn btn-danger btn-sm">إزالة</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$favorites): ?>
            <div class="empty-state">
                <div class="empty-state-icon">❤</div>
                <h3>لا توجد ملخصات في المفضلة</h3>
                <p>اذهب إلى صفحة الملخصات وأضف بعض الملخصات إلى المفضلة!</p>
                <a href="summaries.php" class="btn">استعرض الملخصات</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
