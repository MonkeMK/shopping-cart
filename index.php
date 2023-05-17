<?php
session_start();

use MyApp\Product;
use MyApp\Cart;

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $className . '.php';
});

$cart = new Cart();

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Create a new Product instance
    $product = new Product($product_id, $product_name, $product_price);

    // Add product to the cart
    $cart->addToCart($product);

    // Redirect back to the product listing page or display a success message
    header("Location: index.php");
    exit;
}

// Handle remove from cart action
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];

    // Remove product from the cart
    $cart->removeFromCart($product_id);

    // Redirect back to the cart page or display a success message
    header("Location: index.php");
    exit;
}

// Function to calculate the total price of items in the cart
function calculateCartTotal($cart)
{
    $total = 0;
    foreach ($cart->getItems() as $product) {
        $total += $product->getPrice();
    }
    return $total;
}

// Function to check if the cart is empty
function isCartEmpty($cart)
{
    return count($cart->getItems()) === 0;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Webshop</title>
</head>
<body>
    <h1>Webshop</h1>

    <!-- Product Listing -->
    <h2>Products</h2>
    <ul>
        <li>
            <span>Product A</span>
            <form method="POST" action="index.php">
                <input type="hidden" name="product_id" value="1">
                <input type="hidden" name="product_name" value="Product A">
                <input type="hidden" name="product_price" value="10.99">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </li>
        <li>
            <span>Product B</span>
            <form method="POST" action="index.php">
                <input type="hidden" name="product_id" value="2">
                <input type="hidden" name="product_name" value="Product B">
                <input type="hidden" name="product_price" value="19.99">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </li>
        <!-- Add more products as needed -->
    </ul>

    

    <!-- Cart -->
<h2>Shopping Cart</h2>
<?php if (isCartEmpty($cart)) : ?>
    <p>Your cart is empty.</p>
<?php else : ?>
    <ul>
        <?php foreach ($cart->getItems() as $product) : ?>
            <li>
                <?php echo $product->getName(); ?> - $<?php echo $product->getPrice(); ?>
                <a href="index.php?remove_from_cart=<?php echo $product->getId(); ?>">Remove</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


    <p>
        Total: $<?php echo calculateCartTotal($cart); ?>
        <a href="cart.php">View Cart</a>
    </p>
</body>
</html>
