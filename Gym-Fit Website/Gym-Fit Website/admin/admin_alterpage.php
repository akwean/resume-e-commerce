<?php
session_start();
include '../php/connection.php';

// Check if the user is an admin
if ($_SESSION['user_priv'] !== 'a') {
    header("Location: ../sign-in_sign-up/login.php");
    exit();
}

include 'admin_navbar.php';

$manage_product_message = "";

// Delete Product Logic
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM items WHERE item_id = $delete_id";

    if ($conn->query($sql_delete) === TRUE) {
        $manage_product_message = "Product deleted successfully.";
    } else {
        $manage_product_message = "Error deleting product: " . $conn->error;
    }
}

// Update Product Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['item_name'];
    $description = $_POST['item_desc'];
    $price = $_POST['item_price'];
    $stock = $_POST['stock'];
    $category = $_POST['category_id'];
    $image_path = '';  // Default to empty string if no image is uploaded

    // Handle image upload
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/products/';  // Directory to store images
        $image_name = basename($_FILES['item_image']['name']);
        $target_path = $upload_dir . $image_name;

        // Move the uploaded file to the designated folder
        if (move_uploaded_file($_FILES['item_image']['tmp_name'], $target_path)) {
            $image_path = $target_path;  // Store the image path
        } else {
            $manage_product_message = "Error uploading image.";
        }
    }

    // If there's no new image, keep the current image
    if (empty($image_path)) {
        // Get the current image if no new image is uploaded
        $sql_select = "SELECT product_image FROM items WHERE item_id = $product_id";
        $result_select = $conn->query($sql_select);
        if ($result_select->num_rows > 0) {
            $row = $result_select->fetch_assoc();
            $image_path = $row['product_image']; // Retain the existing image path
        }
    }

    // Update the product with the new image (if uploaded) or existing image
    $sql_update = "UPDATE items SET item_name = '$product_name', item_desc = '$description', 
                   item_price = '$price', stock = '$stock', category_id = '$category', 
                   product_image = '$image_path' WHERE item_id = $product_id";

    if ($conn->query($sql_update) === TRUE) {
        $manage_product_message = "Product updated successfully.";
    } else {
        $manage_product_message = "Error updating product: " . $conn->error;
    }
}

// Fetch all products from the database
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - GymFit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>

<div class="container">
    <h2 class="text-center mt-5">Manage Products</h2>

    <?php if (!empty($manage_product_message)): ?>
        <div class="alert alert-info" id="message">
            <?php echo $manage_product_message; ?>
        </div>
    <?php endif; ?>

    <div class="product-list">
        <h3>All Products</h3>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['item_id'] . "</td>
                                <td>" . $row['item_name'] . "</td>
                                <td>" . $row['item_desc'] . "</td>
                                <td>" . $row['item_price'] . "</td>
                                <td>" . $row['stock'] . "</td>
                                <td>" . getCategoryName($row['category_id'], $conn) . "</td>
                                <td><img src='" . $row['product_image'] . "' alt='Product Image' width='100'></td>
                                <td>
                                    <a href='#' data-bs-toggle='modal' data-bs-target='#editProductModal' data-id='" . $row['item_id'] . "' class='btn btn-warning btn-sm edit-product-btn'>Edit</a>
                                    <a href='?delete_id=" . $row['item_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_manage_products.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="item_name" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="item_desc" required></textarea>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="price">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="item_price" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option value="1">Accessories</option>
                                <option value="2">Equipment</option>
                                <option value="3">Supplements</option>
                                <option value="4">Attire</option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="item_image">Product Image</label>
                            <input type="file" class="form-control" id="item_image" name="item_image" accept="image/*">
                        </div>
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="mb-3">
                            <button type="submit" name="update_product" class="btn btn-success w-100">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const editButtons = document.querySelectorAll('.edit-product-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const productName = row.querySelector('td:nth-child(2)').textContent;
            const description = row.querySelector('td:nth-child(3)').textContent;
            const price = row.querySelector('td:nth-child(4)').textContent;
            const stock = row.querySelector('td:nth-child(5)').textContent;
            const category = row.querySelector('td:nth-child(6)').textContent;

            document.getElementById('product_id').value = productId;
            document.getElementById('product_name').value = productName;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('stock').value = stock;
            document.getElementById('category').value = getCategoryValue(category);
        });
    });

    function getCategoryValue(categoryName) {
        switch (categoryName) {
            case 'Accessories':
                return 1;
            case 'Equipment':
                return 2;
            case 'Supplements':
                return 3;
            case 'Attire':
                return 4;
            default:
                return 1;
        }
    }
</script>

</body>
</html>

<?php
function getCategoryName($category_id, $conn) {
    $sql = "SELECT category_title FROM category WHERE category_id = $category_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['category_title'];
    }

    return 'Unknown';
}
?>
