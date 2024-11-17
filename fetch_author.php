<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['bookname'])) {
    $bookname = $conn->real_escape_string($_POST['bookname']);
    $sql = "SELECT author FROM tbl_bookinfo WHERE bookname = '$bookname'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo htmlspecialchars($row['author']);
    } else {
        echo "";
    }
}

$conn->close();
?>
