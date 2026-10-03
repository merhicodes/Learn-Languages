<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="../CSS/units.css"> -->
    <style>
        body {
            background: linear-gradient(145deg, rgba(10, 182, 255), rgb(7, 1, 33));
            height: 100vh;
        }

        .main {
            top: 0vh;
        }
    </style>
    <title>edit</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['lang_id'])
        && isset($_SESSION['lev'])
        && !empty($_SESSION['lev'])
        && isset($_GET['unit_id'])
        && !empty($_GET['unit_id'])
    ) {
        $unit_id = $_GET['unit_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $lang_id = $_SESSION['lang_id'];
            $sql = "SELECT `title`,`description` from `units` where `id`=$unit_id and `lang_id`=$lang_id;";
            $res = db_connect()->query($sql);
            if (!$res->num_rows) die("unexpected error");
            $res = $res->fetch_assoc();
            $title = $res['title'];
            $desc = $res['description'];
            $lev = $_SESSION['lev'];
    ?>
            <div class="main">
                <form action="../BACK/edit.php?unit_id=<?= $unit_id ?>" method="post">
                    <div class="unit">
                        <a href="../FRONT/units.php?lev=<?=$lev?>"><i class="fas fa-times"></i></a>
                        <label for="title">unit title:</label>
                        <input type="text" name="title" id="title" value="<?= $title ?>">
                        <label for="desc">description:</label>
                        <input type="text" name="desc" id="desc" maxlength="255" value="<?= $desc ?>">
                        <input type="submit" value="submit">
                    </div>
                </form>
            </div>
    <?php
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (
                isset($_POST['title']) && !empty($_POST['title'])
                && isset($_POST['desc']) && !empty($_POST['desc'])
            ) {
                $title = $_POST['title'];
                $desc = $_POST['desc'];
                $sql = "UPDATE `units`
                      SET `title` = '$title', `description` = '$desc'
                      WHERE `id`=$unit_id;
                      ";
                db_connect()->query($sql);
                $lev = $_SESSION['lev'];
                header("location:../FRONT/units.php?lev=$lev");
            } else {
                header("location:../FRONT/units.php?lev=$lev");
                exit;
            }
        }
    } else {
        echo "lev:" . $_SESSION['lev'];
        // header('location:../FRONT/index.php');
    }
    ?>
</body>

</html>