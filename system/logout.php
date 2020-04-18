<?php
session_start();

unset($_SESSION['user_id']);
 
// Redirect to index.php page
header("Location: ../index.php");
?>