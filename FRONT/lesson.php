<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/lesson.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>lessons</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['lev'])
        // && isset($_SESSION['r_less'])
        // && isset($_SESSION['r_lev'])
        // && isset($_SESSION['r_unit'])
        && isset($_SESSION['unit_id'])
        && isset($_GET['less_id'])
        && !empty($_GET['less_id'])
    ) {
        $unit_id = $_SESSION['unit_id'];
        $less_id = $_GET['less_id'];
        ## from here vocab , quiz , cong will use the same less_id so we put it in the session
        $_SESSION['less_id']=$less_id;
        $level = $_SESSION['lev'];
        // if (
        //     $_SESSION['lev'] == $_SESSION['r_lev']
        //     && $unit_id == $_SESSION['r_unit']
        //     && $_SESSION['r_less'] < $_GET['less_id']
        // ) {
        //     header("locaion:lessons.php?unit_id=$unit_id");
        // }
        $con = db_connect();
        $less_data = $con->query("SELECT `video`,`pdf`,`title` from `lessons` where `id`=$less_id and `unit_id`=$unit_id and `level`=$level;");
        if (!$less_data) {
            die($con->error);
        }
        $lesson_title="`id`=$less_id and `unit_id`=$unit_id and `level`=$level;";
        if ($res = $less_data->fetch_assoc()) {
            $lesson_title = $res['title'];
            $video = $res['video'];
            $src = $res['pdf'];
        }
        $next = "<a href='vocabulary.php' id='finish'>Next</a>";
        if ($_SESSION['role_id'] != 2) {
            $next = "<a href='lessons.php?unit_id=$unit_id' id='finish'>Finish</a>";
        }
    ?>
        <header>
            <a href="lessons.php?unit_id=<?= $unit_id ?>"><i class="fas fa-arrow-left"></i></a>
            <!-- <h4>Video lesson</h4> -->
        </header>
        <main>
            <div class="container">
                <div class="title">
                    <h4><?= $lesson_title ?></h4>
                </div>
                <video controls>
                    <source src="<?= $video ?>">
                    can't access this video
                </video>
                <?php
                if (isset($_GET['pdf']) && !empty($_GET['pdf'])) {
                ?>
                    <div class="embed_con">
                        <h3><a href="lesson.php?pdf=0&less_id=<?= $less_id ?>">X</a></h3>
                        <embed src="<?= $src ?>" type="application/pdf" width="100%" height="900px" style="postison:absolute;z-index: 10;" />
                    </div>
                <?php
                } else {
                    echo "<a href='lesson.php?pdf=1&less_id=$less_id'>lesson pdf</a>";
                }
                echo $next;
                ?>
            </div>
        </main>
</body>

</html>
<?php
    } else {
        header('location:units.php');
    }
