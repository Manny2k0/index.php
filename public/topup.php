<?php

// Start the session
session_start();


// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Exit the script
}

// Include the session management file
require_once '../Session/session.php'; // Include the session management file

// Include database connection code here
// Assuming you're using PDO for database connection
try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { // Catch any exceptions
    // If connection fails, display error message and terminate script
    die("Error: " . $e->getMessage());
}

$errors = []; // Create an empty list to store errors

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the credit card number from the form, or set it to empty if not provided
    $creditCardNumber = isset($_POST["credit_card"]) ? $_POST["credit_card"] : '';

    // Get the expiration month from the form, or set it to 0 if not provided
    $expirationMonth = isset($_POST["expiration_month"]) ? $_POST["expiration_month"] : '';
    // Get the expiration year from the form, or set it to 0 if not provided
    $expirationYear = isset($_POST["expiration_year"]) ? $_POST["expiration_year"] : '';
    // Get the CVV from the form, or set it to empty if not provided
    $cvv = isset($_POST["cvv"]) ? $_POST["cvv"] : '';
    // Get the top-up amount from the form, or set it to 0 if not provided
    $topUpAmount = isset($_POST["amount"]) ? $_POST["amount"] : 0;

    // Convert the amount from dollars to cents
    $topUpAmountCents = $topUpAmount * 100;

    // Check if the credit card number is valid
    if (!validateCreditCardNumber($creditCardNumber)) {
        // If it's not valid, add an error message to the list
        $errors[] = "Invalid credit card number";
    }

    // Check if the expiration date is valid
    if (!validateExpirationDate($expirationMonth, $expirationYear)) {
        // If it's not valid, add an error message to the list
        $errors[] = "Invalid expiration date";
    }

    // Check if the CVV is valid
    if (!validateCVV($cvv)) {
        // If it's not valid, add an error message to the list
        $errors[] = "Invalid CVV";
    }

    // Check if the top-up amount is valid
    if (!validateTopUpAmount($topUpAmountCents)) {
        // If it's not valid, add an error message to the list
        $errors[] = "Minimum top-up amount is $5";
    }

    // If there are no errors
    if (empty($errors)) {
        try {
            // Update balance in the database
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE username = :username");
            $stmt->bindParam(':amount', $topUpAmountCents);
            $stmt->bindParam(':username', $_SESSION['Username']);
            $stmt->execute();

            // Insert top-up transaction record into transaction_history table
            insertTopUpTransaction($pdo, $_SESSION['user_id'], $topUpAmountCents); // Insert top-up transaction record

            // Update session balance
            $_SESSION['balance'] += $topUpAmountCents; // Update session balance

            // Check if the balance is zero
            if ($_SESSION['balance'] == 0) {
                // If balance is zero, add error message to array
                $errors[] = "Your balance is zero. Please top up your account."; // Add error message
            }

            // Redirect to the index page
            header("Location: index.php");
            exit(); // Exit the script
        } catch (PDOException $e) { // Catch any exceptions
            // If an error occurs during database operations, display error message and terminate script
            die("Error: " . $e->getMessage()); // Display error message
        }
    }
}

// Function to validate credit card number
function validateCreditCardNumber($creditCardNumber) // Function to validate credit card number
{
    // Check if the credit card number matches the pattern of four groups of four digits separated by hyphens
    if (!preg_match("/^\d{4}-\d{4}-\d{4}-\d{4}$/", $creditCardNumber)) {
        return false; // Return false if the pattern does not match
    }

    // Remove hyphens from the credit card number
    $creditCardNumber = str_replace('-', '', $creditCardNumber); // Remove hyphens from credit card number

    // Check if the credit card number is numeric and has a length of 16 digits
    return is_numeric($creditCardNumber) && strlen($creditCardNumber) === 16; // Return true if the credit card number is numeric and has a length of 16 digits
}

// Function to validate expiration date
function validateExpirationDate($expirationMonth, $expirationYear) // Function to validate expiration date
{
    // Check if both month and year are numeric and have the correct length
    if (!is_numeric($expirationMonth) || strlen($expirationMonth) !== 2 || // Check if month is numeric and has a length of 2 digits
        !is_numeric($expirationYear) || strlen($expirationYear) !== 4) { // Check if year is numeric and has a length of 4 digits
        return false;
    }

    // Check if month is within valid range (1 to 12)
    if ($expirationMonth < 1 || $expirationMonth > 12) { // Check if month is within valid range
        return false; // Return false if month is not within valid range
    }

    // Check if year is within the specified range (2025 to 2045)
    if ($expirationYear < 2025 || $expirationYear > 2045) {
        return false;
    }

    // Get current year and month
    $currentYear = date('Y'); // Get the current year
    $currentMonth = date('m'); // Get the current month

    // Check if the expiration date is in the past
    if ($expirationYear < $currentYear || ($expirationYear == $currentYear && $expirationMonth < $currentMonth)) {
        return false;
    }

    // Expiration date is valid
    return true;
}

// Function to validate CVV
function validateCVV($cvv): bool // Function to validate CVV
{
    // Check if CVV is numeric and has a length of 3 digits
    return is_numeric($cvv) && strlen($cvv) === 3 && ctype_digit($cvv); // Return true if CVV is numeric and has a length of 3 digits
}

// Function to validate top-up amount
function validateTopUpAmount($topUpAmount): bool
{
    // Check if the top-up amount is numeric and greater than or equal to 500 (corresponds to $5.00 in cents)
    return is_numeric($topUpAmount) && $topUpAmount >= 500; // Return true if the top-up amount is numeric and greater than or equal to 500
}

// Insert top-up transaction record into transaction_history table
function insertTopUpTransaction($pdo, $user_id, $topup_amount): void // Function to insert top-up transaction record
{
    try {
        // Prepare and execute SQL query to insert top-up transaction record
        $stmt = $pdo->prepare("INSERT INTO transaction_history (user_id, transaction_date, transaction_type, amount) VALUES (:user_id, CURRENT_TIMESTAMP, 'top_up', :topup_amount)");
        $stmt->bindParam(':user_id', $user_id); // Bind the user ID parameter
        $stmt->bindParam(':topup_amount', $topup_amount); // Bind the top-up amount parameter
        $stmt->execute(); // Execute the query
    } catch (PDOException $e) { // Catch any exceptions
        // If an error occurs during insertion, display error message
        echo "Error inserting top-up transaction: " . $e->getMessage(); // Display error message
    }
}

// Display current balance
$currentBalance = isset($_SESSION['balance']) ? $_SESSION['balance'] : 0.00; // Get current balance from session


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>
    <!-- Combined CSS styles -->
    <style>

            /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .btn-bw {
            background-color: #000;
            color: #fff;
        }

        .btn-bw:hover {
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
            margin-bottom: 20px; /* Add margin bottom to create space */
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        /* Hamburger menu */
        .hamburger-menu {
            display: none; /* Hide by default */
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
            background-color: #088BD3;
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

        .btn-black-white {
            background-color: #000;
            color: #fff;
        }

        .btn-black-white:hover {
            background-color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333; /* Change label text color */
            font-weight: bold; /* Make label text bold */
        }

        /* Add custom CSS styles for form inputs */
        input[type="text"],
        input[type="number"] {
            width: 100%; /* Make input fields span the entire width */
            padding: 8px; /* Add padding to input fields */
            border-radius: 5px; /* Add border radius */
            border: 1px solid #ccc; /* Add border */
            box-sizing: border-box; /* Ensure padding and border are included in width */
            margin-bottom: 10px; /* Add some space between input fields */
        }

        /* Add custom CSS styles for submit button */
        .btn-black-white {
            background-color: #000;
            color: #fff;
            padding: 10px 20px; /* Add padding to button */
            border: none; /* Remove button border */
            border-radius: 5px; /* Add border radius */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-black-white:hover {
            background-color: #333;
        }

        /* Custom CSS for Top Up Instructions */
        .marketing h4 {
            margin-top: 20px; /* Add margin to separate from other elements */
            font-size: 20px; /* Set font size */
            color: #333; /* Set text color */
        }

        .marketing p {
            font-size: 16px; /* Set font size */
            line-height: 1.6; /* Set line height for better readability */
            color: #333; /* Set text color */
            margin-bottom: 20px; /* Add margin to separate from other elements */
        }




        /* Responsive styles */
        @media screen and (max-width: 600px) {
            nav ul li {
                float: none;
                display: block;
            }
        }
    </style>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Top Up</title>
        <!-- Combined CSS styles -->
        <style>
            /* Global styles */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f5f5;
            }

            .btn-bw {
                background-color: #000;
                color: #fff;
            }

            .btn-bw:hover {
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
                margin-bottom: 20px; /* Add margin bottom to create space */
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

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th, table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            table th {
                background-color: #f2f2f2;
                color: #333;
            }

            /* Hamburger menu */
            .hamburger-menu {
                display: none; /* Hide by default */
            }

            /* Space out content a bit */
            .container {
                display: grid;
                grid-template-columns: 1fr;
                grid-gap: 20px;
            }

            .footer {
                text-align: center;
                padding: 20px;
                background-color: #f5f5f5;
                border-top: 1px solid #ddd;
                margin-top: 20px;
                color: #333;
                border-radius: 10px; /* Adding border-radius */
            }

            .founded-date1 {
                font-style: italic; /* Make the text italic */
                font-size: 20px; /* Adjust the font size */
                color: #666; /* Change the color */
                margin-top: 20px; /* Add some space between paragraphs */
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
                background-color: #088BD3;
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

            .btn-black-white {
                background-color: #000;
                color: #fff;
            }

            .btn-black-white:hover {
                background-color: #333;
            }

            label {
                display: block;
                margin-bottom: 5px;
                color: #333; /* Change label text color */
                font-weight: bold; /* Make label text bold */
            }

            /* Add custom CSS styles for form inputs */
            input[type="text"],
            input[type="number"] {
                width: 100%; /* Make input fields span the entire width */
                padding: 8px; /* Add padding to input fields */
                border-radius: 5px; /* Add border radius */
                border: 1px solid #ccc; /* Add border */
                box-sizing: border-box; /* Ensure padding and border are included in width */
                margin-bottom: 10px; /* Add some space between input fields */
            }

            /* Add custom CSS styles for submit button */
            .btn-black-white {
                background-color: #000;
                color: #fff;
                padding: 10px 20px; /* Add padding to button */
                border: none; /* Remove button border */
                border-radius: 5px; /* Add border radius */
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .btn-black-white:hover {
                background-color: #333;
            }

            /* Custom CSS for Top Up Instructions */
            .marketing h4 {
                margin-top: 20px; /* Add margin to separate from other elements */
                font-size: 20px; /* Set font size */
                color: #333; /* Set text color */
            }

            .marketing p {
                font-size: 16px; /* Set font size */
                line-height: 1.6; /* Set line height for better readability */
                color: #333; /* Set text color */
                margin-bottom: 20px; /* Add margin to separate from other elements */
            }




            /* Responsive styles */
            @media screen and (max-width: 600px) {
                nav ul li {
                    float: none;
                    display: block;
                }
            }
        </style>
    </head>
    <body>

    <header class="header">
        <h1 class="founded-date1">Revolut</h1>
    </header>

    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">Top Up</h3> <!-- Move "Top Up" header here -->
            <hr> <!-- Add a horizontal line underneath the header -->
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="transfer.php">Transfer</a></li>
                    <li><a href="transaction.php">History</a></li>
                    <li><a href="functions.php">Card</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </div>

        <div class="mainarea">
            <h1>Top Up</h1>

            <!-- This line displays a heading with the text "Current Balance:" -->
            <h3>Current Balance:
                <!-- This PHP code checks if the session variable 'balance' is set -->
                <?php echo isset($_SESSION['balance']) ? "$" . number_format($_SESSION['balance'] / 100, 2) : '$0.00'; ?>
                <!-- If the balance is set, it displays the balance in dollars with two decimal places, otherwise it displays '$0.00' -->
            </h3>

            <!-- This PHP code block checks if there are any errors -->
            <?php if (!empty($errors)) : ?>
                <!-- If there are errors, it displays a div with the class 'error' -->
                <div class="error">
                    <!-- Inside the div, it displays an unordered list -->
                    <ul>
                        <!-- This PHP foreach loop iterates over each error in the $errors array -->
                        <?php foreach ($errors as $error) : ?>
                            <!-- For each error, it displays a list item with the error message -->
                            <li><?php echo $error; ?></li> <!-- Display the error message -->
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>


            <!-- Insert your top-up form here -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Use htmlspecialchars to prevent XSS attacks -->
                <!-- Insert input fields for credit card and personal information -->
                <!-- Example: -->
                <label for="credit_card">Credit Card Number:</label>
                <input type="text" id="credit_card" name="credit_card" placeholder="Credit Card Number" maxlength="19" required><br><br>

                <label for="expiration_month">Expiration Date:</label>
                <input type="number" id="expiration_month" name="expiration_month" placeholder="MM" min="1" max="12" required>/
                <input type="number" id="expiration_year" name="expiration_year" placeholder="YYYY" min="2025" max="2050" required><br><br>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" placeholder="3 digit number" name="cvv" maxlength="3" required><br><br>

                <label for="amount">Amount to Top Up:</label>
                <input type="number" id="amount" placeholder="Enter Amount" name="amount" min="5" step="1" required><br><br>

                <button type="submit" class="btn btn-black-white">Top Up</button>



            </form>
        </div>

        <div class="container">
            <div class="row marketing">
                <div>
                    <h4>Top Up Instructions</h4>
                    <p>Provide your credit card details and the amount you want to top up. Ensure that the information entered is accurate before submitting the form.</p>
                </div>
            </div>
        </div>
    </div>


    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
        <p class="founded-date">Founded July 1, 2015</p>
    </footer>
    </body>
    </html>


