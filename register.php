<?php
require __DIR__ . '/includes/bootstrap.php';

if (current_user()) {
    redirect('index.php');
}

$next   = safe_return_url($_GET['next'] ?? $_POST['next'] ?? null);
$name   = '';
$email  = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_with_csrf();

    $name     = trim((string) ($_POST['name'] ?? ''));
    $email    = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $confirm  = (string) ($_POST['password_confirm'] ?? '');

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'الرجاء كتابة الاسم (100 حرف كحد أقصى).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'البريد الإلكتروني غير صحيح.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.';
    }
    if ($password !== $confirm) {
        $errors[] = 'كلمتا المرور غير متطابقتين.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT 1 FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetchColumn()) {
            $errors[] = 'هذا البريد مسجّل مسبقاً. جرّب تسجيل الدخول.';
        }
    }

    if (!$errors) {
        // password_hash uses bcrypt with a random salt – the real password is never stored.
        $insert = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $insert->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

        log_in_user(['id' => $pdo->lastInsertId(), 'name' => $name]);
        flash('success', 'أهلاً ' . $name . '! تم إنشاء حسابك بنجاح.');
        redirect($next);
    }
}

$pageTitle  = 'حساب جديد';
$activePage = 'register.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel auth-panel">
    <h1 class="page-title">✨ إنشاء حساب جديد</h1>
    <p class="muted center">أنشئ حسابك لحفظ مفضلتك وتقييم الكتب.</p>

    <?php foreach ($errors as $error): ?>
        <div class="error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="POST" action="register.php" class="stacked-form">
        <?= csrf_field() ?>
        <input type="hidden" name="next" value="<?= e($next) ?>">
        <label>الاسم
            <input type="text" name="name" value="<?= e($name) ?>" maxlength="100" autocomplete="name" required>
        </label>
        <label>البريد الإلكتروني
            <input type="email" name="email" value="<?= e($email) ?>" autocomplete="email" required>
        </label>
        <label>كلمة المرور
            <input type="password" name="password" minlength="8" autocomplete="new-password" required>
        </label>
        <label>تأكيد كلمة المرور
            <input type="password" name="password_confirm" minlength="8" autocomplete="new-password" required>
        </label>
        <button type="submit" class="btn btn-success">إنشاء الحساب</button>
    </form>

    <p class="center">عندك حساب؟ <a href="login.php?next=<?= urlencode($next) ?>">سجّل دخولك</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
