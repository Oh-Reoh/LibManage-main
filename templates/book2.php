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

	<link rel="stylesheet" href="book2.css">

	<title>Books</title>
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
					<img src="images/dashboard_icon(sml).png" alt="Dashboard Icon" class="icon-therest"> Dashboard
				</a>
			</li>

			<li>
				<a href="booklist(Librarian).html" class="active">
					<img src="images/book_icon(big).png" alt="Dashboard Icon" class="icon"> Books
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

		    <!-- Add Member Button -->
			<button class="add-member-btn">Add Member</button>
    
			<!-- Add Book Button -->
			<button class="add-book-btn">Add Book</button>
			
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
            <h1 class="title"><a href="booklist(Librarian).html"> Books </a>>> Harry Potter and the Goblet of Fire</h1>
            <div class="data">
                <!-- Left Column -->
                <div class="left-column">
                    <div class="content-data book-information">
                        <div class="head">
                            
                        </div>
                        <div class="book-info-content">
                            <!-- Book Details (Left Side) -->
                            <div class="book-details">
                                <div class="detail-item">
                                    <span class="label">Publish Year:</span>
                                    <span class="value">2013</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Genre:</span>
                                    <span class="value">Fantasy Literature</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Book Number:</span>
                                    <span class="value">2</span>
                                </div>
                            </div>                    
                            
                            <!-- Book Image (Right Side) -->
                            <div class="book-image">
                                <img src="images/HarryP_info.png" alt="Book Image">
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    <div class="content-data book-history">
                        <div class="head">
                            <h3>Book History</h3>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="right-column">
                    <div class="content-data book-overview">
                        <div class="book-overview-content">
                            <!-- Book Cover Image -->
                            <img src="images/HarryP_overview.png" alt="The Wizard of Oz Cover" class="book-cover">
                    
                            <!-- Book Title and Author -->
                            <h2 class="book-title">Harry Potter and the Goblet of Fire</h2>
                            <p class="book-author">J. K. Rowling</p>
                    
                            <!-- Book Description -->
                            <p class="book-description">
                                Harry Potter and the Goblet of Fire by J.K. Rowling is the fourth book in the Harry Potter series. It follows 14-year-old Harry as he unexpectedly becomes a competitor in the dangerous Triwizard Tournament, a magical competition between three wizarding schools. While facing various life-threatening challenges, Harry also uncovers dark forces at work, leading to the return of the dark wizard, Lord Voldemort. The book explores themes of friendship, bravery, and the growing threat of evil in the magical world.
                            </p>
                    
                            <!-- Rating and Language -->
                            <div class="book-rating">
                                <span>4.9</span>
                                <div class="stars">
                                    ★★★★★
                                </div>
                                <span class="language">ENGLISH</span>
                            </div>
                    
                            <!-- Button -->
                            <button class="read-button">View/Read Book</button>
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

	<script src="booklist(Librarian).js"></script>
</body>
</html>
