(function () {
  var KEY = "grocery-list-items-v3";
  
var DEFAULTS = {
    "Pantry": [
        "Jasmine Rice (1 kg)",
        "Sinandomeng Rice (5 kg)",
        "Brown Rice (1 kg)",
        "Eggs (12 pcs)",
        "All-Purpose Flour (1 kg)",
        "White Sugar (1 kg)",
        "Brown Sugar (500 g)",
        "Iodized Salt (250 g)",
        "Cooking Oil (1 L)",
        "Cornstarch (250 g)",
        "Baking Powder (100 g)",
        "Spaghetti Pasta (500 g)",
        "Elbow Macaroni (400 g)",
        "Pancit Canton Noodles (500 g)",
        "Instant Mami Noodles (70 g)",
        "Peanut Butter (350 g)",
        "Breadcrumbs (200 g)",
        "Oatmeal (500 g)",
        "Canned Corn (425 g)",
        "Canned Mushrooms (400 g)"
    ],

    "Sauces & Seasoning": [
        "Soy Sauce (1 L)",
        "Vinegar (750 ml)",
        "Fish Sauce (350 ml)",
        "Banana Ketchup (320 g)",
        "Tomato Ketchup (320 g)",
        "Mayonnaise (470 ml)",
        "Tomato Sauce (250 g)",
        "Tomato Paste (150 g)",
        "Oyster Sauce (510 g)",
        "Sweet Chili Sauce (300 ml)",
        "Hot Sauce (150 ml)",
        "Magic Sarap (8 g)",
        "Sinigang Mix (44 g)",
        "Chicken Bouillon Cubes (60 g)",
        "Beef Bouillon Cubes (60 g)",
        "Ground Black Pepper (50 g)",
        "Garlic Powder (100 g)",
        "Onion Powder (100 g)",
        "Paprika Powder (50 g)",
        "Mustard (200 g)"
    ],

    "Karne at Isda": [
        "Chicken Breast (500 g)",
        "Chicken Thigh (500 g)",
        "Whole Chicken (1 kg)",
        "Ground Chicken (500 g)",
        "Pork Liempo (500 g)",
        "Pork Kasim (500 g)",
        "Pork Chop (500 g)",
        "Ground Pork (500 g)",
        "Beef Sirloin (500 g)",
        "Ground Beef (500 g)",
        "Pork Longganisa (500 g)",
        "Pork Hotdog (500 g)",
        "Chicken Nuggets (400 g)",
        "Bangus (500 g)",
        "Tilapia (500 g)",
        "Galunggong (500 g)",
        "Tuyo (250 g)",
        "Daing na Bangus (500 g)",
        "Shrimp (500 g)",
        "Squid (500 g)",
        "Canned Tuna (155 g)",
        "Sardines in Tomato Sauce (155 g)"
    ],

    "Gulay at Prutas": [
        "Red Onion (500 g)",
        "Garlic (250 g)",
        "Tomato (500 g)",
        "Potato (1 kg)",
        "Carrots (500 g)",
        "Cabbage (1 pc)",
        "Eggplant (500 g)",
        "Kangkong (1 bundle)",
        "Pechay (1 bundle)",
        "String Beans (250 g)",
        "Okra (250 g)",
        "Squash (1 kg)",
        "Sayote (500 g)",
        "Cucumber (500 g)",
        "Lettuce (250 g)",
        "Green Bell Pepper (250 g)",
        "Ginger (250 g)",
        "Chili Pepper (100 g)",
        "Banana (1 kg)",
        "Mango (1 kg)",
        "Apple (4 pcs)",
        "Orange (4 pcs)",
        "Pineapple (1 pc)",
        "Papaya (1 pc)",
        "Watermelon (1 pc)",
        "Calamansi (500 g)",
        "Avocado (3 pcs)",
        "Grapes (500 g)"
    ],

    "Inumin at Meryenda": [
        "Fresh Milk (1 L)",
        "UHT Milk (250 ml)",
        "Chocolate Milk (200 ml)",
        "Condensed Milk (390 g)",
        "Evaporated Milk (370 ml)",
        "Powdered Milk (300 g)",
        "Bear Brand Powdered Milk (320 g)",
        "Birch Tree Milk Powder (300 g)",
        "Milo Chocolate Drink (300 g)",
        "Ovaltine Chocolate Drink (300 g)",
        "Energen Cereal Drink (40 g)",
        "Coffee 3-in-1 (20 g)",
        "Instant Coffee (100 g)",
        "Orange Juice (1 L)",
        "Apple Juice (1 L)",
        "Mango Juice (1 L)",
        "Powdered Juice (25 g)",
        "Bottled Water (500 ml)",
        "Drinking Water (5 L)",
        "Cola Soft Drink (1.5 L)",
        "Lemon-Lime Soda (1.5 L)",
        "Pandesal (10 pcs)",
        "Sliced Bread (400 g)",
        "Cheese Bread (6 pcs)"
    ],

    "Personal Care": [
        "Safeguard Soap (135 g)",
        "Dove Beauty Bar (135 g)",
        "Bioderm Soap (135 g)",
        "Palmolive Shampoo (180 ml)",
        "Head & Shoulders Shampoo (170 ml)",
        "Sunsilk Shampoo (180 ml)",
        "Cream Silk Conditioner (180 ml)",
        "Palmolive Conditioner (180 ml)",
        "Colgate Toothpaste (150 g)",
        "Closeup Toothpaste (120 g)",
        "Oral-B Toothbrush (1 pc)",
        "Colgate Toothbrush (1 pc)",
        "Rexona Deodorant (50 ml)",
        "Nivea Deodorant (50 ml)",
        "Johnson's Baby Powder (100 g)",
        "Johnson's Baby Oil (125 ml)",
        "Nivea Body Lotion (200 ml)",
        "Vaseline Petroleum Jelly (100 ml)",
        "Cetaphil Cleanser (125 ml)",
        "Cotton Buds (100 pcs)",
        "Facial Tissue (100 sheets)",
        "Sanitary Napkins (8 pcs)",
        "Feminine Wash (150 ml)",
        "Disposable Diapers (10 pcs)"
    ],

    "Gamit sa Bahay": [
        "Ariel Powder Detergent (1 kg)",
        "Tide Powder Detergent (1 kg)",
        "Surf Powder Detergent (1 kg)",
        "Laundry Bar Soap (400 g)",
        "Joy Dishwashing Liquid (485 ml)",
        "Axion Dishwashing Paste (400 g)",
        "Zonrox Bleach (1 L)",
        "Downy Fabric Conditioner (900 ml)",
        "Champion Fabric Conditioner (800 ml)",
        "Mr. Clean Floor Cleaner (1 L)",
        "Lysol Disinfectant Spray (350 g)",
        "Baygon Insect Spray (600 ml)",
        "Garbage Bags (10 pcs)",
        "Scotch-Brite Sponge (3 pcs)",
        "Steel Wool Scrubber (3 pcs)",
        "Paper Towels (2 rolls)",
        "Toilet Paper (4 rolls)",
        "Aluminum Foil (10 m)",
        "Plastic Food Wrap (30 m)",
        "Hand Soap Refill (450 ml)",
        "Air Freshener (320 ml)",
        "Mosquito Coil (10 pcs)",
        "Dishwashing Gloves (1 pair)",
        "Posporo (1 box)",
        "Candles (6 pcs)"
    ],

    "Snacks & Treats": [
        "Potato Chips (100 g)",
        "Cheese Rings (100 g)",
        "Corn Chips (100 g)",
        "Chocolate Bar (45 g)",
        "Fudgee Barr (10 pcs)",
        "Oreo Cookies (133 g)",
        "Cream-O Cookies (133 g)",
        "SkyFlakes Crackers (250 g)",
        "Fita Crackers (200 g)",
        "Graham Crackers (200 g)",
        "Peanuts (100 g)",
        "Popcorn (100 g)",
        "Marshmallows (150 g)",
        "Wafer Sticks (150 g)",
        "Gummy Candies (100 g)",
        "Hard Candies (150 g)",
        "Jelly Cups (6 pcs)",
        "Ice Cream (1 L)",
        "Potato Fries (500 g)",
        "Cheese Spread (165 g)"
    ],

    "Iba pa": [
        "AA Batteries (4 pcs)",
        "AAA Batteries (4 pcs)",
        "LED Light Bulb (9 W)",
        "Disposable Lighter (1 pc)",
        "Adhesive Tape (1 roll)",
        "Super Glue (3 g)",
        "Ballpoint Pen (1 pc)",
        "Notebook (1 pc)",
        "Face Masks (10 pcs)",
        "Rubbing Alcohol 70% (500 ml)",
        "Hand Sanitizer (100 ml)",
        "Insect Repellent Lotion (50 ml)",
        "Shoe Polish (40 g)",
        "Reusable Shopping Bag (1 pc)",
        "Umbrella (1 pc)"
    ]
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