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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Dynamically include the book-specific CSS -->
    <link rel="stylesheet" href="book<?php echo $id; ?>.css">

    <title><?php echo $name; ?></title>
</head>
<body>
    
    <div class="dboard_content">
        <!-- SIDEBAR -->
        <section id="sidebar">
            <a href="../Dashboard(Librarian).php" class="brand">
                <img src="../images/logo_ra.png" alt="Logo Icon" class="logo"> <p>Libmanage</p>
            </a>
            <ul class="side-menu">
                <li>
                    <a href="../Dashboard(Librarian).php" class="active">
                        <img src="../images/dashboard_icon(sml).png" alt="Dashboard Icon" class="icon-therest"> Dashboard
                    </a>
                </li>
                <li>
                    <a href="../booklist(Librarian).php" class="active">
                        <img src="../images/book_icon(big).png" alt="Dashboard Icon" class="icon"> Books
                    </a>
                </li>
                <li>
                    <a href="../Reader'sRequest(Librarian).php" class="active">
                        <img src="../images/readers_request_icon.png" alt="Dashboard Icon" class="icon-therest"> Requests
                    </a>
                </li>
                <li>
                    <a href="#" class="active">
                        <img src="../images/settings_icon.png" alt="Dashboard Icon" class="icon-therest"> Settings
                    </a>
                </li>
            </ul>
            
        </section>
        <!-- SIDEBAR -->

        <!-- Whole Content -->
        <section id="content">
            <!-- NAVBAR -->
            <nav>
                <i class='bx bx-menu toggle-sidebar' ></i>
                <form action="#">
                    <div class="form-group">
                        <input type="text" placeholder="Search books & members">
                        <i class='bx bx-search icon' ></i>
                    </div>
                </form>
                
                <a href="#" class="nav-link">
                    <i class='bx bxs-bell icon' ></i>
                    <span class="badge">5</span>
                </a>

                <div class="profile">
                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                    <ul class="profile-link">
                        <li><a href="../profile.php"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
                        <li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
                        <li><a href="../Mainpage.php"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
                    </ul>
                </div>
            </nav>
            <!-- NAVBAR -->

            <!-- MAIN -->
            <main>
                <h1 class="title"><a href="../booklist(Librarian).php">Books</a> > <?php echo $name; ?></h1>
                <div class="data">
                    <!-- Left Column -->
                    <div class="left-column">
                        <div class="content-data book-information">
                            <div class="book-info-content">
                                <!-- Book Details -->
                                <div class="book-details">
                                    <div class="detail-item">
                                        <span class="label">Publish Year:</span>
                                        <span class="value"><?php echo $year; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Genre:</span>
                                        <span class="value"><?php echo implode(', ', $genres); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Book Number:</span>
                                        <span class="value"><?php echo $id; ?></span>
                                    </div>
                                </div>

                                <!-- Book Image -->
                                <div class="book-image">
                                    <img src="../images/<?php echo $image; ?>" alt="Book Image">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="right-column">
                        <div class="content-data book-overview">
                            <div class="book-overview-content">
                                <h2 class="book-title"><?php echo $name; ?></h2>
                                <p class="book-author"><?php echo $author; ?></p>
                                <p class="book-description"><?php echo $description; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- MAIN -->
        </section>
        <!-- Whole Content -->
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <div class="footer-description">
                    <img src="../logo.png" alt="Libmanage Logo" class="footer-logo">
                    <p>Where Stories Live: <span class="second-line">Find And Borrow Your Next Great Read.</span></p>
                </div>
            </div>
    
            <div class="footer-center">
                <p>&copy; 2024</p>
            </div>
    
            <div class="footer-right">
                <p>Follow Us</p>
                <div class="footer-socials">
                    <a href="#"><img src="../icon-ig.png" alt="Instagram"></a>
                    <a href="#"><img src="../icon-fb.png" alt="Facebook"></a>
                    <a href="#"><img src="../icon-tw.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="../booklist(Librarian).js"></script>
</body>
</html>
