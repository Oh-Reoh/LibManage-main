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

// Fetch the username for the logged-in user
$queryUser = "SELECT username FROM tbl_userinfo WHERE id = :userId";
$stmtUser = $pdo->prepare($queryUser);
$stmtUser->execute(['userId' => $userId]);
$username = $stmtUser->fetchColumn();

if (!$username) {
    error_log("No username found for userId {$userId}");
    exit("Error: Unable to fetch user information.");
}

error_log("Fetched username for userId {$userId}: {$username}");

// Fetch the user's book requests
$queryRequests = "
    SELECT DISTINCT bookname, author, requestdate, issueddate, returndate, 
        CASE 
            WHEN isrequest = 1 THEN 'Pending' 
            WHEN isrequest = 0 THEN 'Approved' 
            WHEN isrequest = 3 THEN 'Denied' 
            ELSE 'Unknown'
        END AS status 
    FROM tbl_bookinfo_logs 
    WHERE requestby = :username
";
$stmtRequests = $pdo->prepare($queryRequests);
$stmtRequests->execute(['username' => strtolower(trim($username))]);

// Fetch all rows
$rows = $stmtRequests->fetchAll(PDO::FETCH_ASSOC);
error_log("Reader requests for username {$username}: " . json_encode($rows));

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
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Bakbak One' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="Dashboard(Reader).css" rel="stylesheet">
    <link rel="stylesheet" href="search.css">

    <title>Your Book Requests and Borrow History</title>
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
                                        <th>ISSUED DATE</th>
                                        <th>RETURN DATE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($rows)): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['bookname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                                            <td><?php echo htmlspecialchars($row['requestdate'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($row['issueddate'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($row['returndate'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No requests found.</td>
                                    </tr>
                                <?php endif; ?>
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

    <script src="Reader'sRequest(Librarian).js"></script>
	<script src="search.js"></script>
</body>
</html>
