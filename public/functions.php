<?php
session_start();


// Define products array

$products = [
    '010' => ['name' => 'Plus', 'description' => 'Get benefits such as in-app help that is fast-tracked and protection for your everyday spending, all for a cost less than buying a coffee. You can have these things and more with Plus.', 'price' => 3.99, 'image' => '../img/319efc11-6505-4d82-8834-cdbad89e7399.webp'],
    '025' => ['name' => 'Premium', 'description' => 'Find flexible benefits that adapt to your life at home and travels around the world. Save, use, send or invest smarter with Premium', 'price' => 8.99, 'image' => '../img/07a646eb-7aba-414d-8a0f-4e6c510f7eb8.webp'],
    '005' => ['name' => 'Metal', 'description' => 'Discover a variety of benefits that enhance your lifestyle and take advantage of higher investment limits and more—all on Metal..', 'price' => 15.99, 'image' => '../img/ffe9c42b-183f-40f4-aca0-357c1afec049.webp'], // Add image key to each product
    '021' => ['name' => 'Ultra', 'description' => 'Discover the extraordinary with premium travel, unique lifestyle benefits, and a platinum-plated, precisely designed card.', 'price' => 45.99, 'image' => '../img/319efc11-6505-4d82-8834-cdbad89e7399.webp'], // Add image key to each product
];

// Initialize shopping cart
if (!isset($_SESSION['cart'])) { // Check if cart is not set
    $_SESSION['cart'] = []; // Initialize cart
}

// Function to add item to cart
function addItemToCart($id) { // Function to add item to cart
    global $products; // Access products array
    if (isset($products[$id])) { // Check if product exists
        if (isset($_SESSION['cart'][$id]) && is_array($_SESSION['cart'][$id])) { // Check if item is already in the cart
            $_SESSION['cart'][$id]['quantity'] = 1; // Increase quantity if item is already in the cart
        } else { // If item is not in the cart
            $_SESSION['cart'][$id] = ['quantity' => 1, 'product' => $products[$id]]; // Add item to the cart
        }
        displayProducts(); // Display products instead of cart
    } else { // If product does not exist
        displayProducts(); // Display products instead of cart
    }
}
// Function to remove item from cart
function removeItemFromCart($id) { // Function to remove item from cart
    if (isset($_SESSION['cart'][$id])) { // Check if item is in the cart
        unset($_SESSION['cart'][$id]); // Remove item from the cart
    }
    displayCart(); // Display cart
}

// Function to display products
function displayProducts() { // Function to display products
    global $products; // Access products array
    require_once '../template/list.php'; // Include template file for product listing
}

// Function to display shopping cart
function displayCart() { // Function to display shopping cart
    require_once '../template/cart.php'; // Include template file for shopping cart display
}



// Get action from query string when they click "add to cart"
$action = isset($_GET['action']) ? $_GET['action'] : null; // Get action from query string
switch ($action) { // Switch statement to handle actions
    case 'cart': // If action is cart
        displayCart(); // Display cart
        break; // Break statement
    case 'addToCart': // If action is addToCart
        $id = isset($_GET['id']) ? $_GET['id'] : null; // Get product ID from query string
        if ($id !== null) { // Check if product ID is not null
            addItemToCart($id); // Add item to cart
        }
        break; // Break statement
    case 'removeFromCart': // If action is removeFromCart
        $id = isset($_GET['id']) ? $_GET['id'] : null;  // Get product ID from query string
        if ($id !== null) { // Check if product ID is not null
            removeItemFromCart($id); // Remove item from cart
        }
        break; // Break statement
    default: // Default case
        displayProducts(); // Display products
        break;
}



?>
