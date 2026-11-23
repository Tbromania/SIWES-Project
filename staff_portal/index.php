
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal | IT Support</title>

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

        .container {
            max-width: 900px;
            margin: 70px auto;
            padding: 20px;
            text-align: center;
        }

        .container h1 {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .portal-options {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .portal-card {
            background: white;
            width: 100%;
            max-width: 330px;
            padding: 35px 28px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.07);
        }

        .portal-icon {
            font-size: 42px;
            margin-bottom: 18px;
        }

        .portal-card h2 {
            font-size: 22px;
            margin-bottom: 12px;
        }

        .portal-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 13px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.2s;
        }

        .button:hover {
            background: #0056b3;
        }

        .footer {
            text-align: center;
            color: #64748b;
            font-size: 12px;
            padding: 25px 15px;
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

            .container {
                margin: 40px auto;
            }

            .container h1 {
                font-size: 27px;
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

    <main class="container">
        <h1>Staff Portal</h1>
        <p class="subtitle">
            Access the IT support desk to manage and respond to support tickets.
        </p>

        <div class="portal-options">

            <section class="portal-card">
                <div class="portal-icon">🔐</div>
                <h2>Staff Login</h2>
                <p>
                    Already have an account? Sign in to access your staff dashboard
                    and view assigned support tickets.
                </p>
                <a href="staff_login.php" class="button">Login</a>
            </section>

            <section class="portal-card">
                <div class="portal-icon">👤</div>
                <h2>Staff Registration</h2>
                <p>
                    Register a staff account to access the IT support portal.
                </p>
                <a href="register.php" class="button">Register</a>
            </section>

        </div>
    </main>

    <footer class="footer">
        IT Support Ticketing System
    </footer>

</body>
</html>
