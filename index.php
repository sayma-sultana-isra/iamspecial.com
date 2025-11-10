<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ParentConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://cdn.prod.website-files.com/65c243352de1c4e472fd29e5/65dbc234c7f8f6609147d3ac_1695575082593x234550197059517630-1708900781407x749810189931684500-dalle-895SX.png') no-repeat center center fixed; /* Replace with your image URL */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .home {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .home h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        .home p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .home a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="home">
        <h1>Welcome to ParentConnect</h1>
        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
            <p>Hello, User!</p>
        <?php else: ?>
            <p>Please <a href="auth/login.php">log in</a> or <a href="auth/signup.php">sign up</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>