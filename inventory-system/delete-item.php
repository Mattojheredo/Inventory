
<?php
// delete-item.php: deletes ONE item and records the deletion in activity history.
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
    $db->beginTransaction();

    // Find the item before deleting it.
    $stmt = $db->prepare('SELECT name, quantity FROM items WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $db->rollBack();
        fail('Item not found. It may have been deleted already.');
    }

    // Record the deletion. item_id is NULL because the item will no longer exist.
    $log = $db->prepare(
        'INSERT INTO item_history (item_id, item_name, action, quantity)
         VALUES (NULL, ?, ?, ?)'
    );
    $log->execute([$item['name'], 'deleted', $item['quantity']]);

    // Delete the item.
    $delete = $db->prepare('DELETE FROM items WHERE id = ?');
    $delete->execute([$id]);

    $db->commit();

} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    error_log('Delete item failed: ' . $e->getMessage());
    fail('Could not delete the item. Please check the database and try again.');
}

header('Location: ' . $returnTo);
exit;
