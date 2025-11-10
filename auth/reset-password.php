<?php
require_once '../config/config.php';
require_once '../config/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);

    // Normally, you would generate a reset token and email it to the user
    // Here, we will just simulate a password reset for simplicity
    $newPassword = 'newpassword'; // This should be a randomly generated password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $sql = "UPDATE User SET password = '$hashedPassword' WHERE email = '$email'";
    executeQuery($sql);

    echo "Password has been reset. New password: $newPassword";
    // In a real application, never display the password; send it via email instead
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="reset-password">
        <form action="reset-password.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>