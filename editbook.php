<?php
session_start();

// Ensure the user is a librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Librarian') {
    die(json_encode(["success" => false, "message" => "Unauthorized access.", "debug" => $_SESSION]));
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookId']);
    $bookname = htmlspecialchars($_POST['bookname']);
    $author = htmlspecialchars($_POST['author']);
    $bookNumber = htmlspecialchars($_POST['bookNumber']);
    $publishYear = intval($_POST['publishYear']);
    $genre = htmlspecialchars($_POST['genre']);
    $description = htmlspecialchars($_POST['description']);

    // Handle image upload
    $image = null; // Keep current image if no new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "images/";
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $imageName; // Update image if upload succeeds
        } else {
            die(json_encode(["success" => false, "message" => "Error uploading image."]));
        }
    }

    // Update query
    $query = "UPDATE tbl_bookinfo 
              SET bookname = ?, author = ?, bookNumber = ?, publishYear = ?, genre = ?, description = ?";
    $params = [$bookname, $author, $bookNumber, $publishYear, $genre, $description];

    if ($image !== null) {
        $query .= ", image = ?";
        $params[] = $image;
    }

    $query .= " WHERE id = ?";
    $params[] = $bookId;

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Error preparing query: " . $conn->error]));
    }

    $stmt->bind_param(
        str_repeat("s", count($params) - 1) . "i", // Prepare dynamic types (e.g., ssssii)
        ...$params
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Book updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating book: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
