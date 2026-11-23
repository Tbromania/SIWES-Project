
<?php
    require "connect2DB.php";
    require_once "send_ticket_email.php";
    session_start();

    $error = "";
    $success = false;
    $email_sent = false;
    $ticket_id = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $action = $_POST['create'] ?? "";

        if ($action == 'create_ticket') {

            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $number = $_POST['phone'];
            $department = $_POST['department'];
            $topic = ucfirst($_POST['topic']);
            $message = $_POST['message'];

            $fullname = $conn->real_escape_string($fullname);
            $number = $conn->real_escape_string($number);
            $username = $conn->real_escape_string($username);
            $department = $conn->real_escape_string($department);
            $topic = $conn->real_escape_string($topic);
            $message = $conn->real_escape_string($message);
            

            $allowed_departments = [
                'IT Support',
                'Accounts',
                'Human Resources',
                'Administration'
            ];

            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email address.";
            } elseif (!in_array($department, $allowed_departments, true)) {
                $error = "Invalid department selected.";
            } else {

                $check = "SELECT * FROM ticket_user WHERE username = '$username'";
                $result = $conn->query($check);

                if (!$result) {
                    $error = "Unable to check user details. Please try again.";
                } else {

                    if ($result->num_rows > 0) {
                        // Existing user
                    } else {
                        $new_user = "INSERT INTO ticket_user (fullname, number, username)
                        VALUES ('$fullname', '$number', '$username')";

                        if (!$conn->query($new_user)) {
                            $error = "Error creating user: " . $conn->error;
                        }
                    }

                    if ($error === "") {
                        $ticket_id = random_int(1000, 9999);
                        $date = date("Y-m-d");
                        $status = "Open";

                     
                        $new_ticket = "INSERT INTO ticket (ticket_id, date_created, owner, department, status, topic, message)
                        VALUES ('$ticket_id', '$date', '$username', '$department', '$status', '$topic', '$message')";

                        if (!$conn->query($new_ticket)) {
                            $error = "Error creating ticket: " . $conn->error;
                        } else {
                            $success = true;
                            $email_sent = sendTicketEmail($username, $ticket_id, $topic, $message);
                        }
                    }

                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open New Ticket</title>

    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

    .error-message {
        color: #dc2626;
        background: #fef2f2;
        border: 1px solid #fecaca;
        padding: 12px 15px;
        margin-top: 15px;
        border-radius: 6px;
        text-align: center;
    }

    .success-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .success-card {
        background: white;
        width: 350px;
        max-width: calc(100% - 50px);
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    }

    .success-card h2 {
        color: #16a34a;
        margin-top: 0;
    }

    .success-card p {
        color: #475569;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .success-card button {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    .success-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
    }

    .success-card button:hover {
        background: #1d4ed8;
    }
        
    nav {
        width: 100%;
        box-sizing: border-box;
        background: #172033;
        padding: 18px 4%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin: 0 0 30px;
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
        display: inline-block;
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

    @media (max-width: 520px) {
        nav {
            padding: 16px 6%;
            gap: 12px;
        }

        .nav-links {
            width: 100%;
            gap: 4px;
        }

        .nav-links a {
            padding: 10px 12px;
            font-size: 13px;
        }
    }
    </style>
</head>
<body>
    
<nav>
    <a href="index.php" class="brand">IT Support</a>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="create_ticket.php" class="active">Create Ticket</a>
        <a href="ticket_login.php">Check Status</a>
    </div>
</nav>


<div class="container">
    <h2>Create Ticket</h2>


    <form method="POST">

        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="fullname"
               placeholder="Enter your full name" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="username"
               placeholder="Enter your email" required>

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone"
               placeholder="Enter your phone number" required>

        <label for="topic">Topic</label>
        <select id="topic" name="topic" required>
            <option value="">-- Select a topic --</option>
            <option value="network">Network</option>
            <option value="hardware">Hardware</option>
            <option value="software">Software</option>
            <option value="acc/login">Account/Login</option>
            <option value="printer">Printer</option>
            <option value="email">Email</option>
            <option value="database">Database</option>
            <option value="security">Security</option>
            <option value="other">Other</option>
        </select>

        <label for="department">Department</label>
        <select id="department" name="department" required>
            <option value="">Select a department</option>
            <option value="IT Support">IT Support</option>
            <option value="Accounts">Accounts</option>
            <option value="Human Resources">Human Resources</option>
            <option value="Administration">Administration</option>
        </select>

        <label for="message">Message</label>
        <textarea id="message" name="message"
                  placeholder="Write your message here..."
                  required></textarea>

        <button type = "submit" name = "create" value = "create_ticket">Create</button>

    <?php if ($error !== "") { ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php } ?>
    </form>
</div>
<?php if ($success) { ?>
    <div class="success-overlay">
        <div class="success-card">
            <h2>Ticket Created!</h2>

            <p>Your ticket was created successfully.</p>

            <p>
                <strong>Username:</strong><br>
                <?= htmlspecialchars($username) ?>
            </p>

            <p>
                <strong>Ticket ID:</strong><br>
                <?= (int)$ticket_id ?>
            </p>

            <p>
                <?= $email_sent
                    ? 'A confirmation email has been sent to you.'
                    : 'Your ticket was created, but the confirmation email could not be sent.' ?>
            </p>

            <button onclick="window.location.href='ticket_login.php'">
                Continue
            </button>
        </div>
    </div>
    <?php } ?>
</body>
</html>