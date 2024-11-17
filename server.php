<?php
session_start();
include 'function.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if username and password are not empty
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
        header('Location: LoginPage.php');
        exit();
    }

    // Prepare SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM tbl_userinfo WHERE username = ?");
    if (!$stmt) {
        $_SESSION['error_message'] = 'Database error. Please try again later.' . $conn->error;
        header('Location: LoginPage.php');
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verify password if username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password against the hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['success_message'] = 'Login successful!';
            $_SESSION['username'] = $username; // Store session for logged-in user
            $_SESSION['role'] = $user['role']; // Store user role in session

            // Redirect based on user role
            if ($_SESSION['role'] == 'librarian') {
                header('Location: Dashboard(Librarian).php'); // Redirect to Librarian Dashboard
            } else if ($_SESSION['role'] == 'regular') {
                header('Location: Dashboard(Reader).php'); // Redirect to Reader Dashboard
            } else {
                $_SESSION['error_message'] = 'Role not recognized. Please contact support.';
                header('Location: LoginPage.php');
                exit();
            }
            exit();
        } else {
            $_SESSION['error_message'] = 'Incorrect username or password.';
        }
    } else {
        $_SESSION['error_message'] = 'Incorrect username or password.';
    }

    $stmt->close();
    header('Location: LoginPage.php'); // Redirect back to login page if authentication fails
    exit();
}
?>
