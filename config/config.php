<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'autism_support_system');

/* Attempt to connect to MySQL database */
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}


// Site settings
define('SITE_URL', 'http://localhost/autism-support/');
define('SITE_NAME', 'Autism Support System');
// In config.php
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/autism-support/');

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to redirect user
function redirect($path) {
    header("Location: " . SITE_URL . $path);
    exit();
}

// Function to sanitize input
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Function to show alert message
function showAlert($message, $type = 'success') {
    return "<div class='alert alert-{$type}'>{$message}</div>";
}
?>