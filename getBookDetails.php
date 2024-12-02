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
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "No book ID provided."]);
    exit();
}

$bookId = intval($_GET['id']); // Ensure we are working with an integer ID

// Fetch the book details
$query = "SELECT id, bookname, author, bookNumber, publishYear, genre, description FROM tbl_bookinfo WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $book]);
    } else {
        echo json_encode(["success" => false, "message" => "Book not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Query failed."]);
}

$conn->close();
?>
