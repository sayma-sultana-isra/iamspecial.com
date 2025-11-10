<?php
// Include the database connection
include_once '../../config/config.php';
// Include the functions file
include_once 'functions.php';  // Adjust the path as necessary

// Now you can call insertNotification() in your code


session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Ensure the uploads directory exists
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Handle form submission (Add Resource)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $resource_type_id = $_POST['resource_type_id'];
    $file_path = null;
    $external_link = null;

    // Handle file upload if it's a document
    if ($resource_type_id == 1 && !empty($_FILES['file']['name'])) {
        $file_name = basename($_FILES['file']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        } else {
            $message = "File upload failed. Please check directory permissions.";
        }
    }

    // Handle external link if it's a video or link
    if (($resource_type_id == 2 || $resource_type_id == 3) && !empty($_POST['external_link'])) {
        $external_link = $_POST['external_link'];
    }

    // Insert resource into the database
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO resources (user_id, title, description, resource_type_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $user_id, $title, $description, $resource_type_id);
        $stmt->execute();
        $resource_id = $stmt->insert_id;

        $detail_stmt = $conn->prepare("INSERT INTO resource_details (resource_id, file_path, external_link) VALUES (?, ?, ?)");
        $detail_stmt->bind_param("iss", $resource_id, $file_path, $external_link);
        $detail_stmt->execute();

        $conn->commit();
        $message = "Resource added successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch resource types
$type_query = "SELECT id, type_name FROM resource_types";
$type_result = $conn->query($type_query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resource</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f6f8;
        }
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label, select, input, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }
        .success-message {
            text-align: center;
            color: green;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Add New Resource</h1>

    <!-- Success/Error Message -->
    <?php if (!empty($message)) : ?>
        <p class="success-message"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="resource_type_id">Resource Type:</label>
            <select name="resource_type_id" id="resource_type_id" required>
                <option value="">Select Type</option>
                <?php while ($row = $type_result->fetch_assoc()) : ?>
                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['type_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="file">Upload Document:</label>
            <input type="file" name="file" id="file">

            <label for="external_link">Video Link:</label>
            <input type="url" name="external_link" id="external_link">

            <button type="submit">Add Resource</button>
        </form>
    </div>

    <!-- Back to Resources Button -->
    <a href="resource.php" class="btn-back">Back to Resources</a>
</body>
</html>
