<?php
/**
 * Renders one book card.
 *
 * @param array $book        Row from the books table
 * @param bool  $isFavorite  Whether the logged-in user has saved this book
 */
function render_book_card(array $book, bool $isFavorite = false): void
{
    $id     = (int) $book['id'];
    $rating = (float) ($book['average_rating'] ?? 0);
    ?>
    <div class="book-card">
        <div class="book-cover">📖</div>
        <div class="book-info">
            <h3><?= e($book['title']) ?></h3>
            <div class="book-author">بواسطة: <?= e($book['author']) ?></div>
            <div class="book-category"><?= e($book['category']) ?></div>
            <div class="reading-time">⏱ <?= e($book['reading_time']) ?></div>

            <div class="card-rating">
                <span class="muted">التقييم:</span>
                <?= stars($rating) ?>
                <span class="muted small">(<?= round($rating, 1) ?>)</span>
            </div>

            <p class="book-excerpt"><?= e(excerpt($book['summary'])) ?></p>

            <div class="card-actions">
                <a href="summary.php?id=<?= $id ?>" class="btn btn-success btn-sm">قراءة الملخص</a>
                <?php if ($isFavorite): ?>
                    <button class="btn btn-secondary btn-sm" disabled>في المفضلة ❤</button>
                <?php else: ?>
                    <form method="POST" action="add_favorite.php" class="inline-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="book_id" value="<?= $id ?>">
                        <input type="hidden" name="return" value="<?= e(current_page_url()) ?>">
                        <button type="submit" class="btn btn-sm">إضافة للمفضلة</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
