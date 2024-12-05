<?php
session_start();
include('db_connect.php');

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'regular') {
    header("Location: LoginPage.php");
    exit();
}

// Get user details
$userId = $_SESSION['user_id'];
$bookName = $_POST['bookname'];

try {
    // Fetch user information (username)
    $userQuery = "SELECT username FROM tbl_userinfo WHERE id = :userId";
    $stmtUser = $pdo->prepare($userQuery);
    $stmtUser->execute(['userId' => $userId]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        header("Location: Dashboard(Reader).php?error=user_not_found");
        exit();
    }

    $username = $userData['username'];

    // Fetch book details from tbl_bookinfo
    $bookQuery = "SELECT bookname, author FROM tbl_bookinfo WHERE bookname = :bookName AND isinuse = 0";
    $stmtBook = $pdo->prepare($bookQuery);
    $stmtBook->execute(['bookName' => $bookName]);
    $bookData = $stmtBook->fetch(PDO::FETCH_ASSOC);

    if ($bookData) {
        $author = $bookData['author'];

        // Insert request into tbl_bookinfo_logs
        $insertQuery = "INSERT INTO tbl_bookinfo_logs 
                (bookname, author, issueddate, returndate, borrowedby, returnedby, bookisinuse, requestby, isrequest, islate) 
                VALUES 
                (:bookname, :author, NULL, NULL, NULL, NULL, 0, :requestby, 1, 0)";
        $stmtInsert = $pdo->prepare($insertQuery);
        $stmtInsert->execute([
            'bookname' => $bookName,
            'author' => $author,
            'requestby' => $username
        ]);

        header("Location: Dashboard(Reader).php?success=requested");
        exit();
    } else {
        header("Location: Dashboard(Reader).php?error=book_unavailable");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    header("Location: Dashboard(Reader).php?error=request_failed");
    exit();
}
?>
