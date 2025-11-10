<?php
session_start();
include_once '../../config/config.php';
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session_id = $_POST['session_id'];
    $feedback_score = $_POST['feedback_score'];
    $comments = $_POST['comments'];
    
    $stmt = $conn->prepare("INSERT INTO feedback (session_id, feedback_score, comments) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $session_id, $feedback_score, $comments);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>