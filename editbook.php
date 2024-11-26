<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$bookId = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch book ID from POST request (form submission)
    $bookId = intval($_POST['bookId']);
} elseif (isset($_GET['bookId'])) {
    // Fetch book ID from GET request (initial page load)
    $bookId = intval($_GET['bookId']);
}

$bookData = [];
// Fetch the book details if bookId is provided
if ($bookId > 0) {
    $stmt = $conn->prepare("SELECT * FROM tbl_bookinfo WHERE id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookData = $result->fetch_assoc();
    $stmt->close();
}

// Process form submission for editing the book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookId']);
    $bookname = htmlspecialchars($_POST['bookname']);
    $author = htmlspecialchars($_POST['author']);
    $bookNumber = htmlspecialchars($_POST['bookNumber']);
    $publishYear = intval($_POST['publishYear']);
    $genre = htmlspecialchars($_POST['genre']);
    $description = htmlspecialchars($_POST['description']);
    $image = $bookData['image']; // Default to current image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "images/";
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $imageName;
        }
    }

    // Update the book record in the database
    $stmt = $conn->prepare("UPDATE tbl_bookinfo SET bookname = ?, author = ?, bookNumber = ?, publishYear = ?, genre = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssisssi", $bookname, $author, $bookNumber, $publishYear, $genre, $description, $image, $bookId);

    if ($stmt->execute()) {
        echo "<script>alert('Book updated successfully.');</script>";
        header("Location: Dashboard(Librarian).php"); // Redirect after successful update
        exit();
    } else {
        echo "<script>alert('Error updating book: " . $stmt->error . "');</script>";
    }

    $stmt->close();
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
    <title>Edit Book</title>
</head>
<body>
    <div class="dboard_content">
        <section id="sidebar">
            <!-- Sidebar content -->
        </section>
        <section id="content">
            <nav>
                <!-- Navigation content -->
            </nav>
            <main>
                <div class="book-registration">
                    <div class="head">
                        <h2>Edit Book</h2>
                        <form action="editbook.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookId); ?>">
                            <br><br>
                            <label for="image">Upload Book Image</label>
                            <br><br>
                            <input type="file" id="image" name="image" accept="image/*">
                            <img src="images/<?php echo htmlspecialchars($bookData['image'] ?? 'blankimg.png'); ?>" alt="Book Image" style="max-width: 100px;">
                            <br><br>
                            <label for="bookname">Book Name</label>
                            <br><br>
                            <input type="text" id="bookname" name="bookname" value="<?php echo htmlspecialchars($bookData['bookname'] ?? ''); ?>" required>
                            <br><br>
                            <label for="author">Author</label>
                            <br><br>
                            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($bookData['author'] ?? ''); ?>" required>
                            <br><br>
                            <label for="bookNumber">Book Number</label>
                            <br><br>
                            <input type="text" id="bookNumber" name="bookNumber" value="<?php echo htmlspecialchars($bookData['bookNumber'] ?? ''); ?>" required>
                            <br><br>
                            <label for="publishYear">Publish Year</label>
                            <br><br>
                            <input type="number" id="publishYear" name="publishYear" value="<?php echo htmlspecialchars($bookData['publishYear'] ?? ''); ?>" required>
                            <br><br>
                            <label for="genre">Genre</label>
                            <br><br>
                            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($bookData['genre'] ?? ''); ?>" required>
                            <br><br>
                            <label for="description">Description</label>
                            <br><br>
                            <textarea id="description" name="description" required><?php echo htmlspecialchars($bookData['description'] ?? ''); ?></textarea>
                            <br><br>
                            <button type="submit" name="submit">Update Book</button>
                        </form>
                    </div>
                </div>
            </main>
        </section>
    </div>
</body>
</html>
