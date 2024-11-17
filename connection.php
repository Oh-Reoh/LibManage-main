<?php
$con = mysqli_connect('localhost', 'root', '', 'libmanagedb');

if (mysqli_connect_errno()) {
    echo 'Database Connection Error: ' . mysqli_connect_error();
} else {
    echo 'Database Connected Successfully!';
}
?>
