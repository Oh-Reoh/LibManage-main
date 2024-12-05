<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'regular';
$dashboardLink = $role === 'librarian' ? 'Dashboard(Librarian).php' : 'Dashboard(Reader).php';
$booklistLink = $role === 'librarian' ? 'booklist(Librarian).php' : 'booklist(Reader).php';
$book = [
'id' => '2',
'title' => 'Harry Potter and the Goblet of Fire',
'author' => 'J.K Rowling',
'year' => '2005',
'genre' => 'Novel, Fantasy',
'number' => '2',
'image' => 'Harry_Potter_and_the_Goblet_of_Fire_cover.png',
'description' => 'Harry Potter and the Goblet of Fire is a fantasy novel written by the British author J. K. Rowling. It is the fourth novel in the Harry Potter series',
'history' => 'No history available yet.',
'rating' => '4.1',
'stars' => '★★★★☆',
'language' => 'ENGLISH',
];

    ?>

    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Bakbak+One' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Baloo+2&display=swap' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='book<?php echo htmlspecialchars($book['id']); ?>.css'>
        <link rel='stylesheet' href='pop-up_add.css'>
        <link rel="stylesheet" href="profile.css">
        <link rel="stylesheet" href="search.css">
        <title><?php echo htmlspecialchars($book['title']); ?></title>
    </head>
    <body>
        <div class='dboard_content'>
            <!-- Sidebar -->
            <section id='sidebar'>
                <a href='<?php echo $dashboardLink; ?>' class='brand'>
                    <img src='images/logo_ra.png' alt='Logo Icon' class='logo'>
                    <p>Libmanage</p>
                </a>
                <ul class='side-menu'>
                    <li><a href='<?php echo $dashboardLink; ?>' class='active'>
                    <img src='images/dashboard_icon.png' alt='Dashboard Icon' class='icon'>Dashboard</a>
                </li>
                    <li><a href='<?php echo $booklistLink; ?>' class='active'>
                    <img src='images/book_icon(big).png' alt='Books Icon' class='icon'>Books</a>
                </li> 
                </ul>
            </section>

            <!-- Main Content -->
            <section id='content'>
                <nav>
                    <i class='bx bx-menu toggle-sidebar'></i>
                    <form action='#'>
                        <div class='form-group'>
                            <input type='text' placeholder='Search books & members'>
                            <i class='bx bx-search icon'></i>
                        </div>
                    </form>

                    <div class='profile'>
                        <img src='https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' alt=''>
                        <ul class='profile-link'>
                            <li><a href='profile.php'><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
                            <li><a href='#'><i class='bx bxs-cog' ></i> Settings</a></li>
                            <li><a href='Mainpage.php'><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
                        </ul>
                    </div>
                </nav>

                <main>
                    <h1 class='title'><a href='<?php echo $booklistLink; ?>'>Books</a> >> <?php echo htmlspecialchars($book['title']); ?></h1>
                    <div class='data'>
                        <div class='left-column'>
                            <div class='content-data book-information'>
                                <div class='book-info-content'>
                                    <div class='book-details'>
                                        <div class='detail-item'>
                                            <span class='label'>Publish Year:</span>
                                            <span class='value'><?php echo htmlspecialchars($book['year'] ?? 'N/A'); ?></span>
                                        </div>
                                        <div class='detail-item'>
                                            <span class='label'>Genre:</span>
                                            <span class='value'><?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></span>
                                        </div>
                                        <div class='detail-item'>
                                            <span class='label'>Book Number:</span>
                                            <span class='value'><?php echo htmlspecialchars($book['number'] ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class='book-image'>
                                        <img src='images/<?php echo htmlspecialchars($book['image']); ?>' alt='Book Image'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='right-column'>
                            <div class='content-data book-overview'>
                                <div class='book-overview-content'>
                                    <img src='images/<?php echo htmlspecialchars($book['image']); ?>' alt='<?php echo htmlspecialchars($book['title']); ?> Cover' class='book-cover'>
                                    <h2 class='book-title'><?php echo htmlspecialchars($book['title']); ?></h2>
                                    <p class='book-author'>by <?php echo htmlspecialchars($book['author']); ?></p>
                                    <p class='book-description'><?php echo htmlspecialchars($book['description']); ?></p>
                                    <div class='book-rating'>
                                        <span><?php echo htmlspecialchars($book['rating'] ?? 'N/A'); ?></span>
                                        <div class='stars'><?php echo htmlspecialchars($book['stars'] ?? '★★★★☆'); ?></div>
                                        <span class='language'><?php echo htmlspecialchars($book['language'] ?? 'ENGLISH'); ?></span>
                                    </div>
                                    <button class='read-button'>View/Read Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </section>
        </div>

        <footer>
            <div class='footer-container'>
                <div class='footer-left'>
                    <div class='footer-description'>
                        <img src='logo.png' alt='Libmanage Logo' class='footer-logo'>
                        <p>Where Stories Live: <span class='second-line'>Find And Borrow Your Next Great Read.</span></p>
                    </div>
                </div>
                <div class='footer-center'>
                    <p>&copy; ALPHA ONE 2024</p>
                </div>
                <div class='footer-right'>
                    <p>Follow Us</p>
                    <div class='footer-socials'>
                        <a href='#'><img src='icon-ig.png' alt='Instagram'></a>
                        <a href='#'><img src='icon-fb.png' alt='Facebook'></a>
                        <a href='#'><img src='icon-tw.png' alt='Twitter'></a>
                    </div>
                </div>
            </div>
        </footer>

        <script src='pop-up.js'></script>
        <script src="dashboard.js"></script>
        <script src="search.js"></script>
        
    </body>
    </html>
    