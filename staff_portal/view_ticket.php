
<?php
session_start();
require "../connect2DB.php";
require_once "../send_ticket_email.php";

if (!isset($_SESSION["staff_username"]) || !isset($_SESSION["department"])) {
    header("Location: staff_login.php");
    exit;
}

$ticket_id = $_GET["id"] ?? "";
$error = "";

$allowed_statuses = ["Open", "In Progress", "On Hold", "Closed"];

/// Save staff reply
if (isset($_POST["send_reply"])) {

    $ticket_id = $_POST["ticket_id"] ?? "";
    $chat_message = trim($_POST["chat_message"] ?? "");
    $department = $_SESSION["department"];

    if ($chat_message !== "") {
        $check = $conn->prepare(
            "SELECT ticket_id FROM ticket
             WHERE ticket_id = ? AND department = ?"
        );

        $check->bind_param("ss", $ticket_id, $department);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $sender = $_SESSION["staff_username"];
            $sender_type = "staff";

            $insert = $conn->prepare(
                "INSERT INTO ticket_messages
                 (ticket_id, sender, sender_type, message)
                 VALUES (?, ?, ?, ?)"
            );

            $insert->bind_param("ssss", $ticket_id, $sender, $sender_type, $chat_message);
            $insert->execute();
        }
    }

    header("Location: view_ticket.php?id=" . urlencode($ticket_id));
    exit;

} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ticket_id = $_POST["ticket_id"] ?? "";
    $new_status = $_POST["status"] ?? "";

    if (in_array($new_status, $allowed_statuses, true)) {
        $department = $_SESSION["department"];

        $check = "SELECT owner, status FROM ticket
                  WHERE ticket_id = '$ticket_id'
                  AND department = '$department'";

        $check_result = $conn->query($check);

        if ($check_result && $check_result->num_rows > 0) {
            $old_ticket = $check_result->fetch_assoc();

            $recipient = $old_ticket["owner"];
            $old_status = $old_ticket["status"];

            $update = "UPDATE ticket
                       SET status = '$new_status'
                       WHERE ticket_id = '$ticket_id'
                       AND department = '$department'";

            if ($conn->query($update)) {

                if ($old_status !== $new_status) {
                    sendStatusUpdateEmail(
                        $recipient,
                        $ticket_id,
                        $new_status
                    );
                }

                header("Location: view_ticket.php?id=" . urlencode($ticket_id));
                exit;

            } else {
                $error = "Error updating status: " . $conn->error;
            }
        } else {
            $error = "Ticket not found.";
        }

    } else {
        $error = "Invalid status selected.";
    }
}

// Retrieve ticket details
$department = $_SESSION["department"];

$query = "SELECT ticket_id, date_created, owner, department, topic, message, status
          FROM ticket
          WHERE ticket_id = '$ticket_id'
          AND department = '$department'";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    die("Ticket not found or you do not have access to it.
         <a href='dashboard.php'>Back to dashboard</a>");
}

$ticket = $result->fetch_assoc();
$message_sql = "SELECT * FROM ticket_messages
                WHERE ticket_id = '$ticket_id'
                ORDER BY sent_at ASC";

$message_result = $conn->query($message_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket</title>

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
        }

        main {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .detail {
            margin: 18px 0;
            overflow-wrap: anywhere;
        }

        .label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        .message {
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .status {
            font-weight: bold;
        }

        button {
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 18px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #b91c1c;
        }

        .back {
            display: inline-block;
            margin-right: 15px;
            color: #2563eb;
            text-decoration: none;
        }

        select {
            display: block;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 8px;
            min-width: 200px;
        }

        .error {
            color: #dc2626;
            margin-bottom: 15px;
        }

        @media (max-width: 520px) {
            nav {
                padding: 16px;
            }

            main {
                margin: 20px auto;
                padding: 0 12px;
            }

            .panel {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<nav>
    <strong>IT Support — Staff Portal</strong>
    <a href="dashboard.php">Dashboard</a>
</nav>

<main>
    <section class="panel">
        <h2>Ticket Details</h2>

        <?php if ($error !== ""): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="detail">
            <span class="label">Ticket ID</span>
            <?= htmlspecialchars($ticket["ticket_id"]) ?>
        </div>

        <div class="detail">
            <span class="label">Date Created</span>
            <?= htmlspecialchars($ticket["date_created"]) ?>
        </div>

        <div class="detail">
            <span class="label">Submitted By</span>
            <?= htmlspecialchars($ticket["owner"]) ?>
        </div>

        <div class="detail">
            <span class="label">Department</span>
            <?= htmlspecialchars($ticket["department"]) ?>
        </div>

        <div class="detail">
            <span class="label">Topic</span>
            <?= htmlspecialchars($ticket["topic"]) ?>
        </div>

        <div class="detail">
            <span class="label">Message</span>
            <div class="message"><?= htmlspecialchars($ticket["message"]) ?></div>
        </div>

        <div class="detail">
            <span class="label">Current Status</span>
            <span class="status"><?= htmlspecialchars($ticket["status"]) ?></span>
        </div>

        <hr>

        <h3>Conversation</h3>

        <?php if ($message_result && $message_result->num_rows > 0) { ?>

            <?php while ($chat = $message_result->fetch_assoc()) { ?>
                <div class="detail" style="background: #f4f6fa; padding: 12px; border-radius: 6px;">
                    <strong>
                        <?= $chat["sender_type"] === "staff" ? "Support Staff" : "Customer" ?>
                    </strong>

                    <small style="color: #64748b;">
                        <?= htmlspecialchars($chat["sent_at"]) ?>
                    </small>

                    <p><?= htmlspecialchars($chat["message"]) ?></p>
                </div>
            <?php } ?>

        <?php } else { ?>

            <p>No replies yet.</p>

        <?php } ?>

        <h3>Send a Reply</h3>

        <form method="POST" action="">

            <input type="hidden" name="ticket_id"
                value="<?= htmlspecialchars($ticket["ticket_id"]) ?>">

            <textarea name="chat_message" rows="4"
                    placeholder="Write your reply..." required
                    style="width: 100%; padding: 12px; box-sizing: border-box;"></textarea>

            <button type="submit" name="send_reply"
                    style="margin-top: 12px; background: #2563eb;">
                Send Reply
            </button>

        </form>
        <hr>

        <h3>Change Ticket Status</h3>

        <form method="POST">
            <input type="hidden" name="ticket_id"
                   value="<?= htmlspecialchars($ticket["ticket_id"]) ?>">

            <label for="status">Select a status:</label>

            <select name="status" id="status" required>
                <option value="Open"
                    <?= $ticket["status"] === "Open" ? "selected" : "" ?>>
                    Open
                </option>

                <option value="In Progress"
                    <?= $ticket["status"] === "In Progress" ? "selected" : "" ?>>
                    In Progress
                </option>

                <option value="On Hold"
                    <?= $ticket["status"] === "On Hold" ? "selected" : "" ?>>
                    On Hold
                </option>

                <option value="Closed"
                    <?= $ticket["status"] === "Closed" ? "selected" : "" ?>>
                    Closed
                </option>
            </select>

            <button type="submit" style="margin-top: 15px;">
                Update Status
            </button>
        </form>

        <p style="margin-top: 25px;">
            <a class="back" href="dashboard.php">Back to Dashboard</a>
        </p>
    </section>
</main>

</body>
</html>
