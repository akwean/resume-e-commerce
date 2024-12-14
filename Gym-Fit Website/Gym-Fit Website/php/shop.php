<?php
// connection
session_start();
require_once 'connection.php'; // To establish database connection

// Check if the user_priv key is set in the session
if (isset($_SESSION['user_priv']) && $_SESSION['user_priv'] === 'a') {
  // Redirect admin users to the admin dashboard
  header("Location: ../admin/admin_page.php");
  exit();
}

if (!isset($_SESSION['username'])) {
  header('location: ../sign-in_sign-up/login.php');
  exit;
}

$message = array(); // Define an empty array for messages

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
    $stock = $row['stock'];  // Make sure $stock is assigned from the database result
} else {
    // Handle the case where no product was found
    $message[] = 'Product not found';
    $stock = 0; // Set $stock to 0 if the product is not found to prevent errors
}

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
 }
}

// Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);

    // SQL query to search for products by name or description
    $sql = "SELECT * FROM items 
            WHERE item_name LIKE '%$search_term%' 
               OR item_desc LIKE '%$search_term%'
            ORDER BY item_name ASC";
} else if (isset($_GET['category_id'])) {
    // Display products by category
    $category_id = $_GET['category_id'];
    $sql = "SELECT * FROM items WHERE category_id = $category_id";
} else {
    // Default to showing all products
    $sql = "SELECT * FROM items ORDER BY rand()";
}

$all_products = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shop</title>
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
  </head>
  <body>
    <?php include 'nav.php'; ?>

    <main>
      <div class="small-container latest-products">
        <h2 class="title">Product Selection</h2>

        <div class="row">
          <?php
          if ($all_products && mysqli_num_rows($all_products) > 0) {
              while ($row = mysqli_fetch_assoc($all_products)) {
                if ($row["stock"] <= 0) {
                  // If the stock is 0, skip this product (or display an out of stock message)
                  continue; // This will skip the rest of the loop for this product
              }
              ?>
                  <div class="col-3">
                    <form action="" method="POST">
                      <div class="product-card">
                        <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['item_name']; ?>" class="product-img" />
                        <h4 class="product-name"><?php echo $row['item_name']; ?></h4>
                        <div class="rating">
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star-o"></i>
                        </div>
                        <p class="price">₱<?php echo $row['item_price']; ?></p>
                        <input type="hidden" name="product_name" value="<?php echo $row['item_name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $row['item_price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                        <button type="submit" class="btn add-to-cart" name="add_to_cart">
                          <i class="fa fa-shopping-cart"></i>
                        </button>
                        <a class="btn add-to-cart" href="seemore.php?item_id=<?php echo $row['item_id']; ?>">
                          <i class="fa fa-eye"></i> View More
                        </a>
                      </div>
                    </form>
                  </div>
                  <?php
              }
          } else {
              echo "<p class='no-results'>No products found matching your search.</p>";
          }
          ?>
        </div>
      </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
