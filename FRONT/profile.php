<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/public.css">
    <link rel="stylesheet" href="../CSS/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>profile</title>
    <style>
        header {
            position: fixed;
            top: 0;
        }
    </style>
</head>

<body>
    <header>
        <ul class="head">
            <a href="home.php">
                <li><i class="fas fa-home"></i></li>
            </a>
            <a href="review.php">
                <li><i class="fas fa-book"></i></li>
            </a>
            <a href="chat.php">
                <li><i class="fa-solid fa-comment"></i></li>
            </a>
            <a href="profile.php">
                <li><i class="fa-solid fa-user"></i></li>
            </a>
        </ul>
    </header>
    <section>
        <?php
        require_once "../BACK/function.php";
        if (
            isset($_SESSION['user_id'])
            && isset($_SESSION['role_id'])
        ) {
            # allowed the admin to go back to admin dashbord
            if (isset($_SESSION['transition']) && $_SESSION['transition'] == 2) {
        ?>
                <a href="../BACK/back_as_admin.php" class="back_a" style="text-decoration: none;">Back</a>
            <?php
            }
            if (isset($_GET['l'])) {
                echo "<script>alert('just orginal user can log out');</script>";
                unset($_GET['l']);
            }
            if (isset($_GET['e'])) {
                echo "<script>alert('just orginal user can edit data');</script>";
                unset($_GET['e']);
            }
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT `name`,`last_name`,`email`,`phone` FROM `users` where `id`=$user_id;";
            $con = db_connect();
            $data = $con->query($sql);
            if (!$data || !$data->num_rows) {
                go_back();
            }
            if ($data = $data->fetch_assoc()) {
                $user_name = $data['name'] . " " . $data['last_name'];
                $email = $data['email'];
                $number = $data['phone'];

            ?>
                <div class="main">
                    <!-- <div class="headir">
                    <span class="icon"><ion-icon name="shield-checkmark" style="color: rgb(255, 0, 0);"></ion-icon></span>
                    <h2>User Name</h2>
                </div> -->
                    <div class="center">
                        <table>
                            <tr>
                                <td><span class="icon"><ion-icon name="shield-checkmark" style="color: rgb(255, 0, 0);"></ion-icon></span></td>
                                <td class="t2">
                                    <h2><?= $user_name ?></h2>
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td class="t2"><?= $email ?></td>
                            </tr>
                            <tr>
                                <td>verified</td>
                                <td class="t2"><input type="checkbox" name="verified" id="verifiy" class="custom-checkbox" checked disabled></td>
                            </tr>
                            <tr>
                                <td>mobile number</td>
                                <td class="t2"><?= $number ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="foot">
                        <button class="edit" onclick="window.location.href='editprofile.php?user_id=<?= $user_id ?>'">Edit profile</button>
                        <button class="logout" onclick="window.location.href='../BACK/logout.php?user_id=<?= $user_id ?>'">Logout</button>
                    </div>
                </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="public.js"></script>
    <!-- <H1>I AM PROFILE PAGE</H1> -->
<?php
            }
        } else {
            print_r($_SESSION);
        }
?>
</body>

</html>