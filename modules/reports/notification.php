<?php
// Include the database connection
include_once '../../config/config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Mark as Read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $message = "Notifications marked as read.";
}

// Fetch Notifications for the Logged-in User
$notif_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$notif_stmt = $conn->prepare($notif_query);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result();
$unread_count = 0;
// Fetch notifications for the logged-in user
$user_id = $_SESSION['user_id'];
$fetch_notifications_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($fetch_notifications_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications_result = $stmt->get_result();

// Display notifications
while ($notification = $notifications_result->fetch_assoc()) {
    echo "<p>{$notification['message']}</p>";
}

// Count unread notifications
foreach ($notifications as $notification) {
    if ($notification['status'] === 'unread') {
        $unread_count++;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f6f8;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .notification-icon {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .notification-icon button {
            position: relative;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .notification-icon button span {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
        }
        .notification-list {
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
        }
        .notification-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .notification-list li {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .notification-list li:last-child {
            border-bottom: none;
        }
        .notification-list li p {
            margin: 0;
        }
        .mark-read-btn {
            display: block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notifications</h1>

        <!-- Notification Icon with Count -->
        <div class="notification-icon">
            <h2>Your Notifications</h2>
            <button>
                🛎️ Notifications
                <?php if ($unread_count > 0): ?>
                    <span><?= $unread_count; ?></span>
                <?php endif; ?>
            </button>
        </div>

        <!-- Notification List -->
        <div class="notification-list">
            <ul>
                <?php if ($notifications->num_rows > 0): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <li>
                            <p><?= htmlspecialchars($notification['message']); ?></p>
                            <small><?= htmlspecialchars($notification['created_at']); ?></small>
                            <?php if ($notification['status'] === 'unread'): ?>
                                <span style="color: red;">(Unread)</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No notifications available.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Mark All as Read Button -->
        <form method="POST">
            <button type="submit" name="mark_as_read" class="mark-read-btn">Mark All as Read</button>
        </form>

        <?php if (!empty($message)): ?>
            <p style="text-align: center; color: green;"><?= $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
