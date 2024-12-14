<?php
include 'connection.php';

// Function to add items to the cart

function cart() {
    global $conn;
    if (isset($_GET['add_to_cart'])) {
        $get_item_id = $_GET['add_to_cart'];
        $select_query = "SELECT * FROM cart WHERE item_id = $get_item_id";
        $result_query = mysqli_query($conn, $select_query);
        $num_rows = mysqli_num_rows($result_query);

        if ($num_rows > 0) {
            echo "<script>
                var toast = new bootstrap.Toast(document.getElementById('cartToast'));
                document.getElementById('toastMessage').textContent = 'Item is already in cart';
                document.getElementById('cartToast').classList.remove('bg-success');
                document.getElementById('cartToast').classList.add('bg-warning');
                toast.show();
            </script>";
        } else {
            $insert_query = "INSERT INTO cart (item_id) VALUES ($get_item_id)";
            $result_query = mysqli_query($conn, $insert_query);
            echo "<script>
                var toast = new bootstrap.Toast(document.getElementById('cartToast'));
                document.getElementById('toastMessage').textContent = 'Item added to cart';
                document.getElementById('cartToast').classList.remove('bg-warning');
                document.getElementById('cartToast').classList.add('bg-success');
                toast.show();
            </script>";
        }
    }
}

// Function for getting the number of items in the cart
function cart_num() {
    global $conn;
    $ip = getIPAddress();
    $select_query = "SELECT * FROM cart WHERE ip_address = '$ip'";
    $result_query = mysqli_query($conn, $select_query);
    $count_cart = mysqli_num_rows($result_query);
    echo $count_cart;
}

// Function to calculate the total price of items in the cart
function total_price() {
    global $conn;
    $ip = getIPAddress();
    $total = 0;
    $cart_query = "SELECT * FROM cart WHERE ip_address='$ip'";
    $result = mysqli_query($conn, $cart_query);
    while ($row = mysqli_fetch_array($result)) {
        $item_id = $row['item_id'];
        $select_items = "SELECT * FROM items WHERE item_id = $item_id";
        $result_items = mysqli_query($conn, $select_items);
        while ($row_item_price = mysqli_fetch_array($result_items)) {
            $item_price = $row_item_price['item_price'];
            $total += $item_price;
        }
    }
    echo $total;
}

// Function to update the stock of items in the cart
function update_cart_stock($item_id, $quantity) {
    global $conn;
    $ip = getIPAddress();
    $update_query = "UPDATE items SET stock = stock - $quantity WHERE item_id = $item_id";
    mysqli_query($conn, $update_query);
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
    global $conn;
    $ip = getIPAddress();
    $delete_query = "DELETE FROM cart WHERE item_id = $item_id AND ip_address = '$ip'";
    mysqli_query($conn, $delete_query);
}
?>