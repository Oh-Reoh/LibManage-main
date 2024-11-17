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

	<link rel="stylesheet" href="Dashboard(Librarian).css">
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

    
			<a href="AddBook.php">
				<button class="add-book-btn">Add Book</button>
			</a>

			<a href="BorrowBook.php">
				<button class="add-book-btn">Borrow a Book</button>
			</a>

			
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
			<h1 class="title">Dashboard</h1>
			
			<!-- INFO DATA -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2>4</h2>
							<p>Total Readers</p>
						</div>
						<img src="images/icon pending-icon.png" alt="Reader Icon" class="icon reader-icon">
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
				<div class="content-data">
					<div class="head">
						<h3>Readers list</h3>
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
				<div class="container">
					<div class="table-wrapper">
						<div class="content-data">
							<div class="head">
								<h3>List of books</h3>
								<button class="add-book-btn" style="background: green; position: absolute; margin: 0 auto; transform: translateX(500px);">
									Export Excel
								</button>

								<div class="menu">
									<i class='bx bx-dots-horizontal-rounded icon'></i>
									<ul class="menu-link">
										<li><a href="#">Edit</a></li>
										<li><a href="#">Save</a></li>
										<li><a href="#">Remove</a></li>
									</ul>
								</div>
							</div>

							<table>
								<thead>
									<tr>
										<th>BOOK NAME</th>
										<th>AUTHOR</th>
										<th>BOOK STATUS</th>
										<th>NUMBER</th>
										<th style="font-size: 15px">REGISTERED DATE</th>
										<!-- <th>RETURN DATE</th> -->
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

										// Query to select all books from the database
										$stmt = $pdo->query("SELECT *, DATE_FORMAT(issueddate, '%Y-%m-%d') AS formatted_issueddate
										FROM tbl_bookinfo;
										");
										while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
											echo "<tr>
													<td><a href='book{$row['id']}.php'>{$row['bookname']}</a></td>
													<td>{$row['author']}</td>
													<td>on shelf</td>
													<td>{$row['id']}</td>
													<td>{$row['formatted_issueddate']}</td>
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
	<script src="booklist(Librarian).js"></script>
</body>
</html>
