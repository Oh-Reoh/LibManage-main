<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Set error message in session and redirect to Dashboard
    $_SESSION['error_message'] = 'Database connection failed. Redirecting to Dashboard.';
    header("Location: Dashboard(Librarian).php");
    exit();
}

// Allowed genres
$allowedGenres = [
    "Fantasy", "Science Fiction", "Dystopian", "Mystery", "Horror", "Thriller",
    "Historical Fiction", "Romance", "Contemporary Fiction", "Literary Fiction",
    "Magical Realism", "Graphic Novels", "Short Stories", "Young Adult (YA)",
    "New Adult (NA)", "Children's", "Middle Grade", "Non-Fiction",
    "Biography/Autobiography", "History", "Science & Nature", "Technology",
    "Self-Help", "Health & Wellness", "Cookbooks", "Art & Photography",
    "Travel", "Business & Economics", "Religion & Spirituality", "True Crime",
    "Humor", "Adventure", "Action", "Science", "Nature", "Novel"
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookname = htmlspecialchars($_POST['bookname'] ?? '');
    $author = htmlspecialchars($_POST['author'] ?? '');
    $bookNumber = htmlspecialchars($_POST['bookNumber'] ?? '');
    $publishYear = intval($_POST['publishYear'] ?? 0);
    $genreInput = htmlspecialchars($_POST['genre'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $issueddate = date('Y-m-d');
    $isinuse = 0;

    $genres = array_map('trim', explode(',', strtolower($genreInput)));
    $invalidGenres = array_diff($genres, array_map('strtolower', $allowedGenres));

    if ($publishYear > date('Y')) {
        // Set error message and redirect
        $_SESSION['error_message'] = 'Invalid publish year. Try again.';
        header("Location: Dashboard(Librarian).php");
        exit();
    } elseif (!empty($invalidGenres)) {
        // Set error message and redirect
        $_SESSION['error_message'] = 'Invalid genre(s): ' . implode(', ', $invalidGenres) . '. Try again.';
        header("Location: Dashboard(Librarian).php");
        exit();
    } else {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_bookinfo WHERE bookNumber = ?");
        $checkStmt->bind_param("s", $bookNumber);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            // Set error message and redirect
            $_SESSION['error_message'] = 'Book number already exists. Please use a unique book number.';
            header("Location: Dashboard(Librarian).php");
            exit();
        } else {
            $image = 'blankimg.png';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $targetDir = "images/";
                $imageName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $imageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $image = $imageName;
                }
            }

            $stmt = $conn->prepare("INSERT INTO tbl_bookinfo (bookname, author, bookNumber, publishYear, genre, description, issueddate, isinuse, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssisssis",
                $bookname,
                $author,
                $bookNumber,
                $publishYear,
                $genreInput,
                $description,
                $issueddate,
                $isinuse,
                $image
            );

            if ($stmt->execute()) {
                $bookId = $stmt->insert_id;
                if (generateBookFiles($bookId, $bookname, $author, $image, $description, $publishYear, $genres, $bookNumber)) {
                    // Redirect to dashboard after success
                    header("Location: Dashboard(Librarian).php");
                    exit();
                } else {
                    // Error generating book files
                    $_SESSION['error_message'] = 'Error generating book files.';
                    header("Location: Dashboard(Librarian).php");
                    exit();
                }
            } else {
                // Error with SQL execution
                $_SESSION['error_message'] = 'Error inserting the book: ' . $stmt->error;
                header("Location: Dashboard(Librarian).php");
                exit();
            }

            $stmt->close();
        }
    }
}

$conn->close();

function generateBookFiles($id, $name, $author, $image, $description, $year, $genres, $bookNumber) {
    $templatePath = 'booktemplate.php';
    $cssTemplatePath = 'booktemplate.css';
    $outputPath = "book{$id}.php";
    $cssOutputPath = "book{$id}.css";

    if (!file_exists($templatePath)) {
        return false;
    }

    $bookTemplate = file_get_contents($templatePath);
    $bookTemplate = str_replace(
        ['{{ID}}', '{{NAME}}', '{{AUTHOR}}', '{{IMAGE}}', '{{DESCRIPTION}}', '{{YEAR}}', '{{GENRES}}', '{{BOOKNUMBER}}'],
        [$id, $name, $author, $image, $description, $year, implode(', ', $genres), $bookNumber],
        $bookTemplate
    );

    if (file_put_contents($outputPath, $bookTemplate) === false) {
        return false;
    }

    if (!copy($cssTemplatePath, $cssOutputPath)) {
        return false;
    }

    return true;
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Dashboard(Librarian).css">
    <title>Dashboard</title>
</head>
<body>
    
    <div class="dboard_content">
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

        <section id="content">
            <nav>
                <i class='bx bx-menu toggle-sidebar' ></i>
                <form action="#">
                    <div class="form-group">
                        <input type="text" placeholder="Search books & members">
                        <i class='bx bx-search icon' ></i>
                    </div>
                </form>


                <div class="profile">
                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                    <ul class="profile-link">
                        <li><a href="profile.php"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
                        <li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
                        <li><a href="Mainpage.php"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
                    </ul>
                </div>
            </nav>

            <main>
                <div class="book-registration">
                    <div class="head">
                        <h2>Add A New Book</h2>
                        <form action="AddBook.php" method="POST" enctype="multipart/form-data">
                        <br><br>
                            <label for="image">Upload Book Image</label>
                            <br><br>
                            <input type="file" id="image" name="image" accept="image/*">
                            <br><br>
                            <label for="bookname">Book Name</label>
                            <br><br>
                            <input type="text" id="bookname" name="bookname" placeholder="Enter the book name" required>
                            <br><br>
                            <label for="author">Author</label>
                            <br><br>
                            <input type="text" id="author" name="author" placeholder="Enter the author's name" required>
                            <br><br>
                            <label for="bookNumber">Book Number</label>
                            <br><br>
                            <input type="text" id="bookNumber" name="bookNumber" placeholder="Enter the book number" required>
                            <br><br>
                            <label for="publishYear">Publish Year</label>
                            <br><br>
                            <input type="number" id="publishYear" name="publishYear" placeholder="Enter the publish year" required>
                            <br><br>
                            <label for="genre">Genre</label>
                            <br><br>
                            <input type="text" id="genre" name="genre" placeholder="Enter genres (comma-separated)" required>
                            <br><br>
                            <label for="description">Description</label>
                            <br><br>
                            <textarea id="description" name="description" placeholder="Enter a brief description" required></textarea>
                            <br><br>
                            <button type="submit" name="submit">Register Book</button>
                        </form>
                    </div>    
                </div>
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

    <script src="dashboard.js"></script>
</body>
</html>
