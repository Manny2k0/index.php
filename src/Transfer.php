<?php

namespace src;

use PDO;
use PDOException;

class Transfer
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    }

    public function transfer($purpose, $recipient, $amount)
    {
        // Validate the recipient
        if (empty($recipient) || !preg_match('/^[a-zA-Z0-9_]+$/', $recipient) || $recipient == $_SESSION['Username']) {
            throw new PDOException("Invalid recipient");
        }

        // Check if the recipient exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $recipient);
        $stmt->execute();
        $recipientUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recipientUser) {
            return false;
        }

        // Validate the purpose
        if (empty($purpose)) {
            return false;
        }

        // Validate the amount
        if ($amount <= 0) {
            return false;
        }

        // Check if the balance is sufficient
        $stmt = $this->pdo->prepare("SELECT balance FROM users WHERE username = :username");
        $stmt->bindParam(':username', $_SESSION['Username']);
        $stmt->execute();
        $balance = $stmt->fetchColumn();

        if ($balance < $amount) {
            return false;
        }

        // Update the balance
        $new_balance = $balance - $amount;
        $stmt = $this->pdo->prepare("UPDATE users SET balance = :new_balance WHERE username = :username");
        $stmt->bindParam(':new_balance', $new_balance);
        $stmt->bindParam(':username', $_SESSION['Username']);
        $stmt->execute();

        // Record the transaction
        $stmt = $this->pdo->prepare("INSERT INTO transaction_history (user_id, recipient_id, transaction_date, transaction_type, amount, purpose_of_transfer) VALUES (:user_id, :recipient_id, CURRENT_TIMESTAMP, 'transfer', :amount, :purpose)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':recipient_id', $recipient);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':purpose', $purpose);
        $stmt->execute();

        return true;
    }

    public function checkNumberType($number): string
    {
        if ($number > 0) {
            return 'positive';
        } elseif ($number < 0) {
            return 'negative';
        } else {
            return 'zero';
        }
    }

    public function factorial($number): string|int
    {
        if ($number < 0) {
            return 'undefined';
        }

        $factorial = 1;
        for ($i = 1; $i <= $number; $i++) {
            $factorial *= $i;
        }
        return $factorial;
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}