<?php
session_start();
include('db_connect.php');

// Verify the user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'regular') {
    header("Location: LoginPage.php");
    exit();
}

$userId = $_SESSION['user_id'];
$bookName = $_POST['bookname'];

try {
    // Fetch book log data for the logged-in user
    $query = "SELECT id, issueddate FROM tbl_bookinfo_logs 
              WHERE bookname = :bookname AND borrowedby = :borrowedby AND bookisinuse = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['bookname' => $bookName, 'borrowedby' => $userId]);
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
            'returnedby' => $userId,
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
        // If no matching log is found
        header("Location: Dashboard(Reader).php?error=book_not_found");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    header("Location: Dashboard(Reader).php?error=return_failed");
    exit();
}
?>
