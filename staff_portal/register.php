
<?php
session_start();
require "../connect2DB.php";

$password_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = ucwords(trim($_POST["fullname"] ?? ""));
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $department = trim($_POST["department"] ?? "");

    if (($_POST["rg_button"]) == "register"){

        // Check whether the username already exists
        $check = $conn->prepare(
            "SELECT username FROM ticket_staff WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {

            $new_staff_login = $conn->prepare(
                "INSERT INTO ticket_staff_login (username, password)
                 VALUES (?, ?)"
            );
            $new_staff_login->bind_param("ss", $username, $password);

            if (!$new_staff_login->execute()) {
                die("Error registering staff login: " . $conn->error);
            }

            $new_staff = $conn->prepare(
                "INSERT INTO ticket_staff (username, fullname, department)
                 VALUES (?, ?, ?)"
            );
            $new_staff->bind_param("sss", $username, $fullname, $department);

            if (!$new_staff->execute()) {
                die("Error registering staff profile: " . $conn->error);
            }

            header("Location: staff_login.php");
            exit;
        } else {
            die("Username " . htmlspecialchars($username) . " already exists.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration | IT Support</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #172033;
            min-height: 100vh;
        }

        nav {
            background: #172033;
            color: white;
            padding: 16px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h2 {
            font-size: 22px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
        }

        nav a:hover {
            color: #60a5fa;
        }

        .page-container {
            min-height: calc(100vh - 58px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 35px 16px;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 430px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .register-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-icon {
            font-size: 38px;
            margin-bottom: 12px;
        }

        .register-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .register-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            color: #172033;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.12);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #0056b3;
        }

        .register-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: #64748b;
        }

        .register-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            nav {
                padding: 16px 5%;
            }

            nav h2 {
                font-size: 18px;
            }

            nav a {
                margin-left: 10px;
                font-size: 12px;
            }

            .register-card {
                padding: 26px 22px;
            }
        }
    </style>
</head>

<body>

    <nav>
        <h2>IT Support</h2>
        <div>
            <a href="../index.php">Home</a>
            <a href="index.php">Staff Portal</a>
        </div>
    </nav>

    <main class="page-container">
        <section class="register-card">

            <div class="register-header">
                <div class="register-icon">👤</div>
                <h1>Staff Registration</h1>
                <p>Create an account to access the staff portal.</p>
            </div>

            <form action="register.php" method="POST">

                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Choose a username"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select your department</option>
                        <option value="IT Support">IT Support</option>
                        <option value="Accounts">Accounts</option>
                        <option value="Human Resources">Human Resources</option>
                        <option value="Administration">Administration</option>
                    </select>
                </div>

                <button type="submit" name="rg_button" value="register">Create Account</button>

            </form>

            <div class="register-footer">
                Already registered?
                <a href="staff_login.php">Login here</a>
            </div>

        </section>
    </main>

</body>
</html>
