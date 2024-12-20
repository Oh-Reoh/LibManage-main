<?php
session_start();
session_regenerate_id(true);  // Regenerate session ID for security and isolation

// Continue with your session check
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

// Database connection
$host = 'localhost';
$dbname = 'libmanagedb';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch user data for the logged-in user (including profile picture)
$query = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no profile picture exists, fallback to a default picture
$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';

// Fetch all books and their current status
$stmt = $pdo->query("
    SELECT b.id, b.bookname, b.author, 
           CASE 
               WHEN b.isinuse = 1 THEN 'Borrowed' 
               ELSE 'On Shelf' 
           END AS book_status,
           l.borrowedby,
           DATE_FORMAT(b.issueddate, '%Y-%m-%d') AS formatted_issueddate
    FROM tbl_bookinfo b
    LEFT JOIN tbl_bookinfo_logs l ON b.bookname = l.bookname AND l.bookisinuse = 1
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="booklist(Librarian).css">
    <link rel="stylesheet" href="pop-up_add.css">
	<link rel="stylesheet" href="search.css">
	<title>Books for Reader</title>
	<style>
        .disabled-book {
            color: gray;
            text-decoration: none;
            cursor: not-allowed;
        }
        .disabled-book:hover {
            text-decoration: underline;
        }
    </style>
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
						<img src="images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard
					</a>
				</li>

				<li>
					<a href="booklist(Reader).php" class="active">
						<img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books
					</a>
				</li>
				<li><a href="Reader'sRequest(Reader).php" class="active"><img src="images/readers_request_icon.png" alt="Dashboard Icon" class="icon-therest"> Requests</a></li>


			</ul>
			
		</section>
		<!-- SIDEBAR -->

	<!-- MAIN CONTENT -->
	<section id="content">
		<nav>
            <i class='bx bx-menu toggle-sidebar' ></i>
			<form id="searchForm" action="#" method="GET">
				<div class="form-group">
					<input type="text" id="searchInput" placeholder="Search books & members" oninput="searchFunction()">
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

		<main>
			<h1 class="title">Books for Readers</h1>
			
		
					
			<!-- BOOKS DATA -->
			<div class="data">
				<div class="container">
					<div class="table-wrapper">
						<table>
							<thead>
								<tr>
									<th>BOOK NAME</th>
									<th>AUTHOR</th>
									<th>BOOK STATUS</th>
									<th>ID</th>
									<th>REGISTERED DATE</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									$bookName = htmlspecialchars($row['bookname']);
									$author = htmlspecialchars($row['author']);
									$bookStatus = $row['book_status'];
									$borrowedBy = $row['borrowedby'];
									$bookId = $row['id'];
									$issueDate = $row['formatted_issueddate'];

									echo "<tr>";
									if ($bookStatus === 'Borrowed' && $borrowedBy !== $userData['username']) {
										// If the book is borrowed by someone else, show a disabled link
										echo "<td><a href='#' class='disabled-book' data-book='$bookName'> $bookName</a></td>";
									} else {
										// Allow the logged-in user to access their borrowed books or available books
										echo "<td><a href='book{$bookId}.php'>$bookName</a></td>";
									}
									echo "
										<td>$author</td>
										<td>$bookStatus</td>
										<td>$bookId</td>
										<td>$issueDate</td>
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
	<!-- MAIN CONTENT -->
	</div>

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
	
	<!-- Script to disable book links for borrowed books -->
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			document.querySelectorAll(".disabled-book").forEach(link => {
				link.addEventListener("click", (event) => {
					const bookName = link.getAttribute("data-book");
					alert(`The book "${bookName}" is currently borrowed and cannot be accessed.`);
					event.preventDefault();
				});
			});
		});
    </script>
	<script src="booklist(Reader).js"></script>
    <script src="booklist(Librarian).js"></script>
    <script src="search.js"></script>
</body>
</html>
