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

// Handle participant addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = trim($_POST['role']); // Trim input to avoid leading/trailing spaces

    if (!empty($role)) {
        // Insert participant into the database
        $stmt = $conn->prepare("INSERT INTO participant (user_id, role) VALUES (?, ?)");
        if (!$stmt) {
            die("Error preparing insert statement: " . $conn->error);
        }
        $stmt->bind_param("is", $user_id, $role);

        if ($stmt->execute()) {
            $message = "Participant added successfully!";
        } else {
            $message = "Error adding participant: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Role cannot be empty.";
    }
}

// Fetch user's participation details
$query = "
    SELECT u.username, p.role
    FROM participant p
    JOIN user u ON p.user_id = u.user_id
    WHERE p.user_id = ?
    ORDER BY p.role ASC";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch aggregate participation stats
$stats_query = "
    SELECT 
        COUNT(*) AS total_participants,
        COUNT(DISTINCT user_id) AS unique_users
    FROM participant";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Query to find the most active participant
$most_active_query = "
    SELECT u.user_id, u.username, COUNT(*) AS participation_count
    FROM participant p
    JOIN users u ON p.user_id = u.user_id
    GROUP BY u.user_id, u.username
    ORDER BY participation_count DESC
    LIMIT 1";
$most_active_result = $conn->query($most_active_query);
$most_active = $most_active_result->fetch_assoc();

// Query to get users who have participated in more than 3 activities
$active_users_query = "
    SELECT u.user_id, u.username, COUNT(*) AS participation_count
    FROM participant p
    JOIN users u ON p.user_id = u.user_id
    GROUP BY u.user_id, u.username
    HAVING COUNT(*) > 3";
$active_users_result = $conn->query($active_users_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Management</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        /* Form Styling */
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 25px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Statistics Section */
        .stats {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stats p {
            margin: 10px 0;
            font-size: 18px;
            color: #555;
        }

        .stats strong {
            font-size: 20px;
            color: #007bff;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            form {
                width: 90%;
            }

            table {
                font-size: 14px;
            }

            .stats {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <h1>Participant Management</h1>

    <?php if (!empty($message)) { echo "<p style='color: green; text-align: center;'>$message</p>"; } ?>

    <form method="POST" action="">
        <label for="role">Role:</label>
        <input type="text" name="role" id="role" placeholder="Enter the participant's role" required>
        <button type="submit">Add Participant</button>
    </form>

    <div class="stats">
        <h2>Participation Statistics</h2>
        <p>Total Participants: <strong><?php echo isset($stats['total_participants']) ? $stats['total_participants'] : 0; ?></strong></p>
        <p>Unique Users: <strong><?php echo isset($stats['unique_users']) ? $stats['unique_users'] : 0; ?></strong></p>
        <p>Most Active User ID: <strong><?php echo isset($most_active['user_id']) ? $most_active['user_id'] : "N/A"; ?></strong></p>
        <p>Most Active Participant: <strong><?php echo isset($most_active['username']) ? $most_active['username'] : "N/A"; ?></strong> 
           (<?php echo isset($most_active['participation_count']) ? $most_active['participation_count'] : 0; ?> participations)</p>
    </div>

    <h2>Users Who Participated in More than 3 Activities</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Participation Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($active_users_result->num_rows > 0): ?>
                <?php while ($row = $active_users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['participation_count']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Your Participations</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No participations found.</p>
    <?php endif; ?>
</body>
</html>
