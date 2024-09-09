<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        /* Basic reset for margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            position: relative; /* Ensure positioning context for menu */
        }

        header {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 60px; /* Adjust to accommodate top menu */
        }

        header h1 {
            font-size: 2.8em;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative; /* Positioning context for the menu */
        }

        .menu-bar {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .menu-bar .menu-icon {
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            transition: color 0.3s;
        }

        .menu-bar .menu-icon:hover {
            color: #f0f8ff;
        }

        .top-menu {
            display: none; /* Hidden by default */
            position: absolute;
            top: 60px;
            left: 20px;
            background: #007bff;
            color: #fff;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            transition: opacity 0.3s;
        }

        .top-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .top-menu a:hover {
            color: #f0f8ff;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s, transform 0.3s;
        }

        nav a:hover {
            color: #f0f8ff;
            transform: translateY(-3px);
        }

        .login-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .login-logout a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            margin-left: 10px;
            font-weight: 500;
            transition: color 0.3s, text-shadow 0.3s;
        }

        .login-logout a:hover {
            color: #f0f8ff;
            text-shadow: 0 0 5px #f0f8ff;
        }

        .hero {
            background: url('https://i.pinimg.com/736x/6e/35/2e/6e352ed0129e1724432707b7aaf107d7.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            color: #fff;
            text-align: center;
            padding: 150px 20px;
            position: relative;
            border-bottom: 5px solid rgba(0, 0, 0, 0.2);
        }

        .hero h1 {
            font-size: 4em;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.75em;
            margin-bottom: 30px;
            font-weight: 300;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .cta-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            display: inline-block;
        }

        .cta-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .content {
            padding: 60px 20px;
            text-align: center;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: 20px auto;
            max-width: 800px;
            border: 1px solid #e1e1e1;
        }

        .content h2 {
            margin-bottom: 20px;
            font-size: 2.2em;
            color: #007bff;
            font-weight: 700;
            line-height: 1.3;
        }

        .content p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #666;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            position: relative;
            margin-top: 20px;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }

        footer p {
            margin: 0;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <header>
        <h1>
            <div class="menu-bar">
                <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
            </div>
            Annual Medical Examination
        </h1>
        <nav>
            <a href="landing.php">List</a>
        <a href="form.php">Form</a>
        <a href="searchdata.php">Search AME Details</a>
        <a href="BNwise.php">Search Batallian Details</a>
        <a href="import.php">Upload Excel-File</a>
        <a href="record.php">Display AME Due</a>
        <a href="display.php">Display Old Records</a>
        <a href="emprecord.php">Employee Previous Record</a>
        </nav>
        <div class="login-logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="top-menu" id="topMenu">
        <a href="landing.php">List</a>
        <a href="form.php">Form</a>
        <a href="searchdata.php">Search AME Details</a>
        <a href="BNwise.php">Search Batallian Details</a>
        <a href="import.php">Upload Excel-File</a>
        <a href="record.php">Display AME Due</a>
        <a href="display.php">Display Old Records</a>
        <a href="emprecord.php">Employee Previous Record</a>
    </div>

    <section class="hero">
        <h1>Border Security Force</h1>
        <p>Duty Unto Death</p>
        <a href="landing.php" class="cta-button">Get Started</a>
    </section>

    
    <section class="content">
        <p><h2>"Go confidently in the direction of your dreams.Live the life you have imagined"</h2></p>

    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('topMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
