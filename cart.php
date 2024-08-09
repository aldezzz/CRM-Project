<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_quantity = intval($_POST['quantity']); // Pastikan quantity adalah integer

    // Get the product ID based on product name
    $product_query = mysqli_query($conn, "SELECT id FROM `products` WHERE name = '$product_name'") or die(mysqli_error($conn));
    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $product_id = $product['id'];

        // Check if the product is already in the cart
        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND product_id = '$product_id'") or die(mysqli_error($conn));
        if (mysqli_num_rows($cart_query) > 0) {
            // Update quantity if the product is already in the cart
            mysqli_query($conn, "UPDATE `cart` SET quantity = quantity + '$product_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'") or die(mysqli_error($conn));
        } else {
            // Add new product to the cart
            mysqli_query($conn, "INSERT INTO `cart` (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$product_quantity')") or die(mysqli_error($conn));
        }
        $message[] = 'Product added to cart!';
    } else {
        $message[] = 'Product not found!';
    }
}

// Fix product_id in cart where it is 0
mysqli_query($conn, "
    UPDATE cart
    JOIN products ON cart.name = products.name
    SET cart.product_id = products.id
    WHERE cart.product_id = 0
") or die(mysqli_error($conn));

// Handle other cart actions (update, delete, delete_all)
if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']); // Pastikan cart_id adalah integer
    $cart_quantity = intval($_POST['cart_quantity']); // Pastikan cart_quantity adalah integer
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die(mysqli_error($conn));
    $message[] = 'Cart quantity updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); // Pastikan delete_id adalah integer
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die(mysqli_error($conn));
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die(mysqli_error($conn));
    header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="home.php">Home</a> / Cart</p>
</div>

<section class="shopping-cart">
   <h1 class="title">Products Added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die(mysqli_error($conn));
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="price">$<?php echo $fetch_cart['price']; ?>/-</div>
         <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="Update" class="option-btn">
         </form>
         <div class="sub-total">Sub Total: <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span></div>
      </div>
      <?php
            $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">Your cart is empty</p>';
         }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
   </div>

   <div class="cart-total">
      <p>Grand Total: <span>$<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">Continue Shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
