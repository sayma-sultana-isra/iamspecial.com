<?php
include_once '../../config/config.php';

$postId = $_GET['post_id'];

$commentsQuery = "
    SELECT Comments.*, User.username 
    FROM Comments
    JOIN User ON Comments.user_id = User.user_id
    WHERE Comments.post_id = ?
    ORDER BY Comments.created_at ASC
";

$stmt = $conn->prepare($commentsQuery);
$stmt->bind_param('i', $postId);
$stmt->execute();
$commentsResult = $stmt->get_result();

$comments = [];
while ($comment = $commentsResult->fetch_assoc()) {
    $comments[] = $comment;
}

$stmt->close();

echo json_encode(['comments' => $comments]);
?>