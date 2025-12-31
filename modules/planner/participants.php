<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "autism_support_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$user_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["participate"])) {
    $event_id = $_POST["event_id"];
    $status = $_POST["status"];

    if ($status == "Remove") {
        // Remove user's participation for the event
        $stmt = $conn->prepare("DELETE FROM event_participants WHERE event_id = ? AND participant_id = ?");
        $stmt->bind_param("ii", $event_id, $user_id);
    } else {
        // Insert or update user's participation status
        $stmt = $conn->prepare("
            INSERT INTO event_participants (event_id, participant_id, status) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE status = VALUES(status)
        ");
        $stmt->bind_param("iis", $event_id, $user_id, $status);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Your status has been updated.');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch events with separate counts for "Interested" and "Going" participants
$event_participants = $conn->query("
    SELECT 
        events.event_id, 
        events.title, 
        COUNT(CASE WHEN event_participants.status = 'Interested' THEN 1 END) AS interested_count,
        COUNT(CASE WHEN event_participants.status = 'Going' THEN 1 END) AS going_count,
        ep.status AS user_status
    FROM events 
    LEFT JOIN event_participants ON events.event_id = event_participants.event_id 
    LEFT JOIN event_participants ep ON ep.event_id = events.event_id AND ep.participant_id = $user_id
    GROUP BY events.event_id
    ORDER BY events.date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants Management</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f8fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        h2 { text-align: center; color: #444; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 15px; background: #f9f9f9; padding: 10px; border-left: 5px solid #007bff; border-radius: 5px; }
        form { display: inline-block; margin-left: 10px; }
        select { padding: 5px; margin-left: 5px; }
        button { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Event Participation</h2>
        <ul>
            <?php while ($event = $event_participants->fetch_assoc()) { 
                $user_status = $event['user_status']; ?>
                <li>
                    <strong><?php echo $event['title']; ?></strong><br>
                    - Interested: <?php echo $event['interested_count']; ?><br>
                    - Going: <?php echo $event['going_count']; ?>
                    <form method="POST">
                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                        <label for="status">Your Status:</label>
                        <select name="status" id="status">
                            <?php if ($user_status == "Interested") { ?>
                                <option value="Interested" selected>Interested</option>
                                <option value="Going">Going</option>
                                <option value="Remove">Remove</option>
                            <?php } elseif ($user_status == "Going") { ?>
                                <option value="Going" selected>Going</option>
                                <option value="Interested">Interested</option>
                                <option value="Remove">Remove</option>
                            <?php } else { ?>
                                <option value="Interested">Interested</option>
                                <option value="Going">Going</option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="participate">Submit</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
