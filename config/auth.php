<?php
// config/auth.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// check_auth is lia lgaya takay check kr sky k user login hai ya nhi
function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /gym-managment-system/login.php");
        exit();
    }
}
?>