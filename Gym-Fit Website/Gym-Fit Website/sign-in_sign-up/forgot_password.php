<?php
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../php/connection.php';
$forgot_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email exists
    $sql = "SELECT * FROM user_account WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(50));
        
        // Save token in the database
        $sql = "UPDATE user_account SET reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $sql);

        // Create the reset link
        $reset_link = "http://localhost/withadmin/sign-in_sign-up/reset_password.php?token=" . $token;

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                             // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                        // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
            $mail->Username   = 'tiknumberone.1@gmail.com';                  // Your SMTP username (your email)
            $mail->Password   = 'xmdy bmgj dfom ayjv';                   // Your SMTP password (your email password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption
            $mail->Port       = 587;                                     // TCP port to connect to

            //Recipients
            $mail->setFrom('tiknumberone.1@gmail.com', 'GYMFit');      // Sender's email
            $mail->addAddress($email);                                  // Add recipient email

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click this link to reset your password: <a href='$reset_link'>$reset_link</a>";
            $mail->AltBody = "Click this link to reset your password: $reset_link";  // Fallback for plain text emails

            $mail->send();
            $forgot_message = 'Reset link has been sent to your email.';
        } catch (Exception $e) {
            $forgot_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $forgot_message = 'No account found with that email.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - GymFit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h2>Forgot Password</h2>
            <!-- Display the login message here-->
            <?php if (!empty($forgot_message)): ?>
                    <div class="alert alert-info">
                        <?php echo $forgot_message; ?>
                </div>
                <?php endif; ?>

            <form action="forgot_password.php" method="POST">
                <div class="mb-3 position-relative">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    <i class="fa-solid fa-envelope position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%);"></i>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php" class="text-danger">Back to Login</a><br>
            </div>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
