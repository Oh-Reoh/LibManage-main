<?php
session_start();

// Force the user's role to 'librarian' when accessing the librarian's dashboard
$_SESSION['role'] = 'librarian';

// Redirect to the login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: LoginPage.php');
    exit();
}

// Check if there is an error message to display
if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    // Clear the error message from the session after it is displayed
    unset($_SESSION['error_message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Bakbak One' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="Dashboard(Librarian).css">
	<link rel="stylesheet" href="pop-up_add.css">
	<title>Dashboard</title>
</head>
<body>
	
	<div class="dboard_content">
		<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="Dashboard(Librarian).php" class="brand">
			<img src="images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
		</a>
		<ul class="side-menu">
			<li>
				<a href="Dashboard(Librarian).php" class="active">
					<img src="images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard
				</a>
			</li>

			<li>
				<a href="booklist(Librarian).php" class="active">
					<img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books
				</a>
			</li>

			<li>
				<a href="Reader'sRequest(Librarian).php" class="active">
					<img src="images/readers_request_icon.png" alt="Dashboard Icon" class="icon-therest"> Requests
				</a>
			</li>

			<li>
				<a href="#" class="active">
					<img src="images/settings_icon.png" alt="Dashboard Icon" class="icon-therest"> Settings
				</a>
			</li>

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
					<!-- Search Input Field -->
					<input type="text" id="searchInput" placeholder="Search books & members" oninput="searchFunction()">
					<i class='bx bx-search icon'></i>
					<!-- Placeholder for search results -->
					<div id="searchResults" class="dropdown"></div>
				</div>
			</form>

    
			<!-- Add Book Button -->
			<button id="addBookBtn" class="add-book-btn">Add Book</button>

			
			<a href="#" class="nav-link">
				<i class='bx Fbxs-bell icon' ></i>
				<span class="badge">5</span>
			</a>

			<div class="profile">
				<img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
				<ul class="profile-link">
					<li><a href="profile.php"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
					<li><a href="Mainpage.php"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR END -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Dashboard</h1>
			
			<!-- INFO DATA -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<?php
							// Database connection
							$host = 'localhost'; // your host
							$dbname = 'libmanagedb'; // your database name
							$username = 'root'; // your username
							$password = ''; // your password

							// Create a PDO instance
							$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
							$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							// Query to count the number of readers (role = 'regular')
							$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_userinfo WHERE role = 'regular'");
							$totalReaders = $stmt->fetchColumn(); // Fetch the count
							?>
							<h2><?php echo $totalReaders; ?></h2> <!-- Display total readers -->
							<img src="images/icon pending-icon.png" alt="Reader Icon" class="icon reader-icon">
							<p>Total Readers</p>
						</div>
					</div>
				</div>

			
				<div class="card">
					<div class="head">
						<div>
							<h2>1</h2>
							<p>Pending Readers</p>
						</div>
						<img src="images/pending-icon.png" alt="Pending Readers Icon" class="icon pending-icon">
					</div>
				</div>
			
				<div class="card">
					<div class="head">
						<div>
							<h2>7</h2>
							<p>Borrowed Books</p>
						</div>
						<img src="images/borrowed-icon.png" alt="Borrowed Books Icon" class="icon borrowed-icon">
					</div>
				</div>
			
				<div class="card">
					<div class="head">
						<div>
							<h2>2</h2>
							<p>Overdue Books</p>
						</div>
						<img src="images/overdue-icon.png" alt="Overdue Books Icon" class="icon overdue-icon">
					</div>
				</div>
			</div>
			<!-- INFO DATA -->
			<div class="data">
				<!-- Readers List -->
				<div class="content-data">
					<div class="head">
						<h3>Readers List</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>

					<div class="container">
						<div class="table-wrapper">
							<table>
								<thead>
									<tr>
										<th>USERNAME</th>
										<th>EMAIL</th>
										<th>DEPARTMENT</th>
										<th>BOOKS BORROWED</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Database connection
									$host = 'localhost'; // your host
									$dbname = 'libmanagedb'; // your database name
									$username = 'root'; // your username
									$password = ''; // your password

									// Create a PDO instance
									$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
									$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

									// Query to fetch readers' information, department, and books borrowed
									$stmt = $pdo->query("
										SELECT u.username, u.email, u.department, GROUP_CONCAT(b.bookname SEPARATOR ', ') AS books_borrowed
										FROM tbl_userinfo u
										LEFT JOIN tbl_bookinfo_logs l ON u.username = l.borrowedby
										LEFT JOIN tbl_bookinfo b ON l.bookname = b.bookname
										WHERE u.role = 'regular'
										GROUP BY u.id
									");

									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										// Check if the email exceeds 10 characters and truncate with '...'
										$email = strlen($row['email']) > 10 ? substr($row['email'], 0, 10) . '...' : $row['email'];

										echo "<tr>
												<td>{$row['username']}</td>
												<td>{$email}</td>
												<td>{$row['department']}</td>
												<td>{$row['books_borrowed']}</td>
											</tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>





				<!-- Books List -->
				<div class="content-data">
					<div class="head">
						<h3>List of Books</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>

					<div class="container">
						<div class="table-wrapper">
							<table>
								<thead>
									<tr>
										<th>BOOK NAME</th>
										<th>AUTHOR</th>
										<th>BOOK STATUS</th>
										<th>NUMBER</th>
										<th style="font-size: 15px">REGISTERED DATE</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Query to select all books from the database
									$stmt = $pdo->query("SELECT *, DATE_FORMAT(issueddate, '%Y-%m-%d') AS formatted_issueddate FROM tbl_bookinfo");
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										// Ensure output is sanitized
										$bookId = htmlspecialchars($row['id']);
										$bookName = htmlspecialchars($row['bookname']);
										$author = htmlspecialchars($row['author']);
										$issuedDate = htmlspecialchars($row['formatted_issueddate']);
										
										echo "<tr>
												<td><a href='book{$bookId}.php'>{$bookName}</a></td>
												<td>{$author}</td>
												<td>on shelf</td>
												<td>{$bookId}</td>
												<td>{$issuedDate}</td>
											</tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- MAIN -->

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

	<!-- Add Book Modal -->
	<div id="addBookModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Add A New Book</h2>
			<form id="addBookForm" action="AddBook.php" method="POST" enctype="multipart/form-data">
				<!-- Image Preview -->
				<div class="image-preview-container">
					<img id="imagePreview" src="images/blankimg.png" alt="Book Cover Preview">
					<input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
					<button type="button" id="uploadButton">Upload Image</button>
				</div>

				<!-- Other form fields -->
				<label for="bookname">Book Name</label>
				<input type="text" id="bookname" name="bookname" placeholder="Enter the book name" required>
				
				<label for="author">Author</label>
				<input type="text" id="author" name="author" placeholder="Enter the author's name" required>
				
				<label for="bookNumber">Book Number</label>
				<input type="text" id="bookNumber" name="bookNumber" placeholder="Enter the book number" required>
				
				<label for="publishYear">Publish Year</label>
				<input type="number" id="publishYear" name="publishYear" placeholder="Enter the publish year" required>
				
				<label for="genre">Genre</label>
				<input type="text" id="genre" name="genre" placeholder="Enter genres (comma-separated)" required>
				
				<label for="description">Description</label>
				<textarea id="description" name="description" placeholder="Enter a brief description" required></textarea>

				<button type="submit" class="modal-submit-btn">Submit</button>
			</form>
		</div>
	</div>

	<script src="booklist(Librarian).js"></script>
	
	<script>
		// Modal Logic
		document.addEventListener("DOMContentLoaded", () => {
			const modal = document.getElementById("addBookModal");
			const openModal = document.getElementById("addBookBtn");
			const closeModal = document.querySelector(".close"); // Correctly select the close button

			// Open modal when "Add Book" button is clicked
			openModal.addEventListener("click", () => {
				modal.style.display = "block";
			});

			// Close modal when the close button is clicked
			closeModal.addEventListener("click", () => {
				modal.style.display = "none";
			});

			// Close modal when clicking outside of it
			window.addEventListener("click", (event) => {
				if (event.target === modal) {
					modal.style.display = "none";
				}
			});
		});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const imageInput = document.getElementById("imageInput");
			const imagePreview = document.getElementById("imagePreview");
			const uploadButton = document.getElementById("uploadButton");

			uploadButton.addEventListener("click", () => {
				imageInput.click(); // Simulate click on file input
			});

			imageInput.addEventListener("change", () => {
				const file = imageInput.files[0];
				if (file) {
					const reader = new FileReader();
					reader.onload = () => {
						imagePreview.src = reader.result; // Update preview with uploaded image
					};
					reader.readAsDataURL(file);
				}
			});
		});
	</script>

	<script>
		// Function to handle the search input event
		function searchFunction() {
			const searchTerm = document.getElementById("searchInput").value;
			const searchResults = document.getElementById("searchResults");
			
			if (searchTerm.length > 0) {
				fetchSearchResults(searchTerm);
			} else {
				// Clear results if search is empty
				searchResults.innerHTML = '';
				searchResults.style.display = 'none';
			}
		}

		function fetchSearchResults(searchTerm) {
			fetch('search.php?query=' + encodeURIComponent(searchTerm))
				.then(response => response.json())
				.then(data => {
					const searchResults = document.getElementById("searchResults");
					searchResults.innerHTML = ''; // Clear previous results

					if (data.success) {
						const ul = document.createElement('ul');
						if (data.type === 'books') {
							data.results.forEach(book => {
								const li = document.createElement('li');
								li.textContent = `${book.bookname} by ${book.author}`;
								ul.appendChild(li);
							});
						} else if (data.type === 'users') {
							data.results.forEach(user => {
								const li = document.createElement('li');
								li.textContent = `${user.username} (${user.email})`;
								ul.appendChild(li);
							});
						}
						searchResults.appendChild(ul);
						searchResults.style.display = 'block'; // Show dropdown
					} else {
						searchResults.innerHTML = '<ul><li>No results found</li></ul>';
						searchResults.style.display = 'block'; // Show dropdown
					}
				})
				.catch(error => {
					console.error('Error fetching search results:', error);
				});
		}
	</script>
</body>
</html>
