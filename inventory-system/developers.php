<?php
$pageTitle = "Developers Info";

$developers = [
    ['Alon, Axl Andrew M.',   'Technical Writer (R), Tester (C)'],
    ['Avilado, Jonard',  'Frontend (R) Technical Writer (R), Tester (C)'],
    ['Avila, Samuel John A.',   'Programmer (R), Technical Writer (R), Tester (C)', 'Avila,jpg'],
    ['Buli, Gene Evan A.', 'Programmer (R), Technical Writer (R), Tester (C)', 'Buli.jpg'],
    ['David, Cedrick A.',  'Frontend (R), Technical Writer (R), Tester (C)'],
    ['Gagam, James Tyron B.',  'Database (AR), Technical Writer (R), Tester (AR)'],
    ['Isuga, Eryhla Nicole I.',  'Developer (R), Technical Writer (R), Tester (C)', 'Isuga.jpg'],
    ['Miole, Matt Jhared R.',  'Developer (AR), Database (AR), Frontend (AR), Technical Writer (R), Tester (AR)', 'Miole.jpg'],
    ['Rubio, Allaiza Lourrain  A.',  'Developer (R), Technical Writer (R), Tester (C)'],
    ['Sukim, Jonas Paul J.',  'Technical Writer (AR), Tester (C)', 'Sukim.jpg'],
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

function resolve_path(string $rel): string {
    $path  = __DIR__;
    $found = [];
    foreach (explode('/', $rel) as $part) {
        if ($part === '' || $part === '.' || $part === '..') return '';
        $match = null;
        foreach (@scandir($path) ?: [] as $entry) {
            if (strcasecmp($entry, $part) === 0) { $match = $entry; break; }
        }
        if ($match === null) return '';
        $path .= '/' . $match;
        $found[] = $match;
    }
    return is_file($path) ? implode('/', $found) : '';
}

function find_photo(string $photo): string {
    $rel = ltrim(str_replace('\\', '/', $photo), '/');
    if ($rel === '') return '';
    if (strpos($rel, '/') !== false) return resolve_path($rel);
    foreach (['images/developers/', 'Images/', 'images/'] as $dir) {
        $found = resolve_path($dir . $rel);
        if ($found !== '') return $found;
    }
    return '';
}
 
include 'includes/header.php';
?>
<header>Developer's Info</header>
<p>BAIT-1A GROUP 4- Inventory Management System.</p>
 
<div class="dev-grid">
  <?php foreach ($developers as $dev):
      [$name, $role] = $dev;
      $photoPath = find_photo($dev[2] ?? '');
      $hasPhoto  = $photoPath !== '';
  ?>
    <div class="dev-card">
      <div class="avatar">
        <?php if ($hasPhoto): ?>
          <img src="<?= htmlspecialchars($photoPath) ?>?v=<?= filemtime(__DIR__ . '/' . $photoPath) ?>" alt="<?= htmlspecialchars($name) ?>" loading="lazy">
        <?php else: ?>
          <?= htmlspecialchars(initials($name)) ?>
        <?php endif; ?>
      </div>
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