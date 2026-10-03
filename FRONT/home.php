<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myTaleek</title>
    <link rel="stylesheet" href="../CSS/public.css">
    <link rel="stylesheet" href="../CSS/home.css">
    <link rel="stylesheet" href="../CSS/setting.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <style>
        body{
            backdrop-filter: blur(3px);
        }
    </style>
</head>

<body onload="goDown()">
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
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && $_SESSION['role_id'] !=0
    ) {
        //  if the admin try to delete teach acount from there we alert him
        if (isset($_GET['d'])) {
            echo "<script>alert('just orginal user can delete acount');</script>";
            unset($_GET['d']);
        }
        if (isset($_GET['c'])) {
            echo "<script>alert('just orginal user can contact');</script>";
            unset($_GET['c']);
        }
        // print_r($_SESSION);
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role_id'];
        $lang_id = 1;
        if ($role == 1) { // teacher or admin
            # allowed the admin to go back to admin dashbord
            if (isset($_SESSION['transition']) && $_SESSION['transition'] == 2) {
    ?>
               <a href="../BACK/back_as_admin.php" class="back_a" style="text-decoration: none;">Back</a>
        <?php
            }
            $lang_info = db_connect()->query("SELECT `id`,`name` from `language`where `teach_id`=$user_id;");
            if ($lang_info->num_rows > 0) {
                $row = $lang_info->fetch_assoc();
                $lang_id = $row['id'];
                $_SESSION['lang_id'] = $lang_id;
                $lang_name = $row['name'];
                $displays = [0, 0, 0, 0, 0, 0];
                $_SESSION['r_lev'] = 6;
                $max_unit = db_connect()->query("SELECT max(`id`) as id,`level`
                                               FROM `units`
                                               WHERE `lang_id`=$lang_id
                                               GROUP BY `level`;
                                               ");
                if (!$max_unit->num_rows) {
                    // die("max unit not found");
                    $max_unit = INF;
                    $max_less = INF;
                } else {
                    $max_unit = $max_unit->fetch_assoc()['id'];
                    $max_less = db_connect()->query("SELECT max(`id`) as id,`level` from `lessons`where `unit_id`=$max_unit;");
                    if (!$max_less->num_rows) {
                        // die("max less not found");
                        $max_less = INF;
                    } else {
                        $max_less = $max_less->fetch_assoc()['id'];
                    }
                }
                $_SESSION['less_id'] = $max_less;
                $_SESSION['r_unit'] = $max_unit;
            } else {
                header('location:select_lang.php');
                exit;
                // echo "<script>alert('no lang');</script>";
            }
        } elseif ($role == 2) {
            $query = "SELECT `lang_id`,max(`date`) ,`less_id`
                FROM `user_lang`
                WHERE `stu_id` = $user_id
                group by `date`;
                ";
            $res = db_connect()->query($query);
            if ($res->num_rows && $row = $res->fetch_assoc()) {
                $lang_id = $row['lang_id'];
                if (isset($row['less_id'])) {
                    $less_id = $row['less_id'];
                }
                if ($lang_id) {
                    $_SESSION['lang_id'] = $lang_id;
                    // $_SESSION['less_id'] = $less_id;
                    $lang_name = db_connect()->query("SELECT `NAME` FROM `LANGUAGE` WHERE `ID`=$lang_id;")->fetch_assoc()['NAME'];
                    $displays = [0, 0, 0, 0, 0, 0];
                }
            } else {
                header("location:select_lang.php");
                exit;
            }
        }
        ?>
        <div class="main">
            <!-- ⚙ : &#9881;-->
            <div class="settings-container">
                <!-- setting -->
                <!-- <i class="fa-solid fa-gear"></i> -->
                <span class="settings-icon setting"><i class="fa-solid fa-gear"></i></span>
                <div class="settings-panel">
                    <div class="panel-item"><a href="select_lang.php">Set Language</a></div>
                    <div class="panel-item"><a href="../BACK/delete_acount.php">Delete Account</a></div>
                    <div class="panel-item"><a href="about.php">About Us</a></div>
                    <div class="panel-item"><a href="contact_us.php?lang_id=<?= $lang_id ?>">Contact Us</a></div>
                </div>
            </div>
            <ul class="lists">
                <li>
                    <a href='units.php?lang_id=<?= $lang_id ?>&lev=6'>
                        <!-- < ?= $lang_name ?> -->
                        <div><img src="../sources/blue4.jpeg" alt="">C2</div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[5] ?>;"></i> C2</span>
                    </a>
                </li>
                <li><a href='units.php?lang_id=<?= $lang_id ?>&lev=5'>
                        <div><img src="../sources/c4.jpeg" alt=""></div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[4] ?>;"></i> C1</span>
                    </a></li>
                <li><a href='units.php?lang_id=<?= $lang_id ?>&lev=4'>
                        <div><img src="../sources/Circle.jpeg" alt=""></div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[3] ?>;"></i> B2</span>
                    </a></li>
                <li><a href='units.php?lang_id=<?= $lang_id ?>&lev=3'>
                        <div><img src="../sources/c2.jpeg" alt=""></div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[2] ?>;"></i> B1</span>
                    </a></li>
                <li><a href='units.php?lang_id=<?= $lang_id ?>&lev=2'>
                        <div><img src="../sources/blue3.jpeg" alt=""></div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[1] ?>;"></i> A2</span>
                    </a></li>
                <li id="A1"><a href='units.php?lang_id=<?= $lang_id ?>&lev=1'>
                        <div><img src="../sources/7.jpeg" alt=""></div>
                        <span><i class="fas fa-lock" style="opacity: <?= $displays[0] ?>;"></i> A1</span>
                    </a></li>
            </ul>
        </div>
        <div style="height: 50px;">

        </div>
    <?php
    } else { // no log in
        header('location:index.php');
    }
    ?>
    <script src="../JS/public.js"></script>
    <script src="../JS/home_audio.js"></script>
    <script src="../JS/setting.js"></script>
    <!-- <script>
        function disappear() {
            document.getElementById('about_container').style.display = 'none';
        }
        function display_aboutUs(){
            document.getElementById('about_container').style.display = 'block';
        }
    </script> -->
</body>

</html>