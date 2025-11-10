<?php
// Function to insert a notification
function insertNotification($user_id, $message) {
    global $conn;
    $created_at = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $message, $created_at);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
?>
