<?php
session_start();
include '../php/connection.php';
require_once 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_message = "";
    // Check if login form was submitted
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stay_signed_in = isset($_POST['stay_signed_in']);

        // Check if username exists
        $user_data = check_username_exists($username, $conn);

        if ($user_data) {
            // Username exists, now verify password
            if (verify_password($password, $user_data['password'])) {
                // Password matches, set session variables
                $_SESSION['user_id'] = $user_data['ua_id'];
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['user_priv'] = $user_data['user_priv'];  // Store user privilege level

                if ($stay_signed_in) {
                    setcookie('user_id', $user_data['ua_id'], time() + (86400 * 30), "/"); // 30 days
                    setcookie('username', $user_data['username'], time() + (86400 * 30), "/"); // 30 days
                    setcookie('user_priv', $user_data['user_priv'], time() + (86400 * 30), "/"); // 30 days
                }

                // Redirect based on user privilege
                if ($_SESSION['user_priv'] == 'a') {
                    // Redirect to the admin dashboard if user is an admin
                    header("Location: ../admin/admin.php");
                } else {
                    // Redirect to the regular user page if user is not an admin
                    header("Location: ../php/index.php");
                }
                exit();
            } else {
                $login_message = "Invalid credentials.";
            }
        } else {
            $login_message = "User not found.";
        }
    }
}

if (isset($_COOKIE['user_id']) && isset($_COOKIE['username']) && isset($_COOKIE['user_priv'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['user_priv'] = $_COOKIE['user_priv'];

    // Redirect based on user privilege
    if ($_SESSION['user_priv'] == 'a') {
        // Redirect to the admin dashboard if user is an admin
        header("Location: ../admin/admin.php");
    } else {
        // Redirect to the regular user page if user is not an admin
        header("Location: ../php/index.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - GymFit</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <div class="login-container">
        <!-- Left Side - Sign In Form -->
        <div class="login-left">
            <h2>Log in</h2>

            <!-- Display the login message here-->
            <?php if (!empty($login_message)): ?>
                <div class="alert alert-info">
                    <?php echo $login_message; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <i class="fa-solid fa-envelope position-absolute"
                        style="top: 50%; right: 15px; transform: translateY(-50%);"></i>
                </div>
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <i id="togglePassword" class="fa-solid fa-eye password-toggle"></i>
                </div>
                <div class="form-check text-start">
                    <input type="checkbox" class="form-check-input" id="stay_signed_in" name="stay_signed_in">
                    <label class="form-check-label" for="stay_signed_in">Stay signed in</label>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Log In</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="forgot_password.php" class="text-danger">Forgot Password?</a><br>
            </div>
        </div>

        <!-- Right Side - Welcome Message -->
        <div class="login-right">
            <div class="login-right-content">
                <img src="../res_img/gymfit_logo.png" alt="GymFit Logo" class="img-fluid"
                    style="width: 120px; margin-bottom: 1rem;">
                <h2 class="welcome-text">Welcome to GymFit!</h2>
                <p class="signup-link">Don't have an account?</p>
                <button class="btn btn-outline-light btn-small oval-button"
                    onclick="window.location.href='register.php'">Sign Up</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>