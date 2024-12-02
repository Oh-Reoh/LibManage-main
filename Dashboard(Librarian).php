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
	<link rel="stylesheet" href="search.css">
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
					<input type="text" id="searchInput" placeholder="Search books & members" oninput="searchFunction()">
					<i class="bx bx-search icon"></i>
					<div id="searchResults" class="dropdown"></div> <!-- Dropdown for results -->
				</div>
			</form>
    
			<!-- Add Book Button -->
			<button id="addBookBtn" class="add-book-btn">Add Book</button>


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
							<p>Pending Requests</p>
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
								<li><a href="#" onclick="openChooseBookPopup()">Edit</a></li>
								<li><a href="#" onclick="openDeletePopup()">Delete</a></li>
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
									$stmt = $pdo->query("SELECT *, 
											CASE 
												WHEN isinuse = 1 THEN 'Borrowed' 
												ELSE 'On Shelf' 
											END AS book_status,
											DATE_FORMAT(issueddate, '%Y-%m-%d') AS formatted_issueddate 
										FROM tbl_bookinfo");
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										// Ensure output is sanitized
										$bookId = htmlspecialchars($row['id']);
										$bookName = htmlspecialchars($row['bookname']);
										$author = htmlspecialchars($row['author']);
										$bookStatus = htmlspecialchars($row['book_status']);
										$issuedDate = htmlspecialchars($row['formatted_issueddate']);
										
										echo "<tr>
												<td><a href='book{$bookId}.php'>{$bookName}</a></td>
												<td>{$author}</td>
												<td>{$bookStatus}</td>
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
	
	<!-- Edit Book Modal -->
	<div id="chooseBookPopup" class="modal">
		<div class="modal-content">
			<span class="close edit-close" onclick="closeChooseBookPopup()">&times;</span>
			<h2>Choose a Book to Edit</h2>
			<form id="chooseBookForm">
				<label for="bookSelect">Select a Book:</label>
				<select id="bookSelect" name="bookId">
					<?php
						// Fetch books from the database for the user to select
						$stmt = $pdo->query("SELECT id, bookname FROM tbl_bookinfo");
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$bookId = htmlspecialchars($row['id']);
							$bookName = htmlspecialchars($row['bookname']);
							echo "<option value='{$bookId}'>{$bookName}</option>";
						}
					?>
				</select>
				<button type="button" onclick="loadEditBookForm()" class="modal-submit-btn">Choose</button>
			</form>
		</div>
	</div>

	<!-- Edit Book Modal -->
	<div id="editBookPopup" class="modal">
		<div class="modal-content">
			<span class="close edit-close" onclick="closeEditPopup()">&times;</span>
			<h2>Edit Book</h2>
			<form id="editBookForm" action="editbook.php" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="bookId" id="editBookId"> <!-- Hidden field for bookId -->

				<!-- Image Preview Section -->
				<label for="image">Book Image:</label>
				<div class="image-preview-container">
					<!-- Default image preview -->
					<img id="uploadImagePreviewEdit" src="images/<?php echo htmlspecialchars($bookData['image'] ?? 'blankimg.png'); ?>" alt="Book Cover Preview" style="max-width: 100px;">
					
					<!-- Hidden file input -->
					<input type="file" id="imageInputEdit" name="image" accept="image/*" style="display: none;">
					
					<!-- Button to trigger the file input click -->
					<button type="button" id="uploadButtonEdit">Upload Image</button>
				</div>

				<label for="bookname">Book Name:</label>
				<input type="text" name="bookname" id="editBookName" required>

				<label for="author">Author:</label>
				<input type="text" name="author" id="editAuthor" required>

				<label for="bookNumber">Book Number:</label>
				<input type="text" name="bookNumber" id="editBookNumber" required>

				<label for="publishYear">Publish Year:</label>
				<input type="text" name="publishYear" id="editPublishYear" required>

				<label for="genre">Genre:</label>
				<input type="text" name="genre" id="editGenre" required>

				<label for="description">Description:</label>
				<textarea name="description" id="editDescription" required></textarea>

				<button type="submit" class="modal-submit-btn">Save Changes</button>
			</form>
		</div>
	</div>


	<!-- Delete Book Modal (Choose Book) -->
	<div id="chooseDeleteBookPopup" class="modal">
		<div class="modal-content">
			<span class="close edit-close" onclick="closeChooseDeleteBookPopup()">&times;</span>
			<h2>Choose a Book to Delete</h2>
			<form id="chooseDeleteBookForm">
				<label for="bookSelectToDelete">Select a Book:</label>
				<select id="bookSelectToDelete" name="bookId">
					<?php
					// Fetch books from the database for the user to select
					$stmt = $pdo->query("SELECT id, bookname FROM tbl_bookinfo");
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$bookId = htmlspecialchars($row['id']);
						$bookName = htmlspecialchars($row['bookname']);
						echo "<option value='{$bookId}'>{$bookName}</option>";
					}
					?>
				</select>
				<button type="button" onclick="loadDeleteBookConfirmationForm()" class="modal-submit-btn">Choose</button>
			</form>
		</div>
	</div>

	<!-- Delete Confirmation Modal -->
	<div id="deleteBookPopup" class="modal">
		<div class="modal-content">
			<span class="close" onclick="closeDeletePopup()">&times;</span>
			<h2>Are you sure you want to delete this book?</h2>
			<p id="deleteBookTitle"></p> <!-- Dynamically populated with book title -->
			<form action="deletebook.php" method="POST" id="deleteBookForm">
				<input type="hidden" name="bookId" id="deleteBookId">
				<div class="button-container">
					<button type="submit" class="modal-submit-btn">Yes, Delete</button>
					<button type="button" class="modal-submit-btn" onclick="closeDeletePopup()">Cancel</button>
				</div>
			</form>
		</div>
	</div>


	<script src="pop-up.js"></script>


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
		// Function to open Choose Book Popup
		function openChooseBookPopup() {
			document.getElementById('chooseBookPopup').style.display = 'block';
		}

		// Function to close Choose Book Popup
		function closeChooseBookPopup() {
			document.getElementById('chooseBookPopup').style.display = 'none';
		}

		// Function to load the Edit Book form with selected book details
		function loadEditBookForm() {
			var bookId = document.getElementById("bookSelect").value;
			if (!bookId) {
				alert("Please select a book to edit");
				return;
			}
			
			// Close the Choose Book modal
			closeChooseBookPopup();

			// Fetch and populate the Edit Book form
			fetch(`getBookDetails.php?id=${bookId}`)
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						// Populate the form with book details
						document.getElementById('editBookId').value = data.data.id;
						document.getElementById('editBookName').value = data.data.bookname;
						document.getElementById('editAuthor').value = data.data.author;
						document.getElementById('editBookNumber').value = data.data.bookNumber;
						document.getElementById('editPublishYear').value = data.data.publishYear;
						document.getElementById('editGenre').value = data.data.genre;
						document.getElementById('editDescription').value = data.data.description;

						// Open the Edit Book Modal
						document.getElementById('editBookPopup').style.display = 'block';
					} else {
						alert(data.message);
					}
				});
		}
	</script>

	<script>
    document.addEventListener("DOMContentLoaded", () => {
		const uploadButtonEdit = document.getElementById("uploadButtonEdit");  // Upload button for Edit Book
		const imageInputEdit = document.getElementById("imageInputEdit");  // Hidden file input for Edit Book
		const uploadImagePreviewEdit = document.getElementById("uploadImagePreviewEdit");  // Image preview for Edit Book

		// When the "Upload Image" button is clicked, trigger the file input click
		uploadButtonEdit.addEventListener("click", () => {
			imageInputEdit.click();  // Trigger the file input to open
		});

		// When a file is selected, update the image preview
		imageInputEdit.addEventListener("change", () => {
			const file = imageInputEdit.files[0];  // Get the selected file

			if (file) {
				const reader = new FileReader();  // Create a new FileReader instance

				// When the file is loaded, update the preview
				reader.onload = function (e) {
					uploadImagePreviewEdit.src = e.target.result;  // Set the preview to the selected image
				};

				reader.readAsDataURL(file);  // Read the file as a data URL
			}
		});
	});
	</script>

	<script>
		// Modal close functionality
		function closeEditPopup() {
			document.getElementById('editBookPopup').style.display = 'none';
		}

		window.addEventListener('click', function (event) {
			const modal = document.getElementById('editBookPopup');
			// Close modal if clicking outside of modal content
			if (event.target === modal) {
				closeEditPopup();
			}
		});

		document.addEventListener("DOMContentLoaded", () => {
			const uploadButtonEdit = document.getElementById("uploadButtonEdit");  // Upload button for Edit Book
			const imageInputEdit = document.getElementById("imageInputEdit");  // Hidden file input for Edit Book
			const uploadImagePreviewEdit = document.getElementById("uploadImagePreviewEdit");  // Image preview for Edit Book

			// When the "Upload Image" button is clicked, trigger the file input click
			uploadButtonEdit.addEventListener("click", () => {
				imageInputEdit.click();  // Trigger the file input to open
			});

			// When a file is selected, update the image preview
			imageInputEdit.addEventListener("change", () => {
				const file = imageInputEdit.files[0];  // Get the selected file

				if (file) {
					const reader = new FileReader();  // Create a new FileReader instance

					// When the file is loaded, update the preview
					reader.onload = function (e) {
						uploadImagePreviewEdit.src = e.target.result;  // Set the preview to the selected image
					};

					reader.readAsDataURL(file);  // Read the file as a data URL
				}
			});
		});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", () => {
			// Function to open the 'Choose Book to Delete' modal
			window.openDeletePopup = function() {
				document.getElementById("chooseDeleteBookPopup").style.display = "block";
			};

			// Function to close the 'Choose Book to Delete' modal
			window.closeChooseDeleteBookPopup = function() {
				document.getElementById("chooseDeleteBookPopup").style.display = "none";
			};

			// Function to load the delete confirmation form and show the confirmation modal
			window.loadDeleteBookConfirmationForm = function() {
				const bookSelectToDelete = document.getElementById("bookSelectToDelete");
				const bookId = bookSelectToDelete.value;

				if (!bookId) {
					alert("Please select a book to delete.");
					return;
				}

				// Show the delete confirmation modal and populate it with book details
				document.getElementById("deleteBookId").value = bookId;
				document.getElementById("deleteBookTitle").textContent = "Are you sure you want to delete this book?";

				document.getElementById("deleteBookPopup").style.display = "block";
			};

			// Function to close the delete confirmation modal
			window.closeDeletePopup = function() {
				document.getElementById("deleteBookPopup").style.display = "none";
			};

			// Handle the form submission for book deletion
			const deleteForm = document.getElementById("deleteBookForm");

			if (deleteForm) {
				deleteForm.addEventListener("submit", function (event) {
					event.preventDefault(); // Prevent the form from submitting the normal way

					// Grab the bookId from the form
					const bookId = document.getElementById('deleteBookId').value;

					// Perform the delete request using fetch
					fetch('deletebook.php', {
						method: 'POST',
						body: new URLSearchParams({ 'bookId': bookId })  // Send bookId in the body
					})
					.then(response => response.json())
					.then(data => {
						// Handle response
						if (data.success) {
							alert(data.message); // Show success message
							closeDeletePopup();  // Close the confirmation modal
							// Optionally, refresh the page or update the UI to reflect the deletion
							window.location.href = "Dashboard(Librarian).php";  // Redirect to the Dashboard
						} else {
							alert("Error: " + data.message);  // Show error message
						}
					})
					.catch(error => {
						console.error("Error:", error);
						alert("There was an issue with the deletion process.");
					});
				});
			}
		});
	</script>


	<script src="search.js"></script>
</body>
</html>
