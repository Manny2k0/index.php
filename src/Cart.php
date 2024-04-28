<?php

namespace src;

class Cart
{
    private $items = [];
    private $lastAction = null;

    public function addItem($id, $product, $quantity = 1): string
    {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
            $this->lastAction = ['action' => 'addItem', 'id' => $id, 'product' => $product, 'quantity' => $quantity];
            return "Item quantity updated successfully."; // Feedback
        } else {
            $this->items[$id] = ['product' => $product, 'quantity' => $quantity];
            $this->lastAction = ['action' => 'addItem', 'id' => $id, 'product' => $product, 'quantity' => $quantity];
            return "Item added to cart successfully."; // Feedback
        }
    }

    public function removeItem($id): string
    {
        if (isset($this->items[$id])) {
            $removedItem = $this->items[$id];
            unset($this->items[$id]);
            $this->lastAction = ['action' => 'removeItem', 'id' => $id, 'item' => $removedItem];
            return "Item removed from cart successfully."; // Feedback
        } else {
            return "Item not found in cart."; // Feedback
        }
    }

    public function updateQuantity($id, $quantity): string
    {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] = $quantity;
            $this->lastAction = ['action' => 'updateQuantity', 'id' => $id, 'quantity' => $quantity];
            return "Item quantity updated successfully."; // Feedback
        } else {
            return "Item not found in cart."; // Feedback
        }
    }

    public function undoLastAction()
    {
        if ($this->lastAction) {
            switch ($this->lastAction['action']) {
                case 'addItem':
                    $this->items[$this->lastAction['id']]['quantity'] -= $this->lastAction['quantity'];
                    if ($this->items[$this->lastAction['id']]['quantity'] <= 0) {
                        unset($this->items[$this->lastAction['id']]);
                    }
                    return "Last action undone. Item removed from cart.";
                case 'removeItem':
                    $this->items[$this->lastAction['id']] = $this->lastAction['item'];
                    return "Last action undone. Item added back to cart.";
                case 'updateQuantity':
                    $this->items[$this->lastAction['id']]['quantity'] = $this->lastAction['quantity'];
                    return "Last action undone. Item quantity restored.";
            }
        } else {
            return "No actions to undo.";
        }
    }

    public function getItems(): string
    {
        return "Your cart contains the following items: " . json_encode($this->items); // Consistency
    }

    public function getTotal(): string
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['product']['price'] * $item['quantity'];
        }
        return "The total cost of items in your cart is: " . $total; // Consistency
    }
}