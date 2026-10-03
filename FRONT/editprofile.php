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
            header("location:../FRONT/profile.php?e=1");
            exit;
        }
        $con = db_connect();
        $user_id = $_SESSION['user_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['required'])) {
                echo "<script>alert('all inputs are required');</script>";
            }
            if (isset($_GET['confirm_pass']) && !empty($_GET['confirm_pass'])) {
                $conf = $_GET['confirm_pass'];
                $sql = "SELECT `pass` FROM `users` where `id`=$user_id;";
                if ($db_pass = $con->query($sql)->fetch_assoc()['pass']) {
                    if ($db_pass === $conf) {
                        unset($_GET['confirm_pass']);
                        $_GET['confirmed'] = 1;
                    }
                } else {
                    die("<pass> error ");
                }
            }
            // print_r($_GET);
            if (!isset($_GET['confirmed'])) {
                // echo "<script>alert('not confiremed!');</script>";

    ?>
                <div class="container">
                    <div class="signup-form">
                        <h2>confirm password</h2>
                        <form action="editprofile.php" method="GET">
                            <div class="input-group">
                                <input type="password" id="password" name="confirm_pass" required style="margin-bottom: 20px;">
                                <button type="submit">confirm</button>
                            </div>
                        </form>
                    </div>
                <?php
                exit;
            }
            $sql = "SELECT `name`,`last_name`,`email`,`phone` FROM `users` where `id`=$user_id;";
            $data = $con->query($sql);
            if (!$data || !$data->num_rows) {
                go_back();
            }
            if ($data = $data->fetch_assoc()) {
                $user_name = $data['name'];
                $last_name = $data['last_name'];
                $email = $data['email'];
                $number = $data['phone'];

                ?>
                    <div class="container">
                        <div class="signup-form">
                            <h2>Update</h2>
                            <form action="editprofile.php" method="POST">
                                <div class="input-group">
                                    <label for="name">First Name:</label>
                                    <input type="text" id="name" name="name" required value="<?= $user_name ?>">
                                </div>
                                <div class="input-group">
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" id="lastname" name="lastname" required value="<?= $last_name ?>">
                                </div>
                                <div class="input-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" readonly value="<?= $email ?>">
                                </div>
                                <div class="input-group">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" required>
                                </div>
                                <div class="input-group">
                                    <label for="phoneNumber">Phone Number:</label>
                                    <input type="tel" id="phoneNumber" name="phoneNumber" readonly value="<?= $number ?>">
                                </div>
                                <button type="submit">Sign Up</button>
                            </form>
                        </div>
                    </div>
        <?php
            } // not fetch
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (
                isset($_POST['name'])
                && isset($_POST['lastname'])
                && isset($_POST['password'])
            ) {
                foreach ($_POST as $p) {
                    $p = valid_input($p);
                }
                extract($_POST);
                $sql = "UPDATE `users`
                      SET `name` = '$name', `last_name` = '$lastname', `pass` = '$password'
                      WHERE `id` = $user_id;
                    ";
                $res = $con->query($sql);
                if (!$res) {
                    die("update error!");
                }
                $sql = "SELECT `teach_id` from `teacher` where `admin_id` is null and `teach_id`=$user_id;";
                $res = $con->query($sql);
                if (!$res) {
                    die("admin error");
                }
                $is_admin = $res->num_rows;
                if ($is_admin) {
                    header("location:../FRONT/admin.php");
                    exit;
                }
                header("location:../FRONT/profile.php");
                exit;
            } else {
                unset($_POST);
                header("location:editprofile.php?required=1");
                exit;
            }
        }
    }
        ?>
</body>

</html>