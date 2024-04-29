<?php

namespace src;

/**
 * Class Contact
 *
 * This class represents a contact with a balance and a record of the last transaction.
 */
class Contact
{
    // The balance of the contact
    private $balance;

    // The last transaction made by the contact
    private $lastTransaction;

    /**
     * Contact constructor.
     *
     * @param $balance - The initial balance of the contact
     */
    public function __construct($balance)
    {
        $this->balance = $balance;
        $this->lastTransaction = null; // No transactions have been made yet
    }

    /**
     * Pay a certain amount from the contact's balance.
     *
     * @param $amount - The amount to be paid
     * @return string - A message indicating the success or failure of the transaction
     */
    public function pay($amount): string
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->lastTransaction = ['action' => 'pay', 'amount' => $amount]; // Record this transaction
            return "Transaction was successful."; // Feedback
        } else {
            return "Insufficient balance."; // Feedback
        }
    }

    /**
     * Undo the last transaction made by the contact.
     *
     * @return string - A message indicating the success or failure of the operation
     */
    public function undoLastTransaction(): string
    {
        if ($this->lastTransaction) {
            $message = "";
            switch ($this->lastTransaction['action']) {
                case 'pay':
                    $this->balance += $this->lastTransaction['amount']; // Restore the balance
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

    /**
     * Get a message indicating the current balance of the contact.
     *
     * @return string - The balance message
     */
    public function getBalanceMessage(): string
    {
        return "Your current balance is: " . $this->balance . "."; // Consistency
    }

    /**
     * Get instructions on how to use the contact's methods.
     *
     * @return string - The instructions
     */
    public function getInstructions(): string
    {
        return "To make a payment, call the pay() method with the amount as the argument. To undo the last transaction, call the undoLastTransaction() method."; // Guidance
    }
}
