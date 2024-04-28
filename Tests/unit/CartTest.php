<?php

namespace unit;

use PHPUnit\Framework\TestCase;
use src\Cart;

class CartTest extends TestCase
{
    private $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart();
    }

    public function testAddItem()
    {
        // Simulate user interaction: Add item to cart
        $output = $this->cart->addItem(1, ['name' => 'Product 1', 'price' => 10], 2);

        // Check feedback: The user should be informed that the item was added successfully
        $this->assertEquals("Item added to cart successfully.", $output);
    }

    public function testRemoveItem()
    {
        // Simulate user interaction: Add item to cart
        $this->cart->addItem(1, ['name' => 'Product 1', 'price' => 10], 2);

        // Simulate user interaction: Remove item from cart
        $output = $this->cart->removeItem(1);

        // Check feedback: The user should be informed that the item was removed successfully
        $this->assertEquals("Item removed from cart successfully.", $output);
    }

    public function testUpdateQuantity()
    {
        // Simulate user interaction: Add item to cart
        $this->cart->addItem(1, ['name' => 'Product 1', 'price' => 10], 2);

        // Simulate user interaction: Update quantity of item in cart
        $output = $this->cart->updateQuantity(1, 3);

        // Check feedback: The user should be informed that the item quantity was updated successfully
        $this->assertEquals("Item quantity updated successfully.", $output);
    }

    public function testUndoLastAction()
    {
        // Simulate user interaction: Add item to cart
        $this->cart->addItem(1, ['name' => 'Product 1', 'price' => 10], 2);

        // Simulate user interaction: Undo last action
        $output = $this->cart->undoLastAction();

        // Check feedback: The user should be informed that the last action was undone
        $this->assertEquals("Last action undone. Item removed from cart.", $output);
    }

    public function testGetTotal()
    {
        // Simulate user interaction: Add items to cart
        $this->cart->addItem(1, ['name' => 'Product 1', 'price' => 10], 2);
        $this->cart->addItem(2, ['name' => 'Product 2', 'price' => 20]);

        // Simulate user interaction: Get total cost of items in cart
        $output = $this->cart->getTotal();

        // Check feedback: The user should be informed of the total cost of items in their cart
        $this->assertEquals("The total cost of items in your cart is: 40", $output);
    }
}