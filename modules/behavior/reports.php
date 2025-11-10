<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include_once '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Behavior Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container">
        <h1>Behavior Reports</h1>
        <p>This is the Behavior Reports page.</p>
        <!-- Add behavior reports content here -->
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>