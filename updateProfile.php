<?php
session_start();
include('db_connect.php'); // Include the PDO connection

// Check if the form is submitted
if (isset($_POST['update_profile'])) {
    // Get user ID from session
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
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
        $targetDirectory = "profilePictures/" . $fileName; // Target directory for uploads
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetDirectory)) {
            $profilePicture = $targetDirectory; // Updated profile picture path
        } else {
            echo "Error uploading the profile picture.";
            exit();
        }
    }

    // Handle password change (optional)
    if (!empty($new_password)) {
        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        // Update query with password change
        $query = "UPDATE tbl_userinfo SET 
                    username = :username, 
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
                    email = :email, 
                    department = :department, 
                    profile_picture = :profile_picture
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
    }

    // Bind the remaining parameters and execute the update query
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':profile_picture', $profilePicture);
    $stmt->bindParam(':id', $userId);

    // Execute the query
    try {
        if ($stmt->execute()) {
            // If successful, redirect to the profile page
            header('Location: profile.php');
            exit();
        } else {
            echo "Error updating profile.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch user data from the database for pre-filling the form
$userId = $_SESSION['user_id'];
$query = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Default profile picture if not set
$profilePic = $userData['profile_picture'] ? $userData['profile_picture'] : 'images/default.jpg';
?>

<!-- Profile Section with Editable Fields for Update -->
<div class="profile-card">
    <div class="profile-header">
        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
        <div class="user-info">
            <!-- Form for updating user information -->
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <p><span class="label">Username:</span>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                </p>
                <p><span class="label">Full Name:</span>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($userData['full_name']); ?>" required>
                </p>
                <p><span class="label">Email:</span>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                </p>
                <p><span class="label">Department:</span>
                    <input type="text" name="department" value="<?php echo htmlspecialchars($userData['department']); ?>" required>
                </p>
                <p><span class="label">Role:</span>
                    <input type="text" name="role" value="<?php echo htmlspecialchars($userData['role']); ?>" disabled>
                </p>
                <p><span class="label">Profile Picture:</span>
                    <input type="file" name="profile_picture">
                </p>
                <p><span class="label">New Password:</span>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                </p>

                <div class="profile-details">
                    <button type="submit" name="update_profile" class="customize-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
