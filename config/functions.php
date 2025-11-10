<?php
// Example function for form validation
function validateInput($data) {
    return htmlspecialchars(trim($data));
}
?>
<?php
require_once 'config.php';

function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    if ($result === false) {
        die("ERROR: Could not execute $sql. " . $conn->error);
    }
    return $result;
}

function sanitizeInput($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}
?>