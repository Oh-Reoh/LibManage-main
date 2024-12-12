<?php
session_start();
include('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Sanitize and validate input
    $username = htmlspecialchars(trim($_POST['username']));
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $department = htmlspecialchars(trim($_POST['department']));
    $new_password = !empty($_POST['new_password']) ? trim($_POST['new_password']) : null;

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
        header("Location: profile.php");
        exit();
    }

    // Validate password length (if provided)
    if ($new_password && strlen($new_password) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters long.";
        header("Location: profile.php");
        exit();
    }

    // Fetch current profile picture
    $query = "SELECT profile_picture FROM tbl_userinfo WHERE id = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $profilePicture = $userData['profile_picture'];

    // Handle file upload for profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 50 * 1024 * 1024; // 50 MB
        $fileTmpName = $_FILES['profile_picture']['tmp_name'];
        $fileType = mime_content_type($fileTmpName);
        $fileSize = $_FILES['profile_picture']['size'];

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or GIF files are allowed.";
            header("Location: profile.php");
            exit();
        }

        // Validate file size
        if ($fileSize > $maxFileSize) {
            $_SESSION['error_message'] = "File size exceeds the 50 MB limit.";
            header("Location: profile.php");
            exit();
        }

        // Define target directory
        $targetDir = "profilePictures/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            $profilePicture = $targetFilePath; // Update profile picture path
        } else {
            $_SESSION['error_message'] = "Failed to upload the profile picture.";
            header("Location: profile.php");
            exit();
        }
    }

    // Prepare SQL query to update user profile
    $updateQuery = "UPDATE tbl_userinfo 
                    SET username = :username, 
                        full_name = :full_name, 
                        email = :email, 
                        department = :department, 
                        profile_picture = :profile_picture";

    $params = [
        ':username' => $username,
        ':full_name' => $full_name,
        ':email' => $email,
        ':department' => $department,
        ':profile_picture' => $profilePicture,
    ];

    // Include password in the update query if provided
    if ($new_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $updateQuery .= ", password = :password";
        $params[':password'] = $hashedPassword;
    }

    $updateQuery .= " WHERE id = :userId";
    $params[':userId'] = $userId;

    // Execute the update query
    try {
        $stmt = $pdo->prepare($updateQuery);
        if ($stmt->execute($params)) {
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update the profile.";
            header("Location: profile.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['error_message'] = "An error occurred. Please try again.";
        header("Location: profile.php");
        exit();
    }
}
?>
