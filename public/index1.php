<?php


require_once 'functions.php'; // Include the functions.php file

$action = filter_input(INPUT_GET, 'action'); // Get the action from the URL

switch ($action){ // Determine which action to take
    case 'addToCart': // If the action is to add an item to the cart
        $id = filter_input(INPUT_GET, 'id'); // Get the product ID
        addItemToCart($id); // Add the item to the cart
        break; // End the case
    case 'removeFromCart': // If the action is to remove an item from the cart
        $id = filter_input(INPUT_GET, 'id'); // Get the product ID
        removeItemFromCart($id); // Remove the item from the cart
        break; // End the case
    case 'emptyCart': // If the action is to empty the cart
        emptyShoppingCart(); // Empty the shopping cart
        break; // End the case
    case 'listProducts': // If the action is to list the products
        displayProducts(); // Display the products
        break; // End the case
    default: // If no action is specified
        displayProducts(); // Display the products
        break; // End the case
}
?>