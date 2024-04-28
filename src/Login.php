<?php

namespace src;

use PDO;

class Login
{
    public function authenticate($username, $password): bool
    {
        // Check the username and password against the database
        // If they are correct, set the session variables
        // This is a simplified example, you should actually hash and salt your passwords, and use prepared statements for the SQL
        $db = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['username'] = $username;
            $_SESSION['authenticated'] = true;
            return true; // Return true if the user is authenticated
        } else {
            return false; // Return false if the user is not authenticated
        }
    }
}