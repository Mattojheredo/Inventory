
-- Existing items table: kept for compatibility.
-- CREATE TABLE IF NOT EXISTS will not replace an existing table.

CREATE TABLE IF NOT EXISTS items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    quantity INTEGER NOT NULL,
    price REAL NOT NULL,
    category TEXT,
    date_added TEXT DEFAULT CURRENT_TIMESTAMP,
    restock_level INTEGER DEFAULT 5,
    expiration_date TEXT
);

-- Stores inventory activity.
CREATE TABLE IF NOT EXISTS item_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER,
    item_name TEXT NOT NULL,
    action TEXT NOT NULL CHECK (
        action IN ('added', 'updated', 'quantity_changed', 'deleted')
    ),
    quantity INTEGER,
    previous_quantity INTEGER,
    date_time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE SET NULL
);

-- Stores grocery-list entries.
CREATE TABLE IF NOT EXISTS grocery_list (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT,
    is_bought INTEGER NOT NULL DEFAULT 0 CHECK (is_bought IN (0, 1)),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Helpful indexes.
CREATE INDEX IF NOT EXISTS idx_item_history_date_time
    ON item_history(date_time DESC);

CREATE INDEX IF NOT EXISTS idx_grocery_list_category
    ON grocery_list(category);

CREATE INDEX IF NOT EXISTS idx_grocery_list_is_bought
    ON grocery_list(is_bought);
