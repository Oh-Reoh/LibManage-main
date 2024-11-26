<?php
session_start();
include 'function.php';

// Database connection
$host = 'localhost'; // Your database host
$username = 'root';   // Your database username
$password = '';       // Your database password
$dbname = 'libmanagedb'; // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
        header('Location: LoginPage.php');
        exit();
    }

    // Query the user from the database
    $stmt = $conn->prepare("SELECT * FROM tbl_userinfo WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Clear any previous session variables
            session_unset();

            // Set session variables
            $_SESSION['success_message'] = 'Login successful!';
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Role-based redirection
            if ($user['role'] === 'librarian') {
                header('Location: Dashboard(Librarian).php');
                exit(); // Stop further execution
            } elseif ($user['role'] === 'regular') {
                header('Location: Dashboard(Reader).php');
                exit(); // Stop further execution
            } else {
                $_SESSION['error_message'] = 'Unknown role. Please contact support.';
                header('Location: LoginPage.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'Password is incorrect for the provided username.';
        }
    } else {
        $_SESSION['error_message'] = 'Username not found in the database.';
    }

    $stmt->close();

    // Redirect back to login on failure
    header('Location: LoginPage.php');
    exit();
}

// Password Reset Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $username = $_POST['username'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_new_password'] ?? '';

    // Validate input
    if (empty($username) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
        header('Location: ForgotPass.php');
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: ForgotPass.php');
        exit();
    }

    // Query to check if the username exists
    $stmt = $conn->prepare("SELECT * FROM tbl_userinfo WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the password

        // Update the password in the database
        $updateStmt = $conn->prepare("UPDATE tbl_userinfo SET password = ? WHERE username = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $username);

        if ($updateStmt->execute()) {
            $_SESSION['success_message'] = 'Password reset successfully. Please log in.';
            header('Location: LoginPage.php'); // Redirect to the login page
            exit();
        } else {
            $_SESSION['error_message'] = 'Error updating password. Please try again.';
        }

        $updateStmt->close();
    } else {
        $_SESSION['error_message'] = 'Username not found.';
    }

    $stmt->close();

    // Redirect back to the forgot password page on failure
    header('Location: ForgotPass.php');
    exit();
}
?>
