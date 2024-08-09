<?php
include 'config.php';
header('Content-Type: application/json');

$order_id = $_GET['order_id'];

$query = "SELECT oi.product_id, p.name, p.image FROM `order_items` oi JOIN `products` p ON oi.product_id = p.id WHERE oi.order_id = '$order_id'";
$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>
