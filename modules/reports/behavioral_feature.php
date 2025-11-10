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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $behavior_type_id = $_POST['behavior_type_id']; // Assuming a dropdown for behavior types
    $occurrence_time = $_POST['occurrence_time'];
    $notes = $_POST['notes'];
    $intensity = $_POST['intensity']; // Intensity (0-5)

    // Insert behavior log into the database
    $stmt = $conn->prepare("INSERT INTO behavioral_logs (user_id, behavior_type_id, occurrence_time, notes, intensity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $user_id, $behavior_type_id, $occurrence_time, $notes, $intensity);

    if ($stmt->execute()) {
        $message = "Behavior logged successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Query for summary table
$summary_query = "
    SELECT 
        bt.type_name AS behavior_type,
        COUNT(bl.id) AS behavior_count,
        AVG(bl.intensity) AS average_intensity
    FROM 
        behavioral_logs AS bl
    JOIN 
        behavior_types AS bt ON bl.behavior_type_id = bt.id
    WHERE 
        bl.user_id = ? 
        AND bl.occurrence_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY 
        bl.behavior_type_id
    ORDER BY 
        behavior_count DESC;
";
$summary_stmt = $conn->prepare($summary_query);
$summary_stmt->bind_param("i", $user_id);
$summary_stmt->execute();
$summary_result = $summary_stmt->get_result();

// Query for log details
$details_query = "
    SELECT 
        bt.type_name AS behavior_type,
        bl.occurrence_time,
        bl.notes,
        bl.intensity,
        DATEDIFF(NOW(), bl.occurrence_time) AS days_ago
    FROM 
        behavioral_logs AS bl
    JOIN 
        behavior_types AS bt ON bl.behavior_type_id = bt.id
    WHERE 
        bl.user_id = ? 
        AND bl.occurrence_time >= DATE_SUB(NOW(), INTERVAL 30 DAY);
";
$details_stmt = $conn->prepare($details_query);
$details_stmt->bind_param("i", $user_id);
$details_stmt->execute();
$details_result = $details_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behavioral Log Feature</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        label, select, input, textarea, button {
            width: 100%;
            margin: 5px 0;
            padding: 10px;
        }
        table {
            width: 100%;
            margin: 20px 0;
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
        .success-message {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Behavioral Log Feature</h1>

    <!-- Display success/error message -->
    <?php if (!empty($message)) : ?>
        <p class="success-message"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form to log behavior -->
    <div class="form-container">
        <form method="POST" action="">
            <label for="behavior_type_id">Behavior Type:</label>
            <select name="behavior_type_id" id="behavior_type_id" required>
                <option value="">Select Behavior Type</option>
                <?php
                // Fetch behavior types dynamically
                $type_query = "SELECT id, type_name FROM behavior_types";
                $type_result = $conn->query($type_query);
                while ($row = $type_result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['type_name']) . "</option>";
                }
                ?>
            </select>

            <label for="occurrence_time">Occurrence Time:</label>
            <input type="datetime-local" name="occurrence_time" id="occurrence_time" required>

            <label for="notes">Notes (optional):</label>
            <textarea name="notes" id="notes" rows="4" placeholder="Details about the behavior"></textarea>

            <label for="intensity">Intensity (0-5):</label>
            <input type="number" name="intensity" id="intensity" min="0" max="5" required>

            <button type="submit">Log Behavior</button>
        </form>
    </div>

    <!-- Summary Table -->
    <h2>Summary of Behaviors (Last 30 Days)</h2>
    <?php if ($summary_result->num_rows > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Behavior Type</th>
                    <th>Count</th>
                    <th>Average Intensity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $summary_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['behavior_type']); ?></td>
                        <td><?= htmlspecialchars($row['behavior_count']); ?></td>
                        <td><?= htmlspecialchars(number_format($row['average_intensity'], 1)); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="text-align: center;">No behaviors logged in the last 30 days.</p>
    <?php endif; ?>

    <!-- Log Details Table -->
    <h2>Log Details (Last 30 Days)</h2>
    <?php if ($details_result->num_rows > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Behavior Type</th>
                    <th>Occurrence Time</th>
                    <th>Notes</th>
                    <th>Intensity</th>
                    <th>Days Ago</th> <!-- Days Ago Column -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $details_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['behavior_type']); ?></td>
                        <td><?= htmlspecialchars($row['occurrence_time']); ?></td>
                        <td><?= htmlspecialchars($row['notes']); ?></td>
                        <td><?= htmlspecialchars($row['intensity']); ?></td>
                        <td><?= htmlspecialchars($row['days_ago']); ?> days ago</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p style="text-align: center;">No detailed logs available.</p>
    <?php endif; ?>
</body>
</html>
