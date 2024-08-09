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
   <title>My Transaction</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .rating-popup {
         display: none; /* Ensures the popup is initially hidden */
         position: fixed;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background: rgba(0, 0, 0, 0.8); /* Darker overlay for better focus */
         justify-content: center;
         align-items: center;
         z-index: 1000; /* Ensure the popup is on top */
      }
      .rating-popup .popup-content {
         background: #ffe6e6;
         padding: 30px;
         border-radius: 15px;
         width: 95%;
         max-width: 700px;
         max-height: 90%; /* Limit the maximum height */
         overflow-y: auto; /* Enable vertical scrolling */
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
         position: relative;
         text-align: center; /* Center align the text */
      }
      .rating-popup .close-btn {
         cursor: pointer;
         font-size: 2rem;
         position: absolute;
         top: 20px;
         right: 20px;
         color: #333;
      }
      .rating-popup h2 {
         margin-bottom: 20px;
         font-size: 1.8rem;
         color: #444;
      }
      .rating-popup .product-list {
         display: flex;
         flex-direction: column;
         gap: 15px;
      }
      .rating-popup .product-item {
         border: 1px solid #ccc;
         padding: 15px;
         border-radius: 10px;
         background: #fff;
      }
      .rating-popup .stars {
         display: flex;
         justify-content: center;
         gap: 10px;
         margin-bottom: 10px;
      }
      .rating-popup .stars i {
         font-size: 2.5rem;
         color: #ccc; /* Default color for inactive stars */
         cursor: pointer;
         transition: color 0.2s;
      }
      .rating-popup .stars i.active {
         color: gold; /* Color for active stars */
      }
      .rating-popup textarea {
         width: 100%;
         height: 100px;
         border: 1px solid #ccc;
         border-radius: 10px;
         padding: 10px;
         font-size: 1rem;
         margin-bottom: 10px;
         resize: none;
      }
      .rating-popup button {
         background: goldenrod;
         color: #fff;
         border: none;
         margin-top: 20px;
         padding: 10px 20px;
         font-size: 1rem;
         border-radius: 10px;
         cursor: pointer;
         transition: background 0.3s;
      }
      .rating-popup button:hover {
         background: darkgoldenrod;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>My Transaction</h3>
   <p> <a href="home.php">home</a> / orders </p>
</div>

<section class="placed-orders">
   <h1 class="title">Review Order</h1>

   <div class="box-container">
      <?php
      $transaction_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `user_id` = '$user_id' AND `payment_status` = 'completed'") or die('query failed');
      if (mysqli_num_rows($transaction_query) > 0) {
         while ($fetch_transactions = mysqli_fetch_assoc($transaction_query)) {
            $order_id = $fetch_transactions['id'];
            
            // Fetch the products in the order
            $products_query = mysqli_query($conn, "SELECT * FROM `order_items` WHERE `order_id` = '$order_id'") or die('query failed');
            $products = mysqli_fetch_all($products_query, MYSQLI_ASSOC);

            $already_rated = false;

            foreach ($products as $product) {
               $product_id = $product['product_id'];
               $check_rating_query = "SELECT * FROM `rating` WHERE user_id = '$user_id' AND order_id = '$order_id' AND product_id = '$product_id'";
               $check_rating_result = mysqli_query($conn, $check_rating_query);
               if (mysqli_num_rows($check_rating_result) > 0) {
                   $already_rated = true;
                   break;
               }
           }
           
      ?>
      <div class="box">
         <p>Placed on: <span><?php echo $fetch_transactions['placed_on']; ?></span></p>
         <p>Name: <span><?php echo $fetch_transactions['name']; ?></span></p>
         <p>Number: <span><?php echo $fetch_transactions['number']; ?></span></p>
         <p>Email: <span><?php echo $fetch_transactions['email']; ?></span></p>
         <p>Address: <span><?php echo $fetch_transactions['address']; ?></span></p>
         <p>Payment method: <span><?php echo $fetch_transactions['method']; ?></span></p>
         <p>Your orders: <span><?php echo $fetch_transactions['total_products']; ?></span></p>
         <p>Total price: <span>$<?php echo $fetch_transactions['total_price']; ?>/-</span></p>
         <p>Request: <span><?php echo $fetch_transactions['request']; ?></span></p>
         <p>Payment status: <span style="color:<?php if($fetch_transactions['payment_status'] == 'completed'){ echo 'green'; }else{ echo 'red'; } ?>;"><?php echo $fetch_transactions['payment_status']; ?></span></p>

         <div class="btn">
            <?php if ($already_rated) { ?>
               <a href="listreview.php?order_id=<?php echo $order_id; ?>">View Reviews</a>
            <?php } else { ?>
               <a href="#" onclick="openPopup('<?php echo $order_id; ?>')">Rate and Review</a>
            <?php } ?>
         </div>
         <div class="btn">
            <a href="order_again.php?order_id=<?php echo $fetch_transactions['id']; ?>">Order Again</a>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No transactions yet!</p>';
      }
      ?>
   </div>
</section>

<!-- Rating Popup -->
<div class="rating-popup" id="ratingPopup">
   <div class="popup-content">
      <span class="close-btn" onclick="closePopup()">×</span>
      <h2>Rate and Review Products</h2>
      <form id="ratingForm">
         <input type="hidden" name="order_id" id="order_id">
         <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
         <div class="product-list" id="productList">
            <!-- Dynamic content will be inserted here -->
         </div>
         <button type="submit">Submit</button>
      </form>
   </div>
</div>

<script>
let selectedRating = 0;

function openPopup(orderId) {
    selectedRating = 0; // Reset the rating
    document.getElementById('ratingPopup').style.display = 'flex';
    document.getElementById('order_id').value = orderId;

    // Fetch products and create form elements
    fetch('get_products.php?order_id=' + orderId)
        .then(response => response.json())
        .then(products => {
            const productList = document.getElementById('productList');
            productList.innerHTML = ''; // Clear existing content

            products.forEach(product => {
                const productItem = document.createElement('div');
                productItem.classList.add('product-item');

                productItem.innerHTML = `
                    <input type="hidden" name="product_id[]" value="${product.product_id}">
                    <p><strong>${product.name}</strong></p>
                    <img src="uploaded_img/${product.image}" alt="${product.name}" style="width: 50px; height: auto;">
                    <div class="stars" data-product-id="${product.product_id}" id="stars_${product.product_id}">
                        <i class="fa fa-star" data-value="1"></i>
                        <i class="fa fa-star" data-value="2"></i>
                        <i class="fa fa-star" data-value="3"></i>
                        <i class="fa fa-star" data-value="4"></i>
                        <i class="fa fa-star" data-value="5"></i>
                    </div>
                    <input type="hidden" name="rating_${product.product_id}" value="0">
                    <textarea name="review_${product.product_id}" placeholder="Write your review here..."></textarea>
                `;

                productList.appendChild(productItem);

                const stars = productItem.querySelectorAll('.stars i');
                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const productId = star.parentElement.getAttribute('data-product-id');
                        const ratingValue = star.getAttribute('data-value');
                        selectedRating = ratingValue;
                        updateStars(star.parentElement, selectedRating);
                        document.querySelector(`input[name="rating_${productId}"]`).value = selectedRating; // Update the hidden input with the selected rating
                    });
                });
            });
        });
}

function updateStars(starContainer, rating) {
    const stars = starContainer.querySelectorAll('i');
    stars.forEach(star => {
        if (star.getAttribute('data-value') <= rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}



function closePopup() {
   document.getElementById('ratingPopup').style.display = 'none';
}

function updateStars(starContainer, rating) {
   const stars = starContainer.querySelectorAll('i');
   stars.forEach(star => {
      if (star.getAttribute('data-value') <= rating) {
         star.classList.add('active');
      } else {
         star.classList.remove('active');
      }
   });
}

document.getElementById('ratingForm').addEventListener('submit', function(event) {
   event.preventDefault();
   
   const formData = new FormData(this);
   const order_id = document.getElementById('order_id').value;
   
   fetch('submit.php', {
      method: 'POST',
      body: formData
   }).then(response => response.text())
     .then(data => {
        alert(data);
        closePopup();
        location.reload(); // Reload to update the status of rated products
     });
});
</script>

<?php include 'footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
