<?php

namespace src;

class Contact
{
    private $balance;
    private $lastTransaction;

    public function __construct($balance)
    {
        $this->balance = $balance;
        $this->lastTransaction = null;
    }

    public function pay($amount): string
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->lastTransaction = ['action' => 'pay', 'amount' => $amount];
            return "Transaction was successful."; // Feedback
        } else {
            return "Insufficient balance."; // Feedback
        }
    }

    public function undoLastTransaction(): string
    {
        if ($this->lastTransaction) {
            $message = "";
            switch ($this->lastTransaction['action']) {
                case 'pay':
                    $this->balance += $this->lastTransaction['amount'];
                    $message = "Last transaction undone. Balance restored."; // Recoverability
                    break;
                default:
                    $message = "Action of the last transaction is not recognized."; // Recoverability
                    break;
            }
            $this->lastTransaction = null; // Reset lastTransaction
            return $message;
        } else {
            return "No transactions to undo."; // Recoverability
        }
    }

    public function getBalanceMessage(): string
    {
        return "Your current balance is: " . $this->balance . "."; // Consistency
    }

    public function getInstructions(): string
    {
        return "To make a payment, call the pay() method with the amount as the argument. To undo the last transaction, call the undoLastTransaction() method."; // Guidance
    }
}