<?php
// delete-item.php: deletes ONE item, found by its id.
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

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    fail('Invalid item ID.');
}

try {
    $stmt = $db->prepare('DELETE FROM items WHERE id = ?');
    $stmt->execute([$id]);
} catch (PDOException $e) {
    fail('Could not delete the item. The database may be locked (close DB Browser and try again).');
}

if ($stmt->rowCount() === 0) {
    fail('Item not found. It may have been deleted already.');
}

header('Location: ' . $returnTo);
exit;