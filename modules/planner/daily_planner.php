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

// Handle task addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_STRING);
    $deadline = filter_input(INPUT_POST, 'deadline', FILTER_SANITIZE_STRING);

    if ($title && $description && $priority && $deadline) {
        $stmt = $conn->prepare("INSERT INTO daily_planner (user_id, title, description, priority, deadline, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
        if ($stmt) {
            $stmt->bind_param("issss", $user_id, $title, $description, $priority, $deadline);
            if ($stmt->execute()) {
                $message = "✅ Task added successfully!";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
        } else {
            $message = "❌ Query Preparation Failed: " . $conn->error;
        }
    } else {
        $message = "❌ Please fill out all fields correctly.";
    }
}

// Handle task completion
if (isset($_GET['complete'])) {
    $task_id = intval($_GET['complete']);
    $stmt = $conn->prepare("UPDATE daily_planner SET status='Completed' WHERE task_id=? AND user_id=?");
    if ($stmt) {
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
    }
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $task_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM daily_planner WHERE task_id=? AND user_id=?");
    if ($stmt) {
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
    }
}

// Fetch tasks for the user
$query = "SELECT * FROM daily_planner WHERE user_id = ? ORDER BY deadline ASC";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $tasks = $stmt->get_result();
} else {
    die("❌ Error fetching tasks: " . $conn->error);
}

// Fetch aggregate stats safely
$stats_query = "
    SELECT 
        COUNT(*) AS total_tasks,
        SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) AS completed_tasks,
        COUNT(*) - SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) AS pending_tasks,
        IFNULL(ROUND((SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2), 0) AS completion_rate
    FROM daily_planner WHERE user_id = ?";
$stats_stmt = $conn->prepare($stats_query);
if ($stats_stmt) {
    $stats_stmt->bind_param("i", $user_id);
    $stats_stmt->execute();
    $stats_result = $stats_stmt->get_result();
    $stats = $stats_result->fetch_assoc();
} else {
    die("❌ Error fetching stats: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Planner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
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
            background-color: #fff;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .completed {
            color: green;
        }
        .pending {
            color: red;
        }
        .actions {
            text-align: center;
        }
        .actions a {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }
        .complete {
            background: green;
        }
        .delete {
            background: red;
        }
    </style>
</head>
<body>
    <h1>Daily Planner</h1>

    <?php if (!empty($message)) { echo "<p style='color: green; text-align: center;'>$message</p>"; } ?>

    <form method="POST" action="">
        <label for="title">Task Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>

        <label for="deadline">Deadline:</label>
        <input type="date" name="deadline" id="deadline" required>

        <button type="submit" name="add_task">Add Task</button>
    </form>

    <h2>Task Statistics</h2>
    <p>Total Tasks: <?php echo $stats['total_tasks']; ?></p>
    <p>Completed Tasks: <?php echo $stats['completed_tasks']; ?></p>
    <p>Pending Tasks: <?php echo $stats['pending_tasks']; ?></p>
    <p>Completion Rate: <?php echo $stats['completion_rate']; ?>%</p>

    <h2>Your Tasks</h2>
    <?php if ($tasks->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        <td class="<?php echo strtolower($task['status']); ?>">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </td>
                        <td class="actions">
                            <a href="?complete=<?php echo $task['task_id']; ?>" class="complete">✔ Complete</a>
                            <a href="?delete=<?php echo $task['task_id']; ?>" class="delete">✖ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No tasks found.</p>
    <?php endif; ?>
</body>
</html>
