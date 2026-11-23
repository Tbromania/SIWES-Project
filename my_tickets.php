<?php
require "connect2DB.php";
session_start();

$username = $_SESSION['username'];

$sql = "SELECT * FROM ticket WHERE owner = '$username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets | IT Support</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            margin: 0;
            color: #172033;
        }

        nav {
            width: 100%;
            background: #172033;
            padding: 18px 4%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .brand {
            color: white;
            font-size: 21px;
            font-weight: bold;
            text-decoration: none;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 11px 16px;
            border-radius: 7px;
        }

        .nav-links a:hover {
            color: white;
            background: #303b50;
        }

        .nav-links a.active {
            color: white;
            background: #2563eb;
        }

        .page {
            max-width: 1150px;
            margin: 45px auto;
            padding: 0 24px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 30px;
            margin: 0 0 10px;
        }

        .page-header p {
            color: #64748b;
            margin: 0;
            line-height: 1.6;
        }

        .ticket-panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .panel-header {
            padding: 22px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-header h2 {
            font-size: 18px;
            margin: 0;
        }

        .ticket-count {
            color: #64748b;
            font-size: 13px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 20px;
            white-space: nowrap;
        }

        td {
            padding: 18px 20px;
            border-top: 1px solid #edf0f5;
            font-size: 14px;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f8faff;
        }

        .ticket-id {
            color: #2563eb;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #dbeafe;
            color: #1d4ed8;
        }

        .view-link {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 9px 13px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        .view-link:hover {
            background: #1d4ed8;
        }

        .empty-state {
            text-align: center;
            padding: 45px 20px;
            color: #64748b;
        }

        .empty-state p {
            margin: 8px 0 20px;
        }

        .create-link {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 7px;
            font-weight: bold;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .page {
                margin: 30px auto;
                padding: 0 14px;
            }

            .page-header h1 {
                font-size: 25px;
            }

            nav {
                padding: 16px 5%;
            }

            .nav-links {
                width: 100%;
            }

            .nav-links a {
                padding: 10px 12px;
                font-size: 13px;
            }

            .panel-header {
                padding: 18px;
            }

            th, td {
                padding: 14px;
            }
        }
    </style>
</head>

<body>

<nav>
    <a href="index.php" class="brand">IT Support</a>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="create_ticket.php">Create Ticket</a>
        <a href="ticket_login.php" class="active">Check Status</a>
    </div>
</nav>

<main class="page">

    <div class="page-header">
        <h1>My Tickets</h1>
        <p>View your support requests and check their current status.</p>
    </div>

    <section class="ticket-panel">

        <div class="panel-header">
            <h2>Ticket History</h2>

            <span class="ticket-count">
                <?php echo $result->num_rows; ?>
                ticket(s)
            </span>
        </div>

        <?php if ($result->num_rows > 0) { ?>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Date Created</th>
                            <th>Topic</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($ticket = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="ticket-id">
                                    #<?php echo $ticket['ticket_id']; ?>
                                </td>

                                <td>
                                    <?php echo $ticket['date_created']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(ucfirst($ticket['topic'])); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($ticket['department']); ?>
                                </td>

                                <td>
                                    <span class="status">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <a class="view-link"
                                       href="ticket_details.php?id=<?php echo $ticket['ticket_id']; ?>">
                                        View Ticket →
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else { ?>

            <div class="empty-state">
                <h3>No tickets yet</h3>
                <p>Your support requests will appear here once created.</p>
                <a href="create_ticket.php" class="create-link">
                    Create a Ticket
                </a>
            </div>

        <?php } ?>

    </section>

</main>

</body>
</html>