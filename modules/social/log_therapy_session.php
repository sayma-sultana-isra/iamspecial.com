[01:18, 1/27/2025] Sayem: <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Therapy Session</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        input, textarea, button {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .feedback-button,
        .analysis-button {
            position: absolute;
            top: 8px;
            background-color: #e74c3c;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .feedback-button {
            right: 110px;
        }

        .analysis-button {
            right: 10px;
        }

        .feedback-button:hover,
        .analysis-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Log Therapy Session</h1>
        <form action="log_therapy_session.php" method="POST">
            <label for="therapist_name">Therapist Name:</label>
            <input type="text" id="therapist_name" name="therapist_name" required>

            <label for="session_date">Session Date:</label>
            <input type="datetime-local" id="session_date" name="session_date" required>

            <label for="session_type">Session Type:</label>
            <input type="text" id="session_type" name="session_type" required>

            <label for="duration_minutes">Duration (minutes):</label>
            <input type="number" id="duration_minutes" name="duration_minutes" required>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea>

            <button type="submit">Log Session</button>
        </form>
        <a href="feedback.php" class="feedback-button">Feedback</a>
        <a href="analysis.php" class="analysis-button">Analysis</a>
    </div>
</body>
</html> logtherapy.php
[01:19, 1/27/2025] Sayem: <?php
session_start();
include_once '../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to log a therapy session.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $therapist_name = $_POST['therapist_name'];
    $session_date = $_POST['session_date'];
    $session_type = $_POST['session_type'];
    $duration_minutes = $_POST['duration_minutes'];
    $notes = $_POST['notes'];

    // Insert therapist name into therapists table and get the therapist_id
    $stmt = $conn->prepare("INSERT INTO therapists (therapist_name) VALUES (?)");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }

    if (!$stmt->bind_param("s", $therapist_name)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    $therapist_id = $stmt->insert_id;
    $stmt->close();

    // Insert session data into therapy_sessions table
    $stmt = $conn->prepare("INSERT INTO therapy_sessions (user_id, therapist_id, session_date, session_type, duration_minutes, notes) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }

    if (!$stmt->bind_param("iissis", $user_id, $therapist_id, $session_date, $session_type, $duration_minutes, $notes)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    if ($stmt->execute()) {
        echo "Therapy session added successfully.";
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>