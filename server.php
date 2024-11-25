<?php
session_start();
include 'function.php';

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

            // Debugging role assignment (for testing only, remove in production)
            // echo "Role: " . $_SESSION['role'];

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
?>
