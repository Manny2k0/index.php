<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose your plan</title>

    <style>



        /* Global styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 800px; /* Adjusted maximum width */
            margin: 0 auto;
            padding: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Changed to 2 columns for smaller screens */
            grid-gap: 20px;
            padding: 20px;
        }

        .card-body {
            display: grid;
            flex-direction: column;
            justify-content: space-between;

        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px; /* Maintain padding */
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: auto; /* Set width to auto to adjust based on content */
            display: inline-block; /* Ensure button width adjusts based on content */
        }


        .btn-primary:hover {
            background-color: #363636;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        .header {
            background-color: #f2f2f2;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-container {
            display: flex;
            justify-content: center;
            background-color: #333;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding-left: 20px;
            width: 90%; /* Adjusted width */
            border-radius: 10px;
            margin: auto; /* Center the navigation */
        }

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 20px; /* Add some space between paragraphs */
        }

        nav {
            border-radius: 10px;
            margin: 0 auto;
            padding: 10px;
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

        nav ul {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        nav ul li {
            padding: 10px 16px;
            transition: background-color 0.3s;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        nav ul li:hover {
            background-color: #111;
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        nav ul li a:hover {
            color: #ddd;
        }

        @media (max-width: 600px) {
            .grid-container {
                grid-template-columns: 1fr;
            }

            nav ul {
                flex-direction: column;
            }
        }

        nav ul li {
            text-align: center;
        }

        @media (max-width: 800px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
        }

        .left-align {
            text-align: left;
            color: #333;
            font-size: 20px;
        }

        .left-align + p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            text-align: left;
        }
    </style>
</head>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>

<div class="container">
    <div class="header clearfix">
        <h1 class="text-muted">Cards</h1> <!-- Move "Top Up" header here -->
        <hr> <!-- Add a horizontal line underneath the header -->
        <div class="nav-container"> <!-- Add a container div for the navigation -->
            <nav>
                <ul>
                    <li><a href="../public/topup.php">Top-up</a></li>
                    <li><a href="../public/index.php">Home</a></li>
                    <li><a href="../public/transfer.php">Transfer</a></li>
                    <li><a href="../public/transaction.php">History</a></li>
                    <li><a href="../public/cart.php">Cart</a></li>
                </ul>
            </nav>
        </div>
        <br>
        <br>
        <h1>Choose your plan</h1>
        <div class="grid-container">
            <?php
            // Check if $products is set and is an array, if not initialize it as an empty array
            $products = isset($products) && is_array($products) ? $products : [];

     foreach ($products as $id => $product): ?>
    <div class="card">
        <!-- Add the new class here -->
        <img src="<?= $product['image'] ?>" class="card-img-top product-image" alt="<?= $product['name'] ?>">
        <div class="card-body">
            <div>
                <h5 class="card-title"><?= $product['name'] ?></h5> <!-- Add the new class to the h5 tag -->
                <p class="card-text"><?= $product['description'] ?></p> <!-- Add the new class to the p tag -->
                <p class="card-text">$<?= $product['price'] ?></p> <!-- Add the new class to the p tag -->
            </div>
            <a href="?action=addToCart&id=<?= $id ?>" class="btn btn-primary">Add to Cart</a> <!-- Add the new class to the button -->
        </div>
    </div>
<?php endforeach; ?>
        </div>



        <!-- Add the new class to the h4 tag -->
        <h4 class="left-align">Card Instruction</h4>
        <p>Discover the array of premium financial products Revolut offers, from Plus to Ultra,
            each tailored to enhance your financial journey</p>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
    <p c