<?php
session_start();
require_once '../config/config.php'; // Include the database connection

$errors = []; // Initialize error array

try {
    // Establish database connection
    $pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!'); // Create a new PDO instance
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set the error mode to exceptions
} catch (PDOException $e) { // Catch any exceptions
    die("Database connection failed: " . $e->getMessage()); // Display error message
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a top-up or transfer
    if (isset($_POST['topup'])) { // If top-up is selected
        // Handle top-up
        handleTopUp($pdo, $errors); // Call the handleTopUp function
    } elseif (isset($_POST['transfer'])) { // If transfer is selected
        // Handle transfer
        handleTransfer($pdo, $errors); // Call the handleTransfer function
    }
}

// Function to handle top-up
function handleTopUp($pdo, &$errors) {
    // Get top-up details from the form
    $topUpAmount = isset($_POST["amount"]) ? $_POST["amount"] : 0;
    $purposeOfTransfer = isset($_POST["Purpose"]) ? $_POST["Purpose"] : '';

    // Convert the amount from dollars to cents
    $topUpAmountCents = $topUpAmount * 100; // Convert the amount to cents

    // Validate top-up amount
    if (!validateTopUpAmount($topUpAmountCents)) { // Validate the top-up amount
        $errors[] = "Minimum top-up amount is $5"; // Display an error message
        return; // Return from the function
    }

    try {
        // Update balance in the database
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE username = :username"); // Prepare SQL query
        $stmt->bindParam(':amount', $topUpAmountCents); // Bind the amount
        $stmt->bindParam(':username', $_SESSION['Username']); // Bind the username
        $stmt->execute(); // Execute the query

        // Insert top-up transaction record into transaction_history table
        insertTopUpTransaction($pdo, $_SESSION['user_id'], 'Owner', $topUpAmountCents, 'top_up', $purposeOfTransfer);

        // Update session balance
        $_SESSION['balance'] += $topUpAmountCents; // Update the session balance

        // Redirect to the index page
        header("Location: index.php");
        exit(); // Exit the script
    } catch (PDOException $e) { // Catch any exceptions
        die("Error: " . $e->getMessage()); // Display error message
    }
}

// Function to insert top-up transaction record into transaction_history table
function insertTopUpTransaction($pdo, $user_id, $topup_amount) // Add $topup_amount parameter
{
    // Get the user's name from the session
    try {
        // Prepare and execute SQL query to fetch user's name based on session username
        $stmt_user = $pdo->prepare("SELECT username FROM users WHERE username = :username"); // Prepare SQL query
        $stmt_user->bindParam(':username', $_SESSION['Username']); // Bind the username
        $stmt_user->execute(); // Execute the query
        $user = $stmt_user->fetch(PDO::FETCH_ASSOC); // Fetch the result
        $username = $user['username']; // Get the user's name

        // Prepare and execute SQL query to insert top-up transaction record
        $stmt = $pdo->prepare("INSERT INTO transaction_history (user_id, transaction_date, transaction_type, amount, username) VALUES (:user_id, CURRENT_TIMESTAMP, 'top_up', :topup_amount, :username)");
        $stmt->bindParam(':user_id', $user_id); // Bind the user ID
        $stmt->bindParam(':topup_amount', $topup_amount); // Bind the top-up amount
        $stmt->bindParam(':username', $username); // Bind the user's name
        $stmt->execute(); // Execute the query
    } catch (PDOException $e) { // Catch any exceptions
        // If an error occurs during insertion, display error message
        echo "Error inserting top-up transaction: " . $e->getMessage();
    }
}

// Function to handle transfer
function handleTransfer($pdo, &$errors) { // Add $errors parameter
    // Get transfer details from the form
    $recipientUsername = isset($_POST["Recipient"]) ? $_POST["Recipient"] : ''; // Get the recipient username
    $transferAmount = isset($_POST["Amount"]) ? $_POST["Amount"] : ''; // Get the transfer amount
    $purposeOfTransfer = isset($_POST["Purpose"]) ? $_POST["Purpose"] : ''; // Get the purpose of transfer

    // Validate the transfer amount
    if (!is_numeric($transferAmount) || $transferAmount <= 0) { // Validate the transfer amount
        $errors[] = "Invalid transfer amount"; // Display an error message
        return; // Return from the function
    }

    // Multiply the transfer amount by 100
    $transferAmountCents = $transferAmount * 100; // Convert the amount to cents

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Update sender's balance
        $stmt_sender = $pdo->prepare("UPDATE users SET balance = balance - :amount WHERE username = :username"); // Prepare SQL query
        $stmt_sender->bindParam(':amount', $transferAmountCents); // Bind the amount
        $stmt_sender->bindParam(':username', $_SESSION['Username']); // Bind the username
        $stmt_sender->execute(); // Execute the query

        // Update recipient's balance
        $stmt_recipient = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE username = :username");
        $stmt_recipient->bindParam(':amount', $transferAmountCents); // Bind the amount
        $stmt_recipient->bindParam(':username', $recipientUsername); // Bind the recipient username
        $stmt_recipient->execute(); // Execute the query

        // Insert transfer transaction record into transaction_history table
        insertTransaction($pdo, $_SESSION['user_id'], $recipientUsername, $transferAmountCents, 'transfer', $purposeOfTransfer);

        // Commit the transaction
        $pdo->commit();

        // Update session balance for the sender
        $_SESSION['balance'] -= $transferAmountCents;

        // Redirect to the index page
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Rollback the transaction if an error occurs
        $pdo->rollBack();
        $errors[] = "Error processing transfer: " . $e->getMessage(); // Display error message
    }
}

// Function to validate top-up amount
function handlePayment($pdo, $amount, $user_id) { // Add $amount and $user_id parameters
    try {
        // Start a transaction
        $pdo->beginTransaction(); // Start a transaction

        // Update user's balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - :amount WHERE id = :user_id"); // Prepare SQL query
        $stmt->bindParam(':amount', $amount); // Bind the amount
        $stmt->bindParam(':user_id', $user_id); // Bind the user ID
        $stmt->execute(); // Execute the query

        // Insert payment transaction record into transaction_history table
        insertTransaction($pdo, $user_id, null, $amount, 'payment', 'Payment for goods/services');

        // Commit the transaction
        $pdo->commit();

        // Redirect to the index page
        header("Location: index.php");
        exit(); // Exit the script
    } catch (PDOException $e) {
        // Rollback the transaction if an error occurs
        $pdo->rollBack(); // Rollback the transaction
        echo "Error processing payment: " . $e->getMessage(); // Display error message
    }
}


// Function to validate top-up amount
function validateTopUpAmount($topUpAmount) { // Add $topUpAmount parameter
    return is_numeric($topUpAmount) && $topUpAmount >= 500; // 500 corresponds to $5.00 in cents
}

// Function to insert transaction record into transaction_history table
function insertTransaction($pdo, $user_id, $recipient_username, $amount, $type, $purpose_of_transfer) {
    try {
        $stmt = $pdo->prepare("INSERT INTO transaction_history (user_id, recipient_username, amount, transaction_type, transaction_date, purpose_of_transfer) VALUES (:user_id, :recipient_username, :amount, :type, NOW(), :purpose_of_transfer)"); // Prepare SQL query
        $stmt->bindParam(':user_id', $user_id); // Include user ID
        // If the transaction type is 'top_up', set recipient_username to 'Owner'
        $recipient_username = ($type === 'top_up') ? 'Owner' : $recipient_username; // Set recipient username to 'Owner' if top-up
        $stmt->bindParam(':recipient_username', $recipient_username); // Include recipient username
        $stmt->bindParam(':amount', $amount); // Include transaction amount
        $stmt->bindParam(':type', $type); // Include transaction type
        $stmt->bindParam(':purpose_of_transfer', $purpose_of_transfer); // Include purpose of transfer
        $stmt->execute(); // Execute the query
    } catch (PDOException $e) {
        echo "Error inserting transaction: " . $e->getMessage(); // Display error message
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
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

        .text-muted {
            text-align: center;
            margin-bottom: -2px;
        }

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 10px; /* Add some space between paragraphs */
        }



        .container {
            max-width: 600px; /* Adjusted maximum width */
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr;
            margin-top: -5px;

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

        .navbar {
            background-color: #333;
            overflow: hidden;
            border-radius: 5px;
            width: 90%; /* Adjusted width */
            margin: auto; /* Center the navbar */
            justify-content: center;
        }

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            float: left;
        }

        .navbar li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar li a:hover {
            background-color: #111;
        }



        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        /* Rounded box style */
        .rounded-box {
            border-radius: 20px;
            background-color: #fff;
            padding: 20px;
            width: 88%; /* Reduce the width to make the box smaller */
            margin: auto; /* Keep the box centered */
        }

        .marketing h4 {
            margin-top: 20px;
            font-size: 20px;
            color: #333;
        }

        .marketing p {
            font-size: 16px;
            color: #666;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media screen and (max-width: 767px) {
            .table-responsive {
                overflow-y: hidden;
            }
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .navbar li {
                float: none;
                display: block;
            }

            .container {
                margin: 0;
                padding: 10px;
                max-width: 90%; /* Adjusted maximum width */
            }

            .rounded-box {
                width: 90%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>
<br>


<h2 class="text-muted">History</h2>
<hr>
<div class="header clearfix">
    <nav class="navbar">
        <ul>
            <li><a href="topup.php">Top-up</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="functions.php">Card</a></li>
            <li><a href="cart.php">Cart</a></li>
        </ul>
    </nav>
    <br>
    <div class="rounded-box">
        <h1>Transaction History</h1>
        <table>
            <thead>
            <tr>
                <th>Date & Time</th>
                <th>Details</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Purpose</th>
            </tr>
            </thead>

            <tbody>
            <?php
            // Previous code...

            $stmt = $pdo->prepare("
SELECT t.*, 
       CASE 
           WHEN t.transaction_type = 'top_up' THEN 'Owner'
           WHEN t.transaction_type = 'payment' THEN 'Cards'
           ELSE recipient.username
       END AS recipient_username,
       u.username AS user_name
FROM transaction_history t
LEFT JOIN users recipient ON t.recipient_id = recipient.id
LEFT JOIN users u ON t.user_id = u.id
WHERE t.user_id = :user_id OR t.sender_id = :user_id
ORDER BY t.transaction_date DESC
"); // Query to fetch transaction history

            $stmt->bindParam(':user_id', $_SESSION['user_id']); // Bind the user ID
            if ($stmt->execute()) { // Execute the query
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Loop through the results
                    echo "<tr>"; // Start a new row
                    echo "<td>" . $row['transaction_date'] . "</td>"; // Output transaction date
                    echo "<td>" . (($row['transaction_type'] === 'payment') ? 'Cards' : ($row['transaction_type'] === 'top_up' ? 'Owner' : $row['recipient_username'])) . "</td>"; // Output recipient username
                    echo "<td>$" . number_format($row['amount'] / 100, 2) . "</td>"; // Output transaction amount
                    echo "<td>" . $row['transaction_type'] . "</td>"; // Output transaction type
                    echo "<td>" . ($row['purpose_of_transfer'] ?? 'N/A') . "</td>"; // Output purpose of transfer
                    echo "</tr>"; // End the row
                }
            } else { // If the query fails
                echo "Error executing SQL query: " . print_r($stmt->errorInfo(), true); // Display error message
            }
            ?>

            </tbody>
        </table>
    </div>
</div>


<div class="container">
    <div class="row marketing">
        <h4>Transaction History</h4>
        <p>Revolut's transaction history provides a detailed record of all financial activities,
            ensuring transparency and accountability for users.</p>
    </div>
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

