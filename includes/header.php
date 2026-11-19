<?php
/**
 * Shared page header. Before including it, set:
 *   $pageTitle  – text for the <title> tag
 *   $activePage – file name of the current page, to highlight it in the menu
 */
$pageTitle  = $pageTitle ?? 'ملخصات';
$activePage = $activePage ?? '';
$user       = current_user();

$navLinks = [
    'index.php'      => 'الرئيسية',
    'summaries.php'  => 'جميع الملخصات',
    'categories.php' => 'التصنيفات',
    'favorites.php'  => 'المفضلة',
    'about.php'      => 'من نحن',
    'contact.php'    => 'اتصل بنا',
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($pageTitle) ?> - ملخصات</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <a href="index.php" class="logo">📚 ملخصات</a>
            <ul class="nav-links">
                <?php foreach ($navLinks as $file => $label): ?>
                    <li><a href="<?= $file ?>" class="<?= $file === $activePage ? 'active' : '' ?>"><?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="nav-user">
                <?php if ($user): ?>
                    <span class="nav-greeting">👋 <?= e($user['name']) ?></span>
                    <form method="POST" action="logout.php" class="inline-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-secondary btn-sm">خروج</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm <?= $activePage === 'login.php' ? 'btn-secondary' : '' ?>">دخول</a>
                    <a href="register.php" class="btn btn-success btn-sm">حساب جديد</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <?php foreach (take_flashes() as $flash): ?>
            <div class="<?= $flash['type'] === 'error' ? 'error' : 'message' ?>"><?= e($flash['message']) ?></div>
        <?php endforeach; ?>
