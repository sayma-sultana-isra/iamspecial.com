<?php
session_start();
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['post_id'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $privacy = $_POST['privacy'];
    $photo = $_POST['current_photo']; 

    if (isset($_POST['delete_photo']) && $_POST['delete_photo'] === 'on') {
        $photo = null; 
    } else if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../../uploads/";
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
        $photo = $_FILES["photo"]["name"];
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
    }

    $stmt = $conn->prepare("UPDATE Post SET content = ?, category = ?, privacy = ?, photo = ? WHERE post_id = ?");
    $stmt->bind_param('ssssi', $content, $category, $privacy, $photo, $postId);
    $stmt->execute();
    $stmt->close();

    header('Location: newsfeed.php');
    exit();
}

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    $stmt = $conn->prepare("SELECT * FROM Post WHERE post_id = ?");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $postResult = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            resize: none;
        }
        select, input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        img {
            max-width: 100%;
            margin: 10px 0;
            border-radius: 8px;
        }
        label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2>
        <form method="POST" action="edit_post.php" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $postResult['post_id']; ?>">

            <textarea name="content" required><?php echo htmlspecialchars($postResult['content']); ?></textarea>

            <!-- Category Dropdown -->
            <select name="category" required>
    <option value="general" <?php echo $postResult['category'] == 'general' ? 'selected' : ''; ?>>General</option>
    <option value="epilepsy" <?php echo $postResult['category'] == 'epilepsy' ? 'selected' : ''; ?>>Epilepsy</option>
    <option value="Bipolar Disorder" <?php echo $postResult['category'] == 'Bipolar Disorder' ? 'selected' : ''; ?>>Bipolar Disorder</option>
    <option value="feeding" <?php echo $postResult['category'] == 'feeding' ? 'selected' : ''; ?>>Feeding/Eating Issues</option>
    <option value="sleep" <?php echo $postResult['category'] == 'sleep' ? 'selected' : ''; ?>>Sleep Disruption</option>
    <option value="ADHD" <?php echo $postResult['category'] == 'ADHD' ? 'selected' : ''; ?>>ADHD</option>
    <option value="anxiety" <?php echo $postResult['category'] == 'anxiety' ? 'selected' : ''; ?>>Anxiety</option>
    <option value="depression" <?php echo $postResult['category'] == 'depression' ? 'selected' : ''; ?>>Depression</option>
    <option value="OCD" <?php echo $postResult['category'] == 'OCD' ? 'selected' : ''; ?>>OCD</option>
    <option value="Schizophrenia" <?php echo $postResult['category'] == 'Schizophrenia' ? 'selected' : ''; ?>>Schizophrenia</option>
    <option value="Down syndrome" <?php echo $postResult['category'] == 'Down syndrome' ? 'selected' : ''; ?>>Down Syndrome</option>
</select>


            <!-- Upload or Delete Photo -->
            <input type="file" name="photo" accept="image/*">
            <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($postResult['photo']); ?>">
            <?php if (!empty($postResult['photo'])): ?>
                <img src="../../uploads/<?php echo htmlspecialchars($postResult['photo']); ?>" alt="Post Image">
                <label for="delete-photo">
                    <input type="checkbox" name="delete_photo" id="delete-photo"> Delete this photo
                </label>
            <?php endif; ?>

            <!-- Privacy Dropdown -->
            <select name="privacy">
                <option value="public" <?php echo $postResult['privacy'] == 'public' ? 'selected' : ''; ?>>Public</option>
                <option value="private" <?php echo $postResult['privacy'] == 'private' ? 'selected' : ''; ?>>Private</option>
                <option value="friends-only" <?php echo $postResult['privacy'] == 'friends-only' ? 'selected' : ''; ?>>Friends Only</option>
            </select>

            <div class="actions">
                <button type="submit">Save Changes</button>
                </div>
               <button id="delete-post" data-post-id="<?php echo $postResult['post_id']; ?>">Delete Post</button>


           
        </form>
    </div>
    
    <script>
        document.getElementById('delete-post').addEventListener('click', function () {
    if (confirm('Are you sure you want to delete this post?')) {
        const postId = this.getAttribute('data-post-id');

        fetch('delete_post.php?post_id=' + postId, {
            method: 'GET',
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Post deleted successfully!');
                    window.location.href = 'newsfeed.php'; // Redirect to the newsfeed
                } else {
                    alert(data.message || 'Failed to delete the post.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the post.');
            });
    }
});

    </script>
</body>
</html>
