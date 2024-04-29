<?php
session_start(); // Start the session

// Check if 'cart' is set in session, if not initialize it as an empty array
if (!isset($_SESSION['cart'])) { // Check if cart is not set
    $_SESSION['cart'] = []; // Initialize cart
}

// Check if 'cart' is set in session, if not initialize it as an empty array
$cart = $_SESSION['cart']; // Assign cart to a variable

// Calculate total
function calculateTotal() { // Function to calculate total
    $total = 0; // Initialize total
    foreach ($_SESSION['cart'] as $item) { // Loop through cart items
        if (is_array($item) && isset($item['product']) && is_array($item['product']) && isset($item['quantity'])) { // Check if item is an array and has product and quantity
            $total += $item['product']['price'] * $item['quantity']; // Calculate total
        }
    }
    // return $total; // Return total
    return number_format($total, 2); // Return formatted total
}

// Remove from cart
if (isset($_GET['action']) && $_GET['action'] === 'removeFromCart' && isset($_GET['id'])) { // Check if action is removeFromCart and id is set
    $id = $_GET['id']; // Assign id to a variable
    if (isset($cart[$id])) { // Check if item is in the cart
        unset($cart[$id]); // Remove item from the cart
        $_SESSION['cart'] = $cart; // Update cart in session
    }
}

// Update quantity
if (isset($_POST['updateQuantity'], $_POST['itemId'], $_POST['quantity']) && is_array($_POST['itemId']) && is_array($_POST['quantity'])) { // Check if updateQuantity, itemId, and quantity are set
    foreach ($_POST['itemId'] as $index => $id) { // Loop through item IDs
        if (isset($cart[$id]) && isset($_POST['quantity'][$index])) { // Check if item is in the cart and quantity is set
            $cart[$id]['quantity'] = $_POST['quantity'][$index]; // Update quantity
        }
    }
    //
    $_SESSION['cart'] = $cart; // Update cart in session
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 100%;
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #ddd;
        }

        .quantity-input {
            width: 50px; /* Adjust as needed */
            padding: 2px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }


        .btn:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-primary {
            background-color: #000911;
        }

        .btn-primary:hover {
            background-color: #00070e;
        }

        .btn-white {
            background-color: #fff;
            color: #007bff;
        }

        .btn-white:hover {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 20px;
        }

        /* Additional styles */
        .checkout-btn {
            margin-top: 10px;
        }

        .float-right {
            float: right;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Shopping Cart</h1>
    <hr>

    <?php if (!empty($_SESSION['cart'])): ?> <!-- Check if cart is not empty -->
        <form action="" method="post">
            <div class="table-container">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?> <!-- Loop through cart items -->
                        <?php if (isset($item['product']) && is_array($item['product'])): ?> <!-- Check if item has product -->
                            <tr>
                                <td><?= $item['product']['name'] ?></td> <!-- Display product name -->
                                <td>
                                    <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" class="quantity-input"> <!-- Quantity input -->
                                    <input type="hidden" name="itemId[]" value="<?= $id ?>"> <!-- Hidden input to store item ID -->
                                </td>
                                <td>$<?= $item['product']['price'] ?></td> <!-- Display product price -->
                                <td>$<?= number_format($item['product']['price'] * $item['quantity'], 2) ?></td> <!-- Display total price -->
                                <td>
                                    <a href="cart.php?action=removeFromCart&id=<?= $id ?>" class="btn btn-danger">Remove</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" name="updateQuantity" class="btn btn-primary btn-large" style="display: none;">Refresh</button>
        </form>
        <?php $total = calculateTotal(); ?>
        <p class="total">Total: $<?= $total ?></p>
        <a href="Contact.php?total=<?= $total ?>" class="btn btn-primary checkout-btn">Checkout</a>
    <?php else: ?>
        <p>Your shopping cart is empty.</p>
    <?php endif; ?>
    <a href="functions.php" class="btn btn-primary float-right">Continue Shopping</a>
</div>
</body>
</html>
