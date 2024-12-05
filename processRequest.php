<?php
session_start();
include('db_connect.php');

// Ensure only librarians can process requests
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header("Location: Dashboard(Reader).php");
    exit();
}

// Handle Accept Request
if (isset($_POST['accept'])) {
    $requestId = $_POST['accept'];

    try {
        $query = "SELECT bookname, requestby FROM tbl_bookinfo_logs WHERE id = :requestId";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['requestId' => $requestId]);
        $requestData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($requestData) {
            $bookName = $requestData['bookname'];
            $borrowedBy = $requestData['requestby'];
            $issuedDate = date('Y-m-d');

            // Update the log as accepted
            $updateLogQuery = "UPDATE tbl_bookinfo_logs 
                               SET isrequest = 0, bookisinuse = 1, issueddate = :issuedDate, borrowedby = :borrowedBy 
                               WHERE id = :requestId";
            $stmtUpdateLog = $pdo->prepare($updateLogQuery);
            $stmtUpdateLog->execute([
                'issuedDate' => $issuedDate,
                'borrowedBy' => $borrowedBy,
                'requestId' => $requestId
            ]);

            // Update the book status
            $updateBookQuery = "UPDATE tbl_bookinfo 
                                SET isinuse = 1 
                                WHERE bookname = :bookName";
            $stmtUpdateBook = $pdo->prepare($updateBookQuery);
            $stmtUpdateBook->execute(['bookName' => $bookName]);

            header("Location: Reader'sRequest(Librarian).php?success=accepted");
            exit();
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: Reader'sRequest(Librarian).php?error=accept_failed");
        exit();
    }
}

if (isset($_POST['deny'])) {
    $requestId = $_POST['deny'];

    try {
        // Mark the request as denied
        $updateLogQuery = "UPDATE tbl_bookinfo_logs 
                           SET isrequest = 3 
                           WHERE id = :requestId";
        $stmtUpdateLog = $pdo->prepare($updateLogQuery);
        $stmtUpdateLog->execute(['requestId' => $requestId]);

        header("Location: Reader'sRequest(Librarian).php?success=denied");
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: Reader'sRequest(Librarian).php?error=deny_failed");
        exit();
    }
}

// Handle Deny Request
if (isset($_POST['deny'])) {
    $requestId = $_POST['deny'];

    try {
        // Mark the request as denied
        $updateLogQuery = "UPDATE tbl_bookinfo_logs 
                           SET isrequest = 3 
                           WHERE id = :requestId";
        $stmtUpdateLog = $pdo->prepare($updateLogQuery);
        $stmtUpdateLog->execute(['requestId' => $requestId]);

        echo json_encode(['success' => true, 'message' => 'Request denied', 'id' => $requestId]);
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Request denial failed']);
        exit();
    }
}

// Handle Return Book
if (isset($_POST['return'])) {
    $logId = $_POST['return'];

    try {
        // Fetch issued date
        $query = "SELECT issueddate FROM tbl_bookinfo_logs WHERE id = :logId";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['logId' => $logId]);
        $logData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($logData) {
            $issuedDate = $logData['issueddate'];
            $returnDate = date('Y-m-d'); // Current date

            // Check if the return is late
            $isLate = (strtotime($returnDate) > strtotime($issuedDate . ' + 14 days')) ? 1 : 0;

            // Update the log for the returned book
            $updateLog = "UPDATE tbl_bookinfo_logs 
                          SET returndate = :returnDate, bookisinuse = 0, islate = :isLate 
                          WHERE id = :logId";
            $stmtUpdate = $pdo->prepare($updateLog);
            $stmtUpdate->execute([
                'returnDate' => $returnDate,
                'isLate' => $isLate,
                'logId' => $logId
            ]);

            // Mark the book as available
            $updateBook = "UPDATE tbl_bookinfo 
                           SET isinuse = 0 
                           WHERE bookname = (SELECT bookname FROM tbl_bookinfo_logs WHERE id = :logId)";
            $stmtBook = $pdo->prepare($updateBook);
            $stmtBook->execute(['logId' => $logId]);

            header("Location: ReturnBooks(Librarian).php?success=returned");
            exit();
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ReturnBooks(Librarian).php?error=return_failed");
        exit();
    }
}

// Redirect if no action is performed
header("Location: Reader'sRequest(Librarian).php");
exit();
?>
