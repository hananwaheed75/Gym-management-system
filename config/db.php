<?php
// config/db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gym_master";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}
?>