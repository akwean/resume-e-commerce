<?php 
session_start();
require_once 'connection.php';

// Retrieve the item_id from the URL
if (isset($_GET['trainer_id'])) {
  $trainer_id = $_GET['trainer_id'];
  
  // Query to get the product details based on the item_id
  $sql_trainer = "SELECT * FROM trainer WHERE trainer_id = ?";
  $stmt = $conn->prepare($sql_trainer);
  $stmt->bind_param("i", $trainer_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Fetch the product details
  if ($row = $result->fetch_assoc()) {
      $trainer_name = $row['trainer_name'];
      $trainer_rate = $row['trainer_rate'];
      $trainer_img = $row['trainer_img'];
      $trainer_info = $row['trainer_info']; // Assuming you have this field in the database
  }
}

include 'nav.php';
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
    <!-- External Footer CSS -->
    <link href="../css/footer.css" rel="stylesheet" />
    <!-- Add your footer CSS link here -->

    <link rel="stylesheet" href="../css/seemore.css" />
  </head>
  <body>
     
<main>
    <div class="container product-page py-5">
  <div class="row g-4">
    <!-- Product Image -->
    <div class="col-lg-6">
      <img src="<?php echo $trainer_img;?>" alt="<?php echo $trainer_name;?>" class="img-fluid rounded shadow-sm"/>
    </div>

    <!-- Product Details -->
    <div class="col-lg-6">
      <h1 class="product-title"><?php echo $trainer_name;?></h1>
      <h2 class="product-price">₱<?php echo $trainer_rate;?></h2>

        <!-- Buttons -->
        <div class="d-flex gap-3">
          <a type="button" class="btn btn-buy-now flex-grow-1" href="#">
            <i class="bi bi-facebook"></i> Contact Now
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
        </ul>
        <div class="tab-content mt-3" id="productTabContent">
          <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
            <p><?php echo $trainer_info;?></p>
          </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>