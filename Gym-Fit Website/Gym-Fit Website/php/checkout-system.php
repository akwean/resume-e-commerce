<?php
session_start();
if (!isset($_SESSION['username'])) {
   header('location: ../sign-in_sign-up/login.php');
   exit;
}

@include 'connection.php';

function generateTrackingNumber()
{
   // Function to generate a random tracking number
   $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   $tracking_number = '';
   $length = 21;

   for ($i = 0; $i < $length; $i++) {
      $tracking_number .= $characters[rand(0, strlen($characters) - 1)];
   }

   return $tracking_number;
}

$shipping_fees = [
   'Luzon' => 15,
   'Visayas' => 150,
   'Mindanao' => 500
];

if (isset($_POST['order_btn'])) {
   $name = $_POST['name'];
   $number = $_POST['number'];
   $method = $_POST['method'];
   $courier = $_POST['courier'];  // Get the selected courier service
   $island = $_POST['island'];    // Get the selected island
   $province = $_POST['province'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $barangay = $_POST['barangay'];

   // Check if the payment method is GCash and get the reference number if available
   $reference_number = ($method === 'gcash' && isset($_POST['reference_number'])) ? $_POST['reference_number'] : '';
   $gcash_amount = ($method === 'gcash' && isset($_POST['gcash_amount'])) ? $_POST['gcash_amount'] : 0;
   $gcash_account_name = ($method === 'gcash' && isset($_POST['gcash_account_name'])) ? $_POST['gcash_account_name'] : '';
   $gcash_account_number = ($method === 'gcash' && isset($_POST['gcash_account_number'])) ? $_POST['gcash_account_number'] : '';

   $selected_items_json = isset($_POST['selected_items_checkout']) ? $_POST['selected_items_checkout'] : '';
   $selected_items = json_decode($selected_items_json, true);

   if (!is_array($selected_items)) {
      die("Selected items data is invalid.");
   }

   $total_price = 0;
   foreach ($selected_items as $item_id) {
      $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$item_id'");
      if (!$cart_query) {
         die("Error fetching cart item: " . mysqli_error($conn));
      }

      while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
         $total_price += $fetch_cart['price'] * $fetch_cart['quantity'];
      }
   }

   // Add shipping fee based on selected island
   $shipping_fee = isset($shipping_fees[$island]) ? $shipping_fees[$island] : 0;
   $total_price += $shipping_fee;

   $order_query = mysqli_query($conn, "INSERT INTO `orders` (user_name, number, payment_method, courier, island, province, street, city, barangay, reference_number, gcash_amount, gcash_account_name, gcash_account_number, total_price) VALUES ('$name', '$number', '$method', '$courier', '$island', '$province', '$street', '$city', '$barangay', '$reference_number', '$gcash_amount', '$gcash_account_name', '$gcash_account_number', '$total_price')");

   if ($order_query) {
      $tracking_number = generateTrackingNumber();
      mysqli_query($conn, "UPDATE `orders` SET tracking_number = '$tracking_number' WHERE user_name = '$name' AND number = '$number'");
      echo "<script>alert('Order placed successfully! Your tracking number is $tracking_number');</script>";
   } else {
      echo "<script>alert('Order placement failed. Please try again.');</script>";
   }
}

include 'nav.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Bootstrap CDN link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome for icons -->
   <link href="https://db.onlinewebfonts.com/c/11eae19d5201ee5e6b1c2ae903ff4ea6?family=Metal+Vengeance" rel="stylesheet">

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="checkout.css">

   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #f8f9fa;
         color: #333;
      }

      .checkout-form {
         background: #fff;
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      h1 {
         color: #e63946;
         font-weight: 600;
      }

      .form-label {
         color: #555;
         font-weight: 500;
      }

      .form-control {
         border-radius: 5px;
         border: 1px solid #ddd;
      }

      .form-control:focus {
         box-shadow: 0 0 4px rgba(230, 57, 70, 0.8);
         border-color: #e63946;
      }

      .btn-danger {
         background-color: #e63946;
         border-color: #e63946;
         transition: background-color 0.3s ease;
      }

      .btn-danger:hover {
         background-color: #d62828;
         border-color: #d62828;
      }

      #gcash_details {
         display: none;
         background: #f1f1f1;
         padding: 15px;
         border-radius: 5px;
         margin-top: 10px;
      }

      .grand-total {
         font-weight: bold;
         font-size: 1.2em;
         color: #e63946;
         margin-top: 15px;
      }
   </style>
</head>

<body>
   <div class="container mt-5">
      <section class="checkout-form">
         <h1 class="text-center mb-4">Complete Your Order</h1>
         <form action="check_orderdetails.php" method="post" onsubmit="return validateGCashAmount()">
            <div class="row mb-4">
               <?php
               if (isset($_POST['selected_items_checkout'])) {
                  $selected_items = json_decode($_POST['selected_items_checkout'], true);
                  $total = 0;
                  $grand_total = 0;
                  if (!empty($selected_items)) {
                     foreach ($selected_items as $item_id) {
                        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$item_id'");
                        if (!$cart_query) {
                           die("Error fetching cart item: " . mysqli_error($conn));
                        }

                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                           $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                           $grand_total = $total += $total_price;
               ?>
                           <div class="col-12">
                              <span><?= $fetch_cart['item_name']; ?> (<?= $fetch_cart['quantity']; ?>)</span>
                           </div>
               <?php
                        }
                     }
                     $island = $_POST['island'] ?? 'Luzon';
                     $shipping_fee = $shipping_fees[$island];
                     $grand_total += $shipping_fee;
                  } else {
                     echo "<div class='col-12'><span>Your cart is empty!</span></div>";
                  }
               }
               ?>
               <div class="col-12">
                  <span class="grand-total" id="grand_total" data-total="<?= $grand_total - $shipping_fee; ?>">Grand Total: Php (<?= $grand_total; ?>)</span>
                  <?php if (isset($tracking_number)) : ?>
                     <span>Tracking Number: <?= $tracking_number; ?></span>
                  <?php endif; ?>
               </div>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="name" class="form-label">Your Name</label>
                     <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="number" class="form-label">Your Number</label>
                     <input type="number" class="form-control" id="number" name="number" placeholder="Contact Number" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="payment_method" class="form-label">Payment Method</label>
                     <select class="form-select" name="method" id="payment_method" onchange="showPaymentDetails()">
                        <option value="cash on delivery" selected>Cash on Delivery</option>
                        <option value="gcash">Gcash</option>
                     </select>
                  </div>
               </div>

               <div id="gcash_details" class="col-12">
                  <div class="mb-3">
                     <label for="reference_number" class="form-label">Reference Number</label>
                     <input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="Enter reference number">
                  </div>
                  <div class="mb-3">
                     <label for="gcash_amount" class="form-label">GCash Amount</label>
                     <input type="number" class="form-control" id="gcash_amount" name="gcash_amount" placeholder="Enter GCash amount">
                  </div>
                  <div class="mb-3">
                     <label for="gcash_account_name" class="form-label">GCash Account Name</label>
                     <input type="text" class="form-control" id="gcash_account_name" name="gcash_account_name" placeholder="Enter GCash account name">
                  </div>
                  <div class="mb-3">
                     <label for="gcash_account_number" class="form-label">GCash Account Number</label>
                     <input type="text" class="form-control" id="gcash_account_number" name="gcash_account_number" placeholder="Enter GCash account number">
                  </div>
                  <img src="../res_img/qr.png" alt="QR Code" id="qr_code_image" class="img-fluid mt-3" style="display: none;">
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="courier_service" class="form-label">Courier Service</label>
                     <select class="form-select" name="courier" id="courier_service">
                        <option value="J&T Express" selected>J&T Express</option>
                        <option value="Flash Express">Flash Express</option>
                     </select>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="island_service" class="form-label">Island</label>
                     <select class="form-select" name="island" id="island_service" onchange="updateShippingFee()">
                        <option value="Luzon" selected>Luzon</option>
                        <option value="Visayas">Visayas</option>
                        <option value="Mindanao">Mindanao</option>
                     </select>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="province" class="form-label">Province</label>
                     <input type="text" class="form-control" id="province" name="province" placeholder="Province" required>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="city" class="form-label">City</label>
                     <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="barangay" class="form-label">Barangay</label>
                     <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay" required>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="street" class="form-label">Street</label>
                     <input type="text" class="form-control" id="street" name="street" placeholder="Street" required>
                  </div>
               </div>
            </div>

            <input type="hidden" name="selected_items_checkout" value='<?= htmlspecialchars(json_encode($selected_items), ENT_QUOTES, 'UTF-8'); ?>'>
            <input type="hidden" id="correct_gcash_amount" value="<?= $grand_total; ?>">
            <input type="submit" value="Order🛒" name="order_btn" class="btn btn-danger btn-lg w-100 mt-3">
         </form>
      </section>
   </div>


   <!-- Custom JavaScript file link -->
   <script>
      function showPaymentDetails() {
         var paymentMethod = document.getElementById("payment_method").value;
         var gcashDetailsDiv = document.getElementById("gcash_details");
         var qrCodeImage = document.getElementById("qr_code_image");
         var referenceNumber = document.getElementById("reference_number");
         var gcashAmount = document.getElementById("gcash_amount");
         var gcashAccountName = document.getElementById("gcash_account_name");
         var gcashAccountNumber = document.getElementById("gcash_account_number");

         if (paymentMethod === "gcash") {
            gcashDetailsDiv.style.display = "block";
            qrCodeImage.style.display = "inline-block";

            // Add required attribute to GCash fields
            referenceNumber.setAttribute("required", "required");
            gcashAmount.setAttribute("required", "required");
            gcashAccountName.setAttribute("required", "required");
            gcashAccountNumber.setAttribute("required", "required");
         } else {
            gcashDetailsDiv.style.display = "none";
            qrCodeImage.style.display = "none";

            // Remove required attribute from GCash fields
            referenceNumber.removeAttribute("required");
            gcashAmount.removeAttribute("required");
            gcashAccountName.removeAttribute("required");
            gcashAccountNumber.removeAttribute("required");

            // Clear the GCash fields
            referenceNumber.value = "";
            gcashAmount.value = "";
            gcashAccountName.value = "";
            gcashAccountNumber.value = "";
         }
      }

      function updateShippingFee() {
         var islandService = document.getElementById("island_service").value;
         var shippingFee = 0;

         switch (islandService) {
            case "Luzon":
               shippingFee = 95;
               break;
            case "Visayas":
               shippingFee = 100;
               break;
            case "Mindanao":
               shippingFee = 105;
               break;
         }

         var grandTotalSpan = document.getElementById("grand_total");
         var initialTotal = parseFloat(grandTotalSpan.getAttribute("data-total"));

         grandTotalSpan.innerText = "Grand Total: Php" + (initialTotal + shippingFee);

         // Update the hidden correct GCash amount field
         document.getElementById("correct_gcash_amount").value = initialTotal + shippingFee;
      }

      function validateGCashAmount() {
         var paymentMethod = document.getElementById("payment_method").value;
         if (paymentMethod === "gcash") {
            var enteredAmount = parseFloat(document.getElementById("gcash_amount").value);
            var correctAmount = parseFloat(document.getElementById("correct_gcash_amount").value);

            if (enteredAmount !== correctAmount) {
               alert("Please enter the correct GCash amount: Php" + correctAmount);
               return false;
            }
         }
         return true;
      }
   </script>

   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>