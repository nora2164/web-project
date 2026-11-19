<?php
require __DIR__ . '/includes/bootstrap.php';

// Icon, colors and description for each category shown on this page.
$categories = [
    'تطوير الذات' => ['🚀', '#667eea', '#764ba2', 'اكتشف طرقاً جديدة لتحسين نفسك وتطوير مهاراتك الشخصية والمهنية'],
    'مال وأعمال'  => ['💼', '#4CAF50', '#45a049', 'تعلم أسرار النجاح المالي وإدارة الأعمال من خبراء العالم'],
    'سيرة ذاتية'  => ['👤', '#ff9800', '#f57c00', 'استلهم من حياة العظماء وقصص نجاحهم الملهمة'],
    'ديني'        => ['🕌', '#9c27b0', '#7b1fa2', 'تعمق في فهم الدين وتزود بمعارف روحية قيمة'],
    'علم النفس'   => ['🧠', '#2196F3', '#1976D2', 'افهم العقل البشري وسلوكيات الإنسان بشكل أعمق'],
    'تكنولوجيا'   => ['💻', '#607d8b', '#455a64', 'ابق على اطلاع بأحدث توجهات التكنولوجيا والابتكار'],
];

// Real number of summaries per category, from the database.
$counts = $pdo->query('SELECT category, COUNT(*) AS total FROM books GROUP BY category')
              ->fetchAll(PDO::FETCH_KEY_PAIR);

// Categories that exist in the database but not in the list above still appear.
foreach (array_keys($counts) as $name) {
    if ($name !== null && $name !== '' && !isset($categories[$name])) {
        $categories[$name] = ['📚', '#667eea', '#764ba2', ''];
    }
}

$pageTitle  = 'التصنيفات';
$activePage = 'categories.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h1 class="page-title">📂 التصنيفات</h1>

    <div class="books-grid">
        <?php foreach ($categories as $name => [$icon, $from, $to, $description]): ?>
            <?php $total = (int) ($counts[$name] ?? 0); ?>
            <a class="book-card category-card" href="summaries.php?category=<?= urlencode($name) ?>">
                <div class="book-cover" style="background: linear-gradient(135deg, <?= $from ?> 0%, <?= $to ?> 100%);"><?= $icon ?></div>
                <div class="book-info">
                    <h3><?= e($name) ?></h3>
                    <?php if ($description): ?>
                        <p class="book-excerpt"><?= e($description) ?></p>
                    <?php endif; ?>
                    <span class="pill">📚 <?= $total > 0 ? $total . ($total === 1 ? ' ملخص' : ' ملخصات') : 'قريباً' ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
