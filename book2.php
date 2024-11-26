<?php
session_start();

// Sample book details (These placeholders will be dynamically replaced during book creation)
$book = [
    'id' => '2', // Ensure this is dynamically replaced
    'title' => 'Murder on the Orient Express',
    'author' => 'Agatha Christie',
    'year' => '1934',
    'genre' => 'novel, mystery',
    'number' => '2',
    'image' => 'murder-on-the-orient-express.jpg',
    'description' => 'Murder on the Orient Express is undoubtedly one of Agatha Christie&#039;s greatest mystery novels.',
    'history' => 'No history available yet.',
    'rating' => '4.1',
    'stars' => '★★★★☆',
    'language' => 'ENGLISH',
];

// Determine user role
$role = strtolower($_SESSION['role'] ?? '');
$dashboardLink = $role === 'librarian' ? 'Dashboard(Librarian).php' : 'Dashboard(Reader).php';
$booklistLink = $role === 'librarian' ? 'booklist(Librarian).php' : 'booklist(Reader).php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Bakbak+One' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel='stylesheet'>
    <link rel="stylesheet" href="book<?php echo htmlspecialchars($book['id']); ?>.css">
    <link rel="stylesheet" href="pop-up_add.css">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
</head>
<body>
    <div class="dboard_content">
        <!-- Sidebar -->
        <section id="sidebar">
            <a href="<?php echo $dashboardLink; ?>" class="brand">
                <img src="images/logo_ra.png" alt="Logo Icon" class="logo">
                <p>Libmanage</p>
            </a>
            <ul class="side-menu">
                <li>
                    <a href="<?php echo $dashboardLink; ?>" class="active">
                        <img src="images/dashboard_icon(sml).png" alt="Dashboard Icon" class="icon-therest"> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo $booklistLink; ?>" class="active">
                        <img src="images/book_icon(big).png" alt="Books Icon" class="icon"> Books
                    </a>
                </li>
            </ul>
        </section>

        <!-- Main Content -->
        <section id="content">
            <nav>
                <i class='bx bx-menu toggle-sidebar'></i>
                <form action="#">
                    <div class="form-group">
                        <input type="text" placeholder="Search books & members">
                        <i class='bx bx-search icon'></i>
                    </div>
                </form>

               <!-- Conditionally Show Edit/Delete Buttons -->
                <?php if ($role === 'librarian'): ?>
                    <button class="editBookBtn action-button add-book-button" 
                            data-book-id="<?php echo htmlspecialchars($book['id']); ?>" 
                            data-book-title="<?php echo htmlspecialchars($book['title']); ?>"
                            onclick="openEditPopup(<?php echo htmlspecialchars($book['id']); ?>)">
                        Edit
                    </button>
                    <button class="deleteBookBtn action-button delete-button add-book-button" 
                            data-book-id="<?php echo htmlspecialchars($book['id']); ?>" 
                            data-book-title="<?php echo htmlspecialchars($book['title']); ?>"
                            onclick="openDeletePopup(<?php echo htmlspecialchars($book['id']); ?>, '<?php echo htmlspecialchars($book['title']); ?>')">
                        Delete
                    </button>
                <?php endif; ?>


                <a href="#" class="nav-link">
                    <i class='bx bxs-bell icon'></i>
                    <span class="badge">5</span>
                </a>
                <div class="profile">
                    <img src="https://via.placeholder.com/36" alt="Profile">
                    <ul class="profile-link">
                        <li><a href="profile.php"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                        <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                        <li><a href="Mainpage.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                    </ul>
                </div>
            </nav>

            <main>
                <h1 class="title"><a href="<?php echo $booklistLink; ?>">Books</a> >> <?php echo htmlspecialchars($book['title']); ?></h1>
                <div class="data">
                    <div class="left-column">
                        <div class="content-data book-information">
                            <div class="book-info-content">
                                <div class="book-details">
                                    <div class="detail-item">
                                        <span class="label">Publish Year:</span>
                                        <span class="value"><?php echo htmlspecialchars($book['year']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Genre:</span>
                                        <span class="value"><?php echo htmlspecialchars($book['genre']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Book Number:</span>
                                        <span class="value"><?php echo htmlspecialchars($book['number']); ?></span>
                                    </div>
                                </div>
                                <div class="book-image">
                                    <img src="images/<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
                                </div>
                            </div>
                        </div>
                        <div class="content-data book-history">
                            <div class="head">
                                <h3>Book History</h3>
                            </div>
                            <p><?php echo htmlspecialchars($book['history']); ?></p>
                        </div>
                    </div>

                    <div class="right-column">
                        <div class="content-data book-overview">
                            <div class="book-overview-content">
                                <img src="images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?> Cover" class="book-cover">
                                <h2 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h2>
                                <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                                <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
                                <div class="book-rating">
                                    <span><?php echo htmlspecialchars($book['rating']); ?></span>
                                    <div class="stars"><?php echo htmlspecialchars($book['stars']); ?></div>
                                    <span class="language"><?php echo htmlspecialchars($book['language']); ?></span>
                                </div>
                                <button class="read-button">View/Read Book</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </section>
    </div>

    <!-- Edit Book Modal -->
    <div id="editBookPopup" class="modal">
        <div class="modal-content">
            <span class="close edit-close" onclick="closeEditPopup()">&times;</span>
            <h2>Edit Book</h2>
            <form id="editBookForm" action="editbook.php" method="POST">
                <input type="hidden" name="bookId" id="editBookId">
                <label for="bookname">Book Name:</label>
                <input type="text" name="bookname" id="editBookName" required>
                <label for="author">Author:</label>
                <input type="text" name="author" id="editAuthor" required>
                <label for="description">Description:</label>
                <textarea name="description" id="editDescription" required></textarea>
                <button type="submit" class="modal-submit-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Delete Book Modal -->
    <div id="deleteBookPopup" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeletePopup()">&times;</span>
            <h2>Are you sure you want to delete this book?</h2>
            <form action="deletebook.php" method="POST">
                <input type="hidden" name="bookId" id="deleteBookId">
                <p id="deleteBookTitle"></p>
                <div class="button-container">
                    <button type="submit" class="modal-delete-btn">Yes, Delete</button>
                    <button type="button" onclick="closeDeletePopup()" class="modal-delete-btn">Cancel</button>
                </div>
            </form>
        </div>
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
                <p>&copy; ALPHA ONE 2024</p>
            </div>
            <div class="footer-right">
                <p>Follow Us</p>
                <div class="footer-socials">
                    <a href="#"><img src="icon-ig.png" alt="Instagram"></a>
                    <a href="#"><img src="icon-fb.png" alt="Facebook"></a>
                    <a href="#"><img src="icon-tw.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="pop-up.js"></script>
</body>
</html>
