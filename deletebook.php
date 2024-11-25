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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['bookId']);

    // Check if the book exists
    $checkStmt = $conn->prepare("SELECT image FROM tbl_bookinfo WHERE id = ?");
    if (!$checkStmt) {
        die(json_encode(["success" => false, "message" => "Query preparation failed."]));
    }
    $checkStmt->bind_param("i", $bookId);
    $checkStmt->execute();
    $checkStmt->bind_result($image);
    $exists = $checkStmt->fetch();
    $checkStmt->close();

    if (!$exists) {
        die(json_encode(["success" => false, "message" => "Book not found."]));
    }

    // Delete the book record
    $deleteStmt = $conn->prepare("DELETE FROM tbl_bookinfo WHERE id = ?");
    if (!$deleteStmt) {
        die(json_encode(["success" => false, "message" => "Error preparing delete query: " . $conn->error]));
    }
    $deleteStmt->bind_param("i", $bookId);

    if ($deleteStmt->execute()) {
        // Remove book image if it exists
        if ($image && $image !== 'blankimg.png') {
            $filePath = "images/" . $image;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the image file
            }
        }
        echo json_encode(["success" => true, "message" => "Book deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting book: " . $deleteStmt->error]);
    }

    $deleteStmt->close();
}

$conn->close();
?>
