
<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'includes/db.php';

try {
    $stmt = $db->query("
        SELECT
            item_name AS name,
            action,
            quantity,
            previous_quantity AS previousQuantity,
            date_time
        FROM item_history
        ORDER BY datetime(date_time) DESC, id DESC
    ");

    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($history, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    error_log('History retrieval failed: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'error' => 'Unable to retrieve activity history.'
    ]);
}
