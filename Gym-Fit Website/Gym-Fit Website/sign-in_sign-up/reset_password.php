<?php
include '../php/connection.php';
require_once 'function.php';
$reset_message = "";

// Check if the token exists in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];  // Retrieve the token from the URL
} else {
    // Token is missing
    die("Invalid request. Token not provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['token']) && !empty($_POST['token'])) {
        $token = $_POST['token'];  // Retrieve the token from the POST request
        $new_password = $_POST['new_password'];  // Get the plain new password

        // Check if the new password is strong
        if (!is_strong_password($new_password)) {
            $reset_message = "Error: Password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, one digit, and one special character.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Check if the token is valid
            $sql = "SELECT * FROM user_account WHERE reset_token='$token'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                // Update the password
                $sql = "UPDATE user_account SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
                if (mysqli_query($conn, $sql)) {
                    $reset_message = "Password updated successfully!";
                    echo '<script>
                        setTimeout(function(){
                            window.location.href = "login.php";
                        }, 3000);
                    </script>';
                } else {
                    $reset_message = "Error updating password.";
                }
            } else {
                $reset_message = "Invalid or expired token.";
            }
        }
    } else {
        $reset_message = "Token is missing in the form.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GymFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h2>Reset Password</h2>

            <!-- Display the reset message here -->
            <?php if (!empty($reset_message)): ?>
                <div class="alert alert-info">
                    <?php echo $reset_message; ?>
                </div>
            <?php endif; ?>

            <form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
                <!-- Include the token as a hidden input field -->
                <input type="hidden" name="token" value="<?php echo $token;  ?>">
                
                <!-- New Password Field with Toggle Icon -->
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                    <i class="fa-solid fa-eye position-absolute toggle-password" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                </div>

                <!-- Confirm Password Field with Toggle Icon -->
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fa-solid fa-eye position-absolute toggle-password" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="text-danger">Back to Login</a><br>
            </div>
        </div>
    </div>

    <!-- JavaScript for password show/hide toggle -->
    <script>
    // Toggle password visibility for the password fields
    const passwordInputs = document.querySelectorAll('.form-control[type="password"]');
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach((icon, index) => {
        icon.addEventListener('click', () => {
            const passwordField = passwordInputs[index];
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            icon.classList.toggle('fa-eye', type === 'password');
            icon.classList.toggle('fa-eye-slash', type === 'text');
        });
    });
</script>


    
    

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

