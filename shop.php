<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

$message = [];

if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Get product_id based on product_name
   $product_query = mysqli_query($conn, "SELECT id FROM products WHERE name = '$product_name'") or die('query failed');
   if (mysqli_num_rows($product_query) > 0) {
      $product = mysqli_fetch_assoc($product_query);
      $product_id = $product['id'];

      // Check if the product is already in the cart
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_cart_numbers) > 0) {
         $message[] = 'Already added to cart!';
      } else {
         mysqli_query($conn, "INSERT INTO cart (user_id, product_id, name, price, quantity, image) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
         $message[] = 'Product added to cart!';
      }
   } else {
      $message[] = 'Product not found!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Our Shop</h3>
      <p><a href="home.php">Home</a> / Shop</p>
   </div>

   <section class="products">

      <h1 class="title">Latest Products</h1>

      <div class="box-container">

         <?php
         $select_products = mysqli_query($conn, "SELECT p.*, AVG(r.rating) AS average_rating FROM products p LEFT JOIN rating r ON p.id = r.product_id GROUP BY p.id") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
               $product_id = $fetch_products['id'];
               $average_rating = round($fetch_products['average_rating']);

               // Menampilkan bintang berdasarkan rata-rata rating
               $stars = '';
               for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $average_rating) {
                     $stars .= '<i class="fas fa-star"></i>'; // Ganti dengan ikon bintang Font Awesome atau karakter bintang Unicode
                  } else {
                     $stars .= '<i class="far fa-star"></i>'; // Ganti dengan ikon bintang kosong Font Awesome atau karakter bintang Unicode
                  }
               }

               echo '<form action="" method="post" class="box">';
               echo '<img class="image" src="uploaded_img/' . $fetch_products['image'] . '" alt="">';
               echo '<div class="name">' . $fetch_products['name'] . '</div>';
               echo '<div class="rating">' . $stars . '</div>'; // Menampilkan bintang di sini
               echo '<div class="price">$' . $fetch_products['price'] . '/-</div>';
               echo '<input type="hidden" name="product_name" value="' . $fetch_products['name'] . '">';
               echo '<input type="hidden" name="product_price" value="' . $fetch_products['price'] . '">';
               echo '<input type="hidden" name="product_image" value="' . $fetch_products['image'] . '">';
               echo '<a href="product_details.php?product_id=' . $product_id . '" class="btn details-btn">View Details</a>';
               echo '</form>';
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>