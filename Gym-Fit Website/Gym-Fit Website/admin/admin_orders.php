<?php 
session_start();
include '../php/connection.php';
if ($_SESSION['user_priv'] !== 'a') {
    // Redirect non-admin users to another page
    header("Location: ../sign-in_sign-up/login.php");
    exit();
}

// Function to update order status and payment status
function updateOrderStatus($conn, $order_id, $status = null, $update_payment = false) {
    $update_query = "UPDATE orders SET ";
    $params = [];
    $types = '';

    if ($status !== null) {
        $update_query .= "order_status = ?, ";
        $params[] = $status;
        $types .= 'i';
    }

    if ($update_payment) {
        $update_query .= "payment_status = 1, ";
    }

    // Remove the trailing comma and space
    $update_query = rtrim($update_query, ', ');
    $update_query .= " WHERE id = ?";
    $params[] = $order_id;
    $types .= 'i';

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    updateOrderStatus($conn, $order_id, 0); // Set order status to cancelled
}

if (isset($_POST['deliver_order'])) {
    $order_id = $_POST['order_id'];
    updateOrderStatus($conn, $order_id, 2); // Set order status to out for delivery
}

if (isset($_POST['received_order'])) {
    $order_id = $_POST['order_id'];
    updateOrderStatus($conn, $order_id, 4); // Set order status to received
}

if (isset($_POST['confirm_payment'])) {
    $order_id = $_POST['order_id'];
    updateOrderStatus($conn, $order_id, null, true); // Confirm payment without changing order status
}

// Function to get order status name
function getOrderStatusName($status) {
    switch ($status) {
        case 0:
            return 'Cancelled';
        case 1:
            return 'Pending';
        case 2:
            return 'Out for Delivery';
        case 3:
            return 'Delivered';
        case 4:
            return 'Received';
        default:
            return 'Unknown';
    }
}

// Function to get payment status name
function getPaymentStatusName($status) {
    return $status == 0 ? 'Not Confirmed' : 'Confirmed';
}

include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/admin_order.css" />
    <script>
        function showActions(orderId) {
            var actionButtons = document.getElementsByClassName('order-actions-' + orderId);
            for (var i = 0; i < actionButtons.length; i++) {
                actionButtons[i].style.display = 'inline-block';
            }
        }
    </script>
</head>
<body>
    
    <div class="container mt-5">
        <h2 class="mt-5">All orders</h2>
        <div class="table-container">
            <table class="table table-striped table-bordered table-hover shadow-sm">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Method</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Barangay</th>
                        <th>Street</th>
                        <th>Total Products</th>
                        <th>Total Price</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Reference Number</th>
                        <th>Order Date</th>
                        <th>Action</th>
                        <th>Confirm Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM orders";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $order_id = $row['id'];
                            $order_status_name = getOrderStatusName($row['order_status']);
                            $order_status = $row['order_status'];
                            $payment_status_name = getPaymentStatusName($row['payment_status']);
                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['phone_number']}</td>";
                            echo "<td>{$row['method']}</td>";
                            echo "<td>{$row['province']}</td>";
                            echo "<td>{$row['city']}</td>";
                            echo "<td>{$row['barangay']}</td>";
                            echo "<td>{$row['street']}</td>";
                            echo "<td>{$row['total_products']}</td>";
                            echo "<td>{$row['total_price']}</td>";
                            echo "<td>{$order_status_name}</td>";
                            echo "<td>{$payment_status_name}</td>"; 
                            echo "<td>{$row['reference_number']}</td>";
                            echo "<td>{$row['order_date']}</td>";
                            echo "<td>
                                    <form method='post'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>";
                            echo "<div class='action-buttons'>";
                            if ($order_status == 1) {
                                echo "<button type='submit' name='deliver_order' class='btn btn-success btn-oval order-actions deliver'>Out for Delivery</button>
                                      <button type='submit' name='cancel_order' class='btn btn-danger btn-oval order-actions cancel'>Cancel</button>";
                            }
                            if ($order_status == 2) {
                                echo "<button type='submit' name='received_order' class='btn btn-success btn-oval order-actions received'>Received</button>";
                            }
                            echo "</div>
                                    </form>
                                </td>";
                            // Confirm Payment Button
                            echo "<td>";
                            if ($row['payment_status'] == 0) {
                                echo "<form method='post'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>
                                        <button type='submit' name='confirm_payment' class='btn btn-primary btn-oval confirm-payment-btn'>Confirm Payment</button>
                                      </form>";
                            } else {
                                echo "<button type='button' class='btn btn-secondary btn-oval confirm-payment-btn' disabled>Confirmed</button>";
                            }
                            echo "</td>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>No orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>
