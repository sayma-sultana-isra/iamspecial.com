<?php
session_start();
include_once '../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$postId = $data['post_id'];
$commentContent = $data['comment'];

$stmt = $conn->prepare("INSERT INTO Comments (post_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param('iis', $postId, $userId, $commentContent);
$stmt->execute();

$commentId = $stmt->insert_id;
$stmt->close();

$commentQuery = "
    SELECT Comments.*, User.username 
    FROM Comments
    JOIN User ON Comments.user_id = User.user_id
    WHERE Comments.comment_id = ?
";

$stmt = $conn->prepare($commentQuery);
$stmt->bind_param('i', $commentId);
$stmt->execute();
$commentResult = $stmt->get_result();
$comment = $commentResult->fetch_assoc();
$stmt->close();

echo json_encode(['success' => true, 'comment' => $comment]);
?>