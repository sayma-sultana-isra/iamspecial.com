<?php
include_once '../../config/config.php';

$postId = $_GET['post_id'];

$likesQuery = "
    SELECT User.username 
    FROM Likes
    JOIN User ON Likes.user_id = User.user_id
    WHERE Likes.post_id = ?
";

$stmt = $conn->prepare($likesQuery);
$stmt->bind_param('i', $postId);
$stmt->execute();
$likesResult = $stmt->get_result();

$likes = [];
while ($like = $likesResult->fetch_assoc()) {
    $likes[] = $like;
}

$stmt->close();

echo json_encode(['likes' => $likes]);
?>