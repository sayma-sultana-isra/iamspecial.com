<?php
session_start();
include_once '../../config/config.php';
include '../../includes/header.php';

$userId = $_SESSION['user_id'];

$postsQuery = "
    SELECT Post.*, User.username,User.profile_photo, 
    (SELECT COUNT(*) FROM Likes WHERE Likes.post_id = Post.post_id) AS like_count,
    (SELECT COUNT(*) FROM Comments WHERE Comments.post_id = Post.post_id) AS comment_count,
    (SELECT COUNT(*) FROM Followers WHERE Followers.user_id = Post.user_id AND Followers.follower_user_id = ?) AS is_following,
    (SELECT COUNT(*) FROM Followers WHERE Followers.user_id = Post.user_id) AS follower_count
    FROM Post
    JOIN User ON Post.user_id = User.user_id
    WHERE privacy = 'public'
    OR (privacy = 'private' AND Post.user_id = ?)
    OR (privacy = 'friends-only' AND Post.user_id IN (
        SELECT user_id FROM Followers WHERE follower_user_id = ?
    ))
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($postsQuery);
$stmt->bind_param('iii', $userId, $userId, $userId);
$stmt->execute();
$postsResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Newsfeed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
     :root {
    --primary-color: #FFB6C1;     /* Light Pink */
    --secondary-color: #87CEEB;   /* Sky Blue */
    --text-color: #4A4A4A;        /* Dark Gray for readability */
    --hover-color: #FFA07A;       /* Light Salmon */
    --shadow-color: rgba(0, 0, 0, 0.1);
    --notification-bg: #98FB98;   /* Pale Green */
    --color1: #F0F8FF;           /* Alice Blue */
    --color2: #E6E6FA;           /* Lavender */
    --color3: #FFF0F5;           /* Lavender Blush */
    --color4: #ffffff;           /* White */
    --accent1: #DDA0DD;          /* Plum */
    --accent2: #98FB98;          /* Pale Green */
    --bg-gradient: linear-gradient(45deg, #FFB6C1, #87CEEB);
    --hover-transition: all 0.3s ease;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #FFB6C1, #87CEEB, #98FB98);
    margin: 0;
    padding: 0;
    color: var(--text-color);
    line-height: 1.6;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #FFB6C1, #87CEEB);
    padding: 0.5rem 2rem;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    height: 60px;
    transition: var(--hover-transition);
}

.container {
    width: 80%;
    margin: 80px auto 20px;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 0 15px var(--shadow-color);
    display: flex;
}

.main-content {
    width: 70%;
    padding-right: 20px;
}

.sidebar {
    width: 30%;
    margin-left: 20px;
    background: linear-gradient(135deg, #E6E6FA, #F0F8FF);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 15px var(--shadow-color);
}

h2 {
    text-align: center;
    color: var(--text-color);
    margin-bottom: 1.5em;
    font-size: 2em;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.post {
    position: relative;
    background: linear-gradient(135deg, #FFF0F5, #F0F8FF);
    padding: 20px;
    margin-bottom: 25px;
    border-radius: 12px;
    border: none;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.post:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.post p {
    margin: 12px 0;
    line-height: 1.6;
}

.post strong {
    color: var(--text-color);
}

.post small {
    color: #666;
}

.post-actions a, 
.post-actions button {
    margin-right: 15px;
    text-decoration: none;
    color: var(--text-color);
    font-weight: 600;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    padding: 8px 15px;
    border-radius: 20px;
    transition: var(--hover-transition);
}

.post-actions a:hover, 
.post-actions button:hover {
    background: var(--hover-color);
    color: white;
    transform: translateY(-2px);
}

.create-post {
    margin-bottom: 30px;
    background: linear-gradient(135deg, #E6E6FA, #F0F8FF);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.create-post textarea {
    width: 100%;
    height: 120px;
    margin-bottom: 15px;
    padding: 15px;
    border: 1px solid var(--color3);
    border-radius: 8px;
    resize: vertical;
    background-color: var(--color4);
    font-family: inherit;
    line-height: 1.6;
}

.create-post select, 
.create-post button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
    transition: var(--hover-transition);
    font-size: 0.95em;
    font-weight: bold;
}

.create-post button {
    background: linear-gradient(135deg, #FFB6C1, #FFA07A);
    color: white;
    transform: scale(1);
}

.create-post select {
    background: #87CEEB;
    color: white;
}

.create-post button:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #FFA07A, #FFB6C1);
}

.comments-container {
    margin-top: 15px;
    display: none;
}

.comment {
    background: linear-gradient(135deg, #F0F8FF, #E6E6FA);
    padding: 15px;
    border-radius: 8px;
    border: none;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.comment-actions {
    text-align: right;
}

.comment-actions button {
    background: none;
    border: none;
    color: var(--text-color);
    cursor: pointer;
    padding: 5px 10px;
    transition: var(--hover-transition);
}

.comment-form {
    display: flex;
    margin-top: 15px;
    gap: 10px;
}

.comment-form textarea {
    flex: 1;
    padding: 12px;
    border: 1px solid var(--color3);
    border-radius: 8px;
    resize: vertical;
    background-color: var(--color4);
    min-height: 60px;
}

.comment-form button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #FFB6C1, #FFA07A);
    color: white;
    cursor: pointer;
    transition: var(--hover-transition);
}

.likes-list-container {
    display: none;
    margin-top: 15px;
}

.likes-list {
    background-color: var(--color4);
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.recommendation {
    background: linear-gradient(135deg, #98FB98, #87CEEB);
    border: none;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
    text-align: center;
    color: white;
    transform: scale(1);
    transition: var(--hover-transition);
}

.recommendation:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.profile-info {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    padding: 10px;
    background: linear-gradient(135deg, #FFB6C1, #87CEEB);
    border-radius: 12px;
    color: white;
}

.profile-info img {
    border-radius: 50%;
    width: 60px;
    height: 60px;
    margin-right: 15px;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.profile-info a {
    text-decoration: none;
    color: white;
    font-size: 1.2em;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.upload-container {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    gap: 10px;
}

.upload-icon {
    cursor: pointer;
    font-size: 1.1em;
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #FFB6C1, #87CEEB);
    padding: 10px 20px;
    border-radius: 20px;
    color: white;
}

.upload-icon:hover {
    transform: scale(1.05);
}

.post-photo {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 12px;
    margin-top: 15px;
}

.edit-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #FFB6C1, #87CEEB);
    border: none;
    color: white;
    cursor: pointer;
    font-size: 1.1em;
    padding: 5px 10px;
    border-radius: 5px;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.edit-btn:hover {
    opacity: 1;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.profile-photo {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.user-name a {
    text-decoration: none;
    color: var(--text-color);
    font-size: 1.1em;
    transition: var(--hover-transition);
}

.user-name a:hover {
    color: var(--hover-color);
}

.user-name a strong {
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.post {
    animation: fadeIn 0.5s ease-out forwards;
}

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(#FFB6C1, #87CEEB);
    border-radius: 5px;
}

@media screen and (max-width: 768px) {
    .container {
        width: 95%;
        flex-direction: column;
    }

    .main-content,
    .sidebar {
        width: 100%;
        margin-left: 0;
        margin-top: 20px;
    }

    .create-post textarea {
        height: 100px;
    }

    .navbar {
        padding: 0.5rem 1rem;
    }

    .create-post button,
    .create-post select {
        width: 100%;
        margin-bottom: 10px;
    }
}


    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h2>Newsfeed</h2>
            <div class="create-post">
    <form method="POST" action="posts.php" enctype="multipart/form-data">
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        <div class="upload-container">
            <label for="photo-upload" class="upload-icon">
                <i class="fa fa-camera"></i> Add Photo
            </label>
            <input type="file" id="photo-upload" name="photo" accept="image/*" style="display: none;">
        </div>
        <select name="privacy">
            <option value="public">Public</option>
            <option value="private">Private</option>
            <option value="friends-only">Friends Only</option>
        </select>
          <select name="category" required>
                <option value="general">General</option>
                <option value="epilepsy">Epilepsy</option>
                <option value="Bipolar Disorder">Bipolar Disorder</option>
                <option value="feeding">feeding/eating issues</option>
                <option value="sleep">Sleep disruption</option>
                <option value="ADHD">ADHD</option>
                <option value="anxiety">Anxiety</option>
                <option value="depression">depression</option>
                <option value="OCD">OCD</option>
                <option value="Schizophrenia">Schizophrenia</option>
                <option value="Down syndrome">Down syndrome</option>
            
            </select>
        <button type="submit">Post</button>
    </form>
</div>

<?php while ($post = $postsResult->fetch_assoc()): ?>
                <div class="post" id="post-<?php echo $post['post_id']; ?>">
                    <p>
                    <div class="user-info">
    <?php if (!empty($post['profile_photo'])): ?>
        <img 
            src="../../uploads/profile_photos/<?php echo htmlspecialchars($post['profile_photo']); ?>" 
            alt="Profile Photo" 
            class="profile-photo"
        >
    <?php else: ?>
        <img 
            src="../../assets/images/default-profile.png" 
            alt="Default Profile Photo" 
            class="profile-photo"
        >
    <?php endif; ?>
    <div class="user-name">
        <a href="view_profile.php?user_id=<?php echo $post['user_id']; ?>">
            <strong>@<?php echo htmlspecialchars($post['username']); ?></strong>
        </a>
    </div>
    <button class="follow-btn" data-user-id="<?php echo $post['user_id']; ?>">
                            <?php echo $post['is_following'] ? 'Unfollow' : 'Follow'; ?>
                        </button>
                        <span class="follower-count"><?php echo $post['follower_count']; ?> Followers</span>
                    </p>
</div>


                       
                     <!-- Edit -->
                     <?php if ($post['user_id'] == $userId): ?>
                        <button class="edit-btn" onclick="window.location.href='edit_post.php?post_id=<?php echo $post['post_id']; ?>'">
                            <i class="fa fa-pencil-alt"></i>
                        </button>
                    <?php endif; ?>
                    <?php if (!empty($post['photo'])): ?>
                        <img src="../../uploads/<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Image" style="max-width:100%; border-radius: 8px; margin: 10px 0;">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <p><small><?php echo htmlspecialchars($post['created_at']); ?></small></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p> <!-- Display category -->
                    <div class="post-actions">
                        <span class="like-count"><?php echo $post['like_count']; ?> Likes</span>
                        <a href="like.php?post_id=<?php echo $post['post_id']; ?>" class="like-btn">Like</a>
                        <button class="view-likes-btn" data-post-id="<?php echo $post['post_id']; ?>">View Likes</button>
                        <a href="#" class="comment-btn" data-post-id="<?php echo $post['post_id']; ?>">Comment (<?php echo $post['comment_count']; ?>)</a>
                        <?php if ($post['user_id'] == $userId): ?>
                            <button class="delete-post-btn" data-post-id="<?php echo $post['post_id']; ?>">Delete</button>
                        <?php endif; ?>

                    </div>
                    <div class="likes-list-container" id="likes-<?php echo $post['post_id']; ?>">
                        <div class="likes-list">
                        </div>
                    </div>
                    <div class="comments-container" id="comments-<?php echo $post['post_id']; ?>">
                        <div class="comments-list">
                        </div>
                        <div class="comment-form">
                            <textarea placeholder="Write a comment..."></textarea>
                            <button class="submit-comment-btn" data-post-id="<?php echo $post['post_id']; ?>">Submit</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
            <div class="sidebar">
            <h2>Recommendations</h2>
            <div id="recommendations">
            </div>
            <div class="recommendation-content">
                <select id="category-select">
                    <option value="motivation">Motivational Quote</option>
                    <option value="inspiration">Inspirational Story</option>
                    <option value="community">Community Highlights</option>
                    <option value="photos">Photos</option>
                    <option value="website">Website</option>
                </select>
                <div class="content-box" id="content-box">
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/test.js"></script>
    <script>
        function fetchRecommendations() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_recommendation.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('recommendations').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        document.getElementById('category-select').addEventListener('change', function() {
            var category = this.value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_recommendation.php?category=' + category, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('content-box').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        });
        fetchRecommendations();
        document.getElementById('category-select').dispatchEvent(new Event('change'));
    </script>
</body>
</html>