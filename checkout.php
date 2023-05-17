<?php
session_start();

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $className . '.php';
});

use MyApp\Product;

// Check if the cart is empty, redirect back to the cart page
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Handle checkout action
if (isset($_POST['checkout'])) {
    // Perform the checkout process
    // ...

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to the order confirmation page or display a success message
    header("Location: confirmation.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <!-- Display shipping information form, payment form, etc. -->
    <form method="POST" action="checkout.php">
        <!-- Add your checkout form fields here -->
        <button type="submit" name="checkout">Place Order</button>
    </form>
</body>
</html>
