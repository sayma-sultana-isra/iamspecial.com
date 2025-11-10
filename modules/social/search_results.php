<?php
require_once '../../config/config.php'; 

$page_title = "Search Results";
include_once '../../includes/header.php';


$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$username = isset($_GET['username']) ? trim($_GET['username']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

if (!empty($search_query)) {
    $stmt = $conn->prepare("INSERT INTO search (keywords, category, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $search_query, $category);
    $stmt->execute();
    $stmt->close();
}

function performSearch($conn, $search_query, $filter_type, $category, $username, $start_date, $end_date) {
    $search_param = "%{$search_query}%";
    $params = [];
    $types = "";

    $query = "SELECT type, id, content, created_at, username, event_title, event_location, event_date, group_name, user_id, category
              FROM (
                  SELECT 'post' AS type, p.post_id AS id, p.content, p.created_at, u.username, NULL AS event_title, NULL AS event_location, NULL AS event_date, NULL AS group_name, u.user_id, p.category
                  FROM post p
                  JOIN user u ON p.user_id = u.user_id
                  WHERE (p.content LIKE ? OR u.username LIKE ?) AND p.privacy = 'public'

                  UNION ALL

                  SELECT 'event' AS type, e.event_id AS id, e.description AS content, e.date AS created_at, u.username, e.title AS event_title, e.location AS event_location, e.date AS event_date, NULL AS group_name, u.user_id, e.category
                  FROM events e
                  JOIN user u ON e.organizer_id = u.user_id
                  WHERE (e.title LIKE ? OR e.description LIKE ? OR e.location LIKE ?)

                  UNION ALL

                  SELECT 'user' AS type, u.user_id AS id, NULL AS content, u.created_at, u.username, NULL AS event_title, NULL AS event_location, NULL AS event_date, NULL AS group_name, u.user_id, NULL AS category
                  FROM user u
                  WHERE u.username LIKE ?
              ) AS combined_results
              WHERE 1=1";

    $params = [$search_param, $search_param, $search_param, $search_param, $search_param, $search_param];
    $types = "ssssss";

    if ($filter_type !== 'all') {
        $query .= " AND type = ?";
        $params[] = $filter_type;
        $types .= "s";
    }

    if ($category !== 'all') {
        $query .= " AND category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if (!empty($username)) {
        $query .= " AND username = ?";
        $params[] = $username;
        $types .= "s";
    }

    if (!empty($start_date)) {
        $query .= " AND created_at >= ?";
        $params[] = $start_date;
        $types .= "s";
    }

    if (!empty($end_date)) {
        $query .= " AND created_at <= ?";
        $params[] = $end_date;
        $types .= "s";
    }

    $query .= " GROUP BY type, id ORDER BY created_at DESC";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        throw new mysqli_sql_exception("Failed to prepare query: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = [];
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
    return $results;
}

$search_results = !empty($search_query) ? performSearch($conn, $search_query, $filter_type, $category, $username, $start_date, $end_date) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 50;
            padding: 50;
        }

        .content {
            padding-top: 150px; /* Adjusted to account for the fixed navbar */
            max-width: 1200px;
            margin: auto;
            padding: 90px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-option {
            margin-right: 15px;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            cursor: pointer;
        }

        .filter-option.active {
            background: #33cc33;
            color: white;
            border-color: #33cc33;
        }

        .search-results {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }

        .result-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .result-type {
            font-size: 0.8em;
            color: #33cc33;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .result-title {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .result-meta {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="content">
        <div class="filters">
            <button class="filter-option <?php echo $filter_type === 'all' ? 'active' : ''; ?>" data-type="all">All</button>
            <button class="filter-option <?php echo $filter_type === 'post' ? 'active' : ''; ?>" data-type="post">Posts</button>
            <button class="filter-option <?php echo $filter_type === 'event' ? 'active' : ''; ?>" data-type="event">Events</button>
            <button class="filter-option <?php echo $filter_type === 'user' ? 'active' : ''; ?>" data-type="user">Users</button>

            <input type="text" id="usernameFilter" placeholder="Filter by username" value="<?php echo htmlspecialchars($username); ?>">
            <select name="category" id="categoryFilter">
                <option value="general" <?php echo $category === 'general' ? 'selected' : ''; ?>>General</option>
                <option value="epilepsy" <?php echo $category === 'epilepsy' ? 'selected' : ''; ?>>Epilepsy</option>
                <option value="Bipolar Disorder" <?php echo $category === 'Bipolar Disorder' ? 'selected' : ''; ?>>Bipolar Disorder</option>
                <option value="feeding" <?php echo $category === 'feeding' ? 'selected' : ''; ?>>Feeding/Eating Issues</option>
                <option value="sleep" <?php echo $category === 'sleep' ? 'selected' : ''; ?>>Sleep Disruption</option>
                <option value="ADHD" <?php echo $category === 'ADHD' ? 'selected' : ''; ?>>ADHD</option>
                <option value="anxiety" <?php echo $category === 'anxiety' ? 'selected' : ''; ?>>Anxiety</option>
                <option value="depression" <?php echo $category === 'depression' ? 'selected' : ''; ?>>Depression</option>
                <option value="OCD" <?php echo $category === 'OCD' ? 'selected' : ''; ?>>OCD</option>
                <option value="Schizophrenia" <?php echo $category === 'Schizophrenia' ? 'selected' : ''; ?>>Schizophrenia</option>
                <option value="Down syndrome" <?php echo $category === 'Down syndrome' ? 'selected' : ''; ?>>Down Syndrome</option>
            </select>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" value="<?php echo htmlspecialchars($start_date); ?>">

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" value="<?php echo htmlspecialchars($end_date); ?>">
        </div>

        <div class="search-results">
            <?php if (empty($search_query)): ?>
                <p>Enter a search term to begin</p>
            <?php elseif (empty($search_results)): ?>
                <p>No results found for "<?php echo htmlspecialchars($search_query); ?>"</p>
            <?php else: ?>
                <?php foreach ($search_results as $result): ?>
                    <div class="result-card" data-type="<?php echo $result['type']; ?>">
                        <div class="result-type"><?php echo ucfirst($result['type']); ?></div>
                        
                        <?php if ($result['type'] === 'event'): ?>
                            <div class="result-title"><?php echo htmlspecialchars($result['event_title']); ?></div>
                            <div><?php echo htmlspecialchars($result['content']); ?></div>
                            <div class="result-meta">
                                Location: <?php echo htmlspecialchars($result['event_location']); ?><br>
                                Date: <?php echo date('F j, Y', strtotime($result['event_date'])); ?><br>
                                Posted by: <a href="view_profile.php?user_id=<?php echo $result['user_id']; ?>"><?php echo htmlspecialchars($result['username']); ?></a>
                            </div>
                        <?php elseif ($result['type'] === 'user'): ?>
                            <div class="result-title">
                                <a href="view_profile.php?user_id=<?php echo $result['user_id']; ?>"><?php echo htmlspecialchars($result['username']); ?></a>
                            </div>
                        <?php else: ?>
                            <div><?php echo htmlspecialchars($result['content']); ?></div>
                            <div class="result-meta">
                                Posted by: <a href="view_profile.php?user_id=<?php echo $result['user_id']; ?>"><?php echo htmlspecialchars($result['username']); ?></a><br>
                                Date: <?php echo date('F j, Y', strtotime($result['created_at'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-option');
            const usernameFilter = document.getElementById('usernameFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const startDateFilter = document.getElementById('startDate');
            const endDateFilter = document.getElementById('endDate');
            const currentUrl = new URL(window.location.href);

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.dataset.type;
                    currentUrl.searchParams.set('type', filterType);
                    window.location.href = currentUrl.toString();
                });
            });

            usernameFilter.addEventListener('change', function() {
                currentUrl.searchParams.set('username', this.value);
                window.location.href = currentUrl.toString();
            });

            categoryFilter.addEventListener('change', function() {
                currentUrl.searchParams.set('category', this.value);
                window.location.href = currentUrl.toString();
            });

            startDateFilter.addEventListener('change', function() {
                currentUrl.searchParams.set('start_date', this.value);
                window.location.href = currentUrl.toString();
            });

            endDateFilter.addEventListener('change', function() {
                currentUrl.searchParams.set('end_date', this.value);
                window.location.href = currentUrl.toString();
            });
        });
    </script>
</body>
</html>