<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/inventory.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_TIMEOUT, 5);

// Adds the expiration_date column to an existing database (only runs once)
try {
    $cols = $db->query('PRAGMA table_info(items)')->fetchAll(PDO::FETCH_COLUMN, 1);
    if ($cols && !in_array('expiration_date', $cols, true)) {
        $db->exec('ALTER TABLE items ADD COLUMN expiration_date TEXT');
    }
} catch (PDOException $e) {
    // The database may be locked right now (close DB Browser). It retries on the next page load.
}
?>