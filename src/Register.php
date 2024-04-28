<?php

namespace src;

use PDO;

class Register
{
    private $pdo; // Database connection

    public function __construct() // Constructor
    {
        // Establish a database connection
        $this->pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    }

    public function validateUsername($username): bool // Validate username
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