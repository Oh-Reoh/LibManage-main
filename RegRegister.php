<?php
include 'function.php';

if (isset($_POST['submit'])) {
    // Retrieve POST data and sanitize it
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    $cpass = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $department = isset($_POST['department']) ? mysqli_real_escape_string($conn, $_POST['department']) : '';

    // Validate the input fields
    if (empty($username) || empty($email) || empty($pass) || empty($cpass) || empty($department)) {
        echo "<script>alert('Please fill in all required fields');</script>";
    } elseif ($pass != $cpass) {
        echo "<script>alert('Confirm password does not match!');</script>";
    } else {
        // Check if the email already exists in the database
        $select = mysqli_query($conn, "SELECT * FROM `tbl_userinfo` WHERE email = '$email'") or die('Query failed: ' . mysqli_error($conn));

        if (mysqli_num_rows($select) > 0) {
            echo "<script>alert('Email already exists');</script>";
        } else {
            // Hash the password using password_hash() for better security
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $insert = mysqli_query($conn, "INSERT INTO `tbl_userinfo`(`username`, `email`, `password`, `department`) 
                                           VALUES('$username','$email','$hashedPassword','$department')") or die('Query failed: ' . mysqli_error($conn));
            
            if ($insert) {
                // Set a success message in session
                $_SESSION['success_message'] = "Registered successfully! Please log in.";
                header('Location: LoginPage.php'); // Redirect to login page
                exit();
            } else {
                echo "<script>alert('Registration failed! Please try again later.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="RegRegister.css">
</head>
<body>
    <div class="banner_mntb">
        <video src="background_RegRegister.mp4" id="backgroundVideo" autoplay muted loop></video>
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="Mainpage.php">Home</a></li>
                <li><a href="#">Book Catalog</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
            <a class="login" href="LoginPage.php">Login</a>
        </div>

        <div class="signup-container">
            <div class="signup-form">
                <span class="close-button" onclick="redirectToMainpage()">
                    <img src="close icon.png" alt="close">
                </span> 

                <script>
                    function redirectToMainpage() {
                        window.location.href = "Mainpage.php";
                    }
                </script>

                <h2 class="sign-up-header">Sign up</h2>
                <form id="registerForm" action="" method="POST">
                    <input type="hidden" name="action" value="register">
                   <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" required>
                   <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                   <label for="password">Preferred Password</label>
                    <input type="password" name="password" placeholder="Preferred Password" required>
                   <label for="confirm-password">Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                   <label for="department">Department</label>
                    <input type="text" name="department" placeholder="Department" required>

                    <button type="submit" name="submit">SIGN UP</button>
                </form>

                <p class="switch-auth">Already have an account? <a href="LoginPage.php">Sign In</a></p>
            </div>

            <div class="image-container">
                <img src="boy-reading-book-animation.gif" alt="Anime Image">
            </div>
        </div>
    </div>
</body>
</html>
