<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: rgb(0, 204, 187); /* Brighter Blue */
            --secondary-color: #33cc33; /* Bright Green */
            --text-color: #ffffff; /* White for better contrast */
            --hover-color: rgb(136, 180, 224); /* Darker Blue */
            --shadow-color: rgba(0, 0, 0, 0.1); /* Soft Shadow */
            --notification-bg: #ff9900; /* Bright Orange */
            --color1: #ffffff; /* White */
            --color2: #e6f7ff; /* Light Blue */
            --color3: #d3ffe3; /* Light Green */
            --color4: #f0f0f0; /* Light Grey */
            --bg-gradient: linear-gradient(45deg, #b3cdd1, #ffffff); /* Light Gradient */
            --hover-transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        body {
            background: var(--bg-gradient);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color), #005bb5);
            padding: 0.5rem 2rem;
            box-shadow: 0 2px 10px var(--shadow-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
            transition: var(--hover-transition);
        }

        .navbar:hover {
            box-shadow: 0 4px 20px var(--shadow-color);
        }

        .nav-brand {
            display: flex;
            align-items: center;
        }

        .nav-brand a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-color);
            font-size: 1.4rem;
            font-weight: 700;
            transition: var(--hover-transition);
        }

        .nav-brand a:hover {
            color: var(--hover-color);
        }

        .logo {
            height: 40px;
            width: auto;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
        }

        .search-bar {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            color: var(--text-color);
            width: 250px;
            margin-right: 10px;
            transition: var(--hover-transition);
        }

        .search-bar::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-bar:focus {
            background: rgba(255, 255, 255, 0.4);
            outline: none;
        }

        .nav-icon {
            color: var(--text-color);
            text-decoration: none;
            font-size: 1.4rem;
            padding: 8px;
            border-radius: 50%;
            transition: var(--hover-transition);
            position: relative;
            cursor: pointer;
        }

        .nav-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--notification-bg);
            color: white;
            font-size: 0.7rem;
            padding: 2px 5px;
            border-radius: 10px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--color1);
            min-width: 180px;
            box-shadow: 0 2px 10px var(--shadow-color);
            z-index: 1;
            border-radius: 10px;
            overflow: hidden;
            transition: var(--hover-transition);
        }

        .dropdown-content a {
            color: var(--primary-color);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: var(--hover-transition);
        }

        .dropdown-content a:hover {
            background-color: var(--color4);
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
            }

            .search-bar {
                width: 150px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="<?php echo SITE_URL; ?>modules/social/newsfeed.php">
                <img src="<?php echo SITE_URL; ?>assets/images/logo.png" alt="Logo" class="logo">
                <span class="brand-name"><?php echo SITE_NAME; ?></span>
            </a>
        </div>
        
        <?php if (isLoggedIn()): ?>
            <div class="nav-icons">
                <div class="search-container">
                    <form action="search_results.php" method="GET" id="searchForm">
                        <input type="text" class="search-bar" name="q" placeholder="Search..." required>
                    </form>
                </div>

                
                <a href="<?php echo SITE_URL; ?>modules/social/logtherapy.php" class="nav-icon">
                    <i class="fas fa-list-alt"></i>
                </a>

               

                <a href="<?php echo SITE_URL; ?>modules/reports/qna.php" class="nav-icon">
                  <i class="fas fa-comments"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>modules/reports/resource.php" class="nav-icon">
                   <i class="fas fa-graduation-cap"></i>

                </a>

                <a href="<?php echo SITE_URL; ?>modules/reports/behavioral_feature.php" class="nav-icon">
                   <i class="fas fa-face-meh"></i>


                </a>

                <a href="<?php echo SITE_URL; ?>modules/planner/daily_planner.php" class="nav-icon">
                    <i class="fas fa-calendar-day"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>modules/social/analysis.php" class="nav-icon">
                    <i class="fas fa-chart-line"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>modules/planner/events.php" class="nav-icon">
                    <i class="fas fa-calendar"></i>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <div class="nav-icon">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="<?php echo SITE_URL; ?>modules/social/view_profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
                        <a href="<?php echo SITE_URL; ?>logout.php">Logout</a>
                    </div>
                    
                </div>
            </div>
        <?php endif; ?>
    </nav>
</body>
</html>