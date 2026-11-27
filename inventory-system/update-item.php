<?php
// update-item.php: edits an EXISTING item (found by its id) and records what changed in the history.
include 'includes/db.php';

function fail(string $msg): void {
    header('Location: view-items.php?error=' . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-items.php');
    exit;
}

$returnTo = 'view-items.php';
if (isset($_POST['return_to']) && preg_match('/^view-items\.php(\?[A-Za-z0-9_%&=+.\-]*)?$/', $_POST['return_to'])) {
    $returnTo = $_POST['return_to'];
}

$id       = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
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

if ($id === false || $id < 1) {
    fail('Invalid item ID.');
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

    // What the item looked like before, so we can tell what changed
    $old = $db->prepare('SELECT name, quantity, price, category, restock_level, expiration_date FROM items WHERE id = ?');
    $old->execute([$id]);
    $before = $old->fetch(PDO::FETCH_ASSOC);
    if (!$before) {
        $db->rollBack();
        fail('Item not found. It may have been deleted already.');
    }

    // WHERE id = ? makes sure only this one row changes; date_added is left untouched
    $db->prepare(
        'UPDATE items
         SET name = ?, quantity = ?, price = ?, category = ?, restock_level = ?, expiration_date = ?
         WHERE id = ?'
    )->execute([$name, $quantity, $price, $category, $restock, $expiry, $id]);

    $log = $db->prepare(
        'INSERT INTO item_history (item_id, item_name, action, quantity, previous_quantity) VALUES (?, ?, ?, ?, ?)'
    );

    if ((int)$before['quantity'] !== $quantity) {
        $log->execute([$id, $name, 'quantity_changed', $quantity, (int)$before['quantity']]);
    }

    $otherChanged =
        $before['name'] !== $name
        || abs((float)$before['price'] - (float)$price) > 0.0001
        || (string)($before['category'] ?? '') !== $category
        || (int)$before['restock_level'] !== $restock
        || (string)($before['expiration_date'] ?? '') !== (string)($expiry ?? '');
    if ($otherChanged) {
        $log->execute([$id, $name, 'updated', $quantity, null]);
    }

    $db->commit();
} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    fail('Could not update the item. The database may be locked (close DB Browser and try again).');
}

header('Location: ' . $returnTo);
exit;