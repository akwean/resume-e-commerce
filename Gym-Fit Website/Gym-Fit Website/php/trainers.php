<?php
session_start();
//connection
require_once 'connection.php';

$sql = "SELECT * FROM trainer ORDER BY rand()";
$all_trainers = $conn -> query($sql);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trainers</title>
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
  <h2 class="title">Trainers Selection</h2>

  <div class="row">

   <!--All products connected in the database-->
   <?php
  while($row = mysqli_fetch_assoc($all_trainers)){
  ?>
  
    <div class="col-3">
      <div class="product-card">
        <img src="<?php echo $row["trainer_img"];?>" alt="<?php echo $row["trainer_name"];?>" class="product-img" />
        <h4 class="product-name"><?php echo $row["trainer_name"];?></h4>
        <div class="rating">
          <i class="fa fa-star"></i>
          <i class="fa fa-star"></i>
          <i class="fa fa-star"></i>
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </div>
        <p class="price">₱<?php echo $row["trainer_rate"]?></p>

        <a class="btn add-to-cart" href="trainer_details.php? trainer_id=<?php echo $row["trainer_id"]?>">
               <i class="fa fa-eye"></i>
        </a>
        <a class="btn add-to-cart" href="#">
                <i class="bi bi-facebook"></i> Contact Now 
        </a>
      </div>
    </div>
    <?php }?>
  </div>
</div>
  </main>

  <?php include_once 'footer.php';?>
     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
