<?php
session_start();
include_once '../../config/config.php';
include '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];


$followingListQuery = "
    SELECT u.user_id, u.username, u.profile_photo 
    FROM followers f 
    JOIN user u ON f.user_id = u.user_id 
    WHERE f.follower_user_id = ?
";
$stmt = $conn->prepare($followingListQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$followingList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #2c3e50;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .heading {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }
        .user-list {
            list-style: none;
            padding: 0;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .user-item:last-child {
            border-bottom: none;
        }
        .user-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }
        .user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-info {
            flex: 1;
        }
        .user-info a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="heading">Following List</h1>
        <ul class="user-list">
            <?php foreach ($followingList as $user): ?>
            <li class="user-item">
                <div class="user-photo">
                    <?php if ($user['profile_photo']): ?>
                        <img src="<?php echo SITE_URL . 'uploads/profile_photos/' . htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo">
                    <?php else: ?>
                        <img src="<?php echo SITE_URL . 'assets/images/default-profile.jpg'; ?>" alt="Default Avatar">
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <a href="view_profile.php?user_id=<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>