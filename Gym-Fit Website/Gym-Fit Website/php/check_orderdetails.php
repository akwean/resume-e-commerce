<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location: ../sign-in_sign-up/login.php');
    exit;
}

include 'connection.php';

$user_id = $_SESSION['user_id'];


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../sign-in_sign-up/phpmailer/src/PHPMailer.php';
require '../sign-in_sign-up/phpmailer/src/SMTP.php';
require '../sign-in_sign-up/phpmailer/src/Exception.php';

function generateTrackingNumber() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $tracking_number = '';
    $length = 10;

    for ($i = 0; $i < $length; $i++) {
        $tracking_number .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $tracking_number;
}

if (isset($_POST['order_btn'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $method = $_POST['method'];
    $courier = $_POST['courier'];
    $province = $_POST['province'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $island = $_POST['island'];

    $reference_number = '';
    $gcash_amount = '';
    $gcash_account_name = '';
    $gcash_account_number = '';

    if ($method === 'gcash') {
        $reference_number = $_POST['reference_number'] ?? '';
        $gcash_amount = $_POST['gcash_amount'] ?? '';
        $gcash_account_name = $_POST['gcash_account_name'] ?? '';
        $gcash_account_number = $_POST['gcash_account_number'] ?? '';
    }

    $selected_items_json = $_POST['selected_items_checkout'] ?? '';
    $selected_items = json_decode($selected_items_json, true);

    if (!is_array($selected_items)) {
        die("Selected items data is invalid.");
    }

    $price_total = 0;
    $product_name = [];

    if (!empty($selected_items)) {
        foreach ($selected_items as $item_id) {
            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$item_id'");
            if (!$cart_query) {
                die("Error fetching cart item: " . mysqli_error($conn));
            }

            while ($product_item = mysqli_fetch_assoc($cart_query)) {
                $product_name[] = $product_item['item_name'] . ' (' . $product_item['quantity'] . ') ';
                $product_price = $product_item['price'] * $product_item['quantity'];
                $price_total += $product_price;
                mysqli_query($conn, "UPDATE items SET stock = stock - {$product_item['quantity']} WHERE item_name = '{$product_item['item_name']}'");
            }
        }
    }

    $total_product = implode(', ', $product_name);
    $tracking_number = generateTrackingNumber();

    // Define shipping fees
    $shipping_fees = [
        'Luzon' => 95,
        'Visayas' => 100,
        'Mindanao' => 105
    ];

    // Add shipping fee based on the selected island
    $shipping_fee = $shipping_fees[$island] ?? 0;
    $price_total += $shipping_fee;

    $detail_query = mysqli_query($conn, "INSERT INTO `orders` (user_id, name, phone_number, method, courier, province, city, barangay, street, total_products, total_price, order_status, payment_status, tracking_number, reference_number, gcash_amount, gcash_account_name, gcash_account_number, island) VALUES ('$user_id','$name','$number','$method','$courier','$province','$city','$barangay','$street','$total_product','$price_total', 1, 0, '$tracking_number', '$reference_number', '$gcash_amount', '$gcash_account_name', '$gcash_account_number', '$island')");

    if (!$detail_query) {
        die("Error inserting order details: " . mysqli_error($conn));
    }

    foreach ($selected_items as $item_id) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$item_id'");
    }

    echo "
        <div class='order-message-container'>
            <div class='message-container'>
                <h3>Thank you for shopping!</h3>
                <div class='order-detail'>
                    <span>".$total_product."</span>
                    <span class='total'>Total: Php".$price_total."/-</span>
                </div>
                <div class='customer-details'>
                    <p>Your Name: <span>".$name."</span></p>
                    <p>Your Number: <span>".$number."</span></p>
                    <p>Courier: <span>".$courier."</span></p>
                    <p>Your Address: <span>".$street.", ". $barangay.", ". $city.", ". $province.", ". $island."  </span></p>
                    <p>Your Payment Mode: <span>".$method."</span></p>";
                    if ($method === 'gcash') {
                        echo "<p>Your Reference Number: <span>".$reference_number."</span></p>
                            <p>GCash Amount: <span>".$gcash_amount."</span></p>
                            <p>GCash Account Name: <span>".$gcash_account_name."</span></p>
                            <p>GCash Account Number: <span>".$gcash_account_number."</span></p>";
                    }
                echo "<p>(*pay when product arrives*)</p>
                </div>
                <div class='tracking-details'>
                    <p>Tracking Number: <span>".$tracking_number."</span></p>
                </div>
                <a href='shop.php' class='btn'>Continue Shopping</a>
            </div>
        </div>
    ";

    // Define the receipt content for the email
    $receipt_content = "
        <h3>Thank you for shopping!</h3>
        <div class='order-detail'>
            <span>".$total_product."</span>
            <span class='total'>Total: Php".$price_total."/-</span>
        </div>
        <div class='customer-details'>
            <p>Your Name: <span>".$name."</span></p>
            <p>Your Number: <span>".$number."</span></p>
            <p>Courier: <span>".$courier."</span></p>
            <p>Your Address: <span>".$street.", ". $barangay.", ". $city.", ". $province.", ". $island."  </span></p>
            <p>Your Payment Mode: <span>".$method."</span></p>";
            if ($method === 'gcash') {
                $receipt_content .= "<p>Your Reference Number: <span>".$reference_number."</span></p>
                    <p>GCash Amount: <span>".$gcash_amount."</span></p>
                    <p>GCash Account Name: <span>".$gcash_account_name."</span></p>
                    <p>GCash Account Number: <span>".$gcash_account_number."</span></p>";
            }
        $receipt_content .= "<p>(*pay when product arrives*)</p>
        </div>
        <div class='tracking-details'>
            <p>Tracking Number: <span>".$tracking_number."</span></p>
        </div>
    ";

    // Fetch the email associated with the user_id
    $user_query = mysqli_query($conn, "SELECT email FROM user_account WHERE ua_id = '$user_id'");
    if (!$user_query) {
        die("Error fetching user email: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($user_query) > 0) {
        $user_data = mysqli_fetch_assoc($user_query);
        $email = $user_data['email'];

        // Debugging: Check if email is fetched correctly
        if (empty($email)) {
            die("Email not found for user_id: $user_id");
        }

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                             // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                        // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
            $mail->Username   = 'tiknumberone.1@gmail.com';                  // Your SMTP username (your email)
            $mail->Password   = 'xmdy bmgj dfom ayjv';                   // Your SMTP password (your email password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption
            $mail->Port       = 587;                                     // TCP port to connect to

            //Recipients
            $mail->setFrom('tiknumberone.1@gmail.com', 'GYMFit');      // Sender's email
            $mail->addAddress($email);                                  // Add recipient email

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Your Order Receipt';
            $mail->Body    = $receipt_content;
            $mail->AltBody = strip_tags($receipt_content);  // Fallback for plain text emails

            $mail->send();
            echo 'Receipt has been sent to your email.❤️😁👌👻';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'No account found with that email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="../css/receipt.css" />  
</head>
<body>
</body>
</html>