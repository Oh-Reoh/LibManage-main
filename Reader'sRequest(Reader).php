<?php
session_start();
session_regenerate_id(true);  // Regenerate session ID for security and isolation

// Check if the user is logged in and is a reader
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if the user is a reader
if ($_SESSION['role'] !== 'regular') {
    header("Location: Dashboard(Librarian).php");  // Redirect to librarian dashboard if not a reader
    exit();
}

// Include the database connection
include('db_connect.php');

// Fetch the user's book requests (where requestby = userId)
$queryRequests = "SELECT * FROM tbl_bookinfo_logs WHERE requestby = :userId";
$stmtRequests = $pdo->prepare($queryRequests);
$stmtRequests->execute(['userId' => $userId]);

// Fetch the user's book borrow history (where borrowedby = userId)
$queryHistory = "SELECT * FROM tbl_bookinfo_logs WHERE borrowedby = :userId";
$stmtHistory = $pdo->prepare($queryHistory);
$stmtHistory->execute(['userId' => $userId]);

// Fetch the user data (e.g., profile picture)
$queryUser = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmtUser = $pdo->prepare($queryUser);
$stmtUser->execute(['userId' => $userId]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

// If no profile picture exists, fallback to a default picture
$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Book Requests and Borrow History</title>
    <link href="Dashboard(Reader).css" rel="stylesheet">
</head>
<body>
    <div class="dboard_content">
        <!-- SIDEBAR -->
		<section id="sidebar">
			<a href="Dashboard(Reader).php" class="brand">
				<img src="images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
			</a>
			<ul class="side-menu">
				<li>
					<a href="Dashboard(Reader).php" class="active">
						<img src="images/dashboard_icon(sml).png" alt="Dashboard Icon" class="icon-therest"> Dashboard
					</a>
				</li>

				<li>
					<a href="booklist(Reader).php" class="active">
						<img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books
					</a>
				</li>

				<li>
					<a href="Reader'sRequest(Reader).php" class="active">
						<img src="images/readers_request_icon(big).png" alt="Dashboard Icon" class="icon"> Requests
					</a>
				</li>

			</ul>
			
		</section>
		<!-- SIDEBAR -->

	<!-- whole content -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar' ></i>
			<form id="searchForm" action="#" method="GET">
				<div class="form-group">
					<input type="text" id="searchInput" placeholder="Search books" oninput="searchFunction()">
					<i class="bx bx-search icon"></i>
					<div id="searchResults" class="dropdown"></div> <!-- Dropdown for results -->
				</div>
			</form>

			

			<!-- Profile Section -->
			<div class="profile">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
                    <ul class="profile-link">
                        <li><a href="profile.php"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                        <li><a href="logout.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                    </ul>
            </div>
		</nav>
		<!-- NAVBAR -->

            <main>
                <h1 class="title">Book Request Status</h1>
                <div class="data">
                    <div class="content-data">
                        <div class="head">
                            <table>
                                <thead>
                                    <tr>
                                        <th>BOOK NAME</th>
                                        <th>AUTHOR</th>
                                        <th>REQUESTED DATE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display the user's own book requests
                                    while ($row = $stmtRequests->fetch(PDO::FETCH_ASSOC)) {
                                        $status = ($row['isrequest'] == 1) ? 'Pending' : ($row['isrequest'] == 2 ? 'Approved' : 'Denied');
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['bookname']) . "</td>
                                                <td>" . htmlspecialchars($row['author']) . "</td>
                                                <td>" . htmlspecialchars($row['issueddate']) . "</td>
                                                <td>" . $status . "</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </section>
    </div>

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
</body>
</html>
