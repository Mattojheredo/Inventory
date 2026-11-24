<?php
include 'includes/db.php';

$error = '';
$total = $units = $low = $expiring = 0;
$lowItems = [];
$value = 0.0;
try {
    $total = (int)$db->query('SELECT COUNT(*) FROM items')->fetchColumn();
    $units = (int)$db->query('SELECT COALESCE(SUM(quantity), 0) FROM items')->fetchColumn();
    $low   = (int)$db->query('SELECT COUNT(*) FROM items WHERE quantity <= restock_level')->fetchColumn();
    $expiring = (int)$db->query("SELECT COUNT(*) FROM items WHERE expiration_date IS NOT NULL AND expiration_date <> '' AND expiration_date <= date('now', '+7 day')")->fetchColumn();
    $lowItems = $db->query(
        'SELECT name, category, quantity, restock_level FROM items
         WHERE quantity <= restock_level
         ORDER BY quantity ASC, name ASC LIMIT 8'
    )->fetchAll(PDO::FETCH_ASSOC);
    $value = (float)$db->query('SELECT COALESCE(SUM(quantity * price), 0) FROM items')->fetchColumn();
} catch (Throwable $e) {
    $error = 'Could not read the database. Close DB Browser and reload.';
}

$pageTitle = 'Dashboard';
include 'includes/header.php';
?>
<header>Dashboard</header>

<?php if ($error): ?>
  <p style="color:#ffb4b4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="cards">
  <div class="card"><span class="card-label">Total Items</span><span class="card-value"><?= $total ?></span></div>
  <div class="card"><span class="card-label">Units in Stock</span><span class="card-value"><?= $units ?></span></div>
  <div class="card"><span class="card-label">Low on Stock</span><span class="card-value"><?= $low ?></span></div>
  <div class="card"><span class="card-label">Expired / Expiring Soon</span><span class="card-value"><?= $expiring ?></span></div>
  <div class="card"><span class="card-label">Inventory Value</span><span class="card-value">₱<?= number_format($value, 2) ?></span></div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Low stock items</h2>
    <a href="view-items.php?low=1">View all</a>
  </div>

  <?php if ($lowItems): ?>
    <div class="panel-scroll">
      <table class="mini-table">
        <thead>
          <tr><th>Item</th><th>Category</th><th>In stock</th><th>Restock level</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($lowItems as $it): ?>
            <tr>
              <td><?= htmlspecialchars($it['name']) ?></td>
              <td><?= htmlspecialchars($it['category'] ?? '') ?></td>
              <td><?= (int)$it['quantity'] ?></td>
              <td><?= (int)$it['restock_level'] ?></td>
              <td>
                <?php if ((int)$it['quantity'] <= 0): ?>
                  <span class="badge badge-out">Out of stock</span>
                <?php else: ?>
                  <span class="badge badge-low">Low stock</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="panel-empty">Nothing is running low. Everything is well stocked.</p>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>