<?php
// Start the session
session_start();

// Include configuration file
require_once('../config/config.php');
// require_once '../classes/User.php';
// require_once '../classes/Transaction.php';

// Initialize an array to store error messages
$errors = [];

// Check if the form is submitted
if (isset($_POST['register'])) { // Check if the form is submitted
    $username = $_POST['username']; // Get the username from the form
    $password = $_POST['password']; // Get the password from the form

    // Perform server-side validation
    if (empty($username) || empty($password)) { // Check if username and password are empty
        $errors[] = "Username and password must be filled out"; // Add an error message
    } else { // If username and password are not empty
        // Check if username only contains alphanumeric characters and underscores and is between 5 and 15 characters long
        if (!preg_match('/^[a-zA-Z0-9_]{5,15}$/', $username)) { // Check if username is valid
            $errors[] = "Username can only contain alphanumeric characters and underscores and must be between 5 and 15 characters long"; // Add an error message
        }
        // Check if password is at least 8 characters long and contains at least one number and one letter
        elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) { // Check if password is valid
            $errors[] = "Password must be at least 8 characters long and contain at least one number and one letter"; // Add an error message
        } else {
            try {
              $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password); // Create a new PDO instance // Create a new PDO instance
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set the error mode attribute to exception

                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)"); // Prepare an SQL statement
                $stmt->execute([$username, $password]); // Execute the SQL statement with the provided parameters

                $_SESSION['Username'] = $username; // Set the session variable 'Username' to the username
                header("Location: index.php"); // Redirect the user to the index page
                exit(); // Exit the script
            } catch (PDOException $e) { // Catch any exceptions
                $errors[] = "Error: " . $e->getMessage(); // Add an error message
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

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

        .btn:hover {
            background-color: #066aa0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
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

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 20px; /* Add some space between paragraphs */
        }

        /* Styling for form fields */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 60%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Styling for the login button */
        form button {
            display: block;
            width: 20%;
            padding: 10px;
            background-color: #5a67d8; /* Indigo color */
            color: #fff; /* White text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s; /* Transition effect */
            font-size: 18px;
        }

        form button:hover {
            background-color: #434190; /* Darker shade of indigo on hover */
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            nav ul li {
                float: none;
                display: block;
            }

            /* Styling for form fields */
            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            .form-group input {
                width: 70%;
            }

            /* Styling for the login button */
            form button {
                width: 30%;
            }

            /* Adjust container padding */
            .container {
                padding: 10px;
            }
        }

    </style>
</head>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>

<div class="container">
    <div class="header">
        <h2 class="text-muted">Registration</h2>
    </div>
    <nav>
        <ul>

            <li><a href="Company.php">History</a></li>
            <li><a href="login.php">Sign in</a></li>
        </ul>
    </nav>
</div>

<div class="container">
    <div class="mainarea">
        <form action="register.php" method="post" class="form-signin" name="Registration_Form">
            <h2 class="form-signin-heading">User Registration</h2>

            <?php if (!empty($errors)) : ?> <!-- Display error messages if there are any -->
                <div class="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?> <!-- Loop through each error message -->
                            <li><?php echo $error; ?></li> <!-- Display each error message -->
                        <?php endforeach; ?> <!-- End the loop -->
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter your Username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your Password" class="form-control" required>
            </div>
            <br>
            <button name="register" value="Sign up" class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
    <p class="founded-date">Founded July 1, 2015</p> <!-- code derived from https://github.com/mariofont -->
</footer>

</body>
</html>
