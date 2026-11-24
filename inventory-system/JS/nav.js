// Instant page switching: clicking a menu link swaps only the content area
// (no full page reload) with a short animation. If anything goes wrong it
// falls back to a normal page load, so links never break.
(function () {
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var token = 0;
  var content = document.querySelector('.page-content');
  if (!content || !window.fetch) return;

  // Thin loading bar at the top
  var bar = document.createElement('div');
  bar.className = 'nav-progress';
  document.body.appendChild(bar);
  function setBar(width, hide) {
    bar.style.opacity = '1';
    bar.style.width = width + '%';
    if (hide) setTimeout(function () {
      bar.style.opacity = '0';
      setTimeout(function () { bar.style.width = '0'; }, 300);
    }, 200);
  }

  // Content slides in piece by piece
  function animateIn() {
    if (reduce) return;
    Array.prototype.forEach.call(content.children, function (child, i) { child.style.setProperty('--i', i); });
    content.classList.add('entering');
    setTimeout(function () { content.classList.remove('entering'); }, 900);
  }

  // Highlight the current page in the sidebar (same rule as script.js)
  function markActive() {
    var page = location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.sidebar a.active, .sidebar summary.active').forEach(function (el) { el.classList.remove('active'); });
    document.querySelectorAll('.sidebar details').forEach(function (d) { d.removeAttribute('open'); });
    document.querySelectorAll('.sidebar ul li a').forEach(function (link) {
      var href = link.getAttribute('href');
      if (href && href !== '#' && href === page) {
        link.classList.add('active');
        var d = link.closest('details');
        if (d) { d.open = true; d.querySelector('summary').classList.add('active'); }
      }
    });
  }

  // Load any stylesheet the new page needs that is not on the page yet
  function loadStyles(doc) {
    var waits = [];
    doc.querySelectorAll('head link[rel="stylesheet"]').forEach(function (l) {
      var href = l.getAttribute('href');
      if (!document.querySelector('head link[href="' + href + '"]')) {
        var n = document.createElement('link');
        n.rel = 'stylesheet';
        n.href = href;
        waits.push(new Promise(function (done) { n.onload = n.onerror = done; }));
        document.head.appendChild(n);
      }
    });
    return Promise.all(waits);
  }

  // Run the new page's own scripts (not the menu scripts that are already running)
  function runScripts(doc) {
    var list = Array.prototype.filter.call(doc.body.querySelectorAll('script'), function (s) {
      return !/(^|\/)(script|nav)\.js(\?|$)/.test(s.getAttribute('src') || '');
    });
    return list.reduce(function (chain, old) {
      return chain.then(function () {
        return new Promise(function (done) {
          var s = document.createElement('script');
          if (old.getAttribute('src')) {
            s.src = old.getAttribute('src');
            s.onload = s.onerror = function () { s.remove(); done(); };
            document.body.appendChild(s);
          } else {
            s.textContent = old.textContent;
            document.body.appendChild(s);
            s.remove();
            done();
          }
        });
      });
    }, Promise.resolve());
  }

  function navigate(url, push) {
    var mine = ++token;
    if (!reduce) content.classList.add('leaving');
    setBar(30);

    var fetching = fetch(url, { headers: { 'X-Requested-With': 'fetch' } }).then(function (r) {
      if (!r.ok) throw new Error(r.status);
      return r.text();
    });
    var pause = new Promise(function (done) { setTimeout(done, reduce ? 0 : 160); });

    Promise.all([fetching, pause]).then(function (res) {
      if (mine !== token) return;
      var doc = new DOMParser().parseFromString(res[0], 'text/html');
      var next = doc.querySelector('.page-content');
      if (!next) throw new Error('not a menu page');
      return loadStyles(doc).then(function () {
        if (mine !== token) return;
        content.innerHTML = next.innerHTML;
        document.title = doc.title;
        if (push) history.pushState({}, '', url);

        var newStats = doc.querySelector('.side-stats'), oldStats = document.querySelector('.side-stats');
        if (newStats && oldStats) oldStats.innerHTML = newStats.innerHTML;

        markActive();
        window.scrollTo(0, 0);
        content.classList.remove('leaving');
        animateIn();
        setBar(100, true);
        return runScripts(doc);
      });
    }).catch(function () {
      if (mine === token) location.href = url;     // fall back to a normal page load
    });
  }

  document.addEventListener('click', function (e) {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    var a = e.target.closest('a');
    if (!a || !a.closest('.wrapper') || a.target || a.hasAttribute('download')) return;
    var u = new URL(a.href, location.href);
    if (u.origin !== location.origin || !/\.php$/.test(u.pathname)) return;
    if (/\/(index|save-item|update-item|delete-item)\.php$/.test(u.pathname)) return;
    e.preventDefault();
    if (u.href === location.href) return;
    navigate(u.href, true);
  });

  window.addEventListener('popstate', function () { navigate(location.href, false); });

  animateIn();
})();