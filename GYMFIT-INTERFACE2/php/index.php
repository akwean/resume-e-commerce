<?php
require_once 'connection.php';
require_once 'functions.php';  // Include the function file for session and IP-related functions

// Check if the user_priv key is set in the session
if (isset($_SESSION['user_priv']) && $_SESSION['user_priv'] === 'a') {
  // Redirect admin users to the admin dashboard
  header("Location: ../admin/admin_page.php");
  exit();
}

$ip = getIPAddress();  // Get the user's IP address

// Handle adding items to the cart
if (isset($_GET['add_to_cart'])) {
  $item_id_to_add = $_GET['add_to_cart'];

  // Insert the item into the cart for the specific user IP address
  $check_query = "SELECT * FROM cart WHERE item_id = ? AND ip_address = ?";
  $stmt_check = mysqli_prepare($conn, $check_query);
  mysqli_stmt_bind_param($stmt_check, "is", $item_id_to_add, $ip);
  mysqli_stmt_execute($stmt_check);
  $result_check = mysqli_stmt_get_result($stmt_check);
  
  if (mysqli_num_rows($result_check) == 0) {
    // Item doesn't exist in cart, so add it
    $insert_query = "INSERT INTO cart (item_id, ip_address, quantity) VALUES (?, ?, 1)";
    $stmt_insert = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt_insert, "is", $item_id_to_add, $ip);
    mysqli_stmt_execute($stmt_insert);
  }
  // Redirect to the current page to avoid multiple submissions
  header("Location: index.php");
  exit();
}

// Fetch featured products
$sql_featured = "SELECT * FROM featured";
$featured_products = $conn->query($sql_featured);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GYMFIT Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
      <div class="carousel-item">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">Join Our Community</h1>
            <p class="description">Connect with Fitness Enthusiasts</p>
            <p class="d-info">Be part of a community that shares your passion for fitness and well-being.</p>
            <a href="about.php" class="btn btn-danger btn-lg">Learn More</a>
          </div>
          <img src="../res_img/carousel_pic_2.png" class="d-block s-image" alt="Community" />
        </div>
      </div>
      <div class="carousel-item">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">Exclusive Offers</h1>
            <p class="description">Unlock Exclusive Deals on Gym Products & Accessories!</p>
            <p class="d-info">Discover our exclusive offers and discounts on top-quality gym products and accessories.</p>
            <a href="shop.php" class="btn btn-danger btn-lg">Shop Deals</a>
          </div>
          <img src="../res_img/carousel_pic_3.png" class="d-block s-image" alt="Exclusive Offers" />
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
      ?>
      <div class="col-3">
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
          <p class="price">₱<?php echo $row["item_price"];?></p>
          <a class="btn add-to-cart" href="index.php?add_to_cart=<?php echo $row["item_id"]?>">
            <i class="fa fa-shopping-cart"></i>
          </a>
          <a class="btn add-to-cart" href="seemore.php?item_id=<?php echo $row["item_id"]?>">
            <i class="fa fa-eye"></i> View More
          </a>
        </div>
      </div>
      <?php }} ?>
    </div>
  </div>

  <!-- Latest Products Section -->
  <div class="small-container latest-products">
    <h2 class="title">Latest Products</h2>
    <div class="row">
      <?php
        $sql_latest = "SELECT * FROM items ORDER BY time_added DESC LIMIT 10";
        $latest_products = $conn->query($sql_latest);
        while ($row = mysqli_fetch_assoc($latest_products)) {
      ?>
      <div class="col-3">
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
          <a class="btn add-to-cart" href="index.php?add_to_cart=<?php echo $row["item_id"]?>">
            <i class="fa fa-shopping-cart"></i>
          </a>
          <a class="btn add-to-cart" href="seemore.php?item_id=<?php echo $row["item_id"]?>">
            <i class="fa fa-eye"></i> View More
          </a>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>

  <!-- Bottom Advertisements -->
  <div id="carouselExampleIndicators" class="carousel slide my-4" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">20% OFF on All Gym Equipment!</h1>
            <p class="description">Hurry up! Limited time offer. Shop now and save big.</p>
            <a href="shop.php" class="btn btn-danger btn-lg">Shop Now</a>
          </div>
          <img src="../res_img/disount_promo_1.png" class="d-block s-image" alt="Fitness Training" />
        </div>
      </div>
      <div class="carousel-item">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">Buy 1 Get 1 Free on Accessories!</h1>
            <p class="description">Get the best deals on gym accessories. Offer valid for a limited time.</p>
            <a href="shop.php" class="btn btn-danger btn-lg">Grab the Deal</a>
          </div>
          <img src="../res_img/discount_promo_2.png" class="d-block s-image" alt="Community" />
        </div>
      </div>
      <div class="carousel-item">
        <div class="container d-flex justify-content-between align-items-center background">
          <div class="text-content">
            <h1 class="text-danger title mb-3">Free Shipping on Orders Over ₱2800!</h1>
            <p class="description">Enjoy free shipping on all orders above ₱2800. Shop for your gym essentials now!</p>
            <a href="shop.php" class="btn btn-danger btn-lg">Shop & Save</a>
          </div>
          <img src="../res_img/discount_promo_3.png" class="d-block s-image" alt="Exclusive Offers" />
        </div>
      </div>
    </div>
  </div>
</main>

<?php include_once 'footer.php'; ?>

</body>
</html>