<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

$message = [];

// Fetch all reviews
$reviews_query = mysqli_query($conn, "
    SELECT r.*, p.name AS product_name, p.image AS product_image, u.name AS user_name
    FROM rating r
    JOIN products p ON r.product_id = p.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
") or die('Query failed');

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
   <title>Admin Reviews</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      .review-container {
         padding: 2rem;
      }

      .review-box {
        display: flex;
    flex-direction: column;
    border: var(--border);
    border-radius: .5rem;
    padding: 0.5rem; /* Kurangi nilai padding untuk membuat kotak lebih kecil */
    margin: 0.5rem auto; /* Mengatur margin atas dan bawah, serta membuat margin kiri dan kanan otomatis */
    box-shadow: var(--box-shadow);
    background-color: var(--white);
    max-width: 700px; /* Menetapkan lebar maksimum kotak ulasan, bisa disesuaikan */
    
    }


      .review-header {
         display: flex;
         align-items: center;
         margin-bottom: 1rem;
      }

      .review-header img {
         width: 50px;
         height: 50px;
         object-fit: cover;
         border-radius: .5rem;
         margin-right: 1rem;
      }

      .review-header .details {
         flex: 1;
      }

      .review-header .details .user-name,
      .review-header .details .product-name {
         font-size: 1.2rem;
         font-weight: bold;
         color: var(--black);
      }

      .review-header .details .product-name {
         color: var(--light-color);
      }

      .review-content {
         font-size: 1rem;
         color: var(--black);
         margin-bottom: .5rem;
      }

      .review-rating {
         font-size: 1rem;
         color: gold;
      }

      .review-timestamp {
         font-size: .8rem;
         color: var(--light-color);
      }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="review-container">

   <h1 class="title">User Reviews</h1>

   <?php
   if (!empty($reviews)) {
      foreach ($reviews as $review) {
         echo '<div class="review-box">';
         echo '<div class="review-header">';
         echo '<img src="uploaded_img/' . htmlspecialchars($review['product_image']) . '" alt="">';
         echo '<div class="details">';
         echo '<p class="user-name">' . htmlspecialchars($review['user_name']) . '</p>';
         echo '<p class="product-name">Product: ' . htmlspecialchars($review['product_name']) . '</p>';
         echo '</div>';
         echo '</div>';
         echo '<div class="review-content">' . htmlspecialchars($review['review']) . '</div>';
         echo '<div class="review-rating">' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '</div>';
         echo '<div class="review-timestamp">' . htmlspecialchars($review['created_at']) . '</div>';
         echo '</div>';
      }
   } else {
      echo '<p>No reviews found.</p>';
   }
   ?>

</section>

<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
