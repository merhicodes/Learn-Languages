<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/units.css">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <style>
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
    </style>
    <title>drop</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['lang_id'])
        && isset($_SESSION['lev'])
        && isset($_GET['unit_id'])
        && !empty($_GET['unit_id'])
    ) {
        $unit_id = $_GET['unit_id'];
        $level = $_SESSION['lev'];
        $lang_id = $_SESSION['lang_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $sql = "SELECT `title` from `units` where `id`=$unit_id and `lang_id`=$lang_id;";
            $res = db_connect()->query($sql);
            if (!$res->num_rows) die("unexpected error");
            $res = $res->fetch_assoc();
            $title = $res['title'];
    ?>
            <form action="../BACK/drop.php?unit_id=<?= $unit_id ?>" method="post">
                <div class="main">
                    <div class="unit">
                        <label for="title">ARE YOU SHURE YOU WANT TO DROP THE UNIT <span style="color: red;"><?= $title ?><span></label>
                        <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                        <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                    </div>
                </div>
            </form>
    <?php
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['confirm']) && !empty($_POST['confirm'])) {
                $ans = $_POST['confirm'];
                $lev = $_SESSION['lev'];
                if ($ans == 'yes') {                    
                    // $sql = "DELETE FROM `units` WHERE `id`=$unit_id;";
                    $why="i don't know :)";
                    if (!delete_unit($unit_id, $level,$lang_id,$why)) {
                        die("reason:[ $why ]");
                    }
                }
                header("location:../FRONT/units.php?lev=$lev");
                exit;
            } else {
                die("can't reach this page :(");
            }
        }
    }
    ?>
</body>

</html>