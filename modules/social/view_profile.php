<?php
session_start();
include_once '../../config/config.php';
include '../../includes/header.php';

$viewUserId = $_GET['user_id'];
$loggedInUserId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT u.*, 
    (SELECT COUNT(*) FROM post WHERE user_id = u.user_id) as posts_count,
    (SELECT COUNT(*) FROM followers WHERE user_id = u.user_id) as followers_count,
    (SELECT COUNT(*) FROM followers WHERE follower_user_id = u.user_id) as following_count
    FROM user u
    WHERE u.user_id = ?
");
$stmt->bind_param("i", $viewUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit();
}

$followCheckQuery = "SELECT * FROM followers WHERE user_id = ? AND follower_user_id = ?";
$stmt = $conn->prepare($followCheckQuery);
$stmt->bind_param('ii', $viewUserId, $loggedInUserId);
$stmt->execute();
$followResult = $stmt->get_result();
$isFollowing = $followResult->num_rows > 0;
$stmt->close();

$postsQuery = "
    SELECT p.*, 
    (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) as likes_count,
    (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id) as comments_count
    FROM post p
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
    LIMIT 5
";
$stmt = $conn->prepare($postsQuery);
$stmt->bind_param("i", $viewUserId);
$stmt->execute();
$posts = $stmt->get_result();
$stmt->close();

$joinDate = new DateTime($user['created_at']);
$now = new DateTime();
$interval = $joinDate->diff($now);
$joinDuration = "";
if ($interval->y > 0) {
    $joinDuration = $interval->y . " year" . ($interval->y > 1 ? "s" : "");
} elseif ($interval->m > 0) {
    $joinDuration = $interval->m . " month" . ($interval->m > 1 ? "s" : "");
} else {
    $joinDuration = $interval->d . " day" . ($interval->d > 1 ? "s" : "");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Add your CSS styles here */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --light-bg: #f5f6fa;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            margin-top: 60px;
        }

        .profile-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .profile-header {
            background: var(--white);
            border-radius: 15px;
            padding-bottom: 20px;
            box-shadow: var(--shadow);
            position: relative;
            margin-bottom: 20px;
        }

        .cover-photo {
            height: 300px;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
            position: relative;
        }

        .cover-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info-top {
            display: flex;
            align-items: flex-start;
            padding: 0 20px;
            position: relative;
            margin-top: 90px;
        }

        .profile-photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 5px solid var(--white);
            overflow: hidden;
            box-shadow: var(--shadow);
            background-color: var(--white);
            margin-top: -150px;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-basic-info {
            margin-left: 230px;
            padding-bottom: 20px;
        }

        .profile-name {
            font-size: 2em;
            font-weight: 600;
            margin: 0;
            color: var(--primary-color);
        }

        .profile-username {
            color: var(--secondary-color);
            font-size: 1.2em;
            margin: 5px 0;
        }

        .profile-stats {
            display: flex;
            gap: 30px;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5em;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.9em;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .profile-sidebar {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .profile-main {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .info-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1.2em;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-bg);
        }

        .info-item {
            display: flex;
            margin-bottom: 15px;
        }

        .info-label {
            width: 120px;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            flex: 1;
            color: var(--text-color);
        }

        .follow-btn {
            background: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s ease;
            text-align: center;
            display: inline-block;
        }

        .follow-btn:hover {
            background: rgb(250, 195, 12);
        }

        .edit-profile-btn {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s ease;
            text-align: center;
            display: inline-block;
        }

        .edit-profile-btn:hover {
            background: var(--hover-color);
        }

        .actions-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }

            .profile-info-top {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: 0;
            }

            .profile-basic-info {
                margin-left: 0;
                margin-top: 20px;
            }

            .profile-stats {
                justify-content: center;
            }

            .profile-photo {
                position: relative;
                top: 0;
                left: 0;
                margin-top: -90px;
            }
        }

        .post-card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            padding: 15px;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .post-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .post-author-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--light-bg);
        }

        .post-author-name {
            font-size: 1em;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0;
        }

        .post-date {
            font-size: 0.9em;
            color: #777;
        }

        .post-content {
            margin: 15px 0;
            color: var(--text-color);
            line-height: 1.5;
        }

        .post-image {
            width: 100%;
            height: auto;
            margin: 15px 0;
        }

        .post-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .post-footer {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
        }

        .post-actions button {
            background: transparent;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }

        .post-actions button:hover {
            color: var(--accent-color);
        }

        .post-actions i {
            font-size: 1.2em;
        }

    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="cover-photo">
                <?php if ($user['cover_photo'] && $user['cover_photo'] != 'default-cover.png'): ?>
                    <img src="<?php echo SITE_URL . 'uploads/cover_photos/' . htmlspecialchars($user['cover_photo']); ?>" alt="Cover Photo">
                <?php else: ?>
                    <img src="<?php echo SITE_URL . 'assets/images/default-cover.jpg'; ?>" alt="Default Cover">
                <?php endif; ?>
            </div>
            
            <div class="profile-info-top">
                <div class="profile-photo">
                    <?php if ($user['profile_photo'] && $user['profile_photo'] != 'default-profile.png'): ?>
                        <img src="<?php echo SITE_URL . 'uploads/profile_photos/' . htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo">
                    <?php else: ?>
                        <img src="<?php echo SITE_URL . 'assets/images/default-profile.png'; ?>" alt="Default Avatar">
                    <?php endif; ?>
                </div>
                
                <div class="profile-basic-info">
                    <h1 class="profile-name">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </h1>
                    <div class="profile-username">@<?php echo htmlspecialchars($user['username']); ?></div>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $user['posts_count']; ?></div>
                            <div class="stat-label">Posts</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="follower-count"><?php echo $user['followers_count']; ?></div>
                            <div class="stat-label">Followers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $user['following_count']; ?></div>
                            <div class="stat-label">Following</div>
                        </div>
                    </div>

                    <div class="actions-container">
                        <?php if ($viewUserId != $loggedInUserId): ?>
                            <button class="follow-btn" id="follow-btn" data-user-id="<?php echo $viewUserId; ?>" data-following="<?php echo $isFollowing ? '1' : '0'; ?>">
                                <?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?>
                            </button>
                        <?php else: ?>
                            <a href="profile.php" class="edit-profile-btn">Edit Profile</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="info-section">
                    <h3 class="section-title">About</h3>
                    <div class="info-item">
                        <span class="info-label">Bio</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['bio'] ?? 'No bio added yet'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Joined</span>
                        <span class="info-value"><?php echo $joinDuration; ?> ago</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Location</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['location'] ?? 'Not specified'); ?></span>
                    </div>
                </div>

                <div class="info-section">
                    <h3 class="section-title">Contact Information</h3>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
                    </div>
                </div>

                <div class="info-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="info-item">
                        <span class="info-label">Birthday</span>
                        <span class="info-value">
                            <?php echo $user['date_of_birth'] ? date('F j, Y', strtotime($user['date_of_birth'])) : 'Not provided'; ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?php echo ucfirst(htmlspecialchars($user['gender'] ?? 'Not specified')); ?></span>
                    </div>
                </div>

                <div class="info-section">
                    <h3 class="section-title">Children Details</h3>
                    <div class="info-item">
                        <span class="info-value">
                            <?php echo nl2br(htmlspecialchars($user['children_details'] ?? 'No children details added')); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-main">
                <div class="info-section">
                    <h3 class="section-title">All Posts</h3>
                    <?php if ($posts->num_rows > 0): ?>
                        <?php while ($post = $posts->fetch_assoc()): ?>
                            <div class="post-card">
                                <div class="post-header">
                                    <div class="post-author">
                                        <div class="post-author-name">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                        <div class="post-date">
                                            <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                        </div>
                                    </div>
                                    <?php if ($post['user_id'] == $loggedInUserId): ?>
                                        <button class="edit-btn" onclick="window.location.href='edit_post.php?post_id=<?php echo $post['post_id']; ?>'">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="post-content">
                                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                                </div>

                                <?php if (!empty($post['photo'])): ?>
                                    <div class="post-image">
                                        <img src="../../uploads/<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Image">
                                    </div>
                                <?php endif; ?>

                                <div class="post-footer">
                                    <div class="post-actions">
                                        <button class="like-btn" data-post-id="<?php echo $post['post_id']; ?>">
                                            <i class="far fa-heart"></i>
                                            <span class="like-count"><?php echo $post['likes_count']; ?></span> Likes
                                        </button>
                                        <button class="comment-btn" data-post-id="<?php echo $post['post_id']; ?>">
                                            <i class="far fa-comment"></i>
                                            <span><?php echo $post['comments_count']; ?></span> Comments
                                        </button>
                                        <?php if ($post['user_id'] == $loggedInUserId): ?>
                                            <button class="delete-post-btn" data-post-id="<?php echo $post['post_id']; ?>">
                                                <i class="far fa-trash-alt"></i> Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="likes-list-container" id="likes-<?php echo $post['post_id']; ?>">
                                    <div class="likes-list"></div>
                                </div>

                                <div class="comments-container" id="comments-<?php echo $post['post_id']; ?>">
                                    <div class="comments-list"></div>
                                    <div class="comment-form">
                                        <textarea placeholder="Write a comment..."></textarea>
                                        <button class="submit-comment-btn" data-post-id="<?php echo $post['post_id']; ?>">Submit</button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-posts">
                            <p>No posts to display.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var followBtn = document.getElementById('follow-btn');

        if (followBtn) {
            followBtn.addEventListener('click', function() {
                var button = this;
                var userId = button.getAttribute('data-user-id');
                var isFollowing = button.getAttribute('data-following') === '1';
                var action = isFollowing ? 'unfollow' : 'follow';

                fetch('follow.php?user_id=' + userId, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        button.setAttribute('data-following', isFollowing ? '0' : '1');
                        button.textContent = isFollowing ? 'Follow' : 'Unfollow';
                        document.getElementById('follower-count').textContent = data.follower_count + ' Followers';
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
    </script>
</body>
</html>