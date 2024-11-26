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

// Fetch the list of books
$sql = "SELECT id, bookname FROM tbl_bookinfo";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode(["success" => true, "books" => $books]);
} else {
    echo json_encode(["success" => false, "message" => "No books found."]);
}

$conn->close();
?>
