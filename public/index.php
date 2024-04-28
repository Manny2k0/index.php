<?php
session_start(); // Start the session

// require_once ('../classes/LoginTest.php'); // Include the User class for user-related operations
// require_once('../session.php');

// Check if the user is logged in
 if (!isset($_SESSION['Username'])) {
// if($_SESSION['Active'] == false){
    // Redirect to login page if not logged in
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Display current balance
$currentBalance = isset($_SESSION['balance']) ? $_SESSION['balance'] : 0.00; // Get current balance from session
// session_destroy();
?>



<!DOCTYPE html>
<html lang="en">
<header>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .btn-black-white {
            background-color: #000;
            color: #fff;
        }

        .btn-black-white:hover {
            background-color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f2f2f2;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        .header h3 {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 24px;
            color: #333;
        }

        nav {
            background-color: #333;
            overflow: hidden;
            border-radius: 5px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            float: left;
        }

        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav ul li a:hover {
            background-color: #111;
        }

        /* Space out content a bit */
        .container {
            display: grid;
            grid-template-columns: 1fr;
            grid-gap: 20px;
        }

        .mainarea {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 20px; /* Add some space between paragraphs */
        }

        .btn:hover {
            background-color: #066aa0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
        }

        .marketing p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .marketing h4 {
            margin-top: 20px;
            font-size: 20px;
            color: #333;
        }

        /* Button */
        form button.btn-black-white,
        nav ul li a.btn-black-white {
            padding: 10px 20px;
            background-color: #000; /* black background */
            color: #fff; /* white text */
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Button hover effect */
        form button.btn-black-white:hover,
        nav ul li a.btn-black-white:hover {
            background-color: #333; /* darker shade of gray */
            color: #fff; /* white text */
        }

        /* Styling for registration link */
        .registration-link {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .registration-link a {
            color: #000;
            text-decoration: none;
        }

        .registration-link a:hover {
            color: #333;
        }

        /* Styling for list items */
        .mainarea ul {
            list-style: none;
            padding: 0;
        }

        .mainarea ul li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }

        .mainarea ul li:before {
            content: "\2022";
            position: absolute;
            left: 0;
            color: #088BD3;
        }

        .registration-link a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000; /* black background */
            color: #fff; /* white text */
            text-decoration: none;
            border-radius: 5px; /* rounded corners */
            transition: background-color 0.3s; /* transition for background color */
        }

        .registration-link a:hover {
            background-color: #111; /* slightly darker shade on hover */
        }



        /* Responsive styles */
        @media screen and (max-width: 600px) {
            nav ul li {
                float: none;
                display: block;
            }
        }

    </style>
</header>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>

<div class="container">
    <div class="header">
        <h3 class="text-muted">Main Menu</h3>
    </div>
    <nav>
        <ul>
            <li><a href="topup.php">Top-up</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="transaction.php">History</a></li>
            <li><a href="../src/functions.php">Cards</a></li>
            <li><a href="../template/cart.php">Cart</a></li>
        </ul>
    </nav>
    <div class="mainarea">
        <h1>Welcome to Revolut</h1>
        <h2>Status: You are logged in <?php echo isset($_SESSION['Username']) ? $_SESSION['Username'] : 'Unknown'; ?></h2>
        <h3>Current Balance: <?php echo isset($_SESSION['balance']) ? "$" . number_format($_SESSION['balance'] / 100, 2) : '$0.00'; ?></h3>




        <p>Manage your finances with ease. Send money, pay bills, and more.</p>
        <form action="logout.php" method="post" name="Logout_Form">
            <button name="Submit" value="Logout" class="btn btn-black-white" type="submit">Log out</button>
        </form>
    </div>
    <!-- Registration link -->
    <div class="registration-link">
        <p>Want to create another account? <a href="register.php">Click Here!</a>.</p>
    </div>
    <div class="container"><!-- Add container here -->
        <div class="row marketing">
            <div class="mainarea">
                <h4>About Revolut</h4>
                <p>Revolut offers innovative banking services to help you save, spend, and manage your money better. With Revolut, you can:</p>
                <ul>
                    <li>Transfer money instantly to friends and family.</li>
                    <li>Quick and easy access to topup</li>
                    <li>Get instant notifications for every transaction.</li>
                    <li>And can have multiple accounts on the platform</li>
                </ul>
                <?php require_once('../template/footer.php'); ?>
            </div>
        </div>
    </div>
</div>


</body>
</html>

