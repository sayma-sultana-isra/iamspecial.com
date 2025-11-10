<?php
session_start();
include_once '../../config/config.php';

$postId = $_GET['post_id'];
$userId = $_SESSION['user_id'];

$likeCheckQuery = "SELECT * FROM Likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($likeCheckQuery);
$stmt->bind_param('ii', $userId, $postId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO Likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $userId, $postId);
    $stmt->execute();
    $stmt->close();
    $action = 'liked';
} else {
    $stmt = $conn->prepare("DELETE FROM Likes WHERE user_id = ? AND post_id = ?");
    $stmt->bind_param('ii', $userId, $postId);
    $stmt->execute();
    $stmt->close();
    $action = 'unliked';
}

echo json_encode(['action' => $action]);
?>