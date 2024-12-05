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
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Role-based redirection
            if ($user['role'] === 'librarian') {
                header('Location: Dashboard(Librarian).php');
                exit();
            } elseif ($user['role'] === 'regular') {
                header('Location: Dashboard(Reader).php');
                exit();
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

    // Validate password length (at least 6 characters)
    if (strlen($newPassword) < 6) {
        $_SESSION['error_message'] = 'Password must be at least 6 characters long.';
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

// Registration Logic (for department and password validation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $department = $_POST['department'] ?? '';

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($department)) {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
        header('Location: RegRegister.php');
        exit();
    }

    // Validate password length (at least 6 characters)
    if (strlen($password) < 6) {
        $_SESSION['error_message'] = 'Password must be at least 6 characters long.';
        header('Location: RegRegister.php');
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: RegRegister.php');
        exit();
    }

    // Validate department (check if the department is valid)
    $departmentQuery = $conn->prepare("SELECT * FROM departments WHERE department_name = ?");
    $departmentQuery->bind_param("s", $department);
    $departmentQuery->execute();
    $deptResult = $departmentQuery->get_result();

    if ($deptResult->num_rows === 0) {
        $_SESSION['error_message'] = 'Invalid department. Please choose a valid department.';
        header('Location: RegRegister.php');
        exit();
    }

    // Check if the username already exists in the database
    $usernameQuery = $conn->prepare("SELECT * FROM tbl_userinfo WHERE username = ?");
    $usernameQuery->bind_param("s", $username);
    $usernameQuery->execute();
    $usernameResult = $usernameQuery->get_result();

    if ($usernameResult->num_rows > 0) {
        $_SESSION['error_message'] = 'User already exists. Please choose a different username.';
        header('Location: RegRegister.php');
        exit();
    }

    // Check if the email already exists in the database
    $emailQuery = $conn->prepare("SELECT * FROM tbl_userinfo WHERE email = ?");
    $emailQuery->bind_param("s", $email);
    $emailQuery->execute();
    $emailResult = $emailQuery->get_result();

    if ($emailResult->num_rows > 0) {
        $_SESSION['error_message'] = 'Email already exists.';
        header('Location: RegRegister.php');
        exit();
    }

    // Hash the password for better security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insertUser = $conn->prepare("INSERT INTO tbl_userinfo (username, email, password, department) VALUES (?, ?, ?, ?)");
    $insertUser->bind_param("ssss", $username, $email, $hashedPassword, $department);

    if ($insertUser->execute()) {
        $_SESSION['success_message'] = 'Registration successful! Please log in.';
        header('Location: LoginPage.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Registration failed. Please try again later.';
    }

    $insertUser->close();
}

// UPDATE PROFILE ERROR HANDLING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    // Collect form data
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $new_password = $_POST['new_password'];

    // Validation for empty fields
    if (empty($username) || empty($full_name) || empty($email) || empty($department)) {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
        $_SESSION['userData'] = $_POST; // Store form data in session for repopulation
        header('Location: profile.php');
        exit();
    }

    // Validate department
    $validDepartments = ['BSPT', 'BSOT', 'BSES', 'BSCS', 'BSIT', 'BSEE', 'BSCpE', 'BSCE', 'BSME', 'BSIE', 'BEE', 'BSCHE', 'BSED'];
    if (!in_array($department, $validDepartments)) {
        $_SESSION['error_message'] = 'Invalid department. Please choose a valid department.';
        $_SESSION['userData'] = $_POST; // Store form data in session
        header('Location: profile.php');
        exit();
    }

    // Check if the username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE username = :username AND id != :userId");
    $stmt->execute(['username' => $username, 'userId' => $_SESSION['user_id']]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error_message'] = 'Username already exists.';
        $_SESSION['userData'] = $_POST; // Store form data in session
        header('Location: profile.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM tbl_userinfo WHERE email = :email AND id != :userId");
    $stmt->execute(['email' => $email, 'userId' => $_SESSION['user_id']]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error_message'] = 'Email already exists.';
        $_SESSION['userData'] = $_POST; // Store form data in session
        header('Location: profile.php');
        exit();
    }

    // If no errors, update the profile
    if (empty($new_password)) {
        $stmt = $pdo->prepare("UPDATE tbl_userinfo SET username = :username, full_name = :full_name, email = :email, department = :department WHERE id = :userId");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':department', $department);
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE tbl_userinfo SET username = :username, full_name = :full_name, email = :email, department = :department, password = :password WHERE id = :userId");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':department', $department);
    }

    $stmt->bindParam(':userId', $_SESSION['user_id']);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Profile updated successfully!';
        header('Location: profile.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Error updating profile. Please try again later.';
        header('Location: profile.php');
        exit();
    }
}

?>
