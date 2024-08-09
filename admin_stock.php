<?php


include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'library/PHPMailer.php';
require 'library/Exception.php';
require 'library/SMTP.php';

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $additional_quantity = $_POST['new_quantity'];
    // mysqli_query($conn, "UPDATE products SET qty = '$new_quantity' WHERE id = '$product_id'") or die('query failed');

    // Fetch the current quantity from the database
    $fetch_query = "SELECT qty FROM products WHERE id = '$product_id'";
    $fetch_result = mysqli_query($conn, $fetch_query);
    $row = mysqli_fetch_assoc($fetch_result);
    $current_quantity = $row['qty'];

    // Calculate new total quantity
    $new_quantity = $current_quantity + $additional_quantity;

    // Update existing product quantity in the database
    mysqli_query($conn, "UPDATE products SET qty = '$new_quantity' WHERE id = '$product_id'") or die('Query failed');

    // Example of inserting new product data into the database
    // $insert_query = "INSERT INTO products (name, qty) VALUES ('New Product Name', '$new_quantity')";
    // $result = mysqli_query($conn, $insert_query);

    // if (!$result) {
    //     die('Failed to add new product: ' . mysqli_error($conn));
    // }

    // Email sending logic
    $mail = new PHPMailer();

    //Enable SMTP debugging
    $mail->SMTPDebug = 0;
    //Set PHPMailer to use SMTP
    $mail->isSMTP();
    //Set SMTP host name
    $mail->Host = "smtp.gmail.com"; //host mail server
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password
    $mail->Username = "ryansauvage93@gmail.com"; // email smtp
    $mail->Password = "slidnmmnestpqjla"; //password email smtp
    // If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "ssl";
    //Set TCP port to connect to
    $mail->Port = 465;

    //Sender
    $mail->From = $mail->Username; //Sender's Email
    $mail->FromName = "FRAMP"; //Sender's Name

    //Receiver
    $mail->addAddress($_POST['email']);

    $mail->isHTML(true);

    $mail->Subject = $_POST['subject'];
    $mail->Body = $_POST['message'];

    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message successfully sent!";
    }

    header('location:admin_stock.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="show-products">

    <h1 class="title">Product Stock Analysis</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                    while ($row = mysqli_fetch_assoc($select_products)) {
                        $status = $row['qty'] < 50 ? 'Warning' : 'OK';
                        $status_class = $row['qty'] < 50 ? 'warning' : 'ok';
                        echo "
                            <tr>
                                <td><img src='uploaded_img/{$row['image']}' class='product-image' alt=''></td>
                                <td>{$row['name']}</td>
                                <td>{$row['qty']}</td>
                                <td class='{$status_class}'>{$status}</td>
                                <td>
                                    <button class='btn' onclick='openModal({$row['id']}, \"{$row['name']}\")'>Request</button>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo '<tr><td colspan="5" class="empty">No products added yet!</td></tr>';
                }
                ?>
            </tbody>

            <!-- , {$row['qty']} -->
        </table>
    </div>

</section>

<!-- First Modal Structure -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Quantity Request</h2>
        <form id="updateForm" action="" method="post">
            <input type="hidden" name="product_id" id="modalProductId">
            <p id="modalProductName"></p>
            <input type="number" name="new_quantity" id="modalProductQuantity" class="form-input" value="0" min="0" required>
            <input type="button" value="Proceed" class="btn" onclick="openSecondModal()">
        </form>
    </div>
</div>

<!-- Second Modal Structure -->
<div id="secondModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeSecondModal()">&times;</span>
        <h2>Confirm Request</h2>
        <form id="confirmForm" action="" method="post">
            <table>
                <tr>
                    <td>Receiver's Email</td>
                    <td><input type="email" name="email" class="form-input" required></td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td><input type="text" name="subject" class="form-input" value="Request new Stock" required></td>
                </tr>
                <tr>
                    <td>Product Name</td>
                    <td><input type="hidden" name="product_id" id="confirmProductId">
                        <p id="confirmProductName" class="modal-product-name"></p>
                    </td>
                </tr>
                <tr>
                    <td>Quantity</td>
                    <td><input type="number" name="new_quantity" id="confirmProductQuantity" class="form-input" min="0" readonly></td>
                </tr>
                <tr>
                    <td>Message</td>
                    <td><textarea name="message" cols="30" rows="10" class="form-input" readonly id="emailMessage"></textarea></td>
                </tr>
            </table>
            <input type="submit" name="update_quantity" value="Confirm" class="btn">
        </form>
    </div>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 16px;
    }
    .title {
        font-size: 3em;
        margin-bottom: 30px;
        text-align: center;
    }
    .warning {
        color: red;
        font-weight: bold;
    }
    .ok {
        color: green;
        font-weight: bold;
    }
    .table-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 20px;
    }
    table {
    width: 90%; /* Increased width to match the modal */
    max-width: 780px; /* Adjusted to fit within the modal's max-width and keep edges */
    border-collapse: collapse;
    font-size: 1.5em;
    margin-bottom: 30px;
    border: 2px solid #ddd;
    text-align: center;
    margin: 0 auto; /* Center the table */
    }
    th, td {
        padding: 20px;
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f4f4f4;
    }
    .empty {
        text-align: center;
        padding: 20px;
    }
    .btn {
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 1.2em;
    margin-top: 20px; /* Optional: adds space between content and button */
    align-self: center; /* Centers the button horizontally */
}
    .btn:hover {
        background-color: #218838;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }
    .form-input.large-input {
        font-size: 1.2em;
        padding: 15px;
    }
    .product-image {
        width: 150px;
        height: auto;
    }
    .modal-product-name {
        font-weight: bold;
    }
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }
    .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 90%; /* Increased width */
    max-width: 800px; /* Increased max-width */
    text-align: left;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    border-radius: 10px;
    word-wrap: break-word; /* Added to prevent text overlapping */
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script>
    function openModal(id, name, quantity) {
        document.getElementById('modalProductId').value = id;
        document.getElementById('modalProductName').innerText = name;
        //document.getElementById('modalProductQuantity').value = quantity; // dimatiin karna biar bisa qty value add nya 0
        document.getElementById('updateModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('updateModal').style.display = "none";
    }

    function openSecondModal() {
        // Get values from the first modal
        const id = document.getElementById('modalProductId').value;
        const name = document.getElementById('modalProductName').innerText;
        const quantity = document.getElementById('modalProductQuantity').value;

        // Set values in the second modal
        document.getElementById('confirmProductId').value = id;
        document.getElementById('confirmProductName').innerText = name;
        document.getElementById('confirmProductQuantity').value = quantity;

         // Set dynamic message in the textarea
         const message = `Dear Vendors,\n\nI hope this message finds you well. We are currently in need of restocking our inventory with ${name}. Could you please assist us by providing the following quantities at your earliest convenience:\n\nProduct Name: ${name}\nQuantity Required: ${quantity} pcs\nDelivery Address: Jl. Ki Hajar Dewantara\n\nPlease let us know the availability and estimated delivery time. We look forward to your prompt response.\n\nBest regards,\nFRAMP`;
         document.getElementById('emailMessage').value = message;
        // Close the first modal and open the second modal
        closeModal();
        document.getElementById('secondModal').style.display = "block";
    }

    function closeSecondModal() {
        document.getElementById('secondModal').style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('updateModal')) {
            closeModal();
        }
        if (event.target == document.getElementById('secondModal')) {
            closeSecondModal();
        }
    }
</script>

<script src="js/admin_script.js"></script>
</body>
</html>
