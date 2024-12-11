<?php
require_once 'connection.php'; // Ensure config.php is included to start the session

session_destroy();  // Destroy all sessions
header("Location: login.php");  // Redirect to login
exit();  // Ensure no further code is executed
?>