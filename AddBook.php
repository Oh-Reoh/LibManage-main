<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert book into database if the form is submitted
if (isset($_POST['submit'])) {
    $bookname = htmlspecialchars($_POST['bookname']);
    $author = htmlspecialchars($_POST['author']);
    $issueddate = date('Y-m-d'); // Format the current date as 'YYYY-MM-DD'

    // Prepare the SQL statement to insert book data into the table
    $stmt = $conn->prepare("INSERT INTO tbl_bookinfo (bookname, author, issueddate) VALUES (?, ?, ?)");
    
    // Check if the statement is prepared successfully
    if ($stmt) {
        $stmt->bind_param("sss", $bookname, $author, $issueddate); // Bind the bookname, author, and issueddate variables

        // Execute the statement and check if the insertion was successful
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('New book registered successfully!');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<script type='text/javascript'>alert('Error preparing the statement: " . $conn->error . "');</script>";
    }
}

$conn->close();
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

            <main>
                <div class="book-registration">
                    <h2>Add A New Book</h2>
                    <form action="AddBook.php" method="POST">
                        <label for="bookname">Book Name</label>
                        <input type="text" id="bookname" name="bookname" placeholder="Enter the book name" required>

                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" placeholder="Enter the author's name" required>

                        <button type="submit" name="submit">Register Book</button>
                    </form>
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
