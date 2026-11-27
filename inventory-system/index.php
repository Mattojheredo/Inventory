<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventor Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Rubik:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/intro.css">
</head>
<body>

<a class="logo" href="index.php" aria-label="Home">IMS</a>
<button class="burger" id="burger" aria-label="Menu" aria-expanded="false"><span></span><span></span><span></span></button>

<nav class="menu" id="menu" aria-label="Main menu">
  <a href="dashboard.php">Dashboard</a>
  <a href="view-items.php">Items</a>
  <a href="history.php">History</a>
  <a href="grocery-list.php">Grocery List</a>
</nav>

<header class="hero">
  <div class="slide s1 on"></div>
  <div class="slide s2"></div>
  <div class="slide s3"></div>
  <div class="slide s4"></div>

  <div class="hero-text">
    <span class="eyebrow reveal" style="--d:.2s">Group 4 &middot; BAIT-1A</span>
    <span class="line" style="--d:.5s"></span>
    <h1 class="reveal" style="--d:.7s">Inventory Management System</h1>
    <span class="line" style="--d:1s"></span>
    <p class="sub reveal" style="--d:1.2s">Simple &middot; Organized &middot; Accurate</p>
    <a class="btn-outline reveal" style="--d:1.5s" href="dashboard.php">Get Started</a>
  </div>

  <a class="scroll" href="#about">scroll down <span>&darr;</span></a>

  <div class="dots" role="tablist" aria-label="Slides">
    <button class="dot on" aria-label="Slide 1"></button>
    <button class="dot" aria-label="Slide 2"></button>
    <button class="dot" aria-label="Slide 3"></button>
    <button class="dot" aria-label="Slide 4"></button>
  </div>
</header>

<section class="about" id="about">
  <h2 class="fade">What you can do</h2>
  <div class="features">
    <a class="feature fade" href="dashboard.php">
      <h3>Dashboard</h3>
      <p>See your total items, stock on hand and low-stock count at a glance.</p>
    </a>
    <a class="feature fade" href="view-items.php">
      <h3>Items</h3>
      <p>Add, edit, search and delete inventory records saved in the database.</p>
    </a>
    <a class="feature fade" href="grocery-list.php">
      <h3>Grocery List</h3>
      <p>Check off what you have bought and keep track of what is left.</p>
    </a>
  </div>
</section>

<footer>Inventory Management System &middot; Group 4 &middot; BAIT-1A &middot; WS101-1A Capstone Project</footer>

<script>
(function () {
  // Background slideshow
  var slides = document.querySelectorAll('.slide');
  var dots = document.querySelectorAll('.dot');
  var current = 0, timer;
  function go(n) {
    slides[current].classList.remove('on'); dots[current].classList.remove('on');
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('on'); dots[current].classList.add('on');
  }
  function start() { timer = setInterval(function () { go(current + 1); }, 6000); }
  dots.forEach(function (d, n) { d.onclick = function () { clearInterval(timer); go(n); start(); }; });
  start();

  // Full-screen menu
  var burger = document.getElementById('burger');
  var menu = document.getElementById('menu');
  function setMenu(open) {
    menu.classList.toggle('open', open);
    burger.classList.toggle('open', open);
    burger.setAttribute('aria-expanded', open);
  }
  burger.onclick = function () { setMenu(!menu.classList.contains('open')); };
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });

  // Fade in sections when they scroll into view
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
  }, { threshold: 0.2 });
  document.querySelectorAll('.fade').forEach(function (el) { io.observe(el); });
})();
</script>
</body>
</html>