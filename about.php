<?php
require __DIR__ . '/includes/bootstrap.php';

// Live numbers from the database.
$stats = [
    'ملخص كتاب'     => (int) $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn(),
    'قارئ مسجّل'    => (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'مجالات معرفية' => (int) $pdo->query('SELECT COUNT(DISTINCT category) FROM books')->fetchColumn(),
    'تقييم'         => (int) $pdo->query('SELECT COUNT(*) FROM ratings')->fetchColumn(),
];

$pageTitle  = 'من نحن';
$activePage = 'about.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h1 class="page-title">من نحن</h1>

    <div class="about-intro">
        <div class="feature-icon">📚</div>
        <h2>رسالتنا</h2>
        <p>
            منصة لملخصات الكتب باللغة العربية، نهدف إلى نشر المعرفة وتسهيل الوصول إلى أفضل الكتب في أقل وقت ممكن.
            نؤمن بأن القراءة هي بوابة التطور الشخصي والمهني.
        </p>
    </div>

    <div class="features-grid">
        <div class="feature feature-card">
            <div class="feature-icon">🎯</div>
            <h3>رؤيتنا</h3>
            <p>أن يكون كل عربي قارئاً ومطلعاً على أفضل الكتب العالمية والعربية</p>
        </div>
        <div class="feature feature-card">
            <div class="feature-icon">⚡</div>
            <h3>ما نقدمه</h3>
            <p>ملخصات مختصرة ودقيقة لأفضل الكتب في مختلف المجالات</p>
        </div>
        <div class="feature feature-card">
            <div class="feature-icon">❤</div>
            <h3>قيمنا</h3>
            <p>الجودة، الدقة، التميز، وخدمة القارئ العربي</p>
        </div>
    </div>

    <div class="stats">
        <h2>إحصائياتنا</h2>
        <div class="stats-grid">
            <?php foreach ($stats as $label => $value): ?>
                <div>
                    <div class="stat-value"><?= number_format($value) ?></div>
                    <div class="muted"><?= $label ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
