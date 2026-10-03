<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <title>delete</title>
    <style>
        body {
            background-color: #08121e;
            height: 100vh;
        }

        .main {
            top: 0vh;
        }

        label {
            text-align: center;
            color: #eee;
        }

        .unit {
            background-color: #111111;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
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
</head>

<body>

    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && $_SESSION['role_id'] == 0
        && isset($_GET['lang_name']) && !empty($_GET['lang_name'])
        && isset($_GET['user_selection']) && !empty($_GET['user_selection'])
    ) {
        $name = $_GET['lang_name'];
        $teach_id = $_GET['user_selection'];
        if (isset($_POST['confirm']) && !empty($_POST['confirm'])) {
            if ($_POST['confirm'] == 'yes') {
                $con = db_connect();
                //  is unique
                $sql = "SELECT  `name` from`language` where `name` like '$name';";
                $res = $con->query($sql);
                if (!$res || $res->num_rows || !$name) {
                    echo "<script>alert('the name should be unique');</script>";
                    header("location:../FRONT/admin.php");
                    exit;
                }
                $sql = "INSERT INTO language (`id`,`name`,`teach_id`) values(null,'$name',$teach_id);";
                $res = $con->query($sql);
                if (!$res) {
                    die("<h1 style='color:#fff;width:100%;text-align:center;'>insert error</h1>");
                }
            }
            header("location:../FRONT/admin.php");
            exit;
        } else {
    ?>
            <form action="add_lang.php?lang_name=<?= $name ?>&user_selection=<?= $teach_id ?>" method="post">
                <div class="main">
                    <div class="unit">
                        <label for="title">ARE YOU SHURE YOU WANT TO NAME THE LANG BY [ <span style="color:#ca3d3d;"><?= $name ?></span> ] <br>.YOU'LL BE NOT EABLE TO EDIT IT</label>
                        <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                        <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                    </div>
                </div>
            </form>
    <?php
        }
    } else {
        print_r($_SESSION);
        echo "<br><hr>";
        print_r($_POST);
        // heade("location:index.php");
        // exit;
    }
    ?>
</body>

</html>