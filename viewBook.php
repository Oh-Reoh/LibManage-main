<?php
session_start();
include('db_connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'regular') {
    header("Location: LoginPage.php");
    exit();
}

$bookId = isset($_GET['bookId']) ? intval($_GET['bookId']) : null;

if ($bookId) {
    try {
        // Fetch book details
        $stmt = $pdo->prepare("SELECT * FROM tbl_bookinfo WHERE id = :bookId");
        $stmt->execute(['bookId' => $bookId]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            // Check if the book is currently borrowed
            if ($book['isinuse'] == 1) {
                $_SESSION['error_message'] = 'This book is currently borrowed.';
                header("Location: Dashboard(Reader).php");
                exit();
            } else {
                // Redirect to the original `book#.php` file
                $bookPage = "book{$bookId}.php";
                if (file_exists($bookPage)) {
                    header("Location: $bookPage");
                    exit();
                } else {
                    $_SESSION['error_message'] = 'Book page not found.';
                    header("Location: Dashboard(Reader).php");
                    exit();
                }
            }
        } else {
            $_SESSION['error_message'] = 'Book not found.';
            header("Location: Dashboard(Reader).php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $_SESSION['error_message'] = 'An error occurred. Please try again.';
        header("Location: Dashboard(Reader).php");
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Invalid book selection.';
    header("Location: Dashboard(Reader).php");
    exit();
}
?>
