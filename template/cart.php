<?php
session_start();

// Check if 'cart' is set in session, if not initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item) && isset($item['product']) && is_array($item['product']) && isset($item['quantity'])) {
            $total += $item['product']['price'] * $item['quantity'];
        }
    }
    return number_format($total, 2);
}

if (isset($_GET['action']) && $_GET['action'] === 'removeFromCart' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($cart[$id])) {
        unset($cart[$id]);
        $_SESSION['cart'] = $cart;
    }
}

if (isset($_POST['updateQuantity'], $_POST['itemId'], $_POST['quantity']) && is_array($_POST['itemId']) && is_array($_POST['quantity'])) {
    foreach ($_POST['itemId'] as $index => $id) {
        if (isset($cart[$id]) && isset($_POST['quantity'][$index])) {
            $cart[$id]['quantity'] = $_POST['quantity'][$index];
        }
    }
    $_SESSION['cart'] = $cart;
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

    <?php if (!empty($_SESSION['cart'])): ?>
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
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <?php if (isset($item['product']) && is_array($item['product'])): ?>
                            <tr>
                                <td><?= $item['product']['name'] ?></td>
                                <td>
                                    <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" class="quantity-input">
                                    <input type="hidden" name="itemId[]" value="<?= $id ?>">
                                </td>
                                <td>$<?= $item['product']['price'] ?></td>
                                <td>$<?= number_format($item['product']['price'] * $item['quantity'], 2) ?></td>
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
        <a href="../public/Contact.php?total=<?= $total ?>" class="btn btn-primary checkout-btn">Checkout</a>
    <?php else: ?>
        <p>Your shopping cart is empty.</p>
    <?php endif; ?>
    <a href="../src/functions.php" class="btn btn-primary float-right">Continue Shopping</a>
</div>
</body>
</html>
