<?php
session_start();
include_once '../../config/config.php';

$commentId = $_GET['comment_id'];
$userId = $_SESSION['user_id'];

// cmnt ownr or pst ownr
$commentCheckQuery = "SELECT Comments.*, Post.user_id AS post_owner_id FROM Comments
                      JOIN Post ON Comments.post_id = Post.post_id
                      WHERE Comments.comment_id = ? AND (Comments.user_id = ? OR Post.user_id = ?)";
$stmt = $conn->prepare($commentCheckQuery);
$stmt->bind_param('iii', $commentId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this comment.']);
    exit();
}

$deleteCommentQuery = "DELETE FROM Comments WHERE comment_id = ?";
$stmt = $conn->prepare($deleteCommentQuery);
$stmt->bind_param('i', $commentId);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
?>
