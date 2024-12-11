<?php 
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
      $item_desc = $row['item_desc']; // Assuming you have this field in the database
  }
}
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
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Bootstrap CDN for Carousel -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Add your footer CSS link here -->

    <link rel="stylesheet" href="../css/seemore.css" />
  </head>
  <body>
    
        <?php include_once 'nav.php';?>
     
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
        <i class="bi bi-check-circle-fill text-success"></i> Availability:
        <span class="text-success">In Stock</span>
      </p>

      <form class="mt-4">
        <!-- Size Selector 
        <div class="mb-3">
          <label for="size-select" class="form-label fw-semibold">
            <i class="bi bi-arrows-expand"></i> Select Size
          </label>
          <select id="size-select" class="form-select">
            <option value="S">Small</option>
            <option value="M">Medium</option>
            <option value="L">Large</option>
            <option value="XL">Extra Large</option>
          </select>
        </div> -->

        <!-- Quantity -->
        <div class="mb-3">
          <label for="quantity" class="form-label fw-semibold">
            <i class="bi bi-box-seam"></i> Quantity
          </label>
          <input type="number" id="quantity" class="form-control w-50" value="1" min="1"/>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-3">
          <a type="button" class="btn btn-add-to-cart flex-grow-1" href="#">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </a>
          <a type="button" class="btn btn-buy-now flex-grow-1" href="#">
            <i class="fas fa-bolt"></i> Buy Now
          </a>
        </div>
      </form>

      <!-- Product Tabs -->
      <div class="product-tabs mt-5">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
              Description
            </button>
          </li>
          <!--<li class="nav-item" role="presentation">
            <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">
              Specifications
            </button>
          </li> -->
        </ul>
        <div class="tab-content mt-3" id="productTabContent">
          <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
            <p><?php echo $item_desc;?></p>
          </div>
          <!--
          <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
            <ul>
              <li>Material: High-Grade Steel</li>
              <li>Weight Capacity: 300kg</li>
              <li>Adjustable Incline Levels</li>
              <li>Padded Seat and Backrest</li>
            </ul>
          </div>
         -->
        </div>
      </div>
    </div>
  </div>
</div>
</main>
   <?php
   include_once 'footer.php';
   ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>