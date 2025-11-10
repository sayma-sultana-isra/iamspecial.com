<?php
session_start();
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $content = $_POST['content'];
    $privacy = $_POST['privacy'];
    $category = $_POST['category']; // Capture selected category
    $photo = NULL; // Default value in case no photo is uploaded

    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../../uploads/";
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
        $photo = $_FILES["photo"]["name"];
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
    }

    // Prepare and execute query to insert post
    $stmt = $conn->prepare("INSERT INTO Post (user_id, content, privacy, category, photo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issss', $userId, $content, $privacy, $category, $photo);
    $stmt->execute();
    $stmt->close();

    header('Location: newsfeed.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/navbar.php'; ?>
    <div class="container">
        <h2>Create a Post</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <textarea name="content" placeholder="What's on your mind?" required></textarea>
            
            <!-- Add category selection here -->
            <select name="category" required>
                <option value="general">General</option>
                <option value="motivational">Motivational</option>
                <option value="inspirational">Inspirational</option>
                <option value="community">Community</option>
                <option value="events">Events</option>
            </select>

            <input type="file" name="photo" accept="image/*">
            <select name="privacy">
                <option value="public">Public</option>
                <option value="private">Private</option>
                <option value="friends-only">Friends Only</option>
            </select>
            <button type="submit">Post</button>
        </form>
    </div>
    <?php include '../../includes/footer.php'; ?>
</body>
</html>
