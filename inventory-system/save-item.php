
<?php
// save-item.php: adds a NEW item and records it in activity history.
include 'includes/db.php';

function fail(string $msg): void {
    header('Location: view-items.php?error=' . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-items.php');
    exit;
}

// Only allow returning to view-items.php.
$returnTo = 'view-items.php';
if (isset($_POST['return_to']) && preg_match('/^view-items\.php(\?[A-Za-z0-9_%&=+.\-]*)?$/', $_POST['return_to'])) {
    $returnTo = $_POST['return_to'];
}

$name     = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT);
$price    = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);
$restock  = filter_var($_POST['restock_level'] ?? 5, FILTER_VALIDATE_INT);
$expiry   = trim($_POST['expiration_date'] ?? '');

if ($expiry !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $expiry);
    if (!$d || $d->format('Y-m-d') !== $expiry) {
        fail('Expiration date is not a valid date.');
    }
} else {
    $expiry = null;
}

if ($name === '') {
    fail('Name is required.');
}
if ($quantity === false || $quantity < 0) {
    fail('Quantity must be a whole number (0 or more).');
}
if ($price === false || $price < 0) {
    fail('Price must be a number (0 or more).');
}
if ($restock === false || $restock < 0) {
    fail('Restock level must be a whole number (0 or more).');
}

try {
    $db->beginTransaction();

    // Add the item.
    $stmt = $db->prepare(
        'INSERT INTO items (name, quantity, price, category, restock_level, expiration_date)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $quantity, $price, $category, $restock, $expiry]);

    // Get the ID assigned to the newly added item.
    $itemId = (int)$db->lastInsertId();

    // Record the addition in activity history.
    $log = $db->prepare(
        'INSERT INTO item_history (item_id, item_name, action, quantity)
         VALUES (?, ?, ?, ?)'
    );
    $log->execute([$itemId, $name, 'added', $quantity]);

    $db->commit();

} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    error_log('Save item failed: ' . $e->getMessage());
    fail('Could not save the item. Please check the database and try again.');
}

header('Location: ' . $returnTo);
exit;
