<?php
session_start(); 
include_once '../../config/config.php';

$userId = $_SESSION['user_id'] ?? null; 

if (!$userId) {
    echo "User not logged in.";
    exit();
}

function fetchCategoryContent($conn, $category) {
    switch ($category) {
        case 'motivation':
            $query = "SELECT motivationcontent AS content FROM MotivationalQuotes ORDER BY RAND() LIMIT 1";
            break;
        case 'inspiration':
            $query = "SELECT inspirationcontent AS content FROM MotivationalQuotes ORDER BY RAND() LIMIT 1";
            break;
        case 'community':
            $query = "SELECT content FROM post ORDER BY RAND() LIMIT 1";
            break;
        case 'photos':
            $query = "SELECT url AS content FROM MotivationalQuotes ORDER BY RAND() LIMIT 1";
            break;
        case 'website':
            $query = "SELECT url AS content FROM MotivationalQuotes ORDER BY RAND() LIMIT 1";
            break;
        default:
            echo json_encode(["error" => "Invalid category."]);
            return;
    }

    $result = $conn->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        echo htmlspecialchars($row['content']);
    } else {
        echo "No content available.";
    }
}



function fetchUserRecommendations($conn, $userId) {
    $userQuery = "SELECT children_details, location FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $userData = $userResult->fetch_assoc();
    $stmt->close();

    $childrenDetails = $userData['children_details'];
    $location = $userData['location'];

    $query = "
        SELECT user_id, username, profile_photo, location, children_details
        FROM user
        WHERE (children_details LIKE ? OR location = ?) AND user_id != ?
        LIMIT 3
    ";
    $stmt = $conn->prepare($query);
    $param = "%$childrenDetails%";
    $stmt->bind_param('ssi', $param, $location, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $profiles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Display profiles
    if (empty($profiles)) {
        echo "We are extremely sorry, we did not find anyone with the same condition or location, but don't worry, we are here.";
    } else {
        foreach ($profiles as $profile) {
            $mentionText = '';

            // Check if both children details and location match
            if (stripos($profile['children_details'], $childrenDetails) !== false && $profile['location'] === $location) {
                $mentionText .= 'Children Details: ' . htmlspecialchars($profile['children_details']) . ' | Location: ' . htmlspecialchars($profile['location']);
            } elseif (stripos($profile['children_details'], $childrenDetails) !== false) {
                $mentionText .= 'Children Details: ' . htmlspecialchars($profile['children_details']);
            } elseif ($profile['location'] === $location) {
                $mentionText .= 'Location: ' . htmlspecialchars($profile['location']);
            }

            // Create a clickable link for the username
            $profileLink = 'view_profile.php?user_id=' . urlencode($profile['user_id']);

            echo '<div class="recommendation">';
            echo '  <div class="profile-info">';
            echo '      <img src="' . htmlspecialchars($profile['profile_photo']) . '" alt="Profile Picture">';
            echo '      <a href="' . htmlspecialchars($profileLink) . '"><strong>' . htmlspecialchars($profile['username']) . '</strong></a>';
            echo '  </div>';
            echo '  <p>' . htmlspecialchars($mentionText) . '</p>';
            echo '</div>';
        }
    }
}

// Determine what to fetch based on the presence of the category parameter
if (isset($_GET['category'])) {
    fetchCategoryContent($conn, $_GET['category']);
} else {
    fetchUserRecommendations($conn, $userId);
}
?>