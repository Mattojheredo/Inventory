(function () {
  var KEY = "grocery-list-items-v3";
  var DEFAULTS = {
    "Pantry": ["Bigas ","Itlog ","Sardinas (de lata)","Corned beef","Instant pancit canton","Instant noodles (mami)","Mantika (cooking oil)","Asukal","Asin","Spam / luncheon meat","Tuna (de lata)","Mackerel sa tomato sauce","Meat loaf","Beef loaf","Century tuna","Peanut butter","Spaghetti pasta","Harina","Baking powder","Cornstarch"],
    "Sauces & Seasoning": ["Toyo","Suka","Patis","Ketchup (banana catsup)","Mayonnaise","Tomato sauce","Tomato paste","Magic Sarap","Knorr cubes / sinigang mix","Paminta"],
    "Karne at Isda": ["Longganisa / hotdog","Tuyo / daing","Manok (whole o hiwa)","Baboy (liempo, kasim)","Bangus","Tilapia","Galunggong","Hipon"],
    "Gulay at Prutas": ["Bawang at sibuyas","Kamatis","Saging","Kangkong","Talong","Sitaw","Okra","Repolyo","Carrots","Patatas","Luya","Sili (siling labuyo)","Kalamansi","Mangga","Pinya","Papaya","Pakwan","Dalandan"],
    "Inumin at Meryenda": ["Kape (3-in-1)","Gatas (evaporada / powdered)","Bearbrand","Birch Tree","Juice (powdered)","Milo / Ovaltine","Energen","Tubig (galon)","Tinapay (pandesal / sliced)","Tinapay na may palaman","Skyflakes / crackers","Biskwit","Cereal / oatmeal","Chichirya"],
    "Personal Care": ["Sabon at shampoo","Bioderm","Safeguard","Toothpaste","Toothbrush","Shampoo (sachet)","Conditioner","Deodorant","Tissue","Napkin","Diaper"],
    "Gamit sa Bahay": ["Sabon panlaba (detergent)","Dishwashing liquid","Zonrox","Fabric conditioner","Garbage bag","Alcohol / sanitizer","Posporo","Lighter","Kandila","Battery"],
    "Snacks & Treats": ["Chips","Chocolates","Candies","Ice cream","Cookies"],
    "Iba pa": ["Robux","Yosi"]
  };
  var CATS = (window.IMS_CATEGORIES || []).slice();
  var RENAME = {"Sauces & Seasoning":"Condiments","Karne at Isda":"Meat & Seafood","Gulay at Prutas":"Fruits & Vegetables","Inumin at Meryenda":"Beverages","Snacks & Treats":"Snacks & Treats","Gamit sa Bahay":"Household","Iba pa":"Others"};
  var state = { q: "", status: "all", cat: "all" };
  var $ = function (id) { return document.getElementById(id); };
  var items = load();

  function load() {
    var out = null;
    try { var s = localStorage.getItem(KEY); if (s) out = JSON.parse(s); } catch (e) {}
    if (!out) {
      out = [];
      Object.keys(DEFAULTS).forEach(function (c) { DEFAULTS[c].forEach(function (n) { out.push({ name: n, cat: c, done: false }); }); });
    }
    out.forEach(function (i) { i.cat = RENAME[i.cat] || i.cat; });                      // old names become the shared names
    out.forEach(function (i) { if (CATS.indexOf(i.cat) === -1) CATS.push(i.cat); });    // keep any other category visible
    return out;
  }
  function save() { try { localStorage.setItem(KEY, JSON.stringify(items)); } catch (e) {} }
  function h(tag, cls, text) {                 // textContent keeps user input safe from HTML injection
    var e = document.createElement(tag);
    if (cls) e.className = cls;
    if (text != null) e.textContent = text;
    return e;
  }

  function renderChips() {
    var box = $("chips");
    box.innerHTML = "";
    ["all"].concat(CATS).forEach(function (c) {
      var n = c === "all" ? items.length : items.filter(function (i) { return i.cat === c; }).length;
      var b = h("button", "chip" + (state.cat === c ? " on" : ""), (c === "all" ? "Lahat" : c) + " · " + n);
      b.type = "button";
      b.onclick = function () { state.cat = c; if (c !== "all") $("cat-select").value = c; render(); };
      box.appendChild(b);
    });
  }

  function render() {
    renderChips();
    document.querySelectorAll("[data-status]").forEach(function (b) {
      b.classList.toggle("on", b.dataset.status === state.status);
    });

    var q = state.q.toLowerCase();
    var shown = items.filter(function (i) {
      return (state.cat === "all" || i.cat === state.cat) &&
            (state.status === "all" || (state.status === "done") === i.done) &&
            i.name.toLowerCase().indexOf(q) !== -1;
    });

    var grid = $("grid");
    grid.innerHTML = "";
    grid.classList.toggle("single", state.cat !== "all");

    CATS.forEach(function (c) {
      var rows = shown.filter(function (i) { return i.cat === c; });
      if (!rows.length) return;
      var total = items.filter(function (i) { return i.cat === c; });
      var card = h("section", "card");
      var head = h("div", "card-head");
      head.append(h("h2", "", c), h("span", "count", total.filter(function (i) { return i.done; }).length + "/" + total.length));

      var ul = h("ul", "list");
      rows.forEach(function (item) {
        var li = h("li", "row" + (item.done ? " done" : ""));
        var cb = h("input");
        cb.type = "checkbox";
        cb.checked = item.done;
        cb.setAttribute("aria-label", item.name);
        cb.onchange = function () { item.done = !item.done; save(); render(); };   // CHECK / UNCHECK
        var del = h("button", "del", "✕");
        del.type = "button";
        del.title = "Delete";
        del.onclick = function () { items = items.filter(function (i) { return i !== item; }); save(); render(); };  // DELETE
        li.append(cb, h("span", "name", item.name), del);
        ul.appendChild(li);
      });
      card.append(head, ul);
      grid.appendChild(card);
    });

    if (!shown.length) grid.appendChild(h("p", "empty", items.length ? "Walang tugma sa hinahanap mo." : "Wala nang laman ang listahan."));

    var done = items.filter(function (i) { return i.done; }).length;
    $("summary").textContent = done + " sa " + items.length + " ang nabili na";
    $("bar").style.width = (items.length ? done / items.length * 100 : 0) + "%";
  }

  // ADD: new item goes to the top of its category
  $("add-form").addEventListener("submit", function (e) {
    e.preventDefault();
    var name = $("item-input").value.trim();
    if (!name) return;
    items.unshift({ name: name, cat: $("cat-select").value, done: false });
    $("item-input").value = "";
    state.q = ""; $("search").value = "";   // make sure the new item is visible
    state.status = "all";
    save(); render();
  });

  $("search").addEventListener("input", function (e) { state.q = e.target.value; render(); });
  document.querySelectorAll("[data-status]").forEach(function (b) {
    b.onclick = function () { state.status = b.dataset.status; render(); };
  });
  $("clear-done").onclick = function () {
    if (!items.some(function (i) { return i.done; })) return;
    if (confirm("Tanggalin lahat ng nabili na?")) { items = items.filter(function (i) { return !i.done; }); save(); render(); }
  };

  CATS.forEach(function (c) { var o = h("option", "", c); o.value = c; $("cat-select").appendChild(o); });
  render();
})();