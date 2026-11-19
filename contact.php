<?php
require __DIR__ . '/includes/bootstrap.php';

$subjects = [
    'suggestion'  => 'مقترح',
    'complaint'   => 'شكوى',
    'inquiry'     => 'استفسار',
    'cooperation' => 'تعاون',
];

$old    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_with_csrf();

    foreach (array_keys($old) as $field) {
        $old[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    if ($old['name'] === '' || mb_strlen($old['name']) > 100) {
        $errors[] = 'الرجاء كتابة الاسم.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'البريد الإلكتروني غير صحيح.';
    }
    if (!isset($subjects[$old['subject']])) {
        $errors[] = 'الرجاء اختيار الموضوع.';
    }
    if (mb_strlen($old['message']) < 10) {
        $errors[] = 'الرسالة قصيرة جداً (10 أحرف على الأقل).';
    }

    if (!$errors) {
        $insert = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $insert->execute([$old['name'], $old['email'], $old['subject'], $old['message']]);
        flash('success', 'شكراً لتواصلك! وصلتنا رسالتك وسنرد عليك قريباً.');
        redirect('contact.php');  // Post/Redirect/Get: refreshing won't resend the form
    }
}

// Pre-fill name for logged-in users.
if ($old['name'] === '' && current_user()) {
    $old['name'] = current_user()['name'];
}

$pageTitle  = 'اتصل بنا';
$activePage = 'contact.php';
require __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <h1 class="page-title">اتصل بنا</h1>

    <div class="contact-grid">
        <div>
            <h2 class="accent-title">أرسل رسالة</h2>

            <?php foreach ($errors as $error): ?>
                <div class="error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form method="POST" action="contact.php" class="stacked-form">
                <?= csrf_field() ?>
                <label>الاسم الكامل
                    <input type="text" name="name" value="<?= e($old['name']) ?>" placeholder="أدخل اسمك الكامل" maxlength="100" required>
                </label>
                <label>البريد الإلكتروني
                    <input type="email" name="email" value="<?= e($old['email']) ?>" placeholder="example@email.com" required>
                </label>
                <label>الموضوع
                    <select name="subject" required>
                        <option value="">اختر الموضوع</option>
                        <?php foreach ($subjects as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $old['subject'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>الرسالة
                    <textarea name="message" rows="5" placeholder="اكتب رسالتك هنا..." minlength="10" required><?= e($old['message']) ?></textarea>
                </label>
                <button type="submit" class="btn">إرسال الرسالة</button>
            </form>
        </div>

        <div>
            <h2 class="accent-title">معلومات الاتصال</h2>
            <div class="contact-info">
                <div class="contact-item"><span class="contact-icon">📧</span><div><h3>البريد الإلكتروني</h3><p>info@booksummaries.com</p></div></div>
                <div class="contact-item"><span class="contact-icon">🕒</span><div><h3>ساعات العمل</h3><p>الأحد - الخميس: 8 صباحاً - 5 مساءً</p></div></div>
                <div class="contact-item"><span class="contact-icon">📍</span><div><h3>العنوان</h3><p>الرياض، المملكة العربية السعودية</p></div></div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
