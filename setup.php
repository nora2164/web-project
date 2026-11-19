<?php
/**
 * One-time installer: creates the database, the tables and the sample books.
 * Safe to run more than once (it skips anything that already exists).
 * Delete this file before putting the site on a public server.
 */

define('SKIP_DB_CONNECT', true);
require __DIR__ . '/includes/bootstrap.php';

$steps = [];

try {
    if ($config['driver'] === 'mysql') {
        // Create the database first, then reconnect to it.
        $server = db_connect($config, false);
        $dbName = str_replace('`', '', $config['name']);
        $server->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $steps[] = "تم تجهيز قاعدة البيانات: $dbName";
        $schemaFile = __DIR__ . '/database/schema.mysql.sql';
    } else {
        $schemaFile = __DIR__ . '/database/schema.sqlite.sql';
    }

    $pdo = db_connect($config);

    // Run the schema file statement by statement (skipping CREATE DATABASE / USE,
    // which were handled above).
    $sql = preg_replace('/^\s*--.*$/m', '', file_get_contents($schemaFile));
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if (preg_match('/^(CREATE DATABASE|USE)\b/i', $statement)) {
            continue;
        }
        $pdo->exec($statement);
    }
    $steps[] = 'تم إنشاء الجداول: users, books, favorites, ratings, contact_messages';

    // Add the sample books only if the table is empty.
    $count = (int) $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
    if ($count === 0) {
        $insert = $pdo->prepare(
            'INSERT INTO books (title, author, category, reading_time, summary, key_points)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        foreach (require __DIR__ . '/database/seed_books.php' as $book) {
            $insert->execute([
                $book['title'], $book['author'], $book['category'],
                $book['reading_time'], $book['summary'], $book['key_points'],
            ]);
            $steps[] = 'تمت إضافة ملخص: ' . $book['title'];
        }
    } else {
        $steps[] = "الملخصات موجودة مسبقاً ($count ملخص)";
    }

    $ok = true;
} catch (PDOException $e) {
    $ok = false;
    $steps[] = 'خطأ: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تثبيت قاعدة البيانات - ملخصات</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container">
        <section class="panel">
            <h1 class="page-title"><?= $ok ? '🎉 اكتمل التثبيت' : '❌ تعذّر التثبيت' ?></h1>
            <ul class="setup-steps">
                <?php foreach ($steps as $step): ?>
                    <li><?= $ok ? '✅' : '•' ?> <?= e($step) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php if ($ok): ?>
                <a href="index.php" class="btn">اذهب إلى الصفحة الرئيسية</a>
            <?php else: ?>
                <p class="muted">تأكد أن MySQL يعمل وأن إعدادات الاتصال في config.php صحيحة.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
