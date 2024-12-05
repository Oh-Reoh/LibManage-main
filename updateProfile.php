<?php
session_start();
include('db_connect.php'); // Include the PDO connection

// Check if user is logged in and session is set
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id']; // This will fetch the logged-in user's ID

// Check if the form is submitted
if (isset($_POST['update_profile'])) {
    // Retrieve the form data
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $new_password = $_POST['new_password']; // New password (if entered)

    // Fetch current user data from the database for profile picture handling
    $query = "SELECT profile_picture FROM tbl_userinfo WHERE id = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle profile picture upload
    $profilePicture = $userData['profile_picture']; // Default to current profile picture

    if ($_FILES['profile_picture']['name']) {
        // Get file extension and generate a unique name
        $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;  // Unique file name

        // Define target directory
        $targetDirectory = "profilePictures/" . $fileName; // Path where the file will be stored

        // Check if the profilePictures directory exists, create it if not
        if (!is_dir('profilePictures')) {
            // Attempt to create the directory if it doesn't exist
            if (!mkdir('profilePictures', 0755, true)) {
                die('Failed to create profilePictures directory. Please check permissions.');
            }
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetDirectory)) {
            // File uploaded successfully
            $profilePicture = $targetDirectory; // Update profile picture path
        } else {
            // Handle errors (file not uploaded)
            $profilePicture = $userData['profile_picture']; // Keep old picture if upload fails
        }
    }

    // Handle password change (optional)
    if (!empty($new_password)) {
        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        // Update query with password change
        $query = "UPDATE tbl_userinfo SET 
                    username = :username, 
                    full_name = :full_name,
                    email = :email, 
                    department = :department, 
                    profile_picture = :profile_picture, 
                    password = :password 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
    } else {
        // If no password change, exclude password from the update
        $query = "UPDATE tbl_userinfo SET 
                    username = :username, 
                    full_name = :full_name,
                    email = :email, 
                    department = :department, 
                    profile_picture = :profile_picture 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
    }

    // Bind the remaining parameters and execute the update query
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':profile_picture', $profilePicture);
    $stmt->bindParam(':id', $userId);

    // Execute the query
    try {
        if ($stmt->execute()) {
            // If successful, redirect to profile.php to show updated information
            header("Location: profile.php"); // Proper redirection
            exit();
        } else {
            echo "Error updating profile.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
