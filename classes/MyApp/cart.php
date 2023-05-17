<?php

namespace MyApp;

class Cart
{
    private $items;

    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $this->items = &$_SESSION['cart'];
    }

    public function addToCart($product)
    {
        $this->items[] = $product;
    }

    public function removeFromCart($productId)
    {
        foreach ($this->items as $key => $product) {
            if ($product->getId() == $productId) {
                unset($this->items[$key]);
                break;
            }
        }
    }

    public function getCartItems()
    {
        return $this->items;
    }

    public function calculateCartTotal()
    {
        $total = 0;
        foreach ($this->items as $product) {
            $total += $product->getPrice();
        }
        return $total;
    }
}
