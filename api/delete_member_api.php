<?php
// api/delete_member_api.php

// Kisi bhi qisam k background text/warning ko clear krny k liye
ob_start();
header("Content-Type: application/json");

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../config/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Authentication check
if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = intval($_POST['id'] ?? 0);

    if ($member_id <= 0) {
        ob_clean();
        echo json_encode(["status" => "error", "message" => "Invalid Member ID."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);

    ob_clean(); // Sab extra kam saaf, ab sirf JSON jaye ga
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Member deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete member from database."]);
    }
    exit;
}
?>