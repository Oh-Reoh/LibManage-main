<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookId']);
    
    if ($bookId <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid Book ID."]);
        exit();
    }

    // Check if the book exists in the database
    $stmt = $conn->prepare("SELECT image FROM tbl_bookinfo WHERE id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $stmt->bind_result($image);
    $exists = $stmt->fetch();
    $stmt->close();

    if (!$exists) {
        echo json_encode(["success" => false, "message" => "Book not found."]);
        exit();
    }

    // Delete the book record
    $deleteStmt = $conn->prepare("DELETE FROM tbl_bookinfo WHERE id = ?");
    $deleteStmt->bind_param("i", $bookId);

    if ($deleteStmt->execute()) {
        // Remove book image if it exists
        if ($image && $image !== 'blankimg.png') {
            $filePath = "images/" . $image;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the image file
            }
        }

        // Remove associated PHP and CSS files
        $phpFile = "book" . $bookId . ".php";
        $cssFile = "book" . $bookId . ".css";

        if (file_exists($phpFile)) {
            unlink($phpFile); // Delete the PHP file
        }
        if (file_exists($cssFile)) {
            unlink($cssFile); // Delete the CSS file
        }

        // Redirect to the Librarian Dashboard after successful deletion
        echo json_encode(["success" => true, "message" => "Book deleted successfully."]);
        header("Location: Dashboard(Librarian).php"); // Redirect
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting book: " . $deleteStmt->error]);
    }

    $deleteStmt->close();
}

$conn->close();
?>
