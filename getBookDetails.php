<?php
session_start();
header('Content-Type: application/json');

// Check if the user is a librarian
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'librarian') {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

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

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "No book ID provided."]);
    exit();
}

$bookId = intval($_GET['id']);

// Fetch the book details
$stmt = $conn->prepare("SELECT id, bookname, author, bookNumber, publishYear, genre, description FROM tbl_bookinfo WHERE id = ?");
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
$conn->close();
?>
