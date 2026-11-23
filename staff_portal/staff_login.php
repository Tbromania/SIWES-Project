<?php
session_start();
require "../connect2DB.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (isset($_POST["login_button"]) && $_POST["login_button"] === "login") {

        // Check the login credentials
        $check = "SELECT username, password
                  FROM ticket_staff_login
                  WHERE username = '$username'";

        $result = $conn->query($check);

        if ($result && $result->num_rows === 1) {
            $staff = $result->fetch_assoc();

            // Verify password
            if ($password === $staff["password"]) {

                // Get the staff member's department
                $department_query = "SELECT department
                                     FROM ticket_staff
                                     WHERE username = '$username'";

                $department_result = $conn->query($department_query);

                if ($department_result && $department_result->num_rows === 1) {
                    $details = $department_result->fetch_assoc();

                    // Store session details
                    session_regenerate_id(true);
                    $_SESSION["staff_username"] = $staff["username"];
                    $_SESSION["department"] = $details["department"];

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Staff department not found.";
                }

            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | IT Support</title>

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

        /* Navigation bar */
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

        /* Login layout */
        .page-container {
            min-height: calc(100vh - 58px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 16px;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-icon {
            font-size: 38px;
            margin-bottom: 12px;
        }

        .login-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Form fields */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.12);
        }

        /* Login button */
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

        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #64748b;
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

            .login-card {
                padding: 26px 22px;
            }
        }
    </style>
</head>

<body>

    <nav>
        <h2>IT Support</h2>

        <div>
            <a href="index.php">Home</a>
            <a href="staff_login.php">Staff Login</a>
        </div>
    </nav>

    <main class="page-container">
        <section class="login-card">

            <div class="login-header">
                <div class="login-icon">🎧</div>
                <h1>Staff Login</h1>
                <p>Sign in to access and manage support tickets.</p>
            </div>

            <form action="staff_login.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
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
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" name="login_button" value="login">Login</button>

            <?php if ($error !== ""): ?>
                <p style="color: red; text-align: center; margin-top: 15px;">
            <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>
            </form>

            <div class="login-footer">
                Authorized staff only
            </div>

        </section>
    </main>

</body>
</html>
