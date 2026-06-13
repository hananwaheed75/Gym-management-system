<?php
// logout.php
session_start();
session_destroy();
header("Location: /gym-managment-system/login.php");
exit();
?>