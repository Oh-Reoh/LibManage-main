<?php
// Start the session
session_start();

// Unset all session variables
session_unset();  // This clears all session variables

// Destroy the session
session_destroy();  // This ends the session

// Redirect the user to the login page or another appropriate page
header("Location: LoginPage.php");  // Redirect to the login page
exit();
?>
