<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="LoginPage.css">
</head>
<body>
    <div class="banner_mntb">
        <video src="background_RegRegister.mp4" id="backgroundVideo" autoplay muted loop></video>
        <div class="login-container">

<!--  ------------------------------------------------------------------------ -->            
            <div class="image-container">
                <div class="welcome-container">
                    <h1>Welcome to     
                        <span class="libmanage">libmanage!</span>
                        <style>
                            .libmanage {
                                font-family: 'Poppins', sans-serif;
                            }
                        </style>
                    </h1>
                    <h2 class="meaning">This is a Book borrowing Website</h2>
                    <style>
                        .meaning {
                            font-size: 30px;
                            font-weight: 900;
                                text-shadow: 0 0 1px #000, 0 0 1px #000;
                        }
                    </style>
                    <p>
                        This is a book borrowing website designed to facilitate the exchange of books among users.
                        It allows individuals to lend and borrow books from a shared collection within a community,
                        promoting a culture of reading and sustainability. Through this platform, users can catalog 
                        their own books, browse available titles, and connect with others to enjoy a diverse range of 
                        literature without the need for purchasing new copies.
                    </p>
                </div>
            </div>
<!--  ------------------------------------------------------------------------ -->


<!--  ------------------------------------------------------------------------ -->

<div class="login-form">
    <span class="close-button" onclick="redirectToMainpage()">
        <img src="close icon.png" alt="close">
    </span>
    <script>
        function redirectToMainpage() {
            window.location.href = "Mainpage.php";
        }
    </script>

    <h2 class="login-header">Hello, Bookworm!</h2>

    <!-- Alert messages -->
    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo "<label class='alert-label error'>" . $_SESSION['error_message'] . "</label>";
        unset($_SESSION['error_message']); // Clear message after displaying
    }
    if (isset($_SESSION['success_message'])) {
        echo "<label class='alert-label success'>" . $_SESSION['success_message'] . "</label>";
        unset($_SESSION['success_message']); // Clear message after displaying
    }
    ?>

    <form action="server.php" method="POST">
        <input type="hidden" name="action" value="login">

        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Username" required>

        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <div class="options">
            <label>
                <input type="checkbox" name="remember_me"> Remember me
            </label>
            <a href="ForgotPass.php">Forgot password?</a>
        </div>

        <button type="submit">LOGIN</button>
    </form>

    <p class="switch-auth">Don't have an account? <a href="RegRegister.php">Sign Up</a></p>
</div>

<!--  ------------------------------------------------------------------------ -->
        
        </div>
    </div>
</body>
</html>