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

// Check if there's an error or success message in session
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message'], $_SESSION['success_message']); // Clear messages
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
    <style>
        .popup-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4caf50; /* Green for success */
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            font-size: 16px;
            display: none;
        }

        .popup-message.error {
            background-color: #f44336; /* Red for errors */
        }
    </style>
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
                    <p>&copy; ALPHA ONE 2024</p>
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
    <!-- Display error or success messages -->    
    <?php if ($successMessage): ?>
        <div class="popup-message" id="successPopup"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="popup-message error" id="errorPopup"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- Modal for updating profile information -->
    <div id="updateProfileModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal-btn">&times;</span>
            <h2>Update Profile</h2>
            <form action="updateProfile.php" method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>

                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userData['full_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($userData['department']); ?>" required>

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

    <!-- Add scripts for modal and pop-up -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Modal functionality
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
            }
        });
    </script>

    <script>
        // Handle pop-up messages
        document.addEventListener("DOMContentLoaded", function() {
            const successPopup = document.getElementById("successPopup");
            const errorPopup = document.getElementById("errorPopup");

            if (successPopup) {
                successPopup.style.display = "block";
                setTimeout(() => {
                    successPopup.style.display = "none";
                }, 3000); // Auto-hide after 3 seconds
            }

            if (errorPopup) {
                errorPopup.style.display = "block";
                setTimeout(() => {
                    errorPopup.style.display = "none";
                }, 3000); // Auto-hide after 3 seconds
            }
        });
    </script>   


    <script src="dashboard.js"></script>
    <script src="search.js"></script>
</body>
</html>
