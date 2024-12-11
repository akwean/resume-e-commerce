<?php
//connection
require_once 'functions.php';  // To include helper functions like getIPAddress()
require_once 'connection.php'; // To establish database connection
cart();
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
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
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
   
  <?php
  include_once 'nav.php';
  ?>

<main>

  <!-- Entire Product Selection -->
<div class="small-container latest-products">
  <h2 class="title">Product Selection</h2>

  <div class="row">

   <!--All products connected in the database-->
   <?php
    if(!isset($_GET['category_id'])){
      $sql = "SELECT * FROM items ORDER BY rand()";
      $all_products = $conn -> query($sql);


  while($row = mysqli_fetch_assoc($all_products)){
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

        <a class="btn add-to-cart" href="shop.php? add_to_cart=<?php echo $row["item_id"]?>">
          <i class="fa fa-shopping-cart"></i>
        </a>
        <a class="btn add-to-cart" href="seemore.php? item_id=<?php echo $row["item_id"]?>">
               <i class="fa fa-eye"></i> View More
        </a>
      </div>
    </div>
    <?php }}?>

    <!--Specific Categories-->
   <?php
    if(isset($_GET['category_id'])){
        $category_id=$_GET['category_id'];
        $sql = "SELECT * FROM items WHERE category_id=$category_id";
        $all_products = $conn -> query($sql);

  while($row = mysqli_fetch_assoc($all_products)){
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

        <a class="btn add-to-cart" href="shop.php? add_to_cart=<?php echo $row["item_id"]?>">
          <i class="fa fa-shopping-cart"></i>
        </a>
        <a class="btn add-to-cart" href="seemore.php? item_id=<?php echo $row["item_id"]?>">
               <i class="fa fa-eye"></i> View More
        </a>
      </div>
    </div>
    <?php }}?>




  </div>
</div>
  </main>

  <?php include_once 'footer.php';?>

  </body>
</html>
