<?php
// Start the session and include connection
require_once 'connection.php';

?>

<!DOCTYPE html>
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
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <!-- Bootstrap CDN for Carousel -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!--For see more/view more (seemore.php)
    <link rel="stylesheet" href="../css/seemore.css" />
-->
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
      <div class="navbar-top">
        <div class="logo-container">
          <img src="../res_img/gymfit_logo.png" alt="GYMFIT Logo" class="logo-img" />
          <h1 class="logo-text">GYMFIT</h1>
        </div>
        <div class="nav-links">
          <a href="index.php">Home</a>
          <a href="shop.php">Shop</a>
          <a href="trainers.php">Trainers</a>
          <a href="about.php">About</a>
          <a href="contact.php">Contact</a>
        </div>
      </div>
      <div class="navbar-bottom">
        <div class="dropdown">
          <div class="menu-icon">
            <i class="fas fa-bars"></i>
            <span>Shop by Category</span>
          </div>
          <div class="dropdown-content">
          <?php
  $sql = "SELECT * FROM category";
  $categories = $conn -> query($sql);
  while($row = mysqli_fetch_assoc($categories)){
  ?>
            <a href="shop.php?category_id=<?php echo $row["category_id"];?>"><?php echo $row["category_title"];?></a>

<?php }?>
</div>
        </div>
        <div class="search-bar">
          <input type="text" placeholder="Search Products" />
          <i class="fas fa-search"></i>
        </div>
        <div class="user-cart-icons">
        <div class="dropdown">
  <i class="fas fa-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"></i>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">
    <?php if (isset($_SESSION['user_priv'])): ?>
      <li><a class="dropdown-item" href="../sign-in_sign-up/logout.php">Log Out</a></li>
    <?php else: ?>
      <li><a class="dropdown-item" href="../sign-in_sign-up/login.php">Login</a></li>
      <li><a class="dropdown-item" href="../sign-in_sign-up/signup.php">Sign Up</a></li>
    <?php endif; ?>
  </ul>
</div>

    <!--for cart-->
    <?php
    $row_count = 0; // Initialize the variable
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $select_rows = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $row_count = mysqli_num_rows($select_rows);
    } else {
        echo "Login first!😸";
    }
?>


<div class="cart-icon">
    <a href="cart.php"><i class="fas fa-shopping-cart"></i></a> <!-- Fixed the anchor tag -->
    <span class="cart-badge"><?php echo $row_count;?></span>
</div>
</div>
</div>
</nav>


    <!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="cartToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="fas fa-check-circle me-2"></i>
        <span id="toastMessage"></span>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

  <!-- Bootstrap Icons (Ensure you've included this in your project) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS (Ensure this is included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>