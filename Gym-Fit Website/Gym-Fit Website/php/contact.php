<?php session_start();?>

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
    <!-- Contact Carousel -->
<div
id="contactCarousel"
class="carousel slide my-4"
data-bs-ride="carousel"
data-bs-interval="3000"
>
<!-- Slides -->
<div class="carousel-inner">
  <!-- Slide 1: Address -->
  <div class="carousel-item active">
    <div
      class="container d-flex justify-content-between align-items-center background"
    >
      <div class="text-content">
        <h1 class="text-danger title mb-3">Our Address</h1>
        <p class="description">Visit Us at Our Location</p>
        <p class="d-info">
          <strong>Main Office:</strong> Bicol University Polangui Campus<br />
          <strong>Operating Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM
        </p>
      </div>
      <img
        src="../res_img/contact_1.png"
        class="d-block s-image"
        alt="Address Icon"
      />
    </div>
  </div>

  <!-- Slide 2: Phone -->
  <div class="carousel-item">
    <div
      class="container d-flex justify-content-between align-items-center background"
    >
      <div class="text-content">
        <h1 class="text-danger title mb-3">Call Us</h1>
        <p class="description">We’re Always Here to Assist</p>
        <p class="d-info">
          <strong>Phone Number:</strong> +639-123-4567<br />
          <strong>Support Hours:</strong> 24/7 Customer Support
        </p>
      </div>
      <img
        src="../res_img/contact_2.png"
        class="d-block s-image"
        alt="Phone Icon"
      />
    </div>
  </div>

  <!-- Slide 3: Email -->
  <div class="carousel-item">
    <div
      class="container d-flex justify-content-between align-items-center background"
    >
      <div class="text-content">
        <h1 class="text-danger title mb-3">Email Us</h1>
        <p class="description">Reach Out Anytime</p>
        <p class="d-info">
          <strong>Email Address:</strong> gymfit@gmail.com<br />
          <strong>Response Time:</strong> Within 24 hours
        </p>
      </div>
      <img
        src="../res_img/contact_3.png"
        class="d-block s-image"
        alt="Email Icon"
      />
    </div>
  </div>

<!-- Slide 4: Contact Form -->
<div class="carousel-item">
    <div
      class="container d-flex justify-content-center align-items-center background"
      style="min-height: 500px; padding: 20px;"
    >
      <div
        class="text-content bg-white shadow-lg p-5 rounded-4"
        style="max-width: 600px; width: 100%;"
      >
        <h1 class="text-danger title mb-4 text-center">Contact Us</h1>
        <p class="text-muted text-center mb-4">
          We’re here to help! Have questions or feedback? Fill out the form, and our team will get back to you shortly.
        </p>
        <form action="contact_process.php" method="POST">
          <!-- Name Field -->
          <div class="form-floating mb-4">
            <input
              type="text"
              class="form-control border-0 shadow-sm rounded-pill"
              id="name"
              name="name"
              placeholder="Your Name"
              required
            />
            <label for="name" class="text-muted ps-4">Your Name</label>
          </div>

          <!-- Email Field -->
          <div class="form-floating mb-4">
            <input
              type="email"
              class="form-control border-0 shadow-sm rounded-pill"
              id="email"
              name="email"
              placeholder="Your Email"
              required
            />
            <label for="email" class="text-muted ps-4">Your Email</label>
          </div>

          <!-- Message Field -->
          <div class="form-floating mb-4">
            <textarea
              class="form-control border-0 shadow-sm rounded-3"
              id="message"
              name="message"
              style="height: 150px;"
              placeholder="Your Message"
              required
            ></textarea>
            <label for="message" class="text-muted ps-4">Your Message</label>
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button
              type="submit"
              class="btn btn-danger px-5 py-2 rounded-pill shadow"
            >
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  

<!-- Carousel Controls -->
<button
  class="carousel-control-prev"
  type="button"
  data-bs-target="#contactCarousel"
  data-bs-slide="prev"
>
  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  <span class="visually-hidden">Previous</span>
</button>
<button
  class="carousel-control-next"
  type="button"
  data-bs-target="#contactCarousel"
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