<?php
$pageTitle = "Developer's Info";

// ---------- Edit the names and roles here ----------
$developers = [
    ['Member One',   'Developer, Database, Frontend, Technical Writer, Tester'],
    ['Member Two',   'Database, Technical Writer, Tester'],
    ['Member Three', 'Frontend, Technical Writer, Tester'],
    ['Member Four',  'Developer, Technical Writer, Tester'],
    ['Member Five',  'Developer, Technical Writer, Tester'],
    ['Member Six',  'Developer, Technical Writer, Tester'],
    ['Member Seven',  'Frontend, '],
    ['Member Eight',  'Developer'],
    ['Member Nine',  'Developer'],
    ['Member Ten',  'Developer'],
];

if (!function_exists('initials')) {
    function initials(string $name): string {
        $words = array_values(array_filter(explode(' ', trim($name))));
        $out = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $out .= strtoupper($w[0]);
        }
        return $out ?: '?';
    }
}

include 'includes/header.php';
?>
<header>Developer's Info</header>
<p>The team behind the Inventory Management System.</p>

<div class="dev-grid">
  <?php foreach ($developers as [$name, $role]): ?>
    <div class="dev-card">
      <div class="avatar"><?= htmlspecialchars(initials($name)) ?></div>
      <h3><?= htmlspecialchars($name) ?></h3>
      <p class="role"><?= htmlspecialchars($role) ?></p>
    </div>
  <?php endforeach; ?>
</div>

<div class="project-info">
  <h2>About the project</h2>
  <dl>
    <dt>Project</dt><dd>Inventory Management System</dd>
    <dt>Group</dt><dd>Group 4</dd>
    <dt>Section</dt><dd>BAIT-1A</dd>
    <dt>Course</dt><dd>WS101-1A Capstone Project</dd>
    <dt>Built with</dt>
    <dd class="tech"><span>HTML</span><span>CSS</span><span>JavaScript</span><span>PHP</span><span>SQLite</span></dd>
  </dl>
</div>

<?php include 'includes/footer.php'; ?>