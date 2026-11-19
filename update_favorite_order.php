<?php
/**
 * Saves the new order of the user's favorites after drag & drop.
 * Called with fetch() from favorites.php; expects JSON and an X-CSRF-Token header.
 */
require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

function respond(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'طريقة غير مسموحة', 405);
}
if (!csrf_valid($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    respond(false, 'انتهت صلاحية الصفحة، حدّثها وحاول مرة أخرى', 400);
}

$userId = current_user_id();
if ($userId === null) {
    respond(false, 'سجّل دخولك أولاً', 401);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['order']) || !is_array($input['order'])) {
    respond(false, 'بيانات غير صالحة', 422);
}

$pdo->beginTransaction();
// user_id in the WHERE clause: a user can only reorder their own list.
$update = $pdo->prepare('UPDATE favorites SET display_order = ? WHERE id = ? AND user_id = ?');
foreach ($input['order'] as $item) {
    $update->execute([(int) ($item['display_order'] ?? 0), (int) ($item['favorite_id'] ?? 0), $userId]);
}
$pdo->commit();

respond(true, 'تم تحديث الترتيب بنجاح');
