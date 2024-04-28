<?php


session_start(); // Begin session to track user's activity
use src\Login;



// rest of your code


require_once __DIR__ . '/../vendor/autoload.php';

// Include configuration and session management files


require_once('config.php'); // Load configuration settings
require_once('session.php'); // Include session management functionality
// Start the session


// Initialize an array to store error messages
$errors = [];

// Check if the user is already logged in
if (isset($_SESSION['Username'])) { // If user is already logged in
    header("Location: index.php"); // Redirect to index.php if logged in
    exit(); // Stop executing the script
}


// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST") { // If login form is submitted
    // Retrieve username and password from the form
    $username = $_POST['Username']; // Get the entered username
    $password = $_POST['Password']; // Get the entered password

    $login = new Login(); // Create a new instance of the Login class
    $login->authenticate($username, $password); // Call the authenticate method to check if the user is valid

    // Your login authentication code here
    // For example, you can query the database to check if the username and password match
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!'); // Establish connection to the database
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception handling

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password"); // Prepare SQL statement
        $stmt->bindParam(':username', $username); // Bind username parameter
        $stmt->bindParam(':password', $password); // Bind password parameter
        $stmt->execute(); // Execute the prepared statement

        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data from the result set

        if ($user) { // If user data is retrieved
            // Login successful, set session variables
            $_SESSION['Username'] = $user['username']; // Store username in session variable
            $_SESSION['balance'] = $user['balance']; // Store balance in session variable
            $_SESSION['user_id'] = $user['id']; // Add this line: Store user ID in session variable

            // Redirect to intended destination
            header("Location: index.php"); // Redirect to index.php
            exit(); // Stop executing the script
        } else {
            // Login failed, add error message to array
            $errors[] = "Invalid username or password"; // Add error message
        }
    } catch (PDOException $e) { // Catch any PDO exceptions
        $errors[] = "Error: " . $e->getMessage(); // Add error message
    }
}

session_destroy(); // Destroy the session
?>



<?php

require_once('../src/Login.php'); // Include the Login class
// Start the session
session_start(); // Begin session to track user's activity

// Initialize an array to store error messages
$errors = [];

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['Username']; // Get the entered username
    $password = $_POST['Password']; // Get the entered password

    $login = new Login(); // Create a new instance of the Login class
    $isAuthenticated = $login->authenticate($username, $password); // Call the authenticate method to check if the user is valid

    if ($isAuthenticated) { // If the user is authenticated
        // Login successful, set session variables
        $_SESSION['Username'] = $username; // Store username in session variable
  //      $_SESSION['Active'] = true;
        header("Location: index.php"); // Redirect to index.php
        exit(); // Stop executing the script
    } else {
        // Login failed, add error message to array
        $errors[] = "Invalid username or password"; // Add error message
    }
}

session_destroy(); // Destroy the session
?>


<?php

// include __DIR__ . '/../template/header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

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

        .header1 {
            text-align: center;
            padding: 20px;

            border-buttom: 1px solid #ddd;
            margin-top: 20px;
            color: #333;

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

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 0px; /* Add some space between paragraphs */
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
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Styling for the login button */
        form button {
            display: block;
            width: 30%;
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

    </style>
</head>

<header>



</header>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>


<div class="container">
    <div class="header">
        <h2 class="text-muted">Main Menu</h2>
    </div>
    <nav>
        <ul>

            <li><a href="Company.php">Company</a></li>
            <li><a href="register.php">Register</a></li>

        </ul>
    </nav>
</div>

<div class="container">
    <div class="mainarea">
        <form action="" method="post" class="form-signin" name="Login_Form">
            <h2 class="form-signin-heading">User Login</h2>
            <?php if (!empty($errors)) : ?> <!-- Display error messages if there are any -->
                <div class="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?> <!-- Loop through the errors array -->
                            <li><?php echo $error; ?></li> <!-- Display each error message -->
                        <?php endforeach; ?> <!-- End of the loop -->
                    </ul>
                </div>
            <?php endif; ?> <!-- End of error message display -->
            <div class="form-group">
                <label for="Username">Username:</label>
                <input type="text" name="Username" id="Username" placeholder="Enter your Username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Password">Password:</label>
                <input type="password" name="Password" id="Password" placeholder="Enter your Password" class="form-control" required>
            </div>
            <button name="Submit"  value="Login" type="submit">Sign in</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
    <p class="founded-date">Founded July 1, 2015</p> <!-- code derived from https://github.com/mariofont -->
</footer>
</body>
</html>
