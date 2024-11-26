<?php
// Database connection
$host = 'localhost';
$dbname = 'libmanagedb';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the search query from the GET request
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Initialize response array
$response = ['success' => false, 'results' => [], 'type' => ''];

// Search for books and users if query is provided
if (!empty($query)) {
    // Search books
    $booksStmt = $pdo->prepare("SELECT bookname, author FROM tbl_bookinfo WHERE bookname LIKE ? OR author LIKE ?");
    $booksStmt->execute(["%$query%", "%$query%"]);
    $books = $booksStmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) > 0) {
        $response['results'] = $books;
        $response['type'] = 'books';
        $response['success'] = true;
    }

    // Search users (if no books found or books and users both match)
    if (empty($response['results'])) {
        $usersStmt = $pdo->prepare("SELECT username, email FROM tbl_userinfo WHERE username LIKE ? OR email LIKE ?");
        $usersStmt->execute(["%$query%", "%$query%"]);
        $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($users) > 0) {
            $response['results'] = $users;
            $response['type'] = 'users';
            $response['success'] = true;
        }
    }
} else {
    $response['message'] = 'No search query provided';
}

// Return response as JSON
echo json_encode($response);
?>
