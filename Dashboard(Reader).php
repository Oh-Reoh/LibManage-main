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

	<link rel="stylesheet" href="Dashboard(Reader).css">
	<title>Dashboard</title>
</head>
<body>
	
	<div class="dboard_content">
		<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="Dashboard(Librarian).html" class="brand">
			<img src="images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
		</a>
		<ul class="side-menu">
			<li>
				<a href="Dashboard(Librarian).html" class="active">
					<img src="images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard
				</a>
			</li>

			<li>
				<a href="booklist(Librarian).html" class="active">
					<img src="images/book_icon.png" alt="Dashboard Icon" class="icon-therest"> Books
				</a>
			</li>

			<li>
				<a href="Reader'sRequest(Librarian).html" class="active">
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
					<li><a href="profile.html"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
					<li><a href="Mainpage.html"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
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




				<div class="container">
					<div class="table-wrapper">
						<div class="content-data">
							<div class="head">
								<h3>List of books</h3>
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
										<th>ISSUED DATE</th>
										<th>RETURN DATE</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><a href="book1.html">The Wizard of OZ</a></td>
										<td>L. Frank Baum</td>
										<td>On shelf</td>
										<td>1</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book2.html">Harry Potter and the Goblet of Fire</a></td>
										<td>J. K. Rowling</td>
										<td>On shelf</td>
										<td>2</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book3.html">Stranger Things</a></td>
										<td>Matt and Ross Duffer</td>
										<td>On shelf</td>
										<td>3</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book4.html">Life of Pi</a></td>
										<td>Yann Martel</td>
										<td>On shelf</td>
										<td>4</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book5.html">The Hunger Games</a></td>
										<td>Suzanne Collins</td>
										<td>On shelf</td>
										<td>5</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book6.html">The Lord of the Rings</a></td>
										<td>J.R.R. Tolkien</td>
										<td>On shelf</td>
										<td>6</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book7.html">Goldilocks and the three bears</a></td>
										<td>James Marshall</td>
										<td>On shelf</td>
										<td>7</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book8.html">Pinocchio</a></td>
										<td>Carlo Collodi</td>
										<td>On shelf</td>
										<td>8</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book9.html">Peter Pan</a></td>
										<td>J. M. Barrie</td>
										<td>On shelf</td>
										<td>9</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td><a href="book10.html">The Lion king</a></td>
										<td>Walt Disney Company</td>
										<td>On shelf</td>
										<td>10</td>
										<td>-</td>
										<td>-</td>
									</tr>
								</tbody>
							</table>

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

	<script src="dashboard.js"></script>
</body>
</html>
