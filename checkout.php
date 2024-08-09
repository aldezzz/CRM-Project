<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['order_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $request = mysqli_real_escape_string($conn, $_POST['request']);
    $address = mysqli_real_escape_string($conn, $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];
    $order_items = [];

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($cart_query) > 0) {
            while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
                $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                $cart_total += $sub_total;

                // Prepare data for order_items
                $order_items[] = [
                    'product_id' => $cart_item['product_id'],
                    'quantity' => $cart_item['quantity'],
                    'price' => $cart_item['price']
                ];
            }
        }

        $total_products = implode(', ', $cart_products);

        $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

        if ($cart_total == 0) {
            $message[] = 'your cart is empty';
        } else {
            if (mysqli_num_rows($order_query) > 0) {
                $message[] = 'order already placed!';
            } else {
                // Insert order into orders table
                mysqli_query($conn, "INSERT INTO orders(user_id, name, number, email, method, address, total_products, total_price, placed_on, request) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$request')") or die('query failed');

                // Get the last inserted order_id
                $order_id = mysqli_insert_id($conn);

                // Insert order items into order_items table
                foreach ($order_items as $item) {
                    $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')";
                    if (!mysqli_query($conn, $insert_item_query)) {
                        throw new Exception('Insert Error: ' . mysqli_error($conn) . ' for query: ' . $insert_item_query);
                    }

                    // Decrease product quantity (qty)
                    $update_quantity_query = "UPDATE products SET qty = qty - '{$item['quantity']}' WHERE id = '{$item['product_id']}'";
                    if (!mysqli_query($conn, $update_quantity_query)) {
                        throw new Exception('Update Error: ' . mysqli_error($conn) . ' for query: ' . $update_quantity_query);
                    }
                }

                // Empty the cart
                mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');

                // Commit the transaction
                mysqli_commit($conn);

                $message[] = 'order placed successfully!';
            }
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_rollback($conn);
        $message[] = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo '$' . $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
   <?php
         }
      } else {
         echo '<p class="empty">your cart is empty</p>';
      }
   ?>
   <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" required placeholder="enter your name">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" required placeholder="enter your number">
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" required placeholder="enter your email">
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method">
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">Paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>request</span>
            <input type="text" min="0" name="request" required placeholder="Please input as detail as possible">
         </div>
         <div class="inputBox">
            <span>address</span>
            <input type="text" name="address" required placeholder="e.g. Street name">
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" required placeholder="e.g. Cikarang">
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" required placeholder="e.g. Jawa Barat">
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" required placeholder="e.g. Indonesia">
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g. 123456">
         </div>
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
