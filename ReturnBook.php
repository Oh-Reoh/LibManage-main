<?php
session_start();
include('db_connect.php');

// Verify the user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'regular') {
    header("Location: LoginPage.php");
    exit();
}

$userId = $_SESSION['user_id'];
$bookName = isset($_POST['bookname']) ? trim($_POST['bookname']) : null;

if (empty($bookName)) {
    header("Location: Dashboard(Reader).php?error=book_not_selected");
    exit();
}

// Fetch the username of the logged-in user
$queryUser = "SELECT username FROM tbl_userinfo WHERE id = :userId";
$stmtUser = $pdo->prepare($queryUser);
$stmtUser->execute(['userId' => $userId]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
$username = $userData['username'];

try {
    // Fetch book log data for the logged-in user
    $query = "SELECT id, issueddate FROM tbl_bookinfo_logs 
              WHERE bookname = :bookname AND borrowedby = :borrowedby AND bookisinuse = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'bookname' => $bookName,
        'borrowedby' => $username // Use username or userId based on your database
    ]);
    $logData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($logData) {
        $logId = $logData['id'];
        $issuedDate = $logData['issueddate'];
        $returnDate = date('Y-m-d');

        // Check if the return is late
        $isLate = (strtotime($returnDate) > strtotime($issuedDate . ' + 14 days')) ? 1 : 0;

        // Update the book log
        $updateLog = "UPDATE tbl_bookinfo_logs 
                      SET returndate = :returndate, bookisinuse = 0, returnedby = :returnedby, islate = :islate 
                      WHERE id = :logid";
        $stmtUpdate = $pdo->prepare($updateLog);
        $stmtUpdate->execute([
            'returndate' => $returnDate,
            'returnedby' => $username,
            'islate' => $isLate,
            'logid' => $logId
        ]);

        // Update the book availability
        $updateBook = "UPDATE tbl_bookinfo SET isinuse = 0 WHERE bookname = :bookname";
        $stmtBook = $pdo->prepare($updateBook);
        $stmtBook->execute(['bookname' => $bookName]);

        // Redirect with success message
        header("Location: Dashboard(Reader).php?success=return_successful");
        exit();
    } else {
        // Debugging for missing book
        error_log("Return failed: Book not found. Book Name: $bookName, User ID: $userId, BorrowedBy: $username");
        header("Location: Dashboard(Reader).php?error=book_not_found");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    header("Location: Dashboard(Reader).php?error=return_failed");
    exit();
}
