<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "libmanagedb");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/**
 * Authenticate a user by username and password.
 *
 * @param string $username The username input.
 * @param string $password The plaintext password input.
 * @return array|false Returns user details on success or false on failure.
 */
function authenticateUser($username, $password) {
    global $conn;

    // Use a prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM tbl_userinfo WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            return $user; // Successful authentication
        }
    }

    $stmt->close();
    return false; // Authentication failed
}

/**
 * Login a user by setting session variables.
 *
 * @param array $user The authenticated user data.
 */
function loginUser($user) {
    $_SESSION["login"] = true;
    $_SESSION["username"] = $user["username"];
    $_SESSION["id"] = $user["id"];
    $_SESSION["department"] = $user["department"];
    $_SESSION["role"] = $user["role"];
}

/**
 * Handle login action.
 */
function handleLogin() {
    global $conn;

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic input validation
    if (empty($username) || empty($password)) {
        $_SESSION["error_message"] = "Please enter both username and password.";
        header("Location: loginPage.php");
        exit();
    }

    // Authenticate the user
    $user = authenticateUser($username, $password);

    if ($user) {
        loginUser($user);

        // Redirect based on role
        if ($user["role"] === "librarian") {
            header("Location: Dashboard(Librarian).php");
        } else {
            header("Location: Dashboard(Reader).php");
        }
        exit();
    } else {
        // Login failed
        $_SESSION["error_message"] = "Incorrect username or password.";
        header("Location: loginPage.php");
        exit();
    }
}

// End of PHP script
?>
