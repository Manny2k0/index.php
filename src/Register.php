<?php

namespace src;

use PDO;

/**
 * Class Register
 *
 * This class is responsible for registering users.
 */
class Register
{
    // Database connection
    private $pdo;

    // Constructor
    public function __construct()
    {
        // Establish a database connection
        $this->pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    }

    // Validate username
    public function validateUsername($username): bool
    {
        // Check if username is at least 5 characters long
        return strlen($username) >= 5;
    }

    public function validatePassword($password)
    {
        // Check if password is at least 8 characters long and contains at least one number and one letter
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
    }

    public function registerUser($username, $password)
    {
        try {
            // Insert user into the database
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            return $stmt->execute([$username, $password]);
        } catch (PDOException $e) {
            // If an error occurs during database operations, return false
            return false;
        }
    }
}
