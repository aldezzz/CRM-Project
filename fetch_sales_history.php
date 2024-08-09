<?php

include('config.php');

 if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $orders = "SELECT total_products FROM orders";
    $result = mysqli_query($conn, $orders);

    $sales_history = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $items = $row['total_products'];
        $parts = explode(',', $items);

        // fungsinya untuk ngeslice bbrp items -> Pouch (1) , Eating Utensils (1) -> biar bisa diitung 1"
        for ($i=0; $i<count($parts); $i++){
             // { ["total_products"]=> string(13) ", lalala (1) " }

            // "lalala (1)"     ", " dipotong
            $name = strtok($parts[$i], '(');
            // "lalala "  cari string sampe ketemu "(" trs dipotong
            $name = trim($name);
            // "lalala"     space setelah lalala hilang

            preg_match('/\((\d+)\)/', $parts[$i], $matches);
            // '/ .... /' yg abis quote itu delimiter
            // \( & \) itu buat parentheseses karena di examples ada (5) -> untuk ngebaca 5nya between parentheseses
            //(\d+) -> kebagi jadi 2: \d, + -> dijadiin dalam 1 ()
            // \d matches any digits yg equivalent [0-9]
            // + quantifier that matches one or more occurrences 
            // fungsinya () dalam \d+ untuk capture the matched digits jadi a group, mau ada 100 numbers, nti dijadiin 1 group 
            // ini disimpen ke $matches di [0] bakal jadi (5) tapi di [1] bakal jadi 5 -> without parentheses
            // item itu untuk save fullnya "lalala (1)"

            if ($matches) {
                $number = intval($matches[1]);

                // Check if $name already exists in $sales_history
                if (array_key_exists($name, $sales_history)) {
                    $sales_history[$name] += $number;
                } else {
                    $sales_history[$name] = $number;
                }
            }
        }   
    }

    $array = [];
    foreach ($sales_history as $name => $value){
        $array[] = [$name, $value];
    }

    echo json_encode($array);

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
 


?>