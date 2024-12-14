<?php
session_start();

include '../php/connection.php';

if ($_SESSION['user_priv'] !== 'a') {
    // Redirect non-admin users to another page
    header("Location: ../sign-in_sign-up/login.php");
    exit();
}

// Include the navigation bar
include 'admin_navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product or Trainer - GymFit</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../css/styles.css" />

    <style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #f4f7fc;
        height: 100vh;
        margin: 0;
      }

      /* Main Container */
      .add-product-container, .add-trainer-container {
        max-width: 550px;
        margin: 0 auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        height: auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-bottom: 30px;
      }

      /* Title Styling */
      h2 {
        text-align: center;
        color: #ff5733;
        font-weight: 600;
        margin-bottom: 25px;
      }

      /* Success/Error Message */
      .alert {
        margin-bottom: 20px;
      }

      .alert-info {
        background-color: #f9f9f9;
        border-left: 5px solid #ff5733;
        padding-left: 15px;
        color: #333;
      }

      /* Form Styling */
      .form-control {
        border-radius: 8px;
        padding: 12px;
        border: 2px solid #ff5733;
        margin-bottom: 20px;
        transition: all 0.3s ease;
      }

      /* Input Focus Effect */
      .form-control:focus {
        border-color: #ff5733;
        box-shadow: 0 0 5px rgba(255, 87, 51, 0.5);
      }

      .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
      }

      /* Add Product Button (Styled Like 'Proceed to Checkout') */
      .submit-btn {
        background-color: #ff4d4d; /* GymFit red */
        color: #fff;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 5px;
        text-transform: uppercase;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.3); /* Subtle shadow */
        transition: background-color 0.3s ease, transform 0.2s ease,
          box-shadow 0.2s ease;
        width: 100%;
      }

      /* Button Hover Effect */
      .submit-btn:hover {
        background-color: #cc0000; /* Darker red */
        transform: scale(1.05); /* Slight enlarge effect */
        box-shadow: 0 6px 12px rgba(255, 77, 77, 0.4); /* Stronger shadow */
      }

      /* Hover Effects for Inputs */
      .form-control:hover {
        border-color: #ff5733;
      }

      /* Responsive Design for Mobile */
      @media (max-width: 768px) {
        .add-product-container, .add-trainer-container {
          padding: 25px;
          margin: 20px;
          height: auto;
        }

        h2 {
          font-size: 1.5rem;
        }

        .form-control {
          font-size: 14px;
        }

        .submit-btn {
          font-size: 16px;
        }
      }

      /* Additional Styling for the Form */
      .container {
        padding-top: 30px;
      }

      /* Responsive Adjustments for Smaller Screens */
      @media (max-width: 768px) {
        .submit-btn {
          font-size: 14px;
          padding: 10px 18px;
        }
      }

      @media (max-width: 480px) {
        .submit-btn {
          font-size: 14px;
          padding: 8px 16px;
        }
      }
    </style>
    
</head>
<body>

<?php 
// Initialize the message variable
$add_product_message = "";
$add_trainer_message = "";

// Check if form is submitted for product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // Get the form data
    $product_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
    $description = isset($_POST['item_desc']) ? $_POST['item_desc'] : '';
    $price = isset($_POST['item_price']) ? $_POST['item_price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';
    $category = isset($_POST['category_id']) ? $_POST['category_id'] : '';

    // Handle the image upload
    $target_dir = "../product_img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $add_product_message = "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        $add_product_message = "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        $add_product_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    // Check if upload is allowed
    if ($upload_ok == 0) {
        $add_product_message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Prepare SQL query
            $sql = "INSERT INTO items (item_name, item_desc, item_price, stock, product_image, category_id)
                    VALUES ('$product_name', '$description', '$price', '$stock', '$target_file', '$category')";

            if ($conn->query($sql) === TRUE) {
                $add_product_message = "New product added successfully.";
            } else {
                $add_product_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $add_product_message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Check if form is submitted for trainer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_trainer'])) {
    // Get the form data
    $trainer_name = isset($_POST['trainer_name']) ? $_POST['trainer_name'] : '';
    $trainer_info = isset($_POST['trainer_info']) ? $_POST['trainer_info'] : '';
    $trainer_rate = isset($_POST['trainer_rate']) ? $_POST['trainer_rate'] : '';

    // Handle the image upload
    $target_dir = "../trainer_img/";
    $target_file = $target_dir . basename($_FILES["trainer_image"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is an actual image
    $check = getimagesize($_FILES["trainer_image"]["tmp_name"]);
    if ($check === false) {
        $add_trainer_message = "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["trainer_image"]["size"] > 2000000) {
        $add_trainer_message = "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        $add_trainer_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    // Check if upload is allowed
    if ($upload_ok == 0) {
        $add_trainer_message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["trainer_image"]["tmp_name"], $target_file)) {
            // Prepare SQL query
            $sql = "INSERT INTO trainer (trainer_name, trainer_info, trainer_rate, trainer_img)
                    VALUES ('$trainer_name', '$trainer_info', '$trainer_rate', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                $add_trainer_message = "New trainer added successfully.";
            } else {
                $add_trainer_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $add_trainer_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

    <div class="add-product-container">
        <h2>Add New Product</h2>

        <!-- Display any messages here -->
        <?php if (!empty($add_product_message)): ?>
            <div class="alert alert-info" id="message">
                <?php echo $add_product_message; ?>
            </div>
        <?php endif; ?>

        <form action="admin_addpage.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="item_name" placeholder="Product Name" required>
            </div>
            <div class="mb-3 form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="item_desc" placeholder="Description" required></textarea>
            </div>
            <div class="mb-3 form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="item_price" placeholder="Price" required>
            </div>
            <div class="mb-3 form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" required>
            </div>
            <div class="mb-3 form-group">
                <label for="category">Category</label>
                <select type="number" class="form-control" id="category" name="category_id" placeholder="Category" required>
                  <option value = "1">Accessories</option>
                  <option value = "2">Equipment</option>
                  <option value = "3">Supplements</option>
                  <option value = "4">Attire</option>
                </select>
            </div>
            <div class="mb-3 form-group">
                <label for="image">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <div class="mb-3">
                <button type="submit" name="add_product" class="submit-btn">Add Product</button>
            </div>
        </form>
    </div>

    <div class="add-trainer-container">
        <h2>Add New Trainer</h2>

        <!-- Display any messages here -->
        <?php if (!empty($add_trainer_message)): ?>
            <div class="alert alert-info" id="message">
                <?php echo $add_trainer_message; ?>
            </div>
        <?php endif; ?>

        <form action="admin_addpage.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 form-group">
                <label for="trainer_name">Trainer Name</label>
                <input type="text" class="form-control" id="trainer_name" name="trainer_name" placeholder="Trainer Name" required>
            </div>
            <div class="mb-3 form-group">
                <label for="trainer_info">Trainer Info</label>
                <textarea class="form-control" id="trainer_info" name="trainer_info" placeholder="Trainer Info" required></textarea>
            </div>
            
            <div class="mb-3 form-group">
                <label for="trainer_image">Trainer Image</label>
                <input type="file" class="form-control" id="trainer_image" name="trainer_image" required>
            </div>

            <div class="mb-3">
                <button type="submit" name="add_trainer" class="submit-btn">Add Trainer</button>
            </div>
        </form>
    </div>

    <script>
        // Hide the message after 3 seconds
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000);
    </script>

</body>
</html>