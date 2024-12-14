<?php
session_start();
require_once 'connection.php';

// Check if the user_priv key is set in the session
if (isset($_SESSION['user_priv']) && $_SESSION['user_priv'] === 'a') {
    // Redirect admin users to the admin dashboard
    header("Location: ../admin/admin_page.php");
    exit();
}

$message = array(); // Define an empty array for messages

// Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;
    $user_id = $_SESSION['user_id'];

    // Query to get the product details based on product name
    $sql_product = "SELECT * FROM items WHERE item_name = ?";
    $stmt = $conn->prepare($sql_product);
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the product details
    if ($row = $result->fetch_assoc()) {
        $stock = $row['stock'];

        // Check if the stock is 0
        if ($stock <= 0) {
            echo "<script>
                alert('The item \"$product_name\" is out of stock and cannot be added to the cart.');
                window.location.href = 'index.php';
            </script>";
            exit();
        }

        // Check if the user_id exists in the user table
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
    } else {
        $message[] = 'Product not found';
    }
}

// Fetch featured products
$sql_featured = "SELECT * FROM featured";
$featured_products = $conn->query($sql_featured);

// Fetch latest products
$sql_latest = "SELECT * FROM items ORDER BY time_added DESC LIMIT 10";
$latest_products = $conn->query($sql_latest);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GYMFIT Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../css/footer.css" rel="stylesheet"> <!-- External Footer CSS -->
</head>
<body>

<?php include_once 'nav.php'; ?>

<main>
  
  <!-- Carousel Adverts -->
  <div id="carouselExampleIndicators" class="carousel slide my-4" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">Welcome to GymFit</h1>
            <p class="description">Achieve Your Fitness Goals Today</p>
            <p class="d-info">
              Whether you're just starting out or you're a seasoned athlete, GymFit is here to help you stay fit, motivated, and healthy.
            </p>
            <a href="shop.php" class="btn btn-danger btn-lg">Shop Now</a>
          </div>
          <img src="../res_img/carousel_pic_1.png" class="d-block s-image" alt="Fitness Training" />
        </div>
      </div>
    </div>
  </div>

   <!-- Featured Products Section -->
   <div class="small-container featured-products">
    <h2 class="title">Featured Products</h2>
    <div class="row">
      <?php
        while ($row_featured = mysqli_fetch_assoc($featured_products)) {
          $sql = "SELECT * FROM items WHERE item_id=" . $row_featured["item_id"];
          $all_product = $conn->query($sql);
          while ($row = mysqli_fetch_assoc($all_product)) {
            // Check stock before displaying the product
            if ($row["stock"] <= 0) {
                continue; // Skip displaying out-of-stock products
            }
      ?>
      <div class="col-3">
      <form method="POST" class="mt-4">
        <div class="product-card">
          <img src="<?php echo $row["product_image"];?>" alt="<?php echo $row["item_name"];?>" class="product-img" />
          <h4 class="product-name"><?php echo $row["item_name"];?></h4>
          <p class="price">₱<?php echo $row["item_price"];?></p>
          <a class="btn add-to-cart" href="shop.php">
               <i class="fa fa-eye"></i> Shop Now
          </a>
        </div>
      </form>
      </div>
      <?php }} ?>
    </div>
  </div>

  <!-- Latest Products Section -->
  <div class="small-container latest-products">
    <h2 class="title">Latest Products</h2>
    <div class="row">
      <?php
        while ($row = mysqli_fetch_assoc($latest_products)) {
            // Check stock before displaying the product
            if ($row["stock"] <= 0) {
                continue; // Skip displaying out-of-stock products
            }
      ?>
      <div class="col-3">
      <form action="" method="POST">
        <div class="product-card">
          <img src="<?php echo $row["product_image"];?>" alt="<?php echo $row["item_name"];?>" class="product-img" />
          <h4 class="product-name"><?php echo $row["item_name"];?></h4>
          <div class="rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-o"></i>
          </div>

          <p class="price">₱<?php echo $row["item_price"]?></p>

          <input type="hidden" name="product_name" value="<?php echo $row["item_name"]; ?>">
          <input type="hidden" name="product_price" value="<?php echo $row["item_price"]; ?>">
          <input type="hidden" name="product_image" value="<?php echo $row["product_image"]; ?>">

          <button type="submit" class="btn add-to-cart" name="add_to_cart">
            <i class="fa fa-shopping-cart"></i>
          </button>
          <a class="btn add-to-cart" href="seemore.php? item_id=<?php echo $row["item_id"]?>">
               <i class="fa fa-eye"></i> View More
          </a>
        </div>
      </form>
    </div>
      <?php } ?>
    </div>
  </div>

</main>

<?php include_once 'footer.php'; ?>

</body>
</html>
