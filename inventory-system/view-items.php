<?php
include 'includes/db.php';
include 'includes/categories.php';   // shared category list

// Error message sent back by save-item.php / update-item.php / delete-item.php
$error = trim($_GET['error'] ?? '');

// ---------- Search by name + category filter ----------
$search = trim($_GET['q'] ?? '');
$filterCat = trim($_GET['category'] ?? '');
$lowOnly = isset($_GET['low']);   // ?low=1 shows only low-stock items

$where = [];
$params = [];
if ($search !== '') {
    $where[] = 'name LIKE ?';
    $params[] = '%' . $search . '%';
}
if ($filterCat !== '') {
    $where[] = 'category = ?';
    $params[] = $filterCat;
}

if ($lowOnly) {
    $where[] = 'quantity <= restock_level';
}

$sql = 'SELECT * FROM items';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY date_added DESC, id DESC';

$stmt = $db->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalItems = (int)$db->query('SELECT COUNT(*) FROM items')->fetchColumn();

$categories = $db->query(
    "SELECT DISTINCT category FROM items WHERE category IS NOT NULL AND category <> '' ORDER BY category"
)->fetchAll(PDO::FETCH_COLUMN);

// Filter list = shared categories + any older category already saved in the database
$filterOptions = array_values(array_unique(array_merge($categoryList, $categories)));

// Where the save/update/delete scripts send you back to (keeps your search and filter)
$returnQuery = http_build_query(array_filter(['q' => $search, 'category' => $filterCat, 'low' => $lowOnly ? '1' : '']));

// How many items are low, and the link that turns the low-stock filter on or off
$lowCount = (int)$db->query('SELECT COUNT(*) FROM items WHERE quantity <= restock_level')->fetchColumn();
$lowToggle = 'view-items.php' . (($q = http_build_query(array_filter(['q' => $search, 'category' => $filterCat, 'low' => $lowOnly ? '' : '1']))) ? '?' . $q : '');
$returnTo = 'view-items.php' . ($returnQuery ? '?' . $returnQuery : '');

$pageTitle = 'Items';
$pageCss   = ['CSS/table.css'];
include 'includes/header.php';
?>
<div class="items-page">
  <header>Items</header>


  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <!-- Toolbar: add, search, category filter, clear -->
  <form class="toolbar" method="get" action="view-items.php">
    <?php if ($lowOnly): ?><input type="hidden" name="low" value="1"><?php endif; ?>
    <button type="button" class="btn btn-add" id="open-add">+ Add Item</button>
    <input type="text" name="q" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
    <select name="category" onchange="this.form.submit()">
      <option value="">All Categories</option>
      <?php foreach ($filterOptions as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $filterCat ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <a href="<?= htmlspecialchars($lowToggle) ?>" class="btn btn-low<?= $lowOnly ? ' on' : '' ?>">Low stock (<?= $lowCount ?>)</a>
    <a href="view-items.php" class="btn btn-clear">Clear</a>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Date Added</th>
          <th>Restock Level</th>
          <th>Expiration</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['category'] ?? '') ?></td>
            <td>
              <?= (int)$item['quantity'] ?>
              <?php if ((int)$item['quantity'] <= 0): ?>
                <span class="badge badge-out">Out</span>
              <?php elseif ((int)$item['quantity'] <= (int)$item['restock_level']): ?>
                <span class="badge badge-low">Low</span>
              <?php endif; ?>
            </td>
            <td>₱<?= number_format((float)$item['price'], 2) ?></td>
            <td><?= htmlspecialchars(date('M j, Y', strtotime($item['date_added']))) ?></td>
            <td><?= (int)$item['restock_level'] ?></td>
            <td>
              <?php
                $exp = $item['expiration_date'] ?? '';
                if ($exp !== '' && ($ts = strtotime($exp))) {
                    $days = (int)floor(($ts - strtotime('today')) / 86400);
                    $cls = $days < 0 ? 'exp-expired' : ($days <= 7 ? 'exp-soon' : '');
                    echo '<span class="' . $cls . '">' . htmlspecialchars(date('M j, Y', $ts)) . ($days < 0 ? ' (expired)' : '') . '</span>';
                } else {
                    echo '<span class="exp-none">—</span>';
                }
              ?>
            </td>
            <td class="actions">
              <button type="button" class="btn btn-edit"
                data-id="<?= (int)$item['id'] ?>"
                data-name="<?= htmlspecialchars($item['name']) ?>"
                data-category="<?= htmlspecialchars($item['category'] ?? '') ?>"
                data-quantity="<?= (int)$item['quantity'] ?>"
                data-price="<?= htmlspecialchars($item['price']) ?>"
                data-restock="<?= (int)$item['restock_level'] ?>"
                data-expiry="<?= htmlspecialchars($item['expiration_date'] ?? '') ?>">Edit</button>

              <!-- Delete goes to delete-item.php with this row's id -->
              <form method="post" action="delete-item.php" class="inline-form"
                    onsubmit="return confirm('Delete this item?');">
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                <input type="hidden" name="return_to" value="<?= htmlspecialchars($returnTo) ?>">
                <button type="submit" class="btn btn-del">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (!$items): ?>
          <tr>
            <td colspan="8" class="empty">
              <?= $lowOnly ? 'Nothing is low on stock right now.' : (($search !== '' || $filterCat !== '') ? 'No items match your search.' : 'No items yet. Click + Add Item to create one.') ?>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="count">Showing <?= count($items) ?> of <?= $totalItems ?> items</p>

<!-- Add / Edit dialog.
     Add  -> save-item.php
     Edit -> update-item.php (sends the item's id, so it edits instead of duplicating) -->
<dialog id="item-dialog">
  <form method="post" id="item-form" action="save-item.php">
    <h2 id="dialog-title">Add Item</h2>
    <input type="hidden" name="id" id="f-id" value="">
    <input type="hidden" name="return_to" value="<?= htmlspecialchars($returnTo) ?>">

    <label>Name
      <input type="text" name="name" id="f-name" required>
    </label>
    <label>Category
      <select name="category" id="f-category">
        <option value="">Select a category</option>
        <?php foreach ($categoryList as $c): ?>
          <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="row">
      <label>Quantity
        <input type="number" name="quantity" id="f-quantity" min="0" step="1" required>
      </label>
      <label>Price (₱)
        <input type="number" name="price" id="f-price" min="0" step="0.01" required>
      </label>
      <label>Restock Level
        <input type="number" name="restock_level" id="f-restock" min="0" step="1">
      </label>
    </div>

    <label>Expiration date (optional)
      <input type="date" name="expiration_date" id="f-expiry">
    </label>

    <div class="dialog-actions">
      <button type="button" class="btn btn-cancel" id="dialog-cancel">Cancel</button>
      <button type="submit" class="btn btn-edit">Save</button>
    </div>
  </form>
</dialog>

<script>
(function () {
  const dialog = document.getElementById('item-dialog');
  const form = document.getElementById('item-form');
  const field = id => document.getElementById(id);

  function openDialog(mode, data) {
    field('dialog-title').textContent = mode === 'add' ? 'Add Item' : 'Edit Item';
    form.action = mode === 'add' ? 'save-item.php' : 'update-item.php';
    field('f-id').value       = data.id || '';
    field('f-name').value     = data.name || '';
    // Pick the category from the list (older categories not in the list are added on the fly)
    const sel = field('f-category');
    const cat = data.category || '';
    if (cat && ![...sel.options].some(o => o.value === cat)) sel.add(new Option(cat, cat));
    sel.value = cat;
    field('f-expiry').value   = data.expiry || '';
    field('f-quantity').value = data.quantity ?? 0;
    field('f-price').value    = data.price ?? 0;
    field('f-restock').value  = data.restock ?? 5;
    dialog.showModal();
    field('f-name').focus();
  }

  document.getElementById('open-add').addEventListener('click', () => openDialog('add', {}));

  document.querySelectorAll('.btn-edit[data-id]').forEach(btn => {
    btn.addEventListener('click', () => openDialog('edit', btn.dataset));
  });

  // Sidebar link "Add Item" opens this page with ?add=1
  if (new URLSearchParams(location.search).has('add')) openDialog('add', {});

  document.getElementById('dialog-cancel').addEventListener('click', () => dialog.close());

  // Click outside the box to close
  dialog.addEventListener('click', e => { if (e.target === dialog) dialog.close(); });
})();
</script>
</div>
<?php include 'includes/footer.php'; ?>