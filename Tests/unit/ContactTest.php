<?php

use PHPUnit\Framework\TestCase;
use src\Contact;

require_once ('src/Contact.php');

class ContactTest extends TestCase
{
    public function testPay()
    {
        $contact = new Contact(100);

        // Test successful payment
        $output = $contact->pay(50);
        $this->assertEquals("Transaction was successful.", $output);

        // Test unsuccessful payment
        $output = $contact->pay(60);
        $this->assertEquals("Insufficient balance.", $output);
    }

    public function testUndoLastTransaction()
    {
        $contact = new Contact(100);

        // Test undoing a transaction
        $contact->pay(50);
        $output = $contact->undoLastTransaction();
        $this->assertEquals("Last transaction undone. Balance restored.", $output);

        // Test undoing when there are no transactions
        $output = $contact->undoLastTransaction();
        $this->assertEquals("No transactions to undo.", $output);
    }

    public function testGetBalanceMessage()
    {
        $contact = new Contact(100);
        // Test balance message
        $output = $contact->getBalanceMessage();
        $this->assertEquals("Your current balance is: 100.", $output);
    }

    public function testGetInstructions()
    {
        $contact = new Contact(100);
        // Test instructions
        $output = $contact->getInstructions();
        $this->assertStringContainsString("To make a payment, call the pay() method with the amount as the argument. To undo the last transaction, call the undoLastTransaction() method.", $output);
    }
}