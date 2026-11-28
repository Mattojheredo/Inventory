
"use strict";

(function () {

    // Button IDs and the activity each one records.
    // PHP now records actual inventory activities in the database.
    const BUTTON_ACTIONS = {
        "add-btn": "added",
        "update-btn": "updated",
        "quantity-btn": "quantity_changed",
        "delete-btn": "deleted"
    };

    const ITEM_NAME_INPUT_ID = "item-name";
    const ITEM_QUANTITY_INPUT_ID = "item-quantity";
    const TABLE_BODY_ID = "historyTableBody";
    const HISTORY_DATA_URL = "history-data.php";
    const TIME_ZONE = "Asia/Manila";

    const ACTION_LABELS = {
        added: "Added",
        updated: "Updated",
        quantity_changed: "Quantity Changed",
        deleted: "Deleted"
    };

    function toNumber(value) {

        if (value === null || value === undefined || value === "") {
            return null;
        }

        const number = Number(value);

        return Number.isFinite(number) ? number : null;
    }

    // History is now retrieved from SQLite through PHP.
    // This preserves the original getStockHistory() function name.

    async function getStockHistory() {

        try {

            const response = await fetch(HISTORY_DATA_URL, {
                headers: {
                    "Accept": "application/json"
                }
            });

            if (!response.ok) {
                throw new Error("Unable to retrieve history. Status: " + response.status);
            }

            const history = await response.json();

            return Array.isArray(history)
                ? history.filter(item => item && typeof item === "object")
                : [];

        } catch (error) {

            console.error("Unable to load history:", error);
            return null;
        }
    }

    // Compatibility helper retained from the original code.
    // Actual inventory activities should be recorded by PHP.

    function logStockChange(details = {}) {

        console.warn(
            "History is now recorded by PHP. Do not manually log inventory actions through JavaScript.",
            details
        );

        return false;
    }

    // Inventory page button information retained for compatibility.

    function getItemName(button) {

        if (button.dataset && button.dataset.itemName) {
            return button.dataset.itemName;
        }

        const input = document.getElementById(ITEM_NAME_INPUT_ID);

        return input ? input.value : "";
    }

    function getItemQuantity(button) {

        if (button.dataset && button.dataset.itemQuantity) {
            return button.dataset.itemQuantity;
        }

        const input = document.getElementById(ITEM_QUANTITY_INPUT_ID);

        return input ? input.value : "";
    }

    // No button listeners are needed.
    // PHP records successful database operations to prevent duplicate entries.

    function setupButtons() {
        // Inventory history is handled by save-item.php,
        // update-item.php, and delete-item.php.
    }

    // Timestamp formatting: Philippine Standard Time (Asia/Manila).
    // Example: Nov 17, 2026 06:11 PM

    function formatDateTime(dateTime) {

        const date = new Date(dateTime);

        if (Number.isNaN(date.getTime())) {
            return "Invalid date";
        }

        return date.toLocaleString("en-US", {
            timeZone: TIME_ZONE,
            year: "numeric",
            month: "short",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
        }).replace(/(\d{4}),/, "$1");
    }

    // Displays the quantity change for each activity.

    function getChangeText(record) {

        const quantity = toNumber(record.quantity);
        const previous = toNumber(record.previousQuantity);

        if (record.action === "added") {
            return quantity !== null ? "+" + quantity : "—";
        }

        if (record.action === "deleted") {
            return quantity !== null ? "-" + quantity : "—";
        }

        if (record.action === "quantity_changed" && previous !== null && quantity !== null) {
            const difference = quantity - previous;
            return (difference > 0 ? "+" : "") + difference;
        }

        if (record.action === "updated") {
            return "—";
        }

        return "—";
    }

    // Displays history records retrieved from SQLite.

    async function displayStockHistory() {

        const tableBody = document.getElementById(TABLE_BODY_ID);

        if (!tableBody) {
            return;
        }

        const history = await getStockHistory();

        tableBody.replaceChildren();

        if (history === null) {

            const row = document.createElement("tr");
            const cell = document.createElement("td");

            cell.colSpan = 4;
            cell.textContent = "Unable to load history. Please refresh the page.";

            row.appendChild(cell);
            tableBody.appendChild(row);

            return;
        }

        if (history.length === 0) {

            const row = document.createElement("tr");
            const cell = document.createElement("td");

            cell.colSpan = 4;
            cell.textContent = "No activity found.";

            row.appendChild(cell);
            tableBody.appendChild(row);

            return;
        }

        history.forEach(record => {

            const row = document.createElement("tr");

            const actionCell = document.createElement("td");
            actionCell.textContent =
                ACTION_LABELS[record.action] || "Unknown";

            const nameCell = document.createElement("td");
            nameCell.textContent =
                typeof record.name === "string" ? record.name : "Unknown item";

            const changeCell = document.createElement("td");
            changeCell.textContent = getChangeText(record);

            const dateCell = document.createElement("td");
            dateCell.textContent = formatDateTime(record.date_time);

            row.appendChild(actionCell);
            row.appendChild(nameCell);
            row.appendChild(changeCell);
            row.appendChild(dateCell);

            tableBody.appendChild(row);
        });
    }

    function setupHistoryPage() {

        if (!document.getElementById(TABLE_BODY_ID)) {
            return;
        }

        displayStockHistory();
    }

    function init() {
        setupButtons();
        setupHistoryPage();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    window.logStockChange = logStockChange;
    window.getStockHistory = getStockHistory;
    window.displayStockHistory = displayStockHistory;

})();
