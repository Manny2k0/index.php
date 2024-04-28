<?php
session_start(); // Start or resume the session

// require_once '../classes/Account.php'; // Include the Account class file
require_once 'session.php'; // Include the session handling file

// Instantiate the Session class
$session = new Session();
// $account = new Account(1, 2, 1000); // Instantiate the Account class
// Call the method to destroy the session
$session->forgetSession(); // Destroy the session

session_unset(); // Unset all session variables

// Redirect the user to the login page
header("Location: login.php"); // Redirect to the login page
exit;
?>