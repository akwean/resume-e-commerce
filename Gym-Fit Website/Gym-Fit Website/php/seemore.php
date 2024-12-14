<?php 
session_start();
require_once 'connection.php';

// Retrieve the item_id from the URL
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
  
    // Query to get the product details based on the item_id
    $sql_product = "SELECT * FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql_product);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
  
    // Fetch the product details
    if ($row = $result->fetch_assoc()) {
        $item_name = $row['item_name'];
        $item_price = $row['item_price'];
        $product_image = $row['product_image'];
        $item_desc = $row['item_desc'];
        $stock = $row['stock'];
    } else {
        echo "<script>
            alert('Item not found.');
            window.location.href = 'seemore.php';
        </script>";
        exit();
    }
}

// Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;
    $user_id = $_SESSION['user_id'];

    // Check if the stock is 0
    if ($stock <= 0) {
        echo "<script>
            alert('The item \"$product_name\" is out of stock and cannot be added to the cart.');
            window.location.href = 'seemore.php';
        </script>";
        exit();
    }

    // Check if the user exists in the user table
    $check_user = mysqli_query($conn, "SELECT * FROM `user_account` WHERE ua_id = '$user_id'");
    if (mysqli_num_rows($check_user) > 0) {
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE item_name = '$product_name' AND user_id = '$user_id'");
        if (mysqli_num_rows($select_cart) > 0) {
            $message[] = 'Product already added to cart';
        } else {
            $insert_product = mysqli_query($conn, "INSERT INTO `cart`(user_id, item_name, price, product_image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')");
            if ($insert_product) {
                $message[] = 'Product added to cart successfully';
            } else {
                $message[] = 'Error: ' . mysqli_error($conn);
            }
        }
    } else {
        $message[] = 'User does not exist';
    }
}

include_once 'nav.php';
?>

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/styles.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/seemore.css" />
</head>
<body>
<main>
    <div class="container product-page py-5">
        <div class="row g-4">
            <!-- Product Image -->
            <div class="col-lg-6">
                <img src="<?php echo $product_image;?>" alt="<?php echo $item_name;?>" class="img-fluid rounded shadow-sm"/>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <h1 class="product-title"><?php echo $item_name;?></h1>
                <h2 class="product-price">₱<?php echo $item_price;?></h2>
                <p class="text-muted">
                    <?php if ($stock > 0): ?>
                        <i class="bi bi-check-circle-fill text-success"></i> Availability
                        <span class="text-success">In Stock</span>
                        <br><strong>Stock: <?php echo $stock; ?></strong>
                    <?php else: ?>
                        <i class="bi bi-x-circle-fill text-danger"></i> Availability:
                        <span class="text-danger">Out of Stock</span>
                    <?php endif; ?>
                </p>

                <!-- Add to Cart Form -->
                <form method="POST" class="mt-4">
                    <input type="hidden" name="product_name" value="<?php echo $item_name; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $item_price; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $product_image; ?>">
                    <button type="submit" class="btn btn-add-to-cart flex-grow-1" name="add_to_cart" 
                        <?php if ($stock <= 0): ?>disabled<?php endif; ?>>
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </form>

                <!-- Product Tabs -->
                <div class="product-tabs mt-5">
                    <ul class="nav nav-tabs" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                                Description
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="productTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <p><?php echo $item_desc;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'footer.php';

// Display messages if any
if (!empty($message)) {
    foreach ($message as $msg) {
        echo "<script>alert('$msg');</script>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
