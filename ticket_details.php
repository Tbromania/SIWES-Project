<?php
require "connect2DB.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ticket_login.php");
    exit;
}

$username = $_SESSION['username'];
$ticket_id = $_GET['id'] ?? '';

if ($ticket_id == "" && $_SESSION['ticket_id'] != ""){
    $ticket_id = $_SESSION['ticket_id'];
};

$sql = "SELECT * FROM ticket
        WHERE ticket_id = '$ticket_id'
        AND owner = '$username'";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Ticket not found. <a href='my_tickets.php'>Back to My Tickets</a>");
}

$ticket = $result->fetch_assoc();

// Save customer reply
if (isset($_POST["send_reply"])) {

    $chat_message = trim($_POST["chat_message"] ?? "");

    if ($chat_message !== "") {

        $sender = $username;
        $sender_type = "customer";

        $insert = $conn->prepare(
            "INSERT INTO ticket_messages
             (ticket_id, sender, sender_type, message)
             VALUES (?, ?, ?, ?)"
        );

        $insert->bind_param(
            "ssss",
            $ticket_id,
            $sender,
            $sender_type,
            $chat_message
        );

        $insert->execute();
    }

    header("Location: ticket_details.php?id=" . urlencode($ticket_id));
    exit;
}

// Load conversation messages
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
    <title>Ticket Details | IT Support</title>

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
            max-width: 850px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-block;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .ticket-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        }

        .ticket-header {
            padding: 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .ticket-number {
            color: #2563eb;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .ticket-header h1 {
            font-size: 26px;
            margin: 0 0 15px;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
        }

        .ticket-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            padding: 25px;
        }

        .info-label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .message-section {
            padding: 0 25px 25px;
        }

        .message-section h2 {
            font-size: 18px;
            margin: 0 0 14px;
        }

        .message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 14px;
        }

        .chat-message {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 5px rgba(15, 23, 42, 0.04);
        }

        .chat-message:has(.chat-sender) {
            border-left: 4px solid #2563eb;
        }

        .reply-box {
            margin-top: 25px;
        }

        .reply-box textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            resize: vertical;
        }

        .reply-box textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .reply-box button {
            margin-top: 12px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 7px;
            font-weight: bold;
            cursor: pointer;
        }

        .reply-box button:hover {
            background: #1d4ed8;
        }

        .chat-meta {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .chat-sender {
            font-weight: bold;
            color: #2563eb;
        }

        .chat-time {
            color: #64748b;
            font-size: 12px;
        }

        .chat-text {
            margin: 0;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .footer {
            padding: 20px 25px;
            border-top: 1px solid #e2e8f0;
            background: #fcfdff;
        }

        .back-button {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 7px;
            font-weight: bold;
            font-size: 14px;
        }

        .back-button:hover {
            background: #1d4ed8;
        }

        @media (max-width: 520px) {
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

            .page {
                margin: 28px auto;
                padding: 0 14px;
            }

            .ticket-header,
            .ticket-info {
                padding: 20px;
            }

            .ticket-header h1 {
                font-size: 22px;
            }

            .ticket-info {
                gap: 20px 12px;
            }

            .message-section {
                padding: 0 20px 20px;
            }

            .footer {
                padding: 18px 20px;
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

    <a href="my_tickets.php" class="back-link">
        ← Back to My Tickets
    </a>

    <section class="ticket-card">

        <div class="ticket-header">
            <div class="ticket-number">
                TICKET #<?php echo htmlspecialchars($ticket['ticket_id']); ?>
            </div>

            <h1><?php echo htmlspecialchars(ucfirst($ticket['topic'])); ?></h1>

            <span class="status">
                <?php echo htmlspecialchars($ticket['status']); ?>
            </span>
        </div>

        <div class="ticket-info">

            <div>
                <div class="info-label">Date Created</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($ticket['date_created']); ?>
                </div>
            </div>

            <div>
                <div class="info-label">Department</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($ticket['department']); ?>
                </div>
            </div>

            <div>
                <div class="info-label">Ticket Owner</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($ticket['owner']); ?>
                </div>
            </div>

            <div>
                <div class="info-label">Current Status</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($ticket['status']); ?>
                </div>
            </div>

        </div>

        <div class="message-section">
            <h2>Ticket Message</h2>

            <div class="message"><?php
                echo htmlspecialchars($ticket['message']);
            ?></div>
        </div>

        <div class="message-section">
        <h2>Conversation</h2>

        <?php if ($message_result && $message_result->num_rows > 0) { ?>

            <?php while ($chat = $message_result->fetch_assoc()) { ?>
            <div class="chat-message">

                <div class="chat-meta">
                    <span class="chat-sender">
                        <?= $chat['sender_type'] === 'staff' ? 'Support Staff' : 'You' ?>
                    </span>

                    <span class="chat-time">
                        <?= htmlspecialchars($chat['sent_at']) ?>
                    </span>
                </div>

                <p class="chat-text">
                    <?= htmlspecialchars($chat['message']) ?>
                </p>

            </div>
        <?php } ?>
        <?php } else { ?>

            <p style="color: #64748b;">No replies yet.</p>

        <?php } ?>

        <h3>Send a Reply</h3>

        <form method="POST" action="" class="reply-box">
            <textarea
                name="chat_message"
                rows="4"
                placeholder="Write your message..."
                required
                style="width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 8px;"
            ></textarea>

            <button
                type="submit"
                name="send_reply"
                style="margin-top: 12px; background: #2563eb; color: white; border: none; padding: 12px 18px; border-radius: 7px; cursor: pointer;"
            >
                Send Reply
            </button>
        </form>
        </div>
        <div class="footer">
            <a href="my_tickets.php" class="back-button">
                ← Back to My Tickets
            </a>
        </div>

    </section>

</main>

</body>
</html>