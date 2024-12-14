<?php
function check_username_exists($username, $conn) {
    // Query to check if the username exists in the database
    $sql = "SELECT * FROM user_account WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to verify password
function verify_password($input_password, $stored_password) {
    return password_verify($input_password, $stored_password);
}

// Function to check if email already exists
function check_email_exists($email, $conn) {
    $sql = "SELECT * FROM user_account WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) > 0);  // Returns true if email exists
}

// Function to check if the password is strong
function is_strong_password($password) {
    $has_minimum_length = strlen($password) >= 8;
    $has_uppercase = preg_match('/[A-Z]/', $password);
    $has_lowercase = preg_match('/[a-z]/', $password);
    $has_digit = preg_match('/\d/', $password);
    $has_special_char = preg_match('/[\W]/', $password);  // Non-word characters (special chars)

    return $has_minimum_length && $has_uppercase && $has_lowercase && $has_digit && $has_special_char;
}

?>