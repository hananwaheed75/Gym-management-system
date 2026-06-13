<?php
// api/add_member_api.php
header("Content-Type: application/json");
include '../config/db.php';
include '../config/auth.php';

// Ensure user is authorized before performing action
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}
// trim use hua takay string ky agay or peechy ka extra space khtm ho jae 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $dob        = trim($_POST['dob'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $notes      = trim($_POST['notes'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($dob) || empty($gender)) {
        echo json_encode(["status" => "error", "message" => "Required fields are missing."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO members (first_name, last_name, dob, gender, status, notes) VALUES (?, ?, ?, ?, 'Active', ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $dob, $gender, $notes);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Member added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add member."]);
    }
}
?>