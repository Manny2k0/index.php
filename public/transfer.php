<?php
session_start(); // Start or resume the session
require_once('../config/config.php'); // Include the database configuration file

if (!isset($_SESSION['Username'])) { // Check if the user is logged in
    header("Location: login.php"); // Redirect to the login page
    exit(); // Exit the script
}

// Initialize variables
try {
    $pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!'); // Create a new PDO instance
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set the error mode attribute to exception

    $username = $_SESSION['Username']; // Get the username from the session
    $stmt = $pdo->prepare("SELECT id, balance FROM users WHERE username = :username"); // Prepare an SQL statement
    $stmt->bindParam(':username', $username); // Bind the parameter
    $stmt->execute(); // Execute the SQL statement
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array


    if (!$user) { // Check if the user is not found
        throw new Exception("User not found"); // Throw an exception
    }

    $user_id = $user['id']; // Get the user ID from the result
    $_SESSION['user_id'] = $user_id; // Set the session variable 'user_id' to the user ID
    $_SESSION['balance'] = $user['balance']; // Set the session variable 'balance' to the user's balance

    $stmt = $pdo->prepare("SELECT * FROM transaction_history WHERE user_id = :user_id ORDER BY transaction_date DESC"); // Prepare an SQL statement
    $stmt->bindParam(':user_id', $user_id); // Bind the parameter
    $stmt->execute(); // Execute the SQL statement
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all the results as an associative array
} catch (PDOException $e) { // Catch any PDO exceptions
    echo "Error: " . $e->getMessage(); // Display the error message
} catch (Exception $e) { // Catch any other exceptions
    echo "Error: " . $e->getMessage(); // Display the error message
}

$errors = []; // Initialize an empty array to store error messages

// Process the transfer
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
    if (isset($_POST['Amount']) && isset($_POST['Recipient']) && isset($_POST['Purpose'])) {  // Check if the form fields are set
        $transfer_amount = $_POST['Amount'] * 100; // Convert the amount to cents
        $recipient_username = $_POST['Recipient']; // Get the recipient username
        $purpose_of_transfer = $_POST['Purpose']; // Get the purpose of the transfer

        if($_SESSION['balance'] < $transfer_amount) { // Check if the user has sufficient balance
            $errors[] = "Insufficient balance"; // Add an error message
        } else { // If the user has sufficient balance
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username"); // Prepare an SQL statement
            $stmt->bindParam(':username', $recipient_username); // Bind the parameter
            $stmt->execute(); // Execute the SQL statement
            $recipient = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array

            if (!$recipient) { // Check if the recipient is not found
                $errors[] = "Recipient not found"; // Add an error message
            } else { // If the recipient is found
                $recipient_id = $recipient['id']; // Get the recipient ID

                try { // Try to process the transfer
                    $pdo->beginTransaction(); // Begin a transaction

                    // Insert transaction for sender
                    $stmt_sender = $pdo->prepare("INSERT INTO transaction_history (user_id, recipient_id, transaction_date, transaction_type, amount, purpose_of_transfer) VALUES (:user_id, :recipient_id, CURRENT_TIMESTAMP, 'transfer', :transfer_amount, :purpose_of_transfer)");
                    $stmt_sender->bindParam(':user_id', $user_id); // Bind the parameter
                    $stmt_sender->bindParam(':recipient_id', $recipient_id); // Bind the parameter
                    $stmt_sender->bindParam(':transfer_amount', $transfer_amount); // Bind the parameter
                    $stmt_sender->bindParam(':purpose_of_transfer', $purpose_of_transfer); // Bind the parameter
                    $stmt_sender->execute();

                    // Insert transaction for recipient
                    $stmt_recipient = $pdo->prepare("INSERT INTO transaction_history (user_id, recipient_id, transaction_date, transaction_type, amount, purpose_of_transfer) VALUES (:recipient_id, :user_id, CURRENT_TIMESTAMP, 'transfer', :transfer_amount, :purpose_of_transfer)");
                    $stmt_recipient->bindParam(':recipient_id', $recipient_id); // Bind the parameter
                    $stmt_recipient->bindParam(':user_id', $user_id); // Bind the parameter
                    $stmt_recipient->bindParam(':transfer_amount', $transfer_amount); // Bind the parameter
                    $stmt_recipient->bindParam(':purpose_of_transfer', $purpose_of_transfer); // Bind the parameter
                    $stmt_recipient->execute(); // Execute the SQL statement

                    // Update sender balance
                    $stmt_balance_sender = $pdo->prepare("SELECT balance FROM users WHERE id = :user_id"); // Prepare an SQL statement
                    $stmt_balance_sender->bindParam(':user_id', $user_id); // Bind the parameter
                    $stmt_balance_sender->execute(); // Execute the SQL statement
                    $current_balance_sender = $stmt_balance_sender->fetchColumn(); // Fetch the result as a column
                    $new_balance_sender = $current_balance_sender - $transfer_amount; // Calculate the new balance
                    $stmt_update_balance_sender = $pdo->prepare("UPDATE users SET balance = :new_balance WHERE id = :user_id"); // Prepare an SQL statement
                    $stmt_update_balance_sender->bindParam(':new_balance', $new_balance_sender); // Bind the parameter
                    $stmt_update_balance_sender->bindParam(':user_id', $user_id); // Bind the parameter
                    $stmt_update_balance_sender->execute(); // Execute the SQL statement

                    // Update recipient balance
                    $stmt_balance_recipient = $pdo->prepare("SELECT balance FROM users WHERE id = :recipient_id");
                    $stmt_balance_recipient->bindParam(':recipient_id', $recipient_id);
                    $stmt_balance_recipient->execute();
                    $current_balance_recipient = $stmt_balance_recipient->fetchColumn(); // Fetch the result as a column
                    $new_balance_recipient = $current_balance_recipient + $transfer_amount;
                    $stmt_update_balance_recipient = $pdo->prepare("UPDATE users SET balance = :new_balance WHERE id = :recipient_id");
                    $stmt_update_balance_recipient->bindParam(':new_balance', $new_balance_recipient);
                    $stmt_update_balance_recipient->bindParam(':recipient_id', $recipient_id);
                    $stmt_update_balance_recipient->execute();

                    $pdo->commit(); // Commit the transaction

                    $_SESSION['balance'] = $new_balance_sender; // Update the session variable 'balance'
                } catch (PDOException $e) { // Catch any PDO exceptions
                    $pdo->rollback(); // Rollback the transaction
                    $errors[] = "Error processing transfer: " . $e->getMessage(); // Add an error message
                }
            }
        }
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
    <title>Transfer Money</title>

    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            background-color: black;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 20px; /* Add some space between paragraphs */
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

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            color: #333;
            border-radius: 10px; /* Adding border-radius */
        }

        .founded-date {
            font-style: italic; /* Make the text italic */
            font-size: 14px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 10px; /* Add some space between paragraphs */
        }

        .mainarea {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }



        /* Input fields */
        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        /* Custom styles for labels */
        label {
            font-weight: bold; /* Make label text bold */
        }

        /* Custom CSS for Transfer Money Instructions */
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
        <h3 class="text-muted">Transfer Money</h3>
        <hr>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="topup.php">Top Up</a></li>
                <li><a href="transaction.php">History</a></li>
                <li><a href="functions.php">Card</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </div>

    <div class="mainarea">
        <h1>Transfer</h1>
        <h3>Current Balance: <?php echo isset($_SESSION['balance']) ? "$" . number_format($_SESSION['balance'] / 100, 2) : '0.00'; ?></h3> <!-- Display current balance -->

        <?php if (!empty($errors)) : ?> <!-- Display error messages if there are any -->
            <div class="alert">
                <ul>
                    <?php foreach ($errors as $error) : ?> <!-- Display error messages if there are any -->
                        <li><?php echo $error; ?></li> <!-- Display error messages if there are any -->
                    <?php endforeach; ?> <!-- Display error messages if there are any -->
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="form-signin" name="Transfer_Form">
            <h2>Transfer</h2>
            <div class="form-group">
                <label for="Purpose">Purpose of Transfer:</label>
                <input type="text" name="Purpose" id="Purpose" placeholder="Enter purpose of transfer" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Recipient">Recipient:</label>
                <input type="text" name="Recipient" id="Recipient" placeholder="Enter recipient Username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Amount">Amount:</label>
                <input type="number" name="Amount" id="Amount" placeholder="Enter transfer amount" class="form-control" required>
            </div>
            <button name="Submit" value="Transfer" class="btn btn-lg btn-primary btn-block" type="submit">Transfer</button>
        </form>



    </div>
</div>

<div class="container">
    <div class="row marketing">
        <div>
            <h4>Transfer Instructions</h4>
            <p>Enter the purpose of the transfer, the recipient's username, and the amount you want to transfer. Ensure that the information entered is accurate before submitting the form.</p>
        </div>
    </div>
</div>

<hr>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
    <p class="founded-date">Founded July 1, 2015</p> <!-- code derived from https://github.com/mariofont -->
</footer>
</body>
</html>