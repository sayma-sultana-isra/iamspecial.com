<?php
session_start();
include_once '../../config/config.php';

// Define default images and upload directories
define('UPLOAD_DIR', '../../uploads/');
define('PROFILE_UPLOAD_DIR', UPLOAD_DIR . 'profile_photos/');
define('COVER_UPLOAD_DIR', UPLOAD_DIR . 'cover_photos/');
define('DEFAULT_PROFILE_PHOTO', 'default-profile.png');
define('DEFAULT_COVER_PHOTO', 'default-cover.jpg');

// Ensure directories for uploads exist
if (!file_exists(PROFILE_UPLOAD_DIR)) {
    mkdir(PROFILE_UPLOAD_DIR, 0777, true);
}
if (!file_exists(COVER_UPLOAD_DIR)) {
    mkdir(COVER_UPLOAD_DIR, 0777, true);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$successMsg = $errorMsg = '';

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Determine profile and cover photo URLs
$profilePhotoUrl = !empty($user['profile_photo']) 
    ? SITE_URL . 'uploads/profile_photos/' . $user['profile_photo'] 
    : SITE_URL . 'assets/images/' . DEFAULT_PROFILE_PHOTO;

$coverPhotoUrl = !empty($user['cover_photo'])
    ? SITE_URL . 'uploads/cover_photos/' . $user['cover_photo']
    : SITE_URL . 'assets/images/' . DEFAULT_COVER_PHOTO;

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        $conn->begin_transaction();

        // Get form input
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $bio = $_POST['bio'];
        $location = $_POST['location'];
        $childrenDetails = $_POST['children_details'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $dateOfBirth = $_POST['date_of_birth'];
        $gender = $_POST['gender'];

        // Update user information
        $updateQuery = "UPDATE user SET first_name = ?, last_name = ?, bio = ?, location = ?, children_details = ?, phone = ?, email = ?, date_of_birth = ?, gender = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssssssi", $firstName, $lastName, $bio, $location, $childrenDetails, $phone, $email, $dateOfBirth, $gender, $userId);

        if ($stmt->execute()) {
            // Handle profile photo upload
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['size'] > 0) {
                $file = $_FILES['profile_photo'];
                $fileName = 'profile_' . $userId . '_' . time() . '_' . basename($file['name']);
                $targetPath = PROFILE_UPLOAD_DIR . $fileName;

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($file['type'], $allowedTypes)) {
                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        // Delete old profile photo if exists
                        if (!empty($user['profile_photo']) && $user['profile_photo'] != DEFAULT_PROFILE_PHOTO) {
                            unlink(PROFILE_UPLOAD_DIR . $user['profile_photo']);
                        }
                        $stmt = $conn->prepare("UPDATE user SET profile_photo = ? WHERE user_id = ?");
                        $stmt->bind_param("si", $fileName, $userId);
                        $stmt->execute();
                    }
                }
            }

            // Handle cover photo upload
            if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['size'] > 0) {
                $file = $_FILES['cover_photo'];
                $fileName = 'cover_' . $userId . '_' . time() . '_' . basename($file['name']);
                $targetPath = COVER_UPLOAD_DIR . $fileName;

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($file['type'], $allowedTypes)) {
                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        // Delete old cover photo if exists
                        if (!empty($user['cover_photo']) && $user['cover_photo'] != DEFAULT_COVER_PHOTO) {
                            unlink(COVER_UPLOAD_DIR . $user['cover_photo']);
                        }
                        $stmt = $conn->prepare("UPDATE user SET cover_photo = ? WHERE user_id = ?");
                        $stmt->bind_param("si", $fileName, $userId);
                        $stmt->execute();
                    }
                }
            }

            $conn->commit();
            $successMsg = "Profile updated successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            throw new Exception("Error updating profile");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $errorMsg = "Error: " . $e->getMessage();
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    if (password_verify($currentPassword, $userData['password'])) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            
            if ($stmt->execute()) {
                $successMsg = "Password updated successfully!";
            } else {
                $errorMsg = "Error updating password.";
            }
        } else {
            $errorMsg = "New passwords do not match.";
        }
    } else {
        $errorMsg = "Current password is incorrect.";
    }
}

// Handle profile photo deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile_photo'])) {
    if (!empty($user['profile_photo']) && $user['profile_photo'] != DEFAULT_PROFILE_PHOTO) {
        unlink(PROFILE_UPLOAD_DIR . $user['profile_photo']);
    }
    $stmt = $conn->prepare("UPDATE user SET profile_photo = NULL WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $successMsg = "Profile photo deleted successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $errorMsg = "Error deleting profile photo.";
    }
}

// Handle cover photo deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cover_photo'])) {
    if (!empty($user['cover_photo']) && $user['cover_photo'] != DEFAULT_COVER_PHOTO) {
        unlink(COVER_UPLOAD_DIR . $user['cover_photo']);
    }
    $stmt = $conn->prepare("UPDATE user SET cover_photo = NULL WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $successMsg = "Cover photo deleted successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $errorMsg = "Error deleting cover photo.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .cover-container {
            position: relative;
            text-align: center;
            margin-bottom: 50px;
        }
        .cover-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .view-profile-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .view-profile-button:hover {
            background: #2980b9;
        }
        .photo-container {
            position: relative;
            text-align: center;
            margin-top: -75px;
        }
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .icon-button {
            position: absolute;
            bottom: 10px;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
        }
        .icon-button:hover {
            background: rgba(0, 0, 0, 0.7);
        }
        .change-icon {
            right: 40px;
        }
        .delete-icon {
            right: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="date"],
        .form-group input[type="password"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .submit-btn {
            background: #3498db;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background: #2980b9;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .password-section h3 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="profile-container">
    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert alert-error"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <div class="cover-container">
        <img src="<?php echo htmlspecialchars($coverPhotoUrl); ?>" alt="Cover Photo" class="cover-preview" id="cover-preview">
        <a href="view_profile.php?" class="view-profile-button">View Profile</a>        <input type="file" name="cover_photo" id="cover-photo-input" accept="image/*" style="display: none;">
        <button type="button" class="icon-button change-icon" onclick="document.getElementById('cover-photo-input').click()">
            <i class="fas fa-camera"></i>
        </button>
        <button type="submit" name="delete_cover_photo" class="icon-button delete-icon" id="delete-cover-photo" style="display: <?php echo empty($user['cover_photo']) ? 'none' : 'block'; ?>;">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="photo-container">
            <img src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="Profile Photo" class="photo-preview" id="profile-preview">
            <input type="file" name="profile_photo" id="profile-photo-input" accept="image/*" style="display: none;">
            <button type="button" class="icon-button change-icon" onclick="document.getElementById('profile-photo-input').click()">
                <i class="fas fa-camera"></i>
            </button>
            <button type="submit" name="delete_profile_photo" class="icon-button delete-icon" id="delete-profile-photo" style="display: <?php echo empty($user['profile_photo']) ? 'none' : 'block'; ?>;">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="children_details">Children Details</label>
            <textarea id="children_details" name="children_details"><?php echo htmlspecialchars($user['children_details'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Select Gender</option>
                <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>

        <button type="submit" name="update_profile" class="submit-btn">Update Profile</button>
    </form>

    <!-- Change Password Section -->
    <div class="password-section">
        <h3>Change Password</h3>
        <form action="" method="POST" id="password-form">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" name="change_password" class="submit-btn">Change Password</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('profile-photo-input').addEventListener('change', function() {
            previewImage(this, 'profile-preview');
            document.getElementById('delete-profile-photo').style.display = 'block';
        });

        document.getElementById('cover-photo-input').addEventListener('change', function() {
            previewImage(this, 'cover-preview');
            document.getElementById('delete-cover-photo').style.display = 'block';
        });
    });
</script>
</body>
</html>