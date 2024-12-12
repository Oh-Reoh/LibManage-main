<?php
// Start the session and regenerate the session ID
session_start();
session_regenerate_id(true);  // Regenerate session ID for security and isolation

// Include the database connection
include('db_connect.php'); // Ensure this is the correct path to your db_connect.php file

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

// Fetch user data from the database
$query = "SELECT full_name, username, profile_picture FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Set the user's name to either full_name or username
$userName = !empty($userData['full_name']) ? $userData['full_name'] : $userData['username'];

// If no profile picture exists, fallback to a default picture
$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';

// Assuming $username is available from the session
$username = $_SESSION['username'];

// Fetch the count of books currently borrowed by the user
$query = "SELECT COUNT(*) as borrowed_books 
          FROM tbl_bookinfo_logs 
          WHERE borrowedby = :username AND bookisinuse = 1";
$stmt = $pdo->prepare($query);
$stmt->execute(['username' => $username]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a valid result is returned
$borrowedBooksCount = $result ? $result['borrowed_books'] : 0; // Default to 0 if no result
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="Dashboard(Reader).css">
	<link rel="stylesheet" href="pop-up_add.css">
	<link rel="stylesheet" href="search.css">
	<title>Dashboard</title>

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

	<!-- NAVBAR -->
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

			<!-- Borrow Book Button in Reader's Dashboard -->
			<button class="add-book-btn" id="borrowBookBtn">Borrow Book</button>

			<button class="add-book-btn" id="returnBookBtn">Return Book</button>

			<div class="profile">
				<img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-img">
				<ul class="profile-link">
					<li><a href="profile.php"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="logout.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Dashboard</h1>
			
			<!-- INFO DATA -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $borrowedBooksCount; ?></h2>
							<p>Currently Borrowed Books</p>
						</div>
						<img src="images/borrowed-icon.png" alt="Borrowed Books Icon" class="icon borrowed-icon">
					</div>
				</div>
			</div>
			<!-- INFO DATA -->		
						
	<div class="data">
		<div class="container">
			<div class="table-wrapper">
				<div class="content-data">
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
                                    // Fetch all books and check who borrowed them
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

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $bookName = htmlspecialchars($row['bookname']);
                                        $author = htmlspecialchars($row['author']);
                                        $bookStatus = $row['book_status'];
                                        $borrowedBy = $row['borrowedby'];
                                        $bookId = $row['id'];
                                        $issueDate = $row['formatted_issueddate'];

                                        echo "<tr>";
                                        if ($bookStatus === 'Borrowed' && $borrowedBy !== $username) {
                                            echo "<td><a href='#' class='disabled-book' data-book='$bookName'> $bookName</a></td>";
                                        } else {
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
			</div>
		</div>
	</div>

	</main>
	<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
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

	<!-- Modal for Borrowing a Book -->
	<div id="borrowBookModal" class="modal">
		<div class="modal-content">
			<span id="closeBorrowModal" class="close-modal-btn">&times;</span>
			<h2>Request Book</h2>
			<form id="borrowForm" action="requestBook.php" method="POST">
				<label for="bookname">Select Book:</label>
				<select name="bookname" required>
					<option value="" disabled selected>Select a book</option>
					<?php
					// Fetch all available books from the tbl_bookinfo table (books that are not yet borrowed)
					$stmt = $pdo->query("SELECT * FROM tbl_bookinfo WHERE isinuse = 0"); // Only available books
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						echo "<option value='{$row['bookname']}'>{$row['bookname']} by {$row['author']}</option>";
					}
					?>
				</select>

				<label for="borrowerName">Your Name:</label>
				<input type="text" id="borrowerName" name="borrowerName" value="<?php echo htmlspecialchars($userName); ?>" placeholder="Enter your full name" required>

				<button type="submit" name="submitRequest" class="submit-btn" id="uploadButton">Submit Request</button>
			</form>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			// Check if the success parameter is in the URL
			const urlParams = new URLSearchParams(window.location.search);
			const success = urlParams.get('success');

			if (success === 'requested') {
				// Show the pop-up message for a successful request
				alert("Your request has been successfully sent.");

				// Optional: Clear the success parameter from the URL to prevent repeated alerts
				history.replaceState(null, null, window.location.pathname);
			}

			// Open the Borrow Book Modal when the "Borrow Book" button is clicked
			const borrowBookBtn = document.getElementById("borrowBookBtn");
			if (borrowBookBtn) {
				borrowBookBtn.onclick = function () {
					document.getElementById("borrowBookModal").style.display = "block";
				};
			}

			// Close the Borrow Book Modal when the "X" button is clicked
			const closeBorrowModal = document.getElementById("closeBorrowModal");
			if (closeBorrowModal) {
				closeBorrowModal.onclick = function () {
					document.getElementById("borrowBookModal").style.display = "none";
				};
			}

			// Close the Borrow Book Modal if the user clicks anywhere outside the modal content
			window.onclick = function (event) {
				const borrowBookModal = document.getElementById("borrowBookModal");
				if (event.target == borrowBookModal) {
					borrowBookModal.style.display = "none";
				}
			};
		});
	</script>


	<!-- Modal for Returning a Book -->
	<div id="returnBookModal" class="modal">
		<div class="modal-content">
			<span id="closeReturnModal" class="close-modal-btn">&times;</span>
			<h2>Return Book</h2>
			<form id="returnForm" action="ReturnBook.php" method="POST">
				<label for="bookname">Select Book to Return:</label>
				<select name="bookname" required>
					<option value="" disabled selected>Select a book</option> <!-- Placeholder option -->
					<?php
					// Corrected query to fetch books borrowed by the current user
					$stmt = $pdo->prepare("
						SELECT b.bookname, b.author, l.id
						FROM tbl_bookinfo_logs l
						JOIN tbl_bookinfo b ON b.bookname = l.bookname
						WHERE l.bookisinuse = 1 AND l.borrowedby = :borrowedby
					");

					// Pass the username if `borrowedby` stores usernames
					$stmt->execute(['borrowedby' => $userData['username']]);

					// Populate dropdown with borrowed books
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						echo "<option value='{$row['bookname']}'>{$row['bookname']} by {$row['author']}</option>";
					}
					?>
				</select>

				<label for="username">Your Name:</label>
				<input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" readonly>
				
				<button type="submit" name="submitReturn" class="submit-btn" id="uploadButton">Submit Return</button>
			</form>
		</div>
	</div>

	<script>
		// Open the Return Book Modal when the "Return a Book" button is clicked
		document.getElementById("returnBookBtn").onclick = function() {
			document.getElementById("returnBookModal").style.display = "block";
		};

		// Close the Return Book Modal when the "X" button is clicked
		document.getElementById("closeReturnModal").onclick = function() {
			document.getElementById("returnBookModal").style.display = "none";
		};

		// Close the Return Book Modal if the user clicks anywhere outside the modal content
		window.onclick = function(event) {
			const returnBookModal = document.getElementById("returnBookModal");
			if (event.target == returnBookModal) {
				returnBookModal.style.display = "none";
			}
		};

		// Show pop-up message based on the return outcome
		document.addEventListener("DOMContentLoaded", function () {
			const urlParams = new URLSearchParams(window.location.search);

			if (urlParams.has('success')) {
				alert("Your book has been successfully returned!");
				// Optionally, clear the success parameter from the URL
				history.replaceState(null, null, window.location.pathname);
			}

			if (urlParams.has('error')) {
				const errorType = urlParams.get('error');
				let errorMessage = "An error occurred.";

				switch (errorType) {
					case 'book_not_selected':
						errorMessage = "Please select a book to return.";
						break;
					case 'book_not_found':
						errorMessage = "The book was not found in your borrowed list.";
						break;
					case 'return_failed':
						errorMessage = "An error occurred while returning the book.";
						break;
				}

				alert(errorMessage);
				// Optionally, clear the error parameter from the URL
				history.replaceState(null, null, window.location.pathname);
			}
		});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", () => {
			// Add click event to links for borrowed books
			document.querySelectorAll(".disabled-book").forEach(link => {
				link.addEventListener("click", (event) => {
					const bookName = link.getAttribute("data-book");
					alert(`The book "${bookName}" is currently borrowed and cannot be accessed.`);
					event.preventDefault(); // Prevent default link behavior
				});
			});
		});
	</script>

	<script src="dashboard.js"></script>
	<script src="search.js"></script>
</body>
</html>
