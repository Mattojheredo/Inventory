<?php
$pageTitle = 'History';
$pageCss   = ['CSS/index.css'];
$pageJs    = ['JS/history.js'];
include 'includes/categories.php';
include 'includes/header.php';
?>
    <main>

        <h1>Activity History Log</h1>
        <p>Track items action such as additions, updates, and deletions in the system.</p>

        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Item Name</th>
                    <th>Change</th>
                    <th>Timestamp</th>
                </tr>
            </thead>

            <tbody id="historyTableBody">
                <tr>
                    <td colspan="4">Loading history...</td>
                </tr>
            </tbody>
        </table>

    </main>

<?php include 'includes/footer.php'; ?>