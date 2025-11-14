<?php
// Include database configuration
include_once '../../config/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_question'])) {
    $question_text = $_POST['question_text'];

    $stmt = $conn->prepare("INSERT INTO questions (user_id, question_text) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $question_text);

    if ($stmt->execute()) {
        $message = "Question posted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_answer'])) {
    $question_id = $_POST['question_id'];
    $answer_text = $_POST['answer_text'];

    $stmt = $conn->prepare("INSERT INTO answers (question_id, user_id, answer_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $question_id, $user_id, $answer_text);

    if ($stmt->execute()) {
        $message = "Answer submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all questions with their answers, but ensure only one question and its answers are displayed at a time
$query = "
    SELECT 
        q.question_id, 
        q.question_text, 
        q.created_at AS question_time,
        u.username AS asker,
        a.answer_id, 
        a.answer_text, 
        a.created_at AS answer_time,
        u2.username AS answerer
    FROM 
        questions q
    LEFT JOIN 
        user u ON q.user_id = u.user_id
    LEFT JOIN 
        answers a ON q.question_id = a.question_id
    LEFT JOIN 
        user u2 ON a.user_id = u2.user_id
    ORDER BY 
        q.created_at DESC, a.created_at ASC;
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A Session</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container, .question-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        textarea, input[type="text"], button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }
        .question, .answer {
            margin: 10px 0;
        }
        .asker, .answerer {
            font-weight: bold;
        }
        .time {
            font-size: 0.9em;
            color: gray;
        }
    </style>
</head>
<body>
    <h1>Q&A Session</h1>

    <!-- Display success/error messages -->
    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form to ask a question -->
    <div class="form-container">
        <h2>Ask a Question</h2>
        <form method="POST" action="">
            <textarea name="question_text" rows="4" placeholder="Type your question here..." required></textarea>
            <button type="submit" name="submit_question">Post Question</button>
        </form>
    </div>

    <!-- Display all questions and answers -->
    <div class="question-container">
        <h2>Questions and Answers</h2>
        <?php 
        $current_question_id = null;
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()): 
                // Display question only once
                if ($current_question_id !== $row['question_id']):
                    $current_question_id = $row['question_id'];
        ?>
                    <div class="question">
                        <p><span class="asker"><?= htmlspecialchars($row['asker']); ?></span> asked:</p>
                        <p><?= htmlspecialchars($row['question_text']); ?></p>
                        <p class="time">Posted on <?= htmlspecialchars($row['question_time']); ?></p>

                        <!-- Form to answer the question -->
                        <form method="POST" action="">
                            <input type="hidden" name="question_id" value="<?= $row['question_id']; ?>">
                            <textarea name="answer_text" rows="2" placeholder="Type your answer here..." required></textarea>
                            <button type="submit" name="submit_answer">Submit Answer</button>
                        </form>
                    </div>
        <?php 
                endif;  // End of question display

                // Display answers for the current question
                if (!empty($row['answer_text'])):
        ?>
                    <div class="answer">
                        <p><span class="answerer"><?= htmlspecialchars($row['answerer']); ?></span> answered:</p>
                        <p><?= htmlspecialchars($row['answer_text']); ?></p>
                        <p class="time">Answered on <?= htmlspecialchars($row['answer_time']); ?></p>
                    </div>
        <?php 
                endif;  // End of answers display
            endwhile;
        else: 
        ?>
            <p>No questions available. Be the first to ask!</p>
        <?php endif; ?>
    </div>
</body>
</html>  
