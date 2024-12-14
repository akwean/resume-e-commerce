<?php
include '../php/connection.php';
session_start(); // Start the session
session_destroy();  // Destroy all sessions
header("Location: login.php");  // Redirect to login
exit();  // Ensure no further code is executed
?>