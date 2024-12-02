<?php
include('db_connect.php'); // Include database connection

// Check if user is logged in and session is set
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: LoginPage.php");
    exit();
}

// Assuming user ID is stored in session after login
$userId = $_SESSION['user_id'];

// Fetch user data from the database
$query = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data is retrieved, otherwise handle the error
if (!$userData) {
    // If no user data is found, display an error message
    echo "<p>User not found!</p>";
    exit();
}

$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';

// Return only the profile picture as JSON response
echo json_encode([
    'profile_picture' => $profilePic
]);
?>
