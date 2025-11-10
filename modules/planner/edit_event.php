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

// Fetch event details based on event_id from the query string
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : 0;
$event_query = "SELECT * FROM events WHERE event_id = $event_id";
$event_result = $conn->query($event_query);
$event = $event_result->fetch_assoc();

// Fetch dropdown options (same as in events.php)
$categories = $conn->query("SELECT category_name FROM categories");
$age_groups = $conn->query("SELECT age_range FROM age_groups");
$event_modes = $conn->query("SELECT event_mode_name FROM event_modes");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $accessibility_level = $_POST['accessibility_level'];
    $event_type = $_POST['event_type'];
    $age_group = $_POST['age_group'];
    $expert_id = $_POST['expert_id'];
    $attendees_count = $_POST['attendees_count'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $event_mode = $_POST['event_mode'];
    $status = $_POST['status'];

    // Update the event in the database
    $sql = "UPDATE events SET title='$title', description='$description', location='$location', category='$category', 
            date='$date', accessibility_level='$accessibility_level', event_type='$event_type', age_group='$age_group', 
            expert_id='$expert_id', attendees_count='$attendees_count', start_time='$start_time', end_time='$end_time', 
            event_mode='$event_mode', status='$status' WHERE event_id=$event_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to events.php after successful update
        header("Location: events.php");
        exit();  // Make sure the script stops after redirection
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container colorful">
        <h2 class="create-event-title">EDIT EVENT</h2>
        <form method="POST" action="edit_event.php?event_id=<?php echo $event_id; ?>">
            <input type="text" name="title" placeholder="Event Title" value="<?php echo $event['title']; ?>" required>
            <textarea name="description" placeholder="Event Description" required><?php echo $event['description']; ?></textarea>
            <input type="text" name="location" placeholder="Location" value="<?php echo $event['location']; ?>" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Education" <?php echo ($event['category'] == 'Education') ? 'selected' : ''; ?>>Education</option>
                <option value="Health" <?php echo ($event['category'] == 'Health') ? 'selected' : ''; ?>>Health</option>
                <option value="Awareness" <?php echo ($event['category'] == 'Awareness') ? 'selected' : ''; ?>>Awareness</option>
                <option value="Others" <?php echo ($event['category'] == 'Others') ? 'selected' : ''; ?>>Others</option>
            </select>
            <input type="date" name="date" value="<?php echo $event['date']; ?>" required>
            <select name="age_group" required>
                <option value="">Select Age Group</option>
                <option value="0-5" <?php echo ($event['age_group'] == '0-5') ? 'selected' : ''; ?>>0-5</option>
                <option value="6-10" <?php echo ($event['age_group'] == '6-10') ? 'selected' : ''; ?>>6-10</option>
                <option value="11-15" <?php echo ($event['age_group'] == '11-15') ? 'selected' : ''; ?>>11-15</option>
            </select>
            <select name="event_mode" required>
                <option value="">Select Event Mode</option>
                <option value="Online" <?php echo ($event['event_mode'] == 'Online') ? 'selected' : ''; ?>>Online</option>
                <option value="Offline" <?php echo ($event['event_mode'] == 'Offline') ? 'selected' : ''; ?>>Offline</option>
            </select>
            <input type="text" name="expert_id" placeholder="Expert ID" value="<?php echo $event['expert_id']; ?>">
            <input type="number" name="attendees_count" placeholder="Attendees Count" value="<?php echo $event['attendees_count']; ?>">
            <input type="time" name="start_time" value="<?php echo $event['start_time']; ?>" required>
            <input type="time" name="end_time" value="<?php echo $event['end_time']; ?>" required>
            <select name="event_type" required>
                <option value="">Select Event Type</option>
                <option value="Webinar" <?php echo ($event['event_type'] == 'Webinar') ? 'selected' : ''; ?>>Webinar</option>
                <option value="Workshop" <?php echo ($event['event_type'] == 'Workshop') ? 'selected' : ''; ?>>Workshop</option>
                <option value="Seminar" <?php echo ($event['event_type'] == 'Seminar') ? 'selected' : ''; ?>>Seminar</option>
            </select>
            <select name="accessibility_level" required>
                <option value="">Select Accessibility Level</option>
                <option value="Low" <?php echo ($event['accessibility_level'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                <option value="Medium" <?php echo ($event['accessibility_level'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="High" <?php echo ($event['accessibility_level'] == 'High') ? 'selected' : ''; ?>>High</option>
            </select>

            <button type="submit">Update Event</button>
        </form>
    </div>
</body>
</html>

<style>
    /* Same styles as events.php */
    body { font-family: Arial, sans-serif; background: linear-gradient(120deg, #f6d365, #fda085); padding: 20px; }
    .container { max-width: 600px; margin: auto; background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); border-radius: 10px; }
    h2 { text-align: center; color: #444; }
    .create-event-title { color: navy; }
    form { display: flex; flex-direction: column; }
    input, select, textarea { margin-bottom: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #ff4081; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 5px; }
    button:hover { background: #e91e63; }
</style>
