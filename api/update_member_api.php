<?php
// ob_start() ka use hua takay output ko temporarily rok lo
ob_start();
header("Content-Type: application/json");

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../config/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = intval($_POST['id'] ?? 0);
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $dob        = trim($_POST['dob'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $status     = trim($_POST['status'] ?? 'Active');
    $notes      = trim($_POST['notes'] ?? '');

    if ($id <= 0 || empty($first_name) || empty($last_name) || empty($dob) || empty($gender)) {
        // ob_clean ka use hai k buffer ke andar jo bhi data already store hai, usko delete (clean) kar dena
        ob_clean();
        echo json_encode(["status" => "error", "message" => "Required fields are missing."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE members SET first_name = ?, last_name = ?, dob = ?, gender = ?, status = ?, notes = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $first_name, $last_name, $dob, $gender, $status, $notes, $id);

    ob_clean();
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Member details updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update database record."]);
    }
    exit;
}
?>