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
// Fetch dropdown options
$categories = $conn->query("SELECT category_name FROM categories");
$age_groups = $conn->query("SELECT age_range FROM age_groups");
$event_modes = $conn->query("SELECT event_mode_name FROM event_modes");

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle event creation
if ($action == 'insert' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $accessibility_level = $_POST['accessibility_level'];
    $event_type = $_POST['event_type'];
    $age_group = $_POST['age_group'];
    $attendees_count = $_POST['attendees_count'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $event_mode = $_POST['event_mode'];

    $sql = "INSERT INTO events (title, description, location, category, date, accessibility_level, event_type, age_group, attendees_count, start_time, end_time, event_mode) 
            VALUES ('$title', '$description', '$location', '$category', '$date', '$accessibility_level', '$event_type', '$age_group', '$attendees_count', '$start_time', '$end_time', '$event_mode')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>New event created successfully</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle event actions (edit, cancel, complete)
if (isset($_GET['action']) && isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    if ($_GET['action'] == 'edit') {
        header("Location: edit_event.php?event_id=$event_id"); 
        exit();
    } elseif ($_GET['action'] == 'cancel') {
        // Deleting the event
        $sql = "DELETE FROM events WHERE event_id='$event_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='success'>Event cancelled and deleted successfully</p>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    } elseif ($_GET['action'] == 'complete') {
        // Updating the status of the event to 'Completed'
        $sql = "UPDATE events SET status='Completed' WHERE event_id='$event_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='success'>Event marked as completed</p>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    }
}

// Fetch total events and completed events counts
$total_events_query = "SELECT COUNT(*) as total FROM events WHERE status != 'Cancelled'";
$total_events_result = $conn->query($total_events_query);
$total_events = $total_events_result->fetch_assoc()['total'];

$completed_events_query = "SELECT COUNT(*) as completed FROM events WHERE status = 'Completed'";
$completed_events_result = $conn->query($completed_events_query);
$completed_events = $completed_events_result->fetch_assoc()['completed'];

// Fetch all upcoming events
$events = $conn->query("SELECT * FROM events ORDER BY date DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container colorful">
        <!-- Add Participants button in top corner -->
        <div class="top-buttons">
            <a href="participants.php" class="participants-btn">Participants</a>
        </div>

        <h2 class="create-event-title">CREATE AN EVENT</h2>
        <form method="POST" action="events.php?action=insert">
            <input type="text" name="title" placeholder="Event Title" required>
            <textarea name="description" placeholder="Event Description" required></textarea>
            <input type="text" name="location" placeholder="Location" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Education">Education</option>
                <option value="Health">Health</option>
                <option value="Awareness">Awareness</option>
                <option value="Others">Others</option>
            </select>
            <input type="date" name="date" required>
            <select name="age_group" required>
                <option value="">Select Age Group</option>
                <option value="0-5">0-5</option>
                <option value="6-10">6-10</option>
                <option value="11-15">11-15</option>
            </select>
            <select name="event_mode" required>
                <option value="">Select Event Mode</option>
                <option value="Online">Online</option>
                <option value="Offline">Offline</option>
            </select>
            <input type="number" name="attendees_count" placeholder="Attendees Count">
            <input type="time" name="start_time" required>
            <input type="time" name="end_time" required>
            <select name="event_type" required>
                <option value="">Select Event Type</option>
                <option value="Webinar">Webinar</option>
                <option value="Workshop">Workshop</option>
                <option value="Seminar">Seminar</option>
            </select>
            <select name="accessibility_level" required>
                <option value="">Select Accessibility Level</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            
            <button type="submit">Create Event</button>
        </form>

        <h2 class="event-stats-title">EVENT STATS</h2>
    
        <p><strong>Events Completed:</strong> <?php echo $completed_events; ?></p>
        <p><strong>Total Events (Including Completed and Ongoing):</strong> <?php echo $total_events; ?></p>

        <h2 class="upcoming-events-title">UPCOMING EVENTS</h2>
        <div class="event-list">
            <?php while ($event = $events->fetch_assoc()) {
                echo "<div class='event'>
                        <h3>{$event['title']}</h3>
                        <p><strong>Date:</strong> {$event['date']}</p>
                        <p><strong>Time:</strong> {$event['start_time']} - {$event['end_time']}</p>
                        <p><strong>Location:</strong> {$event['location']}</p>
                        <p><strong>Category:</strong> {$event['category']}</p>
                        <p><strong>Status:</strong> {$event['status']}</p>
                        <a href='events.php?action=edit&event_id={$event['event_id']}'>Edit</a> |
                        <a href='events.php?action=cancel&event_id={$event['event_id']}'>Cancel</a> |
                        <a href='events.php?action=complete&event_id={$event['event_id']}'>Complete</a>
                    </div>";
            } ?>
        </div>
    </div>
</body>
</html>

<style>
    body { font-family: Arial, sans-serif; background: linear-gradient(120deg, #f6d365, #fda085); padding: 20px; }
    .container { max-width: 600px; margin: auto; background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); border-radius: 10px; }
    h2 { text-align: center; color: #444; }
    .create-event-title { color: navy; }
    .upcoming-events-title { color: green; }
    .event-stats-title { color: #ff4081; }
    form { display: flex; flex-direction: column; }
    input, select, textarea { margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #ff4081; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 5px; }
    button:hover { background: #e91e63; }
    .event-list { margin-top: 20px; }
    .event { background: #fff3e0; padding: 10px; margin: 10px 0; border-left: 5px solid #ff9800; border-radius: 5px; }
    a { text-decoration: none; color: #ff4081; }
    a:hover { color: #e91e63; }

    /* Add styles for the top-right "Participants" button */
    .top-buttons {
        text-align: right;
        margin-bottom: 20px;
    }
    .participants-btn {
        background-color: #ff4081;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }
    .participants-btn:hover {
        background-color: #e91e63;
    }
</style>
