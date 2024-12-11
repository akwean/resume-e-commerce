<?php
session_start();
require_once 'functions.php';  // To include helper functions like getIPAddress()
require_once 'connection.php'; // To establish database connection

$ip = getIPAddress();  // Get the user's IP address

// Ensure the cart is not empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect if the cart is empty
    exit;
}

// Assuming the customer ID is stored in the session when the user is logged in
$cus_id = isset($_SESSION['cus_id']) ? $_SESSION['cus_id'] : null; // Get customer ID from session

// Handle quantity updates and placing the order
if (isset($_POST['place_order'])) {
    // Get and sanitize the order details from the form
    $order_total = $_SESSION['cart_total'];
    $order_status = 'Pending'; // Assuming order status is 'Pending'
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? 'Not Provided'); 
    $courier_service = mysqli_real_escape_string($conn, $_POST['courier_service'] ?? 'Not Selected');
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'Not Selected');

    // Update the quantities in the cart based on user input
    foreach ($_POST['quantity'] as $item_id => $quantity) {
        // Update the quantity in the session cart
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }

    // Recalculate the total
    $order_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        // Check if item_id and item_price exist
        if (isset($item['item_id'], $item['item_price'], $item['quantity'])) {
            $order_total += $item['item_price'] * $item['quantity'];
        } else {
            // Handle missing item price or item_id
            echo "Error: Missing item details (item_id or item_price) for one of the items in your cart.";
            exit;
        }
    }

    $_SESSION['cart_total'] = $order_total;

    // Insert order details into the 'orders' table
    $insert_order = "INSERT INTO orders (cus_id, ip_address, total_amount, status, address, courier_service, payment_method) VALUES ('$cus_id', '$ip', '$order_total', '$order_status', '$address', '$courier_service', '$payment_method')";
    if (mysqli_query($conn, $insert_order)) {
        $order_id = mysqli_insert_id($conn); // Get the last inserted order ID

        // Insert order items into the 'order_items' table
        foreach ($_SESSION['cart'] as $item) {
            $item_id = $item['item_id'];
            $quantity = $item['quantity'];
            $item_price = $item['item_price'];

            $insert_order_item = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES ('$order_id', '$item_id', '$quantity', '$item_price')";
            mysqli_query($conn, $insert_order_item);
        }

        // Clear the cart after the order is placed
        unset($_SESSION['cart']);
        unset($_SESSION['cart_total']);
        header("Location: order_confirmation.php?order_id=$order_id");
        exit;
    } else {
        echo "Error: Could not place the order.";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include_once 'nav.php'; ?>

<main>
    <div class="container">
        <h2>Order Summary</h2>
        <form action="checkout-system.php" method="POST">
            <ul class="list-group">
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    // Ensure item_price and item_id are available before trying to access them
                    if (isset($item['item_price'], $item['item_id'], $item['quantity'])) {
                        $total += $item['item_price'] * $item['quantity'];
                ?>
                    <li class="list-group-item">
                        <strong><?php echo $item['item_name']; ?>:</strong>
                        <span>₱<?php echo $item['item_price']; ?></span>
                        <span> x </span>
                        <input type="number" name="quantity[<?php echo $item['item_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" />
                    </li>
                <?php
                    } else {
                        echo "<li class='list-group-item'>Error: Missing item details for one of the cart items.</li>";
                    }
                endforeach;
                ?>
                <li class="list-group-item">
                    <strong>Total:</strong> ₱<?php echo $total; ?>
                </li>
            </ul>

            <!-- Address Section -->
            <div class="item-card mt-3">
                <h4>Enter Address</h4>
                <input type="text" name="address" class="form-control" placeholder="Enter your address" required />
            </div>

            <!-- Courier Service Section -->
            <div class="item-card mt-3">
                <h4>Select Courier Service</h4>
                <select name="courier_service" class="form-control" required>
                    <option value="Lalamove">Lalamove</option>
                    <option value="LBC Express">LBC Express</option>
                    <option value="JRS Express">JRS Express</option>
                    <option value="J&T Express">J&T Express</option>
                    <option value="Ninja Van">Ninja Van</option>
                    <option value="Grab Express">Grab Express</option>
                </select>
            </div>

            <!-- Payment Method Section -->
            <div class="item-card mt-3">
                <h4>Payment Method</h4>
                <select name="payment_method" class="form-control" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>

            <div class="text-end mt-4">
                <button type="submit" name="place_order" class="btn btn-success">Place Order</button>
            </div>
        </form>
    </div>
</main>

<?php include_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
