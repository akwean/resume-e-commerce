<?php
require_once 'connection.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: ../sign-in_sign-up/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

//removing single items
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);

    $stmt = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $remove_id, $user_id);

    if ($stmt->execute()) {
        header('location:cart.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

//delete all
if (isset($_GET['delete_all'])) {
    $stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        header('location:cart.php');
        exit();
    } else {
        echo "Error deleting records: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_items'])) {
        $selected_items = $_POST['selected_items'];
        $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
        $types = str_repeat('i', count($selected_items));

        $stmt = $conn->prepare("DELETE FROM `cart` WHERE id IN ($placeholders) AND user_id = ?");
        $params = array_merge($selected_items, [$user_id]);
        $stmt->bind_param($types . 'i', ...$params);

        if ($stmt->execute()) {
            header('location:cart.php');
            exit();
        } else {
            echo "Error deleting selected items: " . $conn->error;
        }
        $stmt->close();
    }
}

include 'nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart Page</title>
    <link rel="stylesheet" href="../css/nav_user.css" />
</head>
<body>

    <div class="container cart-section my-5">
        <section class="shopping-cart">
        <h1 class="text-center mb-4">Shopping Cart</h1>

        <div class="table-responsive">
            <form action="checkout-system.php" method="post" id="cart-form">
                <table class="table table-bordered cart-table text-center">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Select All(<input type="checkbox" id="select-all">)</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        $grand_total = 0;
                        $stmt = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {        
                        ?>

                <tr>
                    <td><input type="checkbox" name="selected_items[]" value="<?php echo $row['id']; ?>" class="item-checkbox" data-price="<?php echo $row['price'] * $row['quantity']; ?>"></td>
                    <td><img src="../product_img/<?php echo htmlspecialchars($row['product_image']); ?>" height="100" alt=""></td>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td>Php<?php echo number_format($row['price'], 2); ?>/-</td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>Php<?php echo number_format($row['price'] * $row['quantity'], 2); ?>/-</td>
                    <td><a class="btn btn-danger mt-3" href="cart.php?remove=<?php echo $row['id']; ?>" onclick="return confirm('Remove item from cart?')"><i class="fas fa-trash"></i> Remove</a></td>
                </tr>
                        <?php  
                            $grand_total += $row['price'] * $row['quantity'];
                        } 
                        
                    }
                    $stmt->close();
                    ?>
                    <tr class="table-bottom">
                    <td><button type="submit" formaction="cart.php" formmethod="post" name="delete_selected" class="delete-btn"><i class="fas fa-trash"></i> Delete Selected</button></td>
                    <td><a href="shop.php" class="option-btn">Continue Shopping</a></td>
                    <td colspan="3">Grand Total</td>
                    <td>Php<span id="grand-total"><?php echo number_format($grand_total, 2); ?></span>/-</td>
                    <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn"><i class="fas fa-trash"></i> Delete All</a></td>
                    </tr>

                    </tbody>
                </table>
            </form>
            <div class="text-and mt-4">
            <form action="checkout-system.php" method="post">
            <input type="hidden" name="selected_items_checkout" id="selected_items_checkout" value="">
            <button type="submit" class="btn btn-success btn-lg mt-3">Proceed to Checkout</button>
            </form>
            </div>
        </div>
    </section>
        </div>


<!-- Custom JS File Link -->
<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const grandTotalElement = document.getElementById('grand-total');
    const selectedItemsCheckout = document.getElementById('selected_items_checkout');
    let grandTotal = <?php echo $grand_total; ?>;

    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const itemPrice = parseFloat(checkbox.getAttribute('data-price'));
            if (this.checked) {
                grandTotal += itemPrice;
            } else {
                grandTotal -= itemPrice;
            }
        });
        grandTotalElement.textContent = grandTotal.toFixed(2);
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemPrice = parseFloat(this.getAttribute('data-price'));
            if (this.checked) {
                grandTotal += itemPrice;
            } else {
                grandTotal -= itemPrice;
            }
            grandTotalElement.textContent = grandTotal.toFixed(2);
        });
    });

    document.querySelector('.btn-success').addEventListener('click', function(event) {
        event.preventDefault();
        const selectedItems = Array.from(checkboxes)
                                   .filter(checkbox => checkbox.checked)
                                   .map(checkbox => checkbox.value);
        if (selectedItems.length > 0) {
            selectedItemsCheckout.value = JSON.stringify(selectedItems);
            event.target.closest('form').submit();
        } else {
            alert('Please select at least one item to proceed to checkout.');
        }
    });
});
</script>
 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>