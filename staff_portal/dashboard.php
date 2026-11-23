
<?php
session_start();
require "../connect2DB.php";

if (!isset($_SESSION["staff_username"]) || !isset($_SESSION["department"])) {
    header("Location: staff_login.php");
    exit;
}

$username = $_SESSION["staff_username"];
$department = $_SESSION["department"];
$search = trim($_GET["search"] ?? "");

// Get tickets belonging to this department, excluding closed tickets
if ($search !== "") {
    $stmt = $conn->prepare(
        "SELECT ticket_id, date_created, owner, topic, message, status
         FROM ticket
         WHERE department = ?
         AND status != 'Closed'
         AND (
             CAST(ticket_id AS CHAR) LIKE ?
             OR owner LIKE ?
             OR topic LIKE ?
         )
         ORDER BY date_created DESC"
    );

    $search_term = "%" . $search . "%";
    $stmt->bind_param("ssss", $department, $search_term, $search_term, $search_term);
} else {
    $stmt = $conn->prepare(
        "SELECT ticket_id, date_created, owner, topic, message, status
         FROM ticket
         WHERE department = ?
         AND status != 'Closed'
         ORDER BY date_created DESC"
    );

    $stmt->bind_param("s", $department);
}

$stmt->execute();
$tickets = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            color: #172033;
        }

        nav {
            background: #172033;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        main {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .panel {
            background: white;
            padding: 22px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input {
            flex: 1;
            min-width: 0;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .search-form button {
            padding: 11px 18px;
            background: #172033;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #293852;
        }

        .clear-search {
            display: inline-block;
            margin-bottom: 15px;
            color: #2563eb;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #eef1f6;
        }

        .status {
            font-weight: bold;
        }

        @media (max-width: 700px) {
            nav {
                padding: 16px;
            }

            main {
                padding: 0 12px;
            }

            .table-wrap {
                overflow-x: auto;
            }

            .search-form {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

<nav>
    <strong>IT Support — Staff Portal</strong>
    <div>
        <span><?= ucfirst(htmlspecialchars($username)) ?></span>
        <a href="index.php">Logout</a>
    </div>
</nav>

<main>
    <section class="panel">
        <h2>Staff Dashboard</h2>
        <p>Department: <strong><?= htmlspecialchars($department) ?></strong></p>
        <p>Tickets assigned to your department are listed below.</p>
    </section>

    <section class="panel">
        <h3>Department Tickets</h3>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search by Ticket ID, submitter, or topic..."
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit">Search</button>
        </form>

        <?php if ($search !== ""): ?>
            <a class="clear-search" href="dashboard.php">Clear Search</a>
            <p>Search results for: <strong><?= htmlspecialchars($search) ?></strong></p>
        <?php endif; ?>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Date Created</th>
                        <th>Owner</th>
                        <th>Topic</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($tickets->num_rows > 0): ?>
                        <?php while ($ticket = $tickets->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($ticket["ticket_id"]) ?></td>
                                <td><?= htmlspecialchars($ticket["date_created"]) ?></td>
                                <td><?= htmlspecialchars($ticket["owner"]) ?></td>
                                <td><?= htmlspecialchars($ticket["topic"]) ?></td>
                                <td class="status"><?= htmlspecialchars($ticket["status"]) ?></td>
                                <td>
                                    <a href="view_ticket.php?id=<?= urlencode($ticket["ticket_id"]) ?>">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <?= $search !== ""
                                    ? "No matching open tickets found."
                                    : "No tickets found for this department." ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>
