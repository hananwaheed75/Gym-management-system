<?php
// api/login_api.php
header("Content-Type: application/json");
include '../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please fill all fields."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Password verify kr rhy hain
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];

            echo json_encode(["status" => "success", "message" => "Login successful!"]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Invalid username or password."]);
}
?>