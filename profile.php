<?php
// Include the necessary files
session_start();
include('db_connect.php'); // Include database connection

// Check if user is logged in and session is set
if (!isset($_SESSION['user_id'])) {
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

// Default profile picture if not set
$profilePic = $userData['profile_picture'] ? $userData['profile_picture'] : 'images/default.jpg';

// Update profile logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Retrieve the form data
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $role = $_POST['role']; // assuming the role can also be updated (if needed)
    
    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        // Get the file extension and make the file name unique
        $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;  // Generate unique file name
        $targetDirectory = "profilePictures/" . $fileName;  // Path where the file will be stored
        
        // Check if the profilePictures directory exists, create it if not
        if (!is_dir('profilePictures')) {
            mkdir('profilePictures', 0755, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetDirectory)) {
            // File uploaded successfully
            $profilePicture = $targetDirectory;
        } else {
            // Handle errors (file not uploaded)
            $profilePicture = $userData['profile_picture']; // Keep old picture if upload fails
        }
    } else {
        // No file uploaded, keep the old profile picture
        $profilePicture = $userData['profile_picture'];
    }

    // Update the user's profile in the database
    $updateQuery = "UPDATE tbl_userinfo SET 
                    username = :username,
                    full_name = :full_name,
                    email = :email,
                    department = :department,
                    profile_picture = :profile_picture
                    WHERE id = :id";

    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        'username' => $username,
        'full_name' => $full_name,
        'email' => $email,
        'department' => $department,
        'profile_picture' => $profilePicture,
        'id' => $userId
    ]);

    // Redirect to the profile page to reflect changes
    header("Location: profile.php");
    exit();
}

include('getUserInfo.php');
$profilePic = isset($userData['profile_picture']) && !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';
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
    <link rel="stylesheet" href="pop-up_add.css"> <!-- Modal Styles -->
    <title>User Profile</title>
</head>
<body>
    <div class="dboard_content">
        <!-- SIDEBAR -->
        <section id="sidebar">
            <a href="Dashboard(Librarian).php" class="brand">
                <img src="images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
            </a>
            <ul class="side-menu">
                <li><a href="Dashboard(Librarian).html" class="active"><img src="images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard</a></li>
                <li><a href="booklist(Librarian).html" class="active"><img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books</a></li>
                <li><a href="Reader'sRequest(Librarian).html" class="active"><img src="images/readers_request_icon.png" alt="Dashboard Icon" class="icon-therest"> Requests</a></li>
            </ul>
        </section>
        <!-- SIDEBAR -->

        <!-- Main content -->
        <section id="content">
            <nav>
                <i class='bx bx-menu toggle-sidebar'></i>
                <form id="searchForm" action="#" method="GET">
                    <div class="form-group">
                        <input type="text" id="searchInput" placeholder="Search books & members" oninput="searchFunction()">
                        <i class="bx bx-search icon"></i>
                        <div id="searchResults" class="dropdown"></div>
                    </div>
                </form>

                <div class="profile">
				<img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
					<ul class="profile-link">
						<li><a href="profile.php"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
						<li><a href="Mainpage.php"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
					</ul>
				</div>
            </nav>

            <!-- PROFILE MAIN CONTENT -->
            <main>
                <h1 class="title">Profile</h1>        
                <div class="data">
                    <div class="profile-card">
                        <div class="profile-header">
                            <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
                            <div class="user-info">
                                <!-- Form for updating user information -->
                                <form action="profile.php" method="POST" enctype="multipart/form-data">
                                    <p><span class="label">Username:</span>
                                        <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                                    </p>
                                    <p><span class="label">Full Name:</span>
                                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($userData['full_name']); ?>" required>
                                    </p>
                                    <p><span class="label">Email:</span>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                    </p>
                                    <p><span class="label">Department:</span>
                                        <input type="text" name="department" value="<?php echo htmlspecialchars($userData['department']); ?>" required>
                                    </p>
                                    <p><span class="label">Role:</span>
                                        <input type="text" name="role" value="<?php echo htmlspecialchars($userData['role']); ?>" disabled>
                                    </p>
                                    <p><span class="label">Profile Picture:</span>
                                        <input type="file" name="profile_picture">
                                    </p>

                                    <div class="profile-details">
                                        <button type="submit" name="update_profile" class="customize-btn">Update Profile</button>
                                    </div>
                                </form>
                            </div>
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

    <script src="Reader'sRequest(Librarian).js"></script>
    <script src="search.js"></script>
</body>
</html>
