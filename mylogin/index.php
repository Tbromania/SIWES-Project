<?php
require "login.php";

session_start();
$password_error = "";
$unregistered_error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $action = $_POST['action'] ?? "";

        if ($action == "login"){

        $check = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($check);
        
            if ($result->num_rows > 0){

                $user = $result->fetch_assoc();

                $_SESSION['username'] = $user['Username'];
                $user_password = $user["Password"];

                if ($password === $user_password){
                    header("Location: welcome.php");
                    exit;
                }
                else{
                    $password_error = "Incorrect Password";
                    session_destroy();
                }
            }
        else{
            $unregistered_error = "This is not a registered username";
        }
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>PHP Login</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card {
        background: white;
        padding: 30px;
        width: 300px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
</style>

</head>

<body>
    <header>
        </header>
        
        <div class="card">
        
            <h1>Login</h1>
            
            <form method="POST">
                
                <label>Username:</label>
                <input type="text" name="username"  value = "" <?php echo $username; ?> required>
                <text style = "font-size: 10px; color: red"><?php echo $password_error; ?> </text>


                <label>Password:</label>
                <input type="password" name="password" value = "" required>

                <br></br>
                
                <button name = "action"  value = "login" class="fbutton">Continue</button>
            </form>
        </div>
        
        <a href = "new_profile.php" style = "position: fixed; bottom: 100px; font-size: 13px">Create New</a>
        <text style = "position: fixed; bottom: 80px; font-size: 12px; color: red"><?php echo $unregistered_error; ?></text>


</body>
</html>
