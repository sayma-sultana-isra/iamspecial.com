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

// Handle Delete (only owner can delete)
if (isset($_GET['delete_id'])) {
    $resource_id = $_GET['delete_id'];

    // Check if the resource belongs to the current user
    $check_owner = $conn->prepare("SELECT user_id FROM resources WHERE id = ?");
    $check_owner->bind_param("i", $resource_id);
    $check_owner->execute();
    $result = $check_owner->get_result();

    if ($result->num_rows > 0 && $result->fetch_assoc()['user_id'] == $user_id) {
        $conn->begin_transaction();
        try {
            $delete_detail = $conn->prepare("DELETE FROM resource_details WHERE resource_id = ?");
            $delete_detail->bind_param("i", $resource_id);
            $delete_detail->execute();

            $delete_resource = $conn->prepare("DELETE FROM resources WHERE id = ?");
            $delete_resource->bind_param("i", $resource_id);
            $delete_resource->execute();

            $conn->commit();
            $message = "Resource deleted successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "You are not authorized to delete this resource.";
    }
}

// Fetch resources grouped by type
$resource_query = "
    SELECT 
        r.id,
        r.title,
        r.description,
        rt.type_name AS resource_type,
        rd.file_path,
        rd.external_link,
        r.user_id
    FROM 
        resources r
    JOIN 
        resource_types rt ON r.resource_type_id = rt.id
    LEFT JOIN 
        resource_details rd ON r.id = rd.resource_id
    ORDER BY 
        rt.id, r.created_at DESC
";
$resource_stmt = $conn->prepare($resource_query);
$resource_stmt->execute();
$resources_result = $resource_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f6f8;
        }
        .section-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f7f7f7;
        }
        .success-message {
            text-align: center;
            color: green;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }
        .btn-update {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-add {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Resource Library</h1>

    <!-- Success/Error Message -->
    <?php if (!empty($message)) : ?>
        <p class="success-message"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Button to Add Resource -->
    <a href="add_resource.php" class="btn-add">Add New Resource</a>

    <!-- Resource Sections -->
    <div class="section-container">
        <h2>Uploaded Resources</h2>
        <?php
        $current_type = null;
        while ($row = $resources_result->fetch_assoc()) :
            if ($current_type !== $row['resource_type']) :
                if ($current_type !== null) echo "</table>";
                $current_type = $row['resource_type'];
                echo "<h3>" . htmlspecialchars($current_type) . "</h3>";
                echo "<table><thead><tr><th>Title</th><th>Description</th><th>Content</th><th>Actions</th></tr></thead><tbody>";
            endif;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['title']); ?></td>
                <td><?= htmlspecialchars($row['description']); ?></td>
                <td>
                    <?php if (!empty($row['file_path'])) : ?>
                        <a href="<?= htmlspecialchars($row['file_path']); ?>" download>Download Document</a>
                    <?php elseif (!empty($row['external_link'])) : ?>
                        <a href="<?= htmlspecialchars($row['external_link']); ?>" target="_blank">Watch Video</a>
                    <?php else : ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['user_id'] == $user_id) : ?>
                        <a href="update.php?id=<?= $row['id']; ?>" class="btn btn-update">Update</a>
                        <a href="?delete_id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this resource?');">Delete</a>
                    <?php else : ?>
                        <span>Not authorized</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if ($current_type !== null) echo "</table>"; ?>
    </div>
</body>
</html>
