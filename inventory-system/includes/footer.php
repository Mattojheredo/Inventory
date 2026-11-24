<?php
// Shared bottom of every page. Optional: $pageJs = ['JS/grocery.js'];
$pageJs = $pageJs ?? [];
?>
    </div><!-- /.page-content -->

    <div class="site-footer">
      Inventory Management System &middot; Group 4 &middot; BAIT-1A &middot; WS101-1A Capstone Project
    </div>
  </div><!-- /.main-content -->
</div><!-- /.wrapper -->

<script src="JS/script.js"></script>
<script src="JS/nav.js"></script>
<?php foreach ($pageJs as $js): ?>
<script src="<?= htmlspecialchars($js) ?>"></script>
<?php endforeach; ?>
</body>
</html>