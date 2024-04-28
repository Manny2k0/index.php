<?php
// Define your database connection parameters
$servername = "localhost";
$dbusername = "root"; // default username for localhost is root
$dbpassword = "Eo606752k18!"; // default password for localhost is empty
$dbname = "Register1";

// Create a connection to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname); // Create a connection to the database

// Check the connection
if ($conn->connect_error) { // Check the connection
    die("Connection failed: " . $conn->connect_error); // Check the connection
}
?>
