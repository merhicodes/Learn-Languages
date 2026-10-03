<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="public.css"> -->
    <!-- <link rel="stylesheet" href="editprofile.css"> -->
    <link rel="stylesheet" href="../CSS/sing_up.css">
    <title>edit profile</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
    ) {
        // if the admin try to logout a teach_acount we stop him
        if (isset($_SESSION['transition']) && $_SESSION['transition'] = 2) {
            header("location:../FRONT/home.php?d=1");
            exit;
        }
        $con = db_connect();
        $user_id = $_SESSION['user_id'];
        $role_id = $_SESSION['role_id'];
        if (isset($_POST['confirm_pass']) && !empty($_POST['confirm_pass'])) {
            $conf = $_POST['confirm_pass'];
            $sql = "SELECT `pass` FROM `users` where `id`=$user_id;";
            if ($db_pass = $con->query($sql)->fetch_assoc()['pass']) {
                if ($db_pass === $conf) {
                    // delete acount
                    if ($role_id == 1) { // teacher or sup_teach
                        if (!delete_teach($user_id, $why)) {
                            die("$why");
                        }
                    } elseif ($role_id == 2) { // student
                        if (!delete_student($user_id, $why)) {
                            die("$why");
                        }
                    } elseif ($role_id == 0) {
                        //  replace another email if exists
                    }
                } else {
                    unset($_POST['confirm_pass']);
                    header("location:delete_acount.php");
                    exit;
                }
            } else {
                // back
                die("fetch errro");
            }
        } else {
    ?>
            <div class="container">
                <div class="signup-form">
                    <h2>confirm password</h2>
                    <form action="delete_acount.php" method="POST">
                        <div class="input-group">
                            <input type="password" id="password" name="confirm_pass" required style="margin-bottom: 20px;">
                            <button type="submit">confirm</button>
                        </div>
                    </form>
                </div>
        <?php
        }
    }

        ?>
</body>

</html>