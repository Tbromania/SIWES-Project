<?php
require "connect2DB.php";

session_start();
$password_error = "";
$unregistered_error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $action = $_POST['action'] ?? "";

    if ($action == "login"){
        $check = "SELECT * FROM ticket WHERE owner = '$username'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $ticket = $result->fetch_assoc();

            $_SESSION['username'] = $ticket['owner'];
            $_SESSION['ticket_id'] = $password;

            header("Location: ticket_details.php");
            exit;
        }
        else {
            $password_error = "Incorrect email or ticket ID";
        }
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Ticket Status</title>

<style>
    
    body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    margin: 0;
    min-height: 100vh;
    }

    .card {
    background: white;
    padding: 30px;
    width: 300px;
    max-width: calc(100% - 60px);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

    margin: 70px auto 0;
    }

    h1 {
        text-align: center;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    .fbutton {
        width: 100%;
        padding: 10px;
        cursor: pointer;
    }

        
    nav {
    width: 100%;
    box-sizing: border-box;
    background: #172033;
    padding: 18px 4%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    position: relative;
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
        flex-direction: row;
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
    display: inline-block;
    }
     


    /* Mobile navigation */
    @media (max-width: 520px) {
        nav {
            padding: 16px 6%;
            align-items: flex-start;
        }

        .nav-links {
            width: 100%;
            gap: 5px;
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
        <a href="create_ticket.php">Create Ticket</a>
        <a href="ticket_login.php" class="active">Check Status</a>
    </div>
    </nav>

    <header>
        </header>
        
        <div class="card">
        
            <h1>Open Ticket</h1>
            
            <form method="POST">
                
                <label>Username:</label>
                <input type="text" name="username"  value = "" <?php echo $username; ?> required>
                <text style = "font-size: 10px; color: red"><?php echo $password_error; ?> </text>


                <label>Ticket ID:</label>
                <input type="text" name="password" value = "" required>

                <br></br>
                
                <button name = "action"  value = "login" class="fbutton">Continue</button>
            </form>
        </div>

        <text style = "position: fixed; bottom: 80px; font-size: 12px; color: red"><?php echo $unregistered_error; ?></text>


</body>
</html>
