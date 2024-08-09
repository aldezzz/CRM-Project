<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

function add_to_cart($conn, $user_id, $product_id, $product_name, $product_price, $product_image, $product_quantity) {
    // Check if the product is already in the cart
    $check_cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');
    
    if (mysqli_num_rows($check_cart_query) > 0) {
        // Update the quantity if the product is already in the cart
        mysqli_query($conn, "UPDATE `cart` SET quantity = quantity + '$product_quantity' WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');
    } else {
        // Add the product to the cart
        mysqli_query($conn, "INSERT INTO `cart` (user_id, product_id, name, price, quantity, image) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
    }
}

if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

    // Fetch the order details
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `id` = '$order_id' AND `user_id` = '$user_id'") or die('query failed');
    if (mysqli_num_rows($order_query) > 0) {
        $order = mysqli_fetch_assoc($order_query);

        // Get the order items
        $order_items_query = mysqli_query($conn, "SELECT * FROM `order_items` WHERE `order_id` = '$order_id'") or die('query failed');
        
        if (mysqli_num_rows($order_items_query) > 0) {
            while ($order_item = mysqli_fetch_assoc($order_items_query)) {
                $product_id = $order_item['product_id'];
                $product_quantity = $order_item['quantity'];

                // Fetch product details
                $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$product_id'") or die('query failed');
                if (mysqli_num_rows($product_query) > 0) {
                    $product_details = mysqli_fetch_assoc($product_query);
                    add_to_cart($conn, $user_id, $product_id, $product_details['name'], $product_details['price'], $product_details['image'], $product_quantity);
                }
            }
            header('location:cart.php');
            exit;
        } else {
            echo "Order items not found.<br>";
        }
    } else {
        echo "Order not found.<br>";
    }
} else {
    echo "Order ID not set.<br>";
}
?>
