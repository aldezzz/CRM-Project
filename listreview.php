<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reviewed Products</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .reviewed-products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 20px;
      }

      .reviewed-products .box {
         background: #fff;
         border: 1px solid #ddd;
         border-radius: 10px;
         padding: 20px;
         box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
         text-align: center;
      }

      .reviewed-products .box img {
         max-width: 40%;
         height: auto;
         border-radius: 10px;
         margin-bottom: 15px;
      }

      .reviewed-products .box .product-name {
         font-size: 1.2rem;
         font-weight: bold;
         margin-bottom: 10px;
      }

      .reviewed-products .box .rating {
         display: flex;
         justify-content: center;
         align-items: center;
         gap: 5px;
         margin-bottom: 10px;
      }

      .reviewed-products .box .rating i {
         color: gold;
         font-size: 1.5rem;
      }

      .reviewed-products .box .review {
         font-size: 1rem;
         color: #333;
         margin-bottom: 10px;
      }

      .reviewed-products .box .review-time {
         font-size: 0.9rem;
         color: #666;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Reviewed Products</h3>
   <p> <a href="home.php">home</a> / reviewed products </p>
</div>

<section class="reviewed-products">
   <h1 class="title">Your Reviews</h1>

   <div class="box-container">
      <?php
      $review_query = mysqli_query($conn, "SELECT * FROM `rating` WHERE `user_id` = '$user_id'") or die('query failed');
      if (mysqli_num_rows($review_query) > 0) {
         while ($fetch_reviews = mysqli_fetch_assoc($review_query)) {
            $product_id = $fetch_reviews['product_id'];
            $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$product_id'") or die('query failed');
            $fetch_product = mysqli_fetch_assoc($product_query);
      ?>
      <div class="box">
        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="<?php echo $fetch_product['name']; ?>">
         <p class="product-name"><?php echo $fetch_product['name']; ?></p>
         <div class="rating">
            <?php for ($i = 0; $i < $fetch_reviews['rating']; $i++) { ?>
               <i class="fa fa-star"></i>
            <?php } ?>
            <?php for ($i = 0; $i < 5 - $fetch_reviews['rating']; $i++) { ?>
               <i class="fa fa-star-o"></i>
            <?php } ?>
         </div>
         <p class="review"><?php echo $fetch_reviews['review']; ?></p>
         <p class="review-time"><?php echo date('d M Y', strtotime($fetch_reviews['created_at'])); ?></p>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">You have not reviewed any products yet!</p>';
      }
      ?>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
