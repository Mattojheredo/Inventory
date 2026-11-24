<?php
// save-item.php: adds a NEW item to the SQLite database.
include 'includes/db.php';

function fail(string $msg): void {
    header('Location: view-items.php?error=' . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-items.php');
    exit;
}

// Only allow going back to view-items.php (keeps search/filter in the URL)
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
    $expiry = null;   // no expiration date
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
    // date_added is filled in automatically by the table default (CURRENT_TIMESTAMP)
    $stmt = $db->prepare(
        'INSERT INTO items (name, quantity, price, category, restock_level, expiration_date)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $quantity, $price, $category, $restock, $expiry]);
} catch (PDOException $e) {
    fail('Could not save the item. The database may be locked (close DB Browser and try again).');
}

header('Location: ' . $returnTo);
exit;