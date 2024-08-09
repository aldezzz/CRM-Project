<?php
    
    include 'config.php';
    
    session_start();
    $admin_id = $_SESSION['admin_id'];
    
    if(isset($_SESSION['products_made'])) {
        $products_made = round($_SESSION['products_made'], 0);
    } else {
        $products_made = null;
    }

    
    if(!isset($admin_id)){
        header('location:login.php');
    }
    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   

</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="add-products">
    <form action="predict.php" method="post" style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 400px; margin: auto;">
        <h3 style="margin-bottom: 20px; font-size: 24px; color: #333333; text-align: center;">Predict Product</h3>
        
        <label for="product_name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555555; font-size: 20px;">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required style="width: calc(100% - 22px); padding: 10px; margin-bottom: 20px; border: 1px solid #cccccc; border-radius: 4px;">
        
        <label for="raw_material_name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555555; font-size: 20px;">Raw Material Name:</label>
        <input type="text" id="raw_material_name" name="raw_material_name" required style="width: calc(100% - 22px); padding: 10px; margin-bottom: 20px; border: 1px solid #cccccc; border-radius: 4px;">
        
        <label for="raw_material_amount" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555555; font-size: 20px;">Raw Material Amount:</label>
        <input type="number" id="raw_material_amount" name="raw_material_amount" required style="width: calc(100% - 22px); padding: 10px; margin-bottom: 20px; border: 1px solid #cccccc; border-radius: 4px;">
        
        <label for="labor_hours" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555555; font-size: 20px;">Labor Hours:</label>
        <input type="number" id="labor_hours" name="labor_hours" required style="width: calc(100% - 22px); padding: 10px; margin-bottom: 20px; border: 1px solid #cccccc; border-radius: 4px;">
        
        <input type="submit" value="Predict" style="background-color: #007bff; color: #ffffff; border: none; border-radius: 4px; padding: 10px 15px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease;">
    </form>

</section>

<section class="dashboard" style="text-align: center; font-size: 24px; padding-top: 0">

    <h1>Prediction Result</h1>
    <p>The predicted number of products made is: <?php echo htmlspecialchars($products_made); ?></p>
</section>



<script src="js/admin_script.js"></script>    
</body>    
