<?php
// global $pdo;
session_start();

// Define regex patterns
$addressPattern = "/^[a-zA-Z0-9\s\.,\-]+$/";
$zipCodePattern = "/^[A-Z]\d{2}\s[A-Z0-9]{4}$/"; // Ireland zip code format

// Include config.php for database connection
require_once 'config.php';

// Include database connection code here
// Assuming you're using PDO for database connection
try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error message and terminate script
    die("Error: " . $e->getMessage());
}


// Define insertTransaction function
function insertTransaction($pdo, $userId, $transactionId, $amount, $type, $description)
{
    try {
        $stmt = $pdo->prepare("INSERT INTO transaction_history (user_id, transaction_id, amount, type, description) VALUES (:user_id, :transaction_id, :amount, :type, :description)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to handle payment
function handlePayment($pdo) {
    // Get payment details from the form
    $address = isset($_POST["address"]) ? $_POST["address"] : '';
    $zip_code = isset($_POST["zip_code"]) ? $_POST["zip_code"] : '';
    $state = isset($_POST["state"]) ? $_POST["state"] : '';
    $country = isset($_POST["country"]) ? $_POST["country"] : '';
    $total = isset($_POST["total"]) ? $_POST["total"] : 0;

    // Define regex patterns
    $addressPattern = "/^[a-zA-Z0-9\s\.,\-]+$/";
    $zipCodePattern = "/^[A-Z]\d{2}\s[A-Z0-9]{4}$/"; // Ireland zip code format

    // Validate form data
    if (empty($address) || empty($zip_code) || empty($state) || empty($country) || empty($total)) {
        $_SESSION['error'] = "Please fill in all fields.";
        return;
    }

    // Validate address
    if (!preg_match($addressPattern, $address)) {
        $_SESSION['error'] = "Invalid address. Please enter a valid address.";
        return;
    }

    // Validate zip code
    if (!preg_match($zipCodePattern, $zip_code)) {
        $_SESSION['error'] = "Invalid zip code. Please enter a valid zip code.";
        return;
    }

    // Check if user has sufficient balance
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['Username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $balance = $user['balance'];

    if ($balance < $total) {
        $_SESSION['error'] = "Insufficient balance.";
        return;
    }

    // Convert total from Dollars to Cents
    $total = $total * 100;

    // Deduct payment amount from user's balance
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - :amount WHERE username = :username");
    $stmt->bindParam(':amount', $total);
    $stmt->bindParam(':username', $_SESSION['Username']);
    $stmt->execute();

// Update session balance
    $_SESSION['balance'] -= $total;


// Define details of the transaction
    $details = "Cards";

    // Record transaction in transaction_history table
    $stmt = $pdo->prepare("INSERT INTO transaction_history (user_id, amount, transaction_type, description, transaction_date) VALUES (:user_id, :amount, 'payment', :description, NOW())");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':amount', $total);
    $stmt->bindParam(':description', $details);
    $stmt->execute();


    // Redirect to transaction.php
    header("Location: transaction.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST["address"];
    $zipCode = $_POST["zip_code"];

    // Validate address
    if (!preg_match($addressPattern, $address)) {
        $_SESSION['error'] = "Invalid address. Please enter a valid address.";
        header("Location: contact.php"); // Redirect back to contact.php with error message
        exit();
    }

    // Validate zip code
    if (!preg_match($zipCodePattern, $zipCode)) {
        $_SESSION['error'] = "Invalid zip code. Please enter a valid zip code.";
        header("Location: contact.php"); // Redirect back to contact.php with error message
        exit();
    }

    // Check if there are any errors
    if (!isset($_SESSION['error'])) {
        // Process payment and update balance
        if (isset($_SESSION['balance']) && isset($_POST['total'])) {
            handlePayment($pdo);
        } else {
            // Redirect with error message if balance or total amount is not set
            $_SESSION['error'] = "Error processing payment. Please try again later.";
            header("Location: index.php");
            exit();
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
    <title>Contact Form</title>
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

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 25px; /* Adjust the font size */
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
        input[type="number"],
        select {
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
        <h3 class="text-muted">Contact Form</h3> <!-- Move "Top Up" header here -->
        <hr> <!-- Add a horizontal line underneath the header -->
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="topup.php">Top-up</a></li>
                <li><a href="transfer.php">Transfer</a></li>
                <li><a href="transaction.php">History</a></li>
                <li><a href="index1.php">Card</a></li>
                <li><a href="../template/cart.php">Cart</a></li>
            </ul>
        </nav>
    </div>

    <div class="mainarea">
        <h1>Contact Information</h1>

        <?php
        if (isset($_SESSION['error'])) { // Display error message if it exists
            echo "<p style='color: red;'>".$_SESSION['error']."</p>"; // Display error message
            unset($_SESSION['error']); // Remove error message
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address" required><br><br>

            <label for="zip_code">Zip Code:</label>
            <input type="text" id="zip_code" name="zip_code" placeholder="Enter Zip Code" maxlength="10" required><br><br>


        <label for="state">State:</label>
<select id="state" name="state" required>
    <option value="">Select State</option>
    <option value="Alabama">Alabama</option>
    <option value="Alaska">Alaska</option>
    <option value="Arizona">Arizona</option>
    <option value="Arkansas">Arkansas</option>
    <option value="California">California</option>
    <option value="Colorado">Colorado</option>
    <option value="Connecticut">Connecticut</option>
    <option value="Delaware">Delaware</option>
    <option value="Florida">Florida</option>
    <option value="Georgia">Georgia</option>
    <option value="Hawaii">Hawaii</option>
    <option value="Idaho">Idaho</option>
    <option value="Illinois">Illinois</option>
    <option value="Indiana">Indiana</option>
    <option value="Iowa">Iowa</option>
    <option value="Kansas">Kansas</option>
    <option value="Kentucky">Kentucky</option>
    <option value="Louisiana">Louisiana</option>
    <option value="Maine">Maine</option>
    <option value="Maryland">Maryland</option>
    <option value="Massachusetts">Massachusetts</option>
    <option value="Michigan">Michigan</option>
    <option value="Minnesota">Minnesota</option>
    <option value="Mississippi">Mississippi</option>
    <option value="Missouri">Missouri</option>
    <option value="Montana">Montana</option>
    <option value="Nebraska">Nebraska</option>
    <option value="Nevada">Nevada</option>
    <option value="New Hampshire">New Hampshire</option>
    <option value="New Jersey">New Jersey</option>
    <option value="New Mexico">New Mexico</option>
    <option value="New York">New York</option>
    <option value="North Carolina">North Carolina</option>
    <option value="North Dakota">North Dakota</option>
    <option value="Ohio">Ohio</option>
    <option value="Oklahoma">Oklahoma</option>
    <option value="Oregon">Oregon</option>
    <option value="Pennsylvania">Pennsylvania</option>
    <option value="Rhode Island">Rhode Island</option>
    <option value="South Carolina">South Carolina</option>
    <option value="South Dakota">South Dakota</option>
    <option value="Tennessee">Tennessee</option>
    <option value="Texas">Texas</option>
    <option value="Utah">Utah</option>
    <option value="Vermont">Vermont</option>
    <option value="Virginia">Virginia</option>
    <option value="Washington">Washington</option>
    <option value="West Virginia">West Virginia</option>
    <option value="Wisconsin">Wisconsin</option>
    <option value="Wyoming">Wyoming</option>
</select>
            <br><br>

           <label for="country">Region:</label>
<select id="country" name="country" required>
    <option value="">Select Region</option>
    <option value="United States - Northeast">Northeast</option>
    <option value="United States - Midwest">Midwest</option>
    <option value="United States - South">South</option>
    <option value="United States - West">West</option>
    <option value="United States - Southwest">Southwest</option>
</select><br>

            <?php
            if (isset($_GET['total'])) { // Check if total amount is set
                $totalFromCart = $_GET['total']; // Get total amount from query string
                echo "<p>Total: $" . $totalFromCart . "</p>"; // Display total amount
                echo "<input type='hidden' name='total' value='" . $totalFromCart . "'>"; // Add hidden input field with total amount
            }
            ?>

            <button type="submit" class="btn btn-black-white">Pay</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
        <p class="founded-date">Founded July 1, 2015</p>
    </footer>

</div>
</body>
</html>

