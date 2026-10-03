<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/units.css">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <link rel="stylesheet" href="../CSS/drop.css">
    <!-- <style>
        body {
            background: linear-gradient(145deg, rgba(10, 182, 255), rgb(7, 1, 33));
            height: 100vh;
        }

        .main {
            top: 0vh;
        }

        label {
            text-align: center;
        }

        button[type="submit"] {
            width: 70px;
            height: 30px;
            font-size: large;
            font-weight: 700;
        }

        button[type="submit"]:hover {
            color: #eee;
        }
    </style> -->
    <title>drop less</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['lang_id'])
        && isset($_SESSION['lev'])
        && isset($_SESSION['unit_id'])
        && isset($_GET['less_id'])
        && !empty($_GET['less_id'])
    ) {
        $less_id = $_GET['less_id'];
        $lang_id = $_SESSION['lang_id'];
        $unit_id = $_SESSION['unit_id'];
        $level = $_SESSION['lev'];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $lang_id = $_SESSION['lang_id'];
            $sql = "SELECT `title` from `lessons` where `id`=$less_id and `unit_id`=$unit_id and `level`=$level;";
            $res = db_connect()->query($sql);
            if (!$res->num_rows) die("unexpected error");
            $res = $res->fetch_assoc();
            $title = $res['title'];
    ?>
            <form action="../BACK/drop_less.php?less_id=<?= $less_id ?>" method="POST">
                <div class="main">
                    <div class="unit">
                        <label for="title">ARE YOU SHURE YOU WANT TO DROP THE LESSON <span style="color: red;"><?= $title ?><span></label>
                        <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                        <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                    </div>
                </div>
            </form>
    <?php
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['confirm']) && !empty($_POST['confirm'])) {
                $unit_id = $_SESSION['unit_id'];
                $ans = $_POST['confirm'];
                $lev = $_SESSION['lev'];
                if ($ans == 'yes') {
                    # deletting its words
                    // $sql = "DELETE FROM `words` WHERE `less_id`=$less_id;";
                    // if (!db_connect()->query($sql)) {
                    //     // header("location:../FRONT/lessons.php?unit_id=$unit_id");
                    //     // exit;
                    //     die("can't delete words");
                    // }
                    # deletting its video & pdf
                    ## getting the lang_name;
                    # deletting it
                    $why="i don't know :)";
                    if (!delete_less($less_id, $unit_id, $level,$lang_id,$why)) {
                        die("reason:[ $why ]");
                    }
                    // $sql = "DELETE FROM `LESSONS` WHERE `id`=$less_id;";
                    // db_connect()->query($sql);
                }
                header("location:../FRONT/lessons.php?unit_id=$unit_id");
                exit;
            }
        }
    } else {
        print_r($_SESSION);
    }
    ?>
</body>

</html>