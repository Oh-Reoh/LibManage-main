<?php
$servername = "localhost";
$dbUsername = "root"; // Changed from $username to $dbUsername
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $dbUsername, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $bookname = htmlspecialchars($_POST['bookname']);
    $author = htmlspecialchars($_POST['author']);
    $username = htmlspecialchars($_POST['username']);
    $issueddate = date('Y-m-d');
    $isuse = 1;

    $stmtLogs = $conn->prepare("INSERT INTO tbl_bookinfo_logs 
        (bookname, author, issueddate, borrowedby, requestby, bookisinuse, isrequest) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmtLogs2 = $conn->prepare("UPDATE tbl_bookinfo SET isinuse = 1 WHERE bookname = ? and isinuse = 0");

    if ($stmtLogs) {
        $stmtLogs->bind_param("sssssss", $bookname, $author, $issueddate, $username, $username, $isuse, $isuse);

        if ($stmtLogs->execute()) {
            echo "<script type='text/javascript'>alert('Book log entry added successfully!');</script>";

            if ($stmtLogs2) {
                $stmtLogs2->bind_param("s", $bookname);

                if ($stmtLogs2->execute()) {
                    echo "<script type='text/javascript'>alert('Book status updated successfully!');</script>";
                } else {
                    echo "<script type='text/javascript'>alert('Error updating book status: " . $stmtLogs2->error . "');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Error preparing update statement: " . $conn->error . "');</script>";
            }

        } else {
            echo "<script type='text/javascript'>alert('Error in log entry: " . $stmtLogs->error . "');</script>";
        }

        $stmtLogs->close();
        $stmtLogs2->close();
    } else {
        echo "<script type='text/javascript'>alert('Error preparing log statement: " . $conn->error . "');</script>";
    }
}

$bookOptions = "";
$sql = "SELECT bookname, author FROM tbl_bookinfo";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookOptions .= "<option value='" . htmlspecialchars($row['bookname']) . "'>" . htmlspecialchars($row['bookname']) . "</option>";
    }
} else {
    $bookOptions = "<option value='' disabled>No books available</option>";
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

            <main>
                <div class="book-registration">
                    <h2>Borrowing Book Transaction</h2>
                    <form action="BorrowBook.php" method="POST">
                        <label for="bookname">Book Name</label>
                        <select id="bookname" name="bookname" required onchange="fetchAuthor()">
                            <option value="" disabled selected>Select a book</option>
                            <?php echo $bookOptions; ?>
                        </select>

                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" placeholder="Enter the author's name" required>

                        <label for="username">Enter your full name</label>
                        <input type="text" id="username" name="username" placeholder="Enter the username" required>

                        <button type="submit" name="submit">Borrow Book</button>
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
    <script>
    function fetchAuthor() {
        var bookname = document.getElementById("bookname").value;
        if (bookname) {
            $.ajax({
                url: 'fetch_author.php',
                type: 'POST',
                data: { bookname: bookname },
                success: function(data) {
                    document.getElementById("author").value = data;
                }
            });
        } else {
            document.getElementById("author").value = '';
        }
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
