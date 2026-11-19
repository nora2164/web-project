<?php
require __DIR__ . '/includes/bootstrap.php';

if (current_user()) {
    redirect('index.php');
}

$next  = safe_return_url($_GET['next'] ?? $_POST['next'] ?? null);
$email = '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_with_csrf();

    $email    = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Same message whether the email or the password is wrong,
    // so the form doesn't reveal which emails are registered.
    if ($user && password_verify($password, $user['password_hash'])) {
        log_in_user($user);
        flash('success', 'أهلاً بعودتك يا ' . $user['name'] . '!');
        redirect($next);
    }
    $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
}

$pageTitle  = 'تسجيل الدخول';
$activePage = 'login.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel auth-panel">
    <h1 class="page-title">🔐 تسجيل الدخول</h1>

    <?php if ($error): ?>
        <div class="error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" class="stacked-form">
        <?= csrf_field() ?>
        <input type="hidden" name="next" value="<?= e($next) ?>">
        <label>البريد الإلكتروني
            <input type="email" name="email" value="<?= e($email) ?>" autocomplete="email" required autofocus>
        </label>
        <label>كلمة المرور
            <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button type="submit" class="btn">دخول</button>
    </form>

    <p class="center">ما عندك حساب؟ <a href="register.php?next=<?= urlencode($next) ?>">أنشئ حساباً جديداً</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
