
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tbro's Ticket System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            color: #202938;
        }

        /* Navigation */
        nav {
            background: #172033;
            padding: 18px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .brand {
            color: white;
            font-size: 21px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #d4dbea;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover,
        .nav-links .active {
            color: white;
        }

        /* Welcome section */
        .hero {
            background: #172033;
            color: white;
            padding: 65px 8% 85px;
        }

        .hero small {
            color: #8bb8ff;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .hero h1 {
            font-size: clamp(30px, 5vw, 46px);
            margin: 18px 0;
        }

        .hero p {
            color: #cbd5e1;
            line-height: 1.8;
            max-width: 520px;
        }

        /* Main content */
        main {
            max-width: 1050px;
            margin: -35px auto 0;
            padding: 0 25px 60px;
        }

        .section-heading {
            margin: 35px 0 20px;
        }

        .section-heading h2 {
            margin-bottom: 8px;
        }

        .section-heading p {
            color: #64748b;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e1e7ef;
            box-shadow: 0 5px 18px #1720330a;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 25px;
            background: #e8f0ff;
            margin-bottom: 22px;
        }

        .card h3 {
            margin: 0 0 12px;
        }

        .card p {
            color: #64748b;
            line-height: 1.7;
            min-height: 48px;
        }

        .card a {
            display: inline-block;
            margin-top: 12px;
            padding: 12px 17px;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .card a:hover {
            background: #1d4ed8;
        }

        .status-card .card-icon {
            background: #dcfce7;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #64748b;
            border-top: 1px solid #e1e7ef;
            font-size: 13px;
        }

        @media (max-width: 520px) {
            nav {
                padding: 18px 6%;
            }

            .nav-links {
                gap: 15px;
            }

            .hero {
                padding: 45px 6% 70px;
            }

            main {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>

<body>

<nav>
    <a href="index.php" class="brand">IT Support</a>

    <div class="nav-links">
        <a href="index.php" class="active">Home</a>
        <a href="create_ticket.php">Create Ticket</a>
        <a href="ticket_login.php">Check Status</a>
    </div>
</nav>

<header class="hero">
    <small>Ticket System</small>
    <h1>How can we help you?</h1>
    <p>
        Having a technical issue? Submit a support ticket
        or check the status of a request you've already made.
        We're here to help.
    </p>
</header>

<main>
    <div class="section-heading">
        <br></br>
        <h2>What would you like to do?</h2>
        <p>Choose an option below to get started.</p>
    </div>

    <div class="cards">

        <section class="card">
            <div class="card-icon">＋</div>
            <h3>Create a Ticket</h3>
            <p>
                Report a problem with your network, hardware,
                software, printer, or another IT service.
            </p>
            <a href="create_ticket.php">Submit a Request &rarr;</a>
        </section>

        <section class="card status-card">
            <div class="card-icon">✓</div>
            <h3>Check Ticket Status</h3>
            <p>
                Already submitted a request? Log in with your
                email address and ticket ID to view your ticket.
            </p>
            <a href="ticket_login.php">View My Tickets &rarr;</a>
        </section>

    </div>
</main>

<footer>
    Tbro's Ticket System &copy; <?php echo date("Y"); ?>
</footer>

</body>
</html>
