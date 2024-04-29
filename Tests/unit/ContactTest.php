<?php

use PHPUnit\Framework\TestCase;
use src\Contact;

require_once ('src/Contact.php');

class ContactTest extends TestCase
{
    public function testPay()
    {
        $contact = new Contact(100); // Create a new Contact object with a balance of 100

        // Test successful payment
        $output = $contact->pay(50);
        $this->assertEquals("Transaction was successful.", $output); // Check if the output is correct

        // Test unsuccessful payment
        $output = $contact->pay(60);
        $this->assertEquals("Insufficient balance.", $output); // Check if the output is correct
    }

    public function testUndoLastTransaction()
    {
        $contact = new Contact(100); // Create a new Contact object with a balance of 100

        // Test undoing a transaction
        $contact->pay(50);
        $output = $contact->undoLastTransaction(); // Undo the last transaction
        $this->assertEquals("Last transaction undone. Balance restored.", $output); // Check if the output is correct

        // Test undoing when there are no transactions
        $output = $contact->undoLastTransaction(); // Undo the last transaction
        $this->assertEquals("No transactions to undo.", $output); // Check if the output is correct
    }

    public function testGetBalanceMessage()
    {
        $contact = new Contact(100);
        // Test balance message
        $output = $contact->getBalanceMessage(); // Get the balance message
        $this->assertEquals("Your current balance is: 100.", $output); // Check if the output is correct
    }

    public function testGetInstructions()
    {
        $contact = new Contact(100); // Create a new Contact object with a balance of 100
        // Test instructions
        $output = $contact->getInstructions(); // Get the instructions
        $this->assertStringContainsString("To make a payment, call the pay() method with the amount as the argument. To undo the last transaction, call the undoLastTransaction() method.", $output); // Check if the output contains the correct instructions
    }
}