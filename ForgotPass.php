<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ForgotPass.css">
</head>
<body>
    <div class="banner_mntb">
        <video src="background_RegRegister.mp4" id="backgroundVideo" autoplay muted loop></video>
        <div class="forpass-container">

            <div class="forpass-form">  
                
                <span class="close-button" onclick="redirectToMainpage()">
                    <img src="close icon.png" alt="close">
                </span>

                <script>
                    function redirectToMainpage() {
                        window.location.href = "Mainpage.php";
                    }
                </script>


                <h2 class="forpass-header">Reset account password</h2>
                <p>Enter your username and new password</p>
                <form action="server.php" method="POST">
                    <input type="hidden" name="action" value="reset_password">

                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" required>

                    <label for="new-password">New Password</label>
                    <input type="password" name="new_password" placeholder="New Password" required>

                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required>

                    <button type="submit">RESET PASSWORD</button> 
                </form>

        </div>

    </div>
</body>
</html>