<?php
session_start();
include_once '../../config/config.php';

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view analysis.";
    exit();
}

$userId = $_SESSION['user_id'];

// Function to execute a prepared statement and return the result
function executeQuery($conn, $query, $params) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param(...$params);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

// Query to analyze therapy session effectiveness
$effectivenessQuery = "
    SELECT 
        session_type, 
        AVG(feedback_score) as avg_feedback_score, 
        AVG(duration_minutes) as avg_duration 
    FROM 
        therapy_sessions 
    JOIN 
        feedback 
    ON 
        therapy_sessions.session_id = feedback.session_id 
    WHERE 
        therapy_sessions.user_id = ? 
    GROUP BY 
        session_type 
    ORDER BY 
        avg_feedback_score DESC;
";
$effectivenessResult = executeQuery($conn, $effectivenessQuery, ["i", $userId]);

// Query to analyze behavior improvement over time
$behaviorQuery = "
    SELECT 
        bt.type_name AS behavior_type_name, 
        COUNT(*) as incident_count, 
        DATE(bl.occurrence_time) as date 
    FROM 
        behavioral_logs bl
    JOIN 
        behavior_types bt ON bl.behavior_type_id = bt.id 
    WHERE 
        bl.user_id = ? 
    GROUP BY 
        bt.type_name, 
        DATE(bl.occurrence_time) 
    ORDER BY 
        date ASC;
";
$behaviorResult = executeQuery($conn, $behaviorQuery, ["i", $userId]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapy Progress Analysis</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 1000px;
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
            font-size: 28px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        h2 {
            color: #3498db;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dcdde1;
        }

        th {
            background-color: #3498db;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f1f2f6;
        }

        td {
            font-size: 14px;
        }

        p {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin: 10px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 10px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 8px 12px;
            }

            h1 {
                font-size: 24px;
            }

            h2 {
                font-size: 20px;
            }
        }

        .data-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        td:nth-child(2) {
            font-weight: bold;
            color: #2c3e50;
        }

        td:nth-child(2) {
            position: relative;
        }

        td:last-child {
            color: #7f8c8d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        tr {
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            padding: 20px;
            background-color: #fdf0ed;
            border-radius: 4px;
            margin: 10px 0;
        }

        .success-message {
            color: #27ae60;
            text-align: center;
            padding: 20px;
            background-color: #edfdf5;
            border-radius: 4px;
            margin: 10px 0;
        }

        .user-info {
            text-align: right;
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .timestamp {
            text-align: right;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 20px;
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            table {
                box-shadow: none;
            }

            th {
                background-color: #fff;
                color: #000;
                border-bottom: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Therapy Progress Analysis</h1>
        
        <h2>Therapy Session Effectiveness</h2>
        <?php if ($effectivenessResult->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Session Type</th>
                    <th>Average Feedback Score</th>
                    <th>Average Duration (minutes)</th>
                </tr>
                <?php while ($row = $effectivenessResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['session_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['avg_feedback_score']); ?></td>
                        <td><?php echo htmlspecialchars($row['avg_duration']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No data available.</p>
        <?php endif; ?>

        <h2>Behavior Improvement Over Time</h2>
        <?php if ($behaviorResult->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Behavior Type</th>
                    <th>Incident Count</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = $behaviorResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['behavior_type_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['incident_count']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No data available.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>