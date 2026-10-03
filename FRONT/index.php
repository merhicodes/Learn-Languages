<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylepro.css" />
    <title>log in</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
    ) {
        if($_SESSION['role_id'] == 0){
            // is the admin trying to get home then he is redirected
            header("location:admin.php");
            exit;
        }
    }

    ?>
    <div class="login-box">
        <h2>log in</h2>
        <form action="../BACK/login.php" method="post">
            <div class="marg"></div>
            <div class="input-box">
                <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                <input type="email" name="email" required />
                <!-- required?? -->
                <label>Email</label>
            </div>
            <div class="input-box">
                <span class="icon"><ion-icon name="bag-outline"></ion-icon></span>
                <input type="password" name="pass" required />
                <label>Password</label>
            </div>
            <input type="submit" value="login">
            <div class="forget-pass">
                <a href="#">forget password ?</a>
            </div>
            <div class="register-link">
                <p> Don't have an account <a href="create_user.php">Register</a></p>
            </div>
        </form>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>