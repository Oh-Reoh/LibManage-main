<?php
$host = 'localhost'; 
$dbname = 'libmanagedb'; 
$username = 'root'; 
$password = ''; 

// Create a PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['username'])) {
    $user = htmlspecialchars($_GET['username']);

    $stmt = $pdo->prepare("SELECT bookname, author FROM tbl_bookinfo_logs WHERE borrowedby = ? AND bookisinuse = 1 LIMIT 1");
    $stmt->execute([$user]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo json_encode([
            'success' => true,
            'bookname' => $result['bookname'],
            'author' => $result['author']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
