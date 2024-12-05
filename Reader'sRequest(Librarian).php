<?php
session_start();
session_regenerate_id(true); // Regenerate session ID for security and isolation

// Check if the user is logged in and is a librarian
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian')  {
    header("Location: LoginPage.php");
    exit();
}

include('db_connect.php');

// Get the logged-in user's ID
$userId = $_SESSION['user_id']; // This fetches the logged-in user's ID

// Check if the user is a librarian
if ($_SESSION['role'] !== 'librarian') {
    header("Location: Dashboard(Reader).php"); // Redirect to reader dashboard if not a librarian
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'libmanagedb';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch librarian data from the database
$query = "SELECT * FROM tbl_userinfo WHERE id = :userId";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no profile picture exists, fallback to a default picture
$profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : 'images/default.jpg';

// Fetch all pending book requests (isrequest = 1) from tbl_bookinfo_logs
$query = "SELECT * FROM tbl_bookinfo_logs WHERE isrequest = 1";
$stmt = $pdo->query($query);
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

	<link rel="stylesheet" href="Reader'sRequest(Librarian).css">
	<link rel="stylesheet" href="search.css">
	<title>Requests</title>
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
						<img src="images/dashboard_icon(sml).png" alt="Dashboard Icon" class="icon-therest"> Dashboard
					</a>
				</li>

				<li>
					<a href="booklist(Librarian).php" class="active">
						<img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books
					</a>
				</li>

				<li>
					<a href="Reader'sRequest(Librarian).php" class="active">
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

		<!-- MAIN -->
		<main>
			<h1 class="title">Reader Requests</h1>
			<?php
			// Display success or error messages
			if (isset($_GET['success'])) {
				echo "<script>
					alert('Request successfully " . htmlspecialchars($_GET['success']) . "!');
					window.location.href = 'Reader\'sRequest(Librarian).php';
				</script>";
			} elseif (isset($_GET['error'])) {
				echo "<script>
					alert('An error occurred: " . htmlspecialchars($_GET['error']) . "');
					window.location.href = 'Reader\'sRequest(Librarian).php';
				</script>";
			}
			?>
			<div class="data">
				<div class="content-data">
					<table>
						<thead>
							<tr>
								<th>BOOK NAME</th>
								<th>AUTHOR</th>
								<th>REQUESTED BY</th>
								<th>STATUS</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
								<tr data-id="<?php echo $row['id']; ?>">
									<td><?php echo htmlspecialchars($row['bookname']); ?></td>
									<td><?php echo htmlspecialchars($row['author']); ?></td>
									<td><?php echo htmlspecialchars($row['requestby']); ?></td>
									<td>
										<?php
										// Determine the dynamic status
										switch ($row['isrequest']) {
											case 1:
												echo "Pending";
												break;
											case 0:
												echo "Accepted";
												break;
											case 3:
												echo "Denied";
												break;
											default:
												echo "Unknown";
												break;
										}
										?>
									</td>
									<td>
										<?php if ($row['isrequest'] == 1) { // Show actions only for pending requests ?>
											<form method="POST" action="processRequest.php">
												<button type="submit" name="accept" value="<?php echo $row['id']; ?>">Accept</button>
												<button type="submit" name="deny" value="<?php echo $row['id']; ?>">Deny</button>
											</form>
										<?php } else { ?>
											No Actions Available
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- BOOKS DATA -->	
			<div class="data">
				<div class="container">
					<div class="table-wrapper">
						<div class="content-data">
		
							<table>
								<thead>
									<tr>
										<th>BOOK NAME</th>
										<th>AUTHOR</th>
										<th>BOOK STATUS</th>
										<th>ISSUED DATE</th>
										<th>RETURN DATE</th>
										<th>STATUS</th>
									</tr>
								</thead>
								<tbody>

								<?php
									$host = 'localhost';
									$dbname = 'libmanagedb';
									$username = 'root';
									$password = '';

									$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
									$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

									$stmt = $pdo->query("SELECT *,
														DATE_FORMAT(issueddate, '%Y-%m-%d') AS formatted_issueddate,
														CASE 
															WHEN bookisinuse = 0 THEN 'on shelf'
															WHEN bookisinuse = 1 THEN 'borrowed'
															ELSE 'Unknown'
														END AS book_status,
														CASE 
															WHEN returndate IS NULL OR returndate = '' THEN 'pending'
															ELSE 'returned'
														END AS return_status
													FROM tbl_bookinfo_logs; where bookisinuse = 1");
									
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										$statusColor = ($row['return_status'] == 'pending') ? 'crimson' : 'green';
										
										echo "<tr>
												<td><a href='book{$row['id']}.php'>{$row['bookname']}</a></td>
												<td>{$row['author']}</td>
												<td>{$row['book_status']}</td>
												<td>{$row['formatted_issueddate']}</td>
												<td>{$row['returndate']}</td>
												<td style='background-color: $statusColor; color: white;'>{$row['return_status']}</td>
											</tr>";
									}
								?>

								</tbody>
							</table>
		
						</div>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- whole content -->
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

	<script>
    // Prevent multiple form submissions
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function(event) {
            const buttons = form.querySelectorAll("button");
            buttons.forEach(button => button.disabled = true);
        });
    });
    </script>
	<script>
		// Skip chart rendering if on the Reader'sRequest(Librarian) page
		if (window.location.pathname.includes("Reader'sRequest(Librarian).php")) {
			console.log("Chart rendering skipped for Reader'sRequest(Librarian).php");
		} else {
			const chartContainer = document.getElementById("chartContainer");
			if (!chartContainer) {
				console.error("Chart container not found!");
			} else {
				// Chart rendering logic here
				console.log("Chart rendering lo	gic executed.");
			}
		}
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", () => {
		const tableBody = document.querySelector("table tbody");

		tableBody.addEventListener("click", function (event) {
			if (event.target.tagName === "BUTTON") {
				const button = event.target;
				const action = button.name; // "accept" or "deny"
				const requestId = button.value;

				fetch("processRequest.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: new URLSearchParams({ [action]: requestId }),
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.success) {
							alert(data.message);
							const row = document.querySelector(`tr[data-id="${data.id}"]`);
							if (row) row.remove(); // Remove the row by matching the ID
						} else {
							alert(data.message);
						}
					})
					.catch((error) => console.error("Error:", error));
			}
		});
	});
	</script>

	<script src="Reader'sRequest(Librarian).js"></script>
	<script src="search.js"></script>
</body>
</html>
