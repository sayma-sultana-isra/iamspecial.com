<?php
session_start();
include_once '../../config/config.php';
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to provide feedback.";
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT session_id, session_date, session_type, duration_minutes, notes FROM therapy_sessions WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: bold;
        }

        select, input, textarea {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #dcdde1;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f8f9fa;
        }

        select {
            cursor: pointer;
        }

        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            text-align: center;
            color: #7f8c8d;
            font-size: 16px;
        }
        input[type="number"] {
            width: 100px;
        }
        input[type="number"]::-webkit-inner-spin-button, 
        input[type="number"]::-webkit-outer-spin-button { 
            opacity: 1;
            height: 30px;
        }
        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            color: #27ae60;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Provide Feedback</h1>
        <?php if ($result->num_rows > 0): ?>
            <form action="submit_therapy_feedback.php" method="POST">
                <div class="form-group">
                    <label for="session_id">Select Session:</label>
                    <select id="session_id" name="session_id" required>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?php echo $row['session_id']; ?>">
                                <?php echo "Session on " . htmlspecialchars($row['session_date']) . " - " . htmlspecialchars($row['session_type']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="feedback_score">Feedback Score (1-5):</label>
                    <input type="number" id="feedback_score" name="feedback_score" min="1" max="5" required>
                </div>

                <div class="form-group">
                    <label for="comments">Comments:</label>
                    <textarea id="comments" name="comments" placeholder="Please share your thoughts about the session..."></textarea>
                </div>

                <button type="submit">Submit Feedback</button>
            </form>
        <?php else: ?>
            <p>No therapy sessions available for feedback.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>