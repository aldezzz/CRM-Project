<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

$message = [];

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO cart(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

if(isset($_GET['product_id'])){
   $product_id = $_GET['product_id'];
   $select_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'") or die('query failed');

   if(mysqli_num_rows($select_product) > 0){
      $product_details = mysqli_fetch_assoc($select_product);
   } else {
      echo 'Product not found!';
      exit;
   }
} else {
   echo 'No product selected!';
   exit;
}

// Fetch ratings and reviews, sorted by the most recent first
$reviews_query = mysqli_query($conn, "SELECT * FROM rating WHERE product_id = '$product_id' ORDER BY created_at DESC") or die('query failed');
$reviews = [];
while ($review = mysqli_fetch_assoc($reviews_query)) {
    $reviews[] = $review;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .product-details {
         padding: 2rem;
      }

      .product-details .box-container {
         display: flex;
         flex-wrap: wrap;
         gap: 2rem;
         justify-content: center;
         align-items: flex-start;
      }

      .product-details .box {
         display: flex;
         flex-wrap: wrap;
         width: 100%;
         max-width: 1000px;
         border: var(--border);
         border-radius: .5rem;
         padding: 2rem;
         box-shadow: var(--box-shadow);
         background-color: var(--white);
      }

      .product-details .box .image {
         width: 100%;
         max-width: 300px;
         height: auto;
         margin: 0 auto;
         display: block;
         object-fit: cover;
         border-radius: .5rem;
      }

      .product-details .box .content {
         flex: 1;
         padding-left: 2rem;
      }

      .product-details .box .content .name {
         font-family: 'Georgia', sans-serif;
         font-size: 2rem;
         color: var(--black);
         margin-bottom: 1rem;
         border-bottom: 2px solid var(--black);
         padding-bottom: 0.5rem;
      }

      .product-details .box .content .price {
         font-size: 1.8rem;
         color: var(--red);
         margin-bottom: 1rem;
      }

      .product-details .box .content .description {
         font-size: 1.6rem;
         color: var(--light-color);
         margin-bottom: 1rem;
         line-height: 1.8;
      }

      .product-details .box .content .qty {
         font-size: 1.6rem;
         color: var(--light-color);
         margin-bottom: 1rem;
      }

      .quantity-container {
         display: flex;
         align-items: center;
      }

      .quantity-container input[type="number"] {
         text-align: center;
         width: 50px;
         margin: 0 5px;
      }

      .quantity-btn {
         background-color: #f0f0f0;
         border: 1px solid #ddd;
         padding: 5px 10px;
         cursor: pointer;
      }

      .quantity-btn:hover {
         background-color: #e0e0e0;
      }

      .reviews {
         margin-top: 20px;
         max-height: 300px;
         overflow-y: scroll;
         border: 1px solid #ddd;
         padding: 15px;
         border-radius: 8px;
         background: #f9f9f9;
         width: 100%;
      }

      .review-box {
         border: 1px solid #ddd;
         border-radius: 8px;
         padding: 15px;
         margin-top: 15px;
         position: relative;
         display: flex;
         align-items: flex-start;
         background: #f9f9f9;
      }

      .review-box .profile-icon {
         font-size: 2em;
         color: #ffe6e6;
         margin-right: 10px;
      }

      .review-box .review-content {
         flex: 1;
      }

      .review-box .review-rating {
         color: gold;
         font-size: 1.2em;
      }

      .review-box .review-timestamp {
         font-size: 0.9em;
         color: #666;
         position: absolute;
         right: 10px;
         bottom: 10px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.style.display=\'none\';"></i></div>';
   }
}
?>

<section class="product-details">

   <h1 class="title">PRODUCT DETAILS</h1>

   <div class="box-container">

      <div class="box">
         <img class="image" src="uploaded_img/<?php echo $product_details['image']; ?>" alt="">
         <div class="content">
            <div class="name"><?php echo $product_details['name']; ?></div>
            <div class="price">Price: Rp<?php echo $product_details['price']; ?></div>
            <div class="qty">Available quantity: <?php echo $product_details['qty']; ?></div>
            <div class="description">Description Product: <br> <?php echo $product_details['description']; ?></div>
            <form action="" method="post">
               <input type="hidden" name="product_name" value="<?php echo $product_details['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $product_details['price']; ?>">
               <input type="hidden" name="product_image" value="<?php echo $product_details['image']; ?>">
               <div class="quantity-container">
                  <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
               </div>
               <br>
               <input type="submit" value="add to cart" name="add_to_cart" class="btn">
            </form>
            <!-- <form action="checkout.php" method="post">
               <input type="hidden" name="product_name" value="<?php echo $product_details['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $product_details['price']; ?>">
               <input type="hidden" name="product_quantity" id="checkout_quantity" value="1">
               <input type="submit" value="buy now" name="buy_now" class="btn">
            </form> -->

         </div>
         <div class="reviews">
            <h2>Reviews</h2>
            <?php
            if (!empty($reviews)) {
               foreach ($reviews as $review) {
                  $reviewer_query = mysqli_query($conn, "SELECT name FROM users WHERE id = '".$review['user_id']."'") or die('Query failed: ' . mysqli_error($conn));
                  $reviewer = mysqli_fetch_assoc($reviewer_query);

                  echo '<div class="review-box">';
                  echo '<span class="profile-icon"><i class="fas fa-user"></i></span>';
                  echo '<div class="review-content">';
                  echo '<p class="reviewer">' . htmlspecialchars($reviewer['name']) . '</p>';
                  echo '<p class="review-rating">' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '</p>';
                  echo '<p>' . htmlspecialchars($review['review']) . '</p>';
                  echo '<p class="review-timestamp">' . htmlspecialchars($review['created_at']) . '</p>';
                  echo '</div>';
                  echo '</div>';
               }
            } else {
               echo '<p>No reviews yet for this product.</p>';
            }
            ?>
         </div>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
function changeQuantity(amount) {
   var qtyInput = document.querySelector('input[name="product_quantity"]');
   var checkoutQtyInput = document.getElementById('checkout_quantity');
   var currentQty = parseInt(qtyInput.value);
   var newQty = currentQty + amount;
   if (newQty >= 1) {
      qtyInput.value = newQty;
      checkoutQtyInput.value = newQty;
   }
}
</script>

</body>
</html>