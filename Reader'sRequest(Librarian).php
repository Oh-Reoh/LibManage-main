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

			<li>
				<a href="#" class="active">
					<img src="images/settings_icon.png" alt="Dashboard Icon" class="icon-therest"> Settings
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
			<form action="#">
				<div class="form-group">
					<input type="text" placeholder="Search books & members">
					<style>
						input[type="text"]::placeholder {
						    color: #6F58DA;
						}
					</style>
					<i class='bx bx-search icon' ></i>
				</div>
			</form>
    
			<!-- Add Book Button -->
			<button class="add-book-btn">Add Book</button>
			
			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
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
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Reader Requests</h1>		


			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>---</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>

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

	<script src="Reader'sRequest(Librarian).js"></script>
</body>
</html>
