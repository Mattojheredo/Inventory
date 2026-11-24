<?php
// Shared top of every page: <head>, sidebar, and the opening of the content area.
// Set these BEFORE including this file:
//   $pageTitle = 'Items';                 (text for the browser tab)
//   $pageCss   = ['CSS/table.css'];       (optional: extra CSS for this page)
$pageTitle = $pageTitle ?? 'Inventory Management System';

$pageCss   = $pageCss ?? [];

// ---------- Small stock snapshot for the sidebar ----------
$sideTotal = $sideLow = '–';
try {
    include_once __DIR__ . '/db.php';
    $sideTotal = (int)$db->query('SELECT COUNT(*) FROM items')->fetchColumn();
    $sideLow   = (int)$db->query('SELECT COUNT(*) FROM items WHERE quantity <= restock_level')->fetchColumn();
} catch (Throwable $e) {
    // leave the dashes if the database can't be read
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | IMS</title>
  <link rel="stylesheet" href="CSS/index.css">
  <?php foreach ($pageCss as $css): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
  <?php endforeach; ?>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<div class="wrapper">

  <nav class="sidebar">
    <button id="toggle" aria-label="Toggle menu">
      <span class="material-symbols-outlined">menu</span>
    </button>

    <div class="brand-name">
      <a href="index.php">
        <span class="brand-mark">IMS</span>
        <span class="text brand-title"><b>IMS</b><small>Inventory Management</small></span>
      </a>
    </div>

    <div class="side-label text">Menu</div>
    <ul>
      <li>
        <a href="dashboard.php">
          <span class="material-symbols-outlined">dashboard</span>
          <span class="text">Dashboard</span>
        </a>
      </li>

      <li>
        <details>
          <summary>
            <a href="view-items.php">
              <span class="material-symbols-outlined">inventory_2</span>
              <span class="text">Items</span>
            </a>
            <span class="material-symbols-outlined arrow">chevron_left</span>
          </summary>
          <ul class="submenu">
            <li><a href="view-items.php"><span class="text">View Items</span></a></li>
            <li><a href="view-items.php?add=1"><span class="text">Add Item</span></a></li>
          </ul>
        </details>
      </li>

      <li>
        <a href="history.php">
          <span class="material-symbols-outlined">history</span>
          <span class="text">History</span>
        </a>
      </li>

      <li>
        <a href="grocery-list.php">
          <span class="material-symbols-outlined">checklist</span>
          <span class="text">Grocery List</span>
        </a>
      </li>
    </ul>

    <div class="side-card side-extra">
      <div class="side-title">Stock snapshot</div>
      <div class="side-stats">
        <div><b><?= $sideTotal ?></b><span>Items</span></div>
        <div class="<?= is_int($sideLow) && $sideLow > 0 ? 'warn' : '' ?>"><b><?= $sideLow ?></b><span>Low stock</span></div>
      </div>
    </div>

    <ul class="side-bottom">
      <li>
        <a href="developers.php">
          <span class="material-symbols-outlined">groups</span>
          <span class="text">Developer's Info</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="main-content">
    <div class="page-content">