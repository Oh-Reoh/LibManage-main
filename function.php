<?php
// Start the session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "libmanagedb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["action"])) {
    if ($_POST["action"] == "register") {
        // Call register function here if implemented
    } elseif ($_POST["action"] == "login") {
        login();
    }
}

// LOGIN
function login() {
    global $conn;

    // Retrieve username and password
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];  // User-entered password

    // Check if user exists in tbl_userinfo
    $userQuery = "SELECT * FROM tbl_userinfo WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);

        // Verify password using password_verify() since stored password is hashed
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION["login"] = true;
            $_SESSION["username"] = $user["username"];
            $_SESSION["id"] = $user["id"];
            $_SESSION["department"] = $user["department"];  // Assuming department column exists
            echo "Login Successful";  // For debugging or AJAX purposes
            header("Location: Dashboard(Librarian).php");  // Redirect to dashboard
            exit();
        } else {
            // Wrong password
            $_SESSION["error_message"] = "Incorrect password. Please try again.";
            header("Location: loginPage.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION["error_message"] = "User not registered. Please sign up.";
        header("Location: loginPage.php");
        exit();
    }
}
?>
