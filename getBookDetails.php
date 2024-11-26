<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$stmt = $conn->prepare("SELECT id, bookname, author, bookNumber, publishYear, genre, description FROM tbl_bookinfo WHERE id = ?");
$stmt->bind_param("i", $bookId);

// Check if the statement is valid
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Query preparation failed: " . $conn->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the book details from the database
    $book = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $book]);
} else {
    // No book found for the given ID
    echo json_encode(["success" => false, "message" => "Book not found. Please check the book ID."]);
}

$stmt->close();
$conn->close();
?>
