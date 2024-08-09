<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $raw_material_name = $_POST['raw_material_name'];
    $raw_material_amount = $_POST['raw_material_amount'];
    $labor_hours = $_POST['labor_hours'];

    $data = array(
        'product_name' => $product_name,
        'raw_material_name' => $raw_material_name,
        'raw_material_amount' => $raw_material_amount,
        'labor_hours' => $labor_hours
    );

    $json_data = json_encode($data);

    // Set up cURL
    $ch = curl_init('http://localhost:5000/predict');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    // Execute the request and capture the response
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($httpcode == 200) {
        $result = json_decode($response, true);
       
        $products_made = $result['predicted_products_made'];

        $_SESSION['products_made'] = $products_made;
        header('Location: predict_product.php');
        exit();
    } else {
        echo "<h2>Error making request to Flask API:</h2>";
        echo "<pre>";
        echo htmlspecialchars($response);
        echo "</pre>";
    }
}

?>
