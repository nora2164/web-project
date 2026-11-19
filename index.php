<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/book_card.php';

$latestBooks = $pdo->query('SELECT * FROM books ORDER BY created_at DESC, id DESC LIMIT 6')->fetchAll();
$favoriteIds = favorite_book_ids($pdo, current_user_id());

$pageTitle  = 'الرئيسية';
$activePage = 'index.php';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <h1>اكتشف عالم المعرفة من خلال الملخصات</h1>
    <p>اقرأ ملخصات لأفضل الكتب في أقل وقت. وفر الوقت واكتسب المعرفة!</p>

    <form action="summaries.php" method="GET" class="hero-search">
        <input type="search" name="q" class="search-box" placeholder="ابحث عن كتاب أو مؤلف..." aria-label="بحث">
        <button type="submit" class="btn">بحث</button>
    </form>

    <div class="hero-actions">
        <a href="summaries.php" class="btn">استعرض الملخصات</a>
        <a href="favorites.php" class="btn btn-success">ملخصاتي المفضلة</a>
    </div>
</section>

<section class="panel">
    <h2 class="section-title">أحدث الملخصات</h2>
    <div class="books-grid">
        <?php if ($latestBooks): ?>
            <?php foreach ($latestBooks as $book): ?>
                <?php render_book_card($book, in_array((int) $book['id'], $favoriteIds, true)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>لا توجد ملخصات متاحة حالياً. <a href="setup.php">اضغط هنا لإضافة الملخصات</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="panel panel-soft">
    <h2 class="section-title">لماذا ملخصات الكتب؟</h2>
    <div class="features-grid">
        <div class="feature">
            <div class="feature-icon">⏱</div>
            <h3>توفير الوقت</h3>
            <p>اقرأ الكتاب في دقائق بدلاً من ساعات</p>
        </div>
        <div class="feature">
            <div class="feature-icon">🎯</div>
            <h3>تركيز على الأفكار</h3>
            <p>احصل على الأفكار الرئيسية فقط</p>
        </div>
        <div class="feature">
            <div class="feature-icon">❤</div>
            <h3>المفضلة</h3>
            <p>أنشئ حسابك واحفظ الملخصات المفضلة لديك بالترتيب الذي تريده</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
