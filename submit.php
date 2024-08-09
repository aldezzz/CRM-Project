<?php
include 'config.php';
session_start();

// Check if the user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Get the order ID
$order_id = isset($_POST['order_id']) ? mysqli_real_escape_string($conn, $_POST['order_id']) : null;

// Validate the order ID
if (!$order_id) {
    echo 'Order ID is missing.';
    exit();
}

// Loop through each product ID and process ratings and reviews
if (isset($_POST['product_id']) && is_array($_POST['product_id'])) {
    foreach ($_POST['product_id'] as $product_id) {
        $product_id = mysqli_real_escape_string($conn, $product_id);
        $rating = isset($_POST["rating_$product_id"]) ? mysqli_real_escape_string($conn, $_POST["rating_$product_id"]) : null;
        $review = isset($_POST["review_$product_id"]) ? mysqli_real_escape_string($conn, $_POST["review_$product_id"]) : '';

        // Validate rating
        if (!$rating) {
            echo 'Rating is missing for product ID ' . htmlspecialchars($product_id);
            continue;
        }

        // Prepare and execute the SQL query
        $insert_query = "INSERT INTO `rating` (user_id, product_id, rating, review, order_id, created_at) 
                         VALUES ('$user_id', '$product_id', '$rating', '$review', '$order_id', NOW())";
        if (!mysqli_query($conn, $insert_query)) {
            echo 'Query failed: ' . mysqli_error($conn);
            exit();
        }
    }

    echo 'Thank you for your reviews!';
} else {
    echo 'No product IDs found.';
}
?>
