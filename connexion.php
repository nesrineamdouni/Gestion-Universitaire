<?php
$servername = "";
$username = "root";
$password = "";
$dbname = "inscription_universitaire";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
