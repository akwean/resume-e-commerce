<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "gym-fit_db";



$conn = new mysqli($servername, $username, $password, $database);
?>