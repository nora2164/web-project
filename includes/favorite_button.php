<?php
/**
 * Add / remove favorite button for one book.
 * Expects: $bookId, $isFavorite, $returnUrl
 */
?>
<form method="POST" action="<?= $isFavorite ? 'remove_favorite.php' : 'add_favorite.php' ?>" class="inline-form">
    <?= csrf_field() ?>
    <input type="hidden" name="book_id" value="<?= (int) $bookId ?>">
    <input type="hidden" name="return" value="<?= e($returnUrl) ?>">
    <?php if ($isFavorite): ?>
        <button type="submit" class="btn btn-secondary">إزالة من المفضلة</button>
    <?php else: ?>
        <button type="submit" class="btn">إضافة إلى المفضلة ❤</button>
    <?php endif; ?>
</form>
