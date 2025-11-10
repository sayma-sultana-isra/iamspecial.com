<?php
session_start();
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$postId = $_GET['post_id'];
$userId = $_SESSION['user_id'];


$postCheckQuery = "SELECT * FROM Post WHERE post_id = ? AND user_id = ?";
$stmt = $conn->prepare($postCheckQuery);
$stmt->bind_param('ii', $postId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this post.']);
    $stmt->close();
    exit();
}


$post = $result->fetch_assoc();
$stmt->close();


$photo = $post['photo'];
if (!empty($photo)) {
    $photoPath = "../../uploads/" . $photo;
    if (file_exists($photoPath)) {
        if (!unlink($photoPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the post photo.']);
            exit();
        }
    }
}


$deletePostQuery = "DELETE FROM Post WHERE post_id = ?";
$stmt = $conn->prepare($deletePostQuery);
$stmt->bind_param('i', $postId);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete the post from the database.']);
    $stmt->close();
    exit();
}
$stmt->close();


$deleteCommentsQuery = "DELETE FROM Comments WHERE post_id = ?";
$stmt = $conn->prepare($deleteCommentsQuery);
$stmt->bind_param('i', $postId);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete associated comments.']);
    $stmt->close();
    exit();
}
$stmt->close();


echo json_encode(['success' => true, 'message' => 'Post and associated comments deleted successfully.']);
?>
