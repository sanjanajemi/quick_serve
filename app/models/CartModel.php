<?php

namespace App\Models;

class CartModel
{
    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Add item to cart
     */
    public function add(int $itemId)
    {
        if (!isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId] = 1;
        } else {
            $_SESSION['cart'][$itemId]++;
        }
    }

    /**
     * Remove item entirely
     */
    public function remove(int $itemId)
    {
        if (isset($_SESSION['cart'][$itemId])) {
            unset($_SESSION['cart'][$itemId]);
        }
    }

    /**
     * Update quantity
     */
    public function updateQuantity(int $itemId, int $qty)
    {
        if ($qty <= 0) {
            $this->remove($itemId);
        } else {
            $_SESSION['cart'][$itemId] = $qty;
        }
    }

    /**
     * Get all items
     */
    public function getItems(): array
    {
        return $_SESSION['cart'];
    }

    /**
     * Get total number of items
     */
    public function countItems(): int
    {
        return array_sum($_SESSION['cart']);
    }}