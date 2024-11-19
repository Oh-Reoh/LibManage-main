<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmanagedb";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Array of genres to add
$newGenres = ["Adventure", "Action", "Science", "Nature", "Satire", "Novel"];

foreach ($newGenres as $genre) {
    // Insert each genre into the genres table
    $stmt = $conn->prepare("INSERT INTO tbl_genres (genre_name) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $genre);

        if ($stmt->execute()) {
            echo "Genre '$genre' added successfully.<br>";
        } else {
            echo "Error adding genre '$genre': " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement for genre '$genre': " . $conn->error . "<br>";
    }
}

$conn->close();
?>
