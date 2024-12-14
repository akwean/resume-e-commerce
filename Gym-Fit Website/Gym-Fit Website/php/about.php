<?php
//connection
session_start();
require_once 'connection.php';
?>


<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
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
    <link href="footer.css" rel="stylesheet" />
    <!-- Add your footer CSS link here -->

    <link rel="stylesheet" href="../css/about.css" />
  </head>

  <body>
   
<?php include_once 'nav.php';?>

<main>
<!--carousel about us-->
    <div
      id="aboutUsCarousel"
      class="carousel slide my-4"
      data-bs-ride="carousel"
      data-bs-interval="3000"
    >
      <!-- Slides -->
      <div class="carousel-inner">
        <!-- Slide 1: About GymFit -->
        <div class="carousel-item active">
          <div
            class="container d-flex justify-content-between align-items-center background"
          >
            <div class="text-content">
              <h1 class="text-danger title mb-3">About GymFit</h1>
              <p class="description">Empowering Your Fitness Journey</p>
              <p class="d-info">
                At GymFit, we believe in making fitness accessible to everyone.
                Our goal is to provide the best gym products, accessories, and
                personalized coaching services to help you reach your fitness
                goals.
              </p>
              <a href="shop.php" class="btn btn-danger btn-lg">Shop Now</a>
              <!-- Button -->
            </div>
            <img
              src="../res_img/about_us_1.png"
              class="d-block s-image"
              alt="GymFit Overview"
            />
          </div>
        </div>

        <!-- Slide 2: Our Mission -->
        <div class="carousel-item">
          <div
            class="container d-flex justify-content-between align-items-center background"
          >
            <div class="text-content">
              <h1 class="text-danger title mb-3">Our Mission</h1>
              <p class="description">Committed to Excellence</p>
              <p class="d-info">
                Our mission is to help individuals of all levels improve their
                health, fitness, and overall well-being through high-quality
                products and expert coaching.
              </p>
              <a href="shop.php" class="btn btn-danger btn-lg">Shop Now</a>
              <!-- Button -->
            </div>
            <img
              src="../res_img/about_us_2.png"
              class="d-block s-image"
              alt="Mission Image"
            />
          </div>
        </div>

        <!-- Slide 3: Our Values -->
        <div class="carousel-item">
          <div
            class="container d-flex justify-content-between align-items-center background"
          >
            <div class="text-content">
              <h1 class="text-danger title mb-3">Our Values</h1>
              <p class="description">Integrity, Quality, and Support</p>
              <p class="d-info">
                GymFit is built on values of integrity, quality, and
                unparalleled customer support. We aim to be your trusted partner
                in every step of your fitness journey.
              </p>
              <a href="contact.php" class="btn btn-danger btn-lg">Contact Us</a>
              <!-- Button -->
            </div>
            <img
              src="../res_img/about_us_3.png"
              class="d-block s-image"
              alt="Our Values"
            />
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#aboutUsCarousel"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#aboutUsCarousel"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
</main>

    <?php include_once 'footer.php';?>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  </body>
</html>
