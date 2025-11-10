<?php
require_once '../config/config.php';
require_once '../config/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = sanitizeInput($_GET['token']);

    // Verify the token (this is a simplified example)
    $sql = "SELECT * FROM User WHERE verification_token = '$token'";
    $result = executeQuery($sql);
    $user = $result->fetch_assoc();

    if ($user) {
        $sql = "UPDATE User SET verified = 1 WHERE user_id = " . $user['user_id'];
        executeQuery($sql);
        echo "Email verified successfully!";
    } else {
        echo "Invalid verification token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="verify">
        <h2>Email Verification</h2>
        <p>Please check your email for the verification link.</p>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>