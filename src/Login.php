<?php

namespace src;

use PDO;

/**
 * Class Login
 *
 * This class is responsible for authenticating users.
 */
class Login
{
    /**
     * Authenticate a user with a username and password.
     *
     * @param string $username - The username of the user
     * @param string $password - The password of the user
     * @return bool - Returns true if the user is authenticated, false otherwise
     */
    public function authenticate($username, $password): bool
    {
        // Create a new PDO instance
        $db = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');

        // Prepare a SQL statement to select the user with the given username and password
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");

        // Execute the SQL statement with the given username and password
        $stmt->execute(['username' => $username, 'password' => $password]);

        // If the SQL statement returns more than 0 rows, the user is authenticated
        if ($stmt->rowCount() > 0) {
            // Set the session variables
            $_SESSION['username'] = $username;
            $_SESSION['authenticated'] = true;

            // Return true if the user is authenticated
            return true;
        } else {
            // Return false if the user is not authenticated
            return false;
        }
    }
}
