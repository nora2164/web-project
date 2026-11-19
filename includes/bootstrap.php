<?php
/**
 * Loaded at the top of every page: starts the session, connects to the
 * database and defines the shared helper functions.
 */

declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

/* ------------------------------------------------------------------ */
/* Database                                                           */
/* ------------------------------------------------------------------ */

function db_connect(array $config, bool $withDatabase = true): PDO
{
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    if ($config['driver'] === 'sqlite') {
        $pdo = new PDO('sqlite:' . $config['path'], null, null, $options);
        $pdo->exec('PRAGMA foreign_keys = ON');
        return $pdo;
    }

    $dsn = 'mysql:host=' . $config['host'] . ';charset=utf8mb4';
    if ($withDatabase) {
        $dsn .= ';dbname=' . $config['name'];
    }
    return new PDO($dsn, $config['user'], $config['pass'], $options);
}

$config = require __DIR__ . '/../config.php';

if (!defined('SKIP_DB_CONNECT')) {
    try {
        $pdo = db_connect($config);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log('DB connection failed: ' . $e->getMessage());
        exit('<!DOCTYPE html><html lang="ar" dir="rtl"><meta charset="UTF-8">'
            . '<body style="font-family:Tahoma,sans-serif;padding:2rem">'
            . '<h2>❌ تعذّر الاتصال بقاعدة البيانات</h2>'
            . '<p>تأكد أن خادم MySQL يعمل، ثم <a href="setup.php">اضغط هنا لإنشاء قاعدة البيانات</a>.</p>'
            . '</body></html>');
    }
}

/* ------------------------------------------------------------------ */
/* Output helpers                                                     */
/* ------------------------------------------------------------------ */

/** Escape a value before printing it in HTML (prevents XSS). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Cut a UTF-8 string without breaking Arabic characters. */
function excerpt(?string $text, int $length = 120): string
{
    $text = (string) $text;
    return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '…' : $text;
}

/** Star string for an average rating, e.g. 3.6 → ⭐⭐⭐☆☆ */
function stars(float $rating): string
{
    $full = (int) floor($rating);
    return str_repeat('⭐', $full) . str_repeat('☆', 5 - $full);
}

/* ------------------------------------------------------------------ */
/* Flash messages (shown once, stored in the session)                 */
/* ------------------------------------------------------------------ */

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function take_flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/* ------------------------------------------------------------------ */
/* Redirects                                                          */
/* ------------------------------------------------------------------ */

/**
 * Only allow redirects to pages of this site (e.g. "summary.php?id=3"),
 * so a link can't send users to an outside website.
 */
function safe_return_url(?string $url, string $fallback = 'index.php'): string
{
    if ($url !== null && preg_match('/^[a-z_]+\.php(\?[A-Za-z0-9_=&%.+\-]*)?$/', $url)) {
        return $url;
    }
    return $fallback;
}

/** The current page as a relative URL, e.g. "summaries.php?q=abc". */
function current_page_url(): string
{
    $path  = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '') ?: 'index.php';
    $query = $_SERVER['QUERY_STRING'] ?? '';
    return safe_return_url($query !== '' ? $path . '?' . $query : $path);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/* ------------------------------------------------------------------ */
/* CSRF protection                                                    */
/* ------------------------------------------------------------------ */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_valid(?string $token): bool
{
    return is_string($token) && hash_equals(csrf_token(), $token);
}

/** Stop the request unless it is a POST with a valid CSRF token. */
function require_post_with_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid($_POST['csrf_token'] ?? null)) {
        http_response_code(400);
        flash('error', 'انتهت صلاحية الطلب، حاول مرة أخرى.');
        redirect('index.php');
    }
}

/* ------------------------------------------------------------------ */
/* Authentication                                                     */
/* ------------------------------------------------------------------ */

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function current_user_id(): ?int
{
    return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
}

/** Send guests to the login page, then bring them back here afterwards. */
function require_login(string $returnTo = 'index.php'): int
{
    $userId = current_user_id();
    if ($userId === null) {
        flash('error', 'سجّل دخولك أولاً للمتابعة.');
        redirect('login.php?next=' . urlencode(safe_return_url($returnTo)));
    }
    return $userId;
}

function log_in_user(array $user): void
{
    session_regenerate_id(true);  // prevents session fixation
    $_SESSION['user'] = ['id' => (int) $user['id'], 'name' => $user['name']];
}

/* ------------------------------------------------------------------ */
/* Ratings                                                            */
/* ------------------------------------------------------------------ */

/** Recalculate a book's cached average rating after a vote. */
function refresh_book_rating(PDO $pdo, int $bookId): void
{
    $stmt = $pdo->prepare('SELECT AVG(rating) AS avg_rating, COUNT(*) AS total FROM ratings WHERE book_id = ?');
    $stmt->execute([$bookId]);
    $row = $stmt->fetch();

    $update = $pdo->prepare('UPDATE books SET average_rating = ?, rating_count = ? WHERE id = ?');
    $update->execute([round((float) $row['avg_rating'], 2), (int) $row['total'], $bookId]);
}

/** IDs of the books the logged-in user has in favorites. */
function favorite_book_ids(PDO $pdo, ?int $userId): array
{
    if ($userId === null) {
        return [];
    }
    $stmt = $pdo->prepare('SELECT book_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
