<?php
session_start();
require_once 'connection.php'; 
require_once 'functions.php'; 

$ip = getIPAddress();  // Get the user's IP address

// Handle item removal from the cart
if (isset($_POST['remove_item'])) {
    $item_id_to_remove = $_POST['remove_item'];  // Get the item_id from the button value

    // Delete the item from the cart table based on the item_id and user's IP address
    $remove_query = "DELETE FROM cart WHERE item_id = ? AND ip_address = ?";
    $stmt = mysqli_prepare($conn, $remove_query);
    mysqli_stmt_bind_param($stmt, "is", $item_id_to_remove, $ip);
    if (mysqli_stmt_execute($stmt)) {
        // Remove the item from the session cart as well
        foreach ($_SESSION['cart'] as $index => $cart_item) {
            if ($cart_item['item_id'] == $item_id_to_remove) {
                unset($_SESSION['cart'][$index]);
                break;
            }
        }
        // Re-index the session cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    // Recalculate the total price
    $_SESSION['cart_total'] = 0;
    foreach ($_SESSION['cart'] as $item) {
        $_SESSION['cart_total'] += $item['item_price'] * $item['quantity'];
    }

    // Redirect to cart page to reflect the removal
    header("Location: cart.php");
    exit;
}

// Handle cart quantity updates
if (isset($_POST['update_cart'])) {
    // Check if 'quantity' is set before accessing
    if (isset($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $index => $new_quantity) {
            // Ensure the quantity is not less than 1
            $_SESSION['cart'][$index]['quantity'] = max(1, $new_quantity);
        }

        // Update the quantities in the database as well
        $ip = getIPAddress();
        foreach ($_SESSION['cart'] as $item) {
            $item_id = $item['item_id'];
            $quantity = $item['quantity'];

            $update_query = "UPDATE cart SET quantity = ? WHERE item_id = ? AND ip_address = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "iis", $quantity, $item_id, $ip);
            mysqli_stmt_execute($stmt);
        }

        // Recalculate the total price
        $_SESSION['cart_total'] = 0;
        foreach ($_SESSION['cart'] as $item) {
            $_SESSION['cart_total'] += $item['item_price'] * $item['quantity'];
        }

        // Redirect to cart page after update
        header("Location: cart.php");
        exit;
    }
}

// Fetch the cart items from the database with a JOIN to get item details
$cart_items = [];  // Array to store cart items
$total = 0;
$cart_query = "
    SELECT cart.item_id, cart.quantity, items.item_price, items.item_name, items.product_image 
    FROM cart 
    JOIN items ON cart.item_id = items.item_id 
    WHERE cart.ip_address = '$ip'
";
$result = mysqli_query($conn, $cart_query);

while ($row = mysqli_fetch_array($result)) {
    $cart_items[] = [
        'item_id' => $row['item_id'],
        'item_name' => $row['item_name'],
        'item_price' => $row['item_price'],
        'item_image' => $row['product_image'],
        'quantity' => $row['quantity']  // Using the quantity from the cart table
    ];
    $total += $row['item_price'] * $row['quantity'];  // Calculating total based on quantity
}

// Store the cart data in session
$_SESSION['cart'] = $cart_items;
$_SESSION['cart_total'] = $total;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart Page</title>
    <link rel="stylesheet" href="../css/cart.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include_once 'nav.php'; ?>

<main>
    <div class="container cart-section my-5">
        <h1 class="text-center mb-4">Shopping Cart</h1>

        <div class="table-responsive">
            <form action="cart.php" method="POST">
                <table class="table table-bordered cart-table text-center">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Price</th>
                            <th scope="col" colspan="2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $item['item_image']; ?>" alt="Image" /><br>
                                    <?php echo $item['item_name']; ?>
                                </td>
                                <td>₱<?php echo $item['item_price']; ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $index; ?>]" value="<?php echo $item['quantity']; ?>" min="1" />
                                </td>
                                <td>₱<?php echo $item['item_price'] * $item['quantity']; ?></td>
                                <td>
                                    <button class="btn btn-danger mt-3" type="submit" name="remove_item" value="<?php echo $item['item_id']; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-end mt-4">
                    <p class="fw-bold">Total: ₱<?php echo $_SESSION['cart_total']; ?></p>
                    <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
                    <a href="checkout-system.php" class="btn btn-success mt-3">Proceed to Checkout</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
