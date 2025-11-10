<?php
session_start();
include_once '../../config/config.php';

$userId = $_GET['user_id'];
$followerUserId = $_SESSION['user_id'];

if ($userId == $followerUserId) {
    echo json_encode(['error' => 'You cannot follow yourself.']);
    exit();
}

$followCheckQuery = "SELECT * FROM followers WHERE user_id = ? AND follower_user_id = ?";
$stmt = $conn->prepare($followCheckQuery);
$stmt->bind_param('ii', $userId, $followerUserId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO followers (user_id, follower_user_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $userId, $followerUserId);
    $stmt->execute();
    $stmt->close();
    $action = 'followed';
} else {
    $stmt = $conn->prepare("DELETE FROM followers WHERE user_id = ? AND follower_user_id = ?");
    $stmt->bind_param('ii', $userId, $followerUserId);
    $stmt->execute();
    $stmt->close();
    $action = 'unfollowed';
}

$followerCountQuery = "SELECT COUNT(*) AS follower_count FROM followers WHERE user_id = ?";
$stmt = $conn->prepare($followerCountQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$followerCountResult = $stmt->get_result();
$followerCount = $followerCountResult->fetch_assoc()['follower_count'];
$stmt->close();

$followingCountQuery = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_user_id = ?";
$stmt = $conn->prepare($followingCountQuery);
$stmt->bind_param('i', $followerUserId);
$stmt->execute();
$followingCountResult = $stmt->get_result();
$followingCount = $followingCountResult->fetch_assoc()['following_count'];
$stmt->close();

echo json_encode([
    'action' => $action, 
    'follower_count' => $followerCount,
    'following_count' => $followingCount
]);
?>