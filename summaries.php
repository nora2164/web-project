<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/book_card.php';

$search   = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));

// Build the query from the filters the user chose.
$where  = [];
$params = [];

if ($search !== '') {
    // Escape LIKE wildcards so "%" or "_" in the search box are matched literally.
    $like = '%' . strtr($search, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
    $where[]  = "(title LIKE ? ESCAPE '!' OR author LIKE ? ESCAPE '!' OR category LIKE ? ESCAPE '!')";
    array_push($params, $like, $like, $like);
}
if ($category !== '') {
    $where[]  = 'category = ?';
    $params[] = $category;
}

$sql = 'SELECT * FROM books' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY title';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

$favoriteIds = favorite_book_ids($pdo, current_user_id());

if ($search !== '') {
    $heading = 'نتائج البحث عن: "' . $search . '"';
} elseif ($category !== '') {
    $heading = 'ملخصات: ' . $category;
} else {
    $heading = 'جميع ملخصات الكتب';
}

$pageTitle  = $search !== '' ? 'بحث: ' . $search : 'جميع الملخصات';
$activePage = 'summaries.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h1 class="page-title">📖 <?= e($heading) ?></h1>

    <form action="summaries.php" method="GET" class="search-form">
        <?php if ($category !== ''): ?>
            <input type="hidden" name="category" value="<?= e($category) ?>">
        <?php endif; ?>
        <input type="search" name="q" id="search" class="search-box" value="<?= e($search) ?>"
               placeholder="ابحث عن كتاب أو مؤلف أو تصنيف... (Ctrl + K)" aria-label="بحث">
        <button type="submit" class="btn">بحث</button>
    </form>

    <?php if ($search !== '' || $category !== ''): ?>
        <p class="results-meta">
            <?= count($books) ?> نتيجة
            · <a href="summaries.php">عرض جميع الملخصات</a>
        </p>
    <?php endif; ?>

    <div class="books-grid">
        <?php if ($books): ?>
            <?php foreach ($books as $book): ?>
                <?php render_book_card($book, in_array((int) $book['id'], $favoriteIds, true)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <?php if ($search !== ''): ?>
                    <h3>لا توجد نتائج لـ "<?= e($search) ?>"</h3>
                    <p>جرّب كلمة أخرى أو ابحث باسم المؤلف.</p>
                <?php elseif ($category !== ''): ?>
                    <h3>لا توجد ملخصات في تصنيف: <?= e($category) ?></h3>
                    <p>يمكنك استعراض جميع الملخصات المتاحة</p>
                <?php else: ?>
                    <h3>لا توجد ملخصات متاحة بعد</h3>
                    <p><a href="setup.php">اضغط هنا لإضافة الملخصات</a></p>
                <?php endif; ?>
                <a href="summaries.php" class="btn">عرض جميع الملخصات</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
