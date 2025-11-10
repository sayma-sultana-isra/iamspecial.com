<?php
// Include the database connection
session_start();
include_once '../../config/config.php';

$userId = $_SESSION['user_id'];// Hardcoded for single-user system; replace with `$_SESSION['user_id']` if using login

// Handle behavior logging
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $behavior_type = $_POST['behavior_type'];
    $occurrence_time = $_POST['occurrence_time'];
    $notes = $_POST['notes'];

    // Insert the behavior log into the database
    $stmt = $conn->prepare("INSERT INTO behavioral_log (user_id, behavior_type, occurrence_time, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $behavior_type, $occurrence_time, $notes);

    if ($stmt->execute()) {
        $message = "Behavior logged successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Fetch behavioral logs for the user
$query = "SELECT * FROM behavioral_log WHERE user_id = $user_id ORDER BY occurrence_time DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behavioral Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Behavioral Log</h1>

    <!-- Display success/error message -->
    <?php if (!empty($message)) { echo "<p style='color: green; text-align: center;'>$message</p>"; } ?>

    <!-- Form to log behavior -->
    <form method="POST" action="behavioral_feature.php">
        <label for="behavior_type">Behavior Type:</label>
        <input type="text" name="behavior_type" id="behavior_type" placeholder="e.g., Aggression, Eye Contact" required>

        <label for="occurrence_time">Occurrence Time:</label>
        <input type="datetime-local" name="occurrence_time" id="occurrence_time" required>

        <label for="notes">Notes (optional):</label>
        <textarea name="notes" id="notes" rows="4" placeholder="Details about the behavior"></textarea>

        <button type="submit">Log Behavior</button>
    </form>

    <h2>Logged Behaviors</h2>

    <!-- Display logged behaviors -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Behavior Type</th>
                    <th>Occurrence Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['behavior_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['occurrence_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No behaviors logged yet. Use the form above to add a log.</p>
    <?php endif; ?>
</body>
</html>
