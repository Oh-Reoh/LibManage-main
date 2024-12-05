<?php
// Start the session and regenerate the session ID
session_start();
session_regenerate_id(true);  // Ensure new session ID for security and isolation

// Include the necessary files
include('db_connect.php'); // Include database connection

// Check if user is logged in and session is set
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit();
}

// Assuming user ID is stored in session after login
$userId = $_SESSION['user_id']; // This fetches the logged-in user's ID

// Fetch user data from the database for the logged-in user
$query = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no profile picture exists, fallback to a default picture
$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';

// Check if there's an error message in session
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear error message after it is used

// Handle form submission to update the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Collect form data
    $username = htmlspecialchars($_POST['username']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $department = htmlspecialchars($_POST['department']);
    $new_password = $_POST['new_password'];

    // Call server.php for validation and error handling
    include 'server.php';

    // If validation succeeds, proceed with updating the profile
    if (isset($_SESSION['success_message'])) {
        // If the password is valid and department is valid, proceed with updating profile
        if (empty($new_password)) {
            // If no password provided, do not update password
            $updateQuery = "UPDATE tbl_userinfo SET username = :username, full_name = :full_name, email = :email, department = :department WHERE id = :userId";
            $stmt = $pdo->prepare($updateQuery);
        } else {
            // If new password provided, hash it and update
            $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE tbl_userinfo SET username = :username, full_name = :full_name, email = :email, department = :department, password = :password WHERE id = :userId";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':password', $hashedPassword);
        }

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Profile updated successfully!';
            header('Location: profile.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Error updating profile. Please try again later.';
            header('Location: profile.php'); // Redirect back to profile page on error
            exit();
        }
    } else {
        // If there were validation errors, redirect back to the profile page with error messages
        header('Location: profile.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="pop-up_add.css">
    <title>User Profile</title>
</head>
<body>
    <div class="dboard_content">
        <!-- SIDEBAR -->
        <section id="sidebar">
            <a href="<?php echo ($userData['role'] === 'librarian') ? 'Dashboard(Librarian).php' : 'Dashboard(Reader).php'; ?>" class="brand">
                <img src="images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
            </a>
            <ul class="side-menu">
                <!-- Dashboard Link -->
                <li><a href="<?php echo ($userData['role'] === 'librarian') ? 'Dashboard(Librarian).php' : 'Dashboard(Reader).php'; ?>" class="active">
                    <img src="images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard</a></li>

                <!-- Books Link -->
                <li><a href="<?php echo ($userData['role'] === 'librarian') ? 'booklist(Librarian).php' : 'booklist(Reader).php'; ?>" class="active">
                    <img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books</a></li>

                <!-- Requests Link -->
                <li><a href="<?php echo ($userData['role'] === 'librarian') ? "Reader'sRequest(Librarian).php" : "Reader'sRequest(Reader).php"; ?>" class="active">
                    <img src="images/readers_request_icon.png" alt="Dashboard Icon" class="icon-therest"> Requests</a></li>
            </ul>
        </section>

        <!-- SIDEBAR -->

        <!-- Main content -->
        <section id="content">
            <nav>
                <i class='bx bx-menu toggle-sidebar'></i>
                <form id="searchForm" action="#" method="GET">
                    <div class="form-group">
                        <input type="text" id="searchInput" placeholder="Search books" oninput="searchFunction()">
                        <i class="bx bx-search icon"></i>
                        <div id="searchResults" class="dropdown"></div>
                    </div>
                </form>

                <div class="profile">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
                    <ul class="profile-link">
                        <li><a href="profile.php"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                        <li><a href="Mainpage.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                    </ul>
                </div>
            </nav>

            <!-- PROFILE MAIN CONTENT -->
            <main>
                <h1 class="title">Profile</h1>
                <div class="data">
                    <div class="profile-card">
                        <div class="profile-header">
                            <!-- Display the user's profile picture -->
                            <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
                            <div class="user-info">
                                <p><span class="label">Username:</span> <?php echo htmlspecialchars($userData['username']); ?></p>
                                <p><span class="label">Full Name:</span> <?php echo htmlspecialchars($userData['full_name']); ?></p>
                                <p><span class="label">Email:</span> <?php echo htmlspecialchars($userData['email']); ?></p>
                                <p><span class="label">Department:</span> <?php echo htmlspecialchars($userData['department']); ?></p>
                                <p><span class="label">Role:</span> <?php echo htmlspecialchars($userData['role']); ?></p>
                            </div>
                        </div>
                        <div class="profile-details">
                            <button id="openUpdateProfileModal" name="update_profile" class="customize-btn">Update Profile</button>
                        </div>
                    </div>
                </div>
            </main>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <div class="footer-description">
                        <img src="logo.png" alt="Libmanage Logo" class="footer-logo">
                        <p>Where Stories Live: <span class="second-line">Find And Borrow Your Next Great Read.</span></p>
                    </div>
                </div>

                <div class="footer-center">
                    <p>&copy; 2024</p>
                </div>

                <div class="footer-right">
                    <p>Follow Us</p>
                    <div class="footer-socials">
                        <a href="#"><img src="icon-ig.png" alt="Instagram"></a>
                        <a href="#"><img src="icon-fb.png" alt="Facebook"></a>
                        <a href="#"><img src="icon-tw.png" alt="Twitter"></a>
                    </div>
                </div>
                <div class="footer-right-most">
                    <div class="footer-contact">
                        <p>Call Us<span class="second-line">09928571488</span></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal for updating profile information -->
    <div id="updateProfileModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal-btn">&times;</span>
            <h2>Update Profile</h2>
            <form action="server.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_profile">

                <!-- Display any error messages if they exist -->
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="error-message">
                        <p><?php echo $_SESSION['error_message']; ?></p>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['userData']['username']) ? htmlspecialchars($_SESSION['userData']['username']) : htmlspecialchars($userData['username']); ?>" required>

                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo isset($_SESSION['userData']['full_name']) ? htmlspecialchars($_SESSION['userData']['full_name']) : htmlspecialchars($userData['full_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['userData']['email']) ? htmlspecialchars($_SESSION['userData']['email']) : htmlspecialchars($userData['email']); ?>" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?php echo isset($_SESSION['userData']['department']) ? htmlspecialchars($_SESSION['userData']['department']) : htmlspecialchars($userData['department']); ?>" required>

                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture">

                <label for="new_password">New Password (Optional):</label>
                <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current password">

                <div class="profile-details">
                    <button type="submit" name="update_profile" class="modal-submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const updateProfileModal = document.getElementById("updateProfileModal");
            const openUpdateProfileModal = document.getElementById("openUpdateProfileModal");
            const closeModalBtn = document.querySelector(".close-modal-btn");

            if (updateProfileModal && openUpdateProfileModal && closeModalBtn) {
                openUpdateProfileModal.addEventListener("click", function () {
                    updateProfileModal.style.display = "block";
                });

                closeModalBtn.addEventListener("click", function () {
                    updateProfileModal.style.display = "none";
                });

                window.addEventListener("click", function (event) {
                    if (event.target === updateProfileModal) {
                        updateProfileModal.style.display = "none";
                    }
                });
            } else {
                console.error("Modal elements not found! Please check if the modal and buttons are correctly referenced.");
            }
        });
    </script>

    <script src="dashboard.js"></script>
    <script src="search.js"></script>
</body>
</html>
