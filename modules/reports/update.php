<?php
include 'db.php'; // Include the database connection

$current_user_id = 1; // Replace with the logged-in user ID
$chat_partner_id = 2; // Replace with the chat partner ID

// Fetch messages between the two users
$query = $conn->prepare("
    SELECT sender_id, receiver_id, message, timestamp 
    FROM Messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY timestamp ASC
");
$query->bind_param("iiii", $current_user_id, $chat_partner_id, $chat_partner_id, $current_user_id);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin: 10px 0;
        }
        .sent {
            text-align: right;
            color: #007BFF;
        }
        .received {
            text-align: left;
            color: #333;
        }
        .timestamp {
            font-size: 0.8em;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Chat with User <?= $chat_partner_id; ?></h2>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="message <?= $row['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                <p><?= htmlspecialchars($row['message']); ?></p>
                <span class="timestamp"><?= $row['timestamp']; ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_message = $_POST['message'];
    $insertQuery = $conn->prepare("INSERT INTO Messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $insertQuery->bind_param("iis", $current_user_id, $chat_partner_id, $new_message);
    if ($insertQuery->execute()) {
        header("Location: chat.php"); // Refresh the page to display the new message
        exit();
    } else {
        echo "Error: " . $insertQuery->error;
    }
}
?>
<form method="POST">
    <textarea name="message" placeholder="Type your message here..." required></textarea>
    <button type="submit">Send</button>
</form>

