<?php
$pageTitle = 'Grocery List';
$pageCss   = ['CSS/grocery-list.css'];
$pageJs    = ['JS/grocery.js'];
include 'includes/categories.php';
include 'includes/header.php';
?>
<div class="grocery-page">
  <header>Grocery List</header>
  <p class="tagline">Track • Check • Delete</p>

  <div class="controls">
    <form class="add-row" id="add-form">
      <input id="item-input" class="field" type="text" placeholder="Mag-add ng item (hal. Bigas)" autocomplete="off">
      <select id="cat-select" class="field" aria-label="Category"></select>
      <button class="btn" type="submit">Add Item</button>
    </form>

    <div class="filters">
      <input id="search" class="field" type="search" placeholder="Maghanap ng item" autocomplete="off">
      <div class="seg">
        <button type="button" data-status="all">Lahat</button>
        <button type="button" data-status="todo">Bibilhin</button>
        <button type="button" data-status="done">Nabili na</button>
      </div>
      <button type="button" class="btn ghost" id="clear-done">Clear bought</button>
    </div>

    <div class="progress"><div id="bar"></div></div>
    <div id="summary"></div>
    <div id="chips" class="chips"></div>
  </div>

  <div id="grid" class="grid"></div>
</div>
<script>window.IMS_CATEGORIES = <?= json_encode($categoryList) ?>;</script>
<?php include 'includes/footer.php'; ?>