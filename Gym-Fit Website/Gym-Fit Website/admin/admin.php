<?php 
session_start();
include '../php/connection.php';
if ($_SESSION['user_priv'] !== 'a') {
    // Redirect non-admin users to another page
    header("Location: ../sign-in_sign-up/login.php");
    exit();
}
// Include the navigation bar
include 'admin_navbar.php'; 

// Fetch data from database
$total_sales_query = "SELECT SUM(total_price) AS total_sales FROM orders";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total_orders'];

$total_products_query = "SELECT COUNT(*) AS total_products FROM items";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

$active_users_query = "SELECT COUNT(*) AS active_users FROM user_account WHERE user_priv = 'u'";
$active_users_result = $conn->query($active_users_query);
$active_users = $active_users_result->fetch_assoc()['active_users'];

// Query to fetch recent orders with multiple products
$recent_orders_query = "
SELECT 
    o.id AS order_id, 
    o.name AS customer_name, 
    GROUP_CONCAT(o.total_products SEPARATOR ', ') AS product_names, 
    o.total_price AS amount, 
    o.order_status AS status
FROM orders o
LEFT JOIN cart c ON o.id = c.id 
LEFT JOIN items i ON c.item_id = i.item_id
GROUP BY o.id
ORDER BY o.id DESC
LIMIT 5";

$recent_orders_result = $conn->query($recent_orders_query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GYMFIT | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/admin.css" />
  </head>
  <body>
    <div class="container mt-5">
      <h1 class="text-center mb-4">Dashboard</h1>
      <div class="row dashboard-cards text-center">
        <div class="col-md-3 mb-4">
          <div class="card shadow-lg p-3 border-radius-10">
            <h5>Total Sales</h5>
            <p class="fs-4 fw-bold">$<?php echo number_format($total_sales, 2); ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card shadow-lg p-3 border-radius-10">
            <h5>Orders</h5>
            <p class="fs-4 fw-bold"><?php echo $total_orders; ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card shadow-lg p-3 border-radius-10">
            <h5>Products</h5>
            <p class="fs-4 fw-bold"><?php echo $total_products; ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card shadow-lg p-3 border-radius-10">
            <h5>Active Users</h5>
            <p class="fs-4 fw-bold"><?php echo $active_users; ?></p>
          </div>
        </div>
      </div>

      <h3 class="mt-5">Recent Orders</h3>
      <table class="table table-striped table-bordered table-hover shadow-sm">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Products</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($order = $recent_orders_result->fetch_assoc()) { ?>
          <tr>
            <td>#<?php echo $order['order_id']; ?></td>
            <td><?php echo $order['customer_name']; ?></td>
            <td><?php echo $order['product_names']; ?></td>
            <td>$<?php echo number_format($order['amount'], 2); ?></td>
            <td>
              <?php
                if ($order['status'] == 0) {
                    echo '<span class="badge bg-danger">Canceled</span>';
                } elseif ($order['status'] == 1) {
                    echo '<span class="badge bg-light text-dark">Pending</span>';
                }elseif ($order['status'] == 2) {
                  echo '<span class="badge bg-warning text-dark">Out for Delivery</span>';
                } elseif ($order['status'] == 3) {
                echo '<span class="badge bg-info text-light">Delivered</span>';
                } elseif ($order['status'] == 4) {
                  echo '<span class="badge bg-success text-light">Recieved</span>';
                  }  
                else {
                    echo '<span class="badge bg-dark">Unknown</span>';
                }
              ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <script src="../js/script.js"></script>
    <script src="../js/admin.js"></script>
  </body>
</html>
