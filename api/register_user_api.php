<?php
header("Content-Type: application/json");
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $name     = trim($_POST['name'] ?? '');

    if (empty($username) || empty($password) || empty($name)) {
        echo json_encode(["status" => "error", "message" => "Please fill all fields!"]);
        exit;
    }

    // Check if username already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already taken!"]);
        exit;
    }

    // Password ko secure hash mai convert kr rhy hain (Bcrypt)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $name);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Account registered successfully! Now you can login."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed. DB Error."]);
    }
}
?>