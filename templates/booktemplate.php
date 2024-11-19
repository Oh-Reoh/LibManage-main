<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="book{{ID}}.css">
	<title>{{NAME}}</title>
</head>
<body>
	<div class="dboard_content">
		<!-- Sidebar -->
		<section id="sidebar">
			<a href="../Dashboard(Librarian).php" class="brand">
				<img src="../images/logo_ra.png" alt="Logo Icon" class="logo">
				<p>Libmanage</p>
			</a>
			<ul class="side-menu">
				<li>
					<a href="../Dashboard(Librarian).php" class="active">
						<img src="../images/dashboard_icon.png" alt="Dashboard Icon" class="icon"> Dashboard
					</a>
				</li>
				<li>
					<a href="../booklist(Librarian).php">
						<img src="../images/book_icon.png" alt="Book Icon" class="icon"> Books
					</a>
				</li>
			</ul>
		</section>
		<!-- Main Content -->
		<section id="content">
			<main>
				<h1 class="title">{{NAME}}</h1>
				<div class="data">
					<div class="left-column">
						<div class="content-data book-information">
							<div class="book-info-content">
								<div class="book-details">
									<div class="detail-item">
										<span class="label">Publish Year:</span>
										<span class="value">{{YEAR}}</span>
									</div>
									<div class="detail-item">
										<span class="label">Genre:</span>
										<span class="value">{{GENRES}}</span>
									</div>
									<div class="detail-item">
										<span class="label">Book Number:</span>
										<span class="value">{{BOOKNUMBER}}</span>
									</div>
								</div>
								<div class="book-image">
									<img src="../images/{{IMAGE}}" alt="Book Image">
								</div>
							</div>
						</div>
					</div>
					<div class="right-column">
						<div class="content-data book-overview">
							<div class="book-overview-content">
								<img src="../images/{{IMAGE}}" alt="{{NAME}}" class="book-cover">
								<h2 class="book-title">{{NAME}}</h2>
								<p class="book-author">by {{AUTHOR}}</p>
								<p class="book-description">{{DESCRIPTION}}</p>
							</div>
						</div>
					</div>
				</div>
			</main>
		</section>
	</div>
	<script src="../dashboard.js"></script>
</body>
</html>
