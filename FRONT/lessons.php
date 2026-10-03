<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/add_lesson.css">
    <link rel="stylesheet" href="../CSS/lessons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>Lessons</title>
    <style>
        .x {
            opacity: 1;
        }
    </style>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        // && isset($_SESSION['r_less'])
        && isset($_SESSION['lev'])
        && !empty($_SESSION['lev'])
        && isset($_GET['unit_id'])
    ) {
        $role = $_SESSION['role_id'];
        $lev = $_SESSION['lev'];
        $unit_id = $_GET['unit_id'];
        // $less_id = $_SESSION['less_id'];
        // if ($role == 2) {
        //     if (!isset($_SESSION['less_id'])) {
        //         header("location:units.php?lev=$lev");
        //         exit;
        //     }
        // }
        /**
         * if he trys to get unit > the reached unit in the reached level
         * other wise its already finished all previous units
         */
        // getting the max less_id in this unit
        // $max_less = db_connect()->query("SELECT max(`id`) as id from `lessons` where `unit_id`=$unit_id;");
        // if (!$max_less->num_rows) {
        //     header("location:units.php?lev=$lev");
        //     exit;
        // }
        // $max_less = $max_less->fetch_assoc()['id'];
        // if ($max_less < $less_id) {
        //     // header("location:units.php?lev=$lev");
        //     echo "$max_less < $less_id";
        //     exit;
        // }
        $_SESSION['unit_id'] = $_GET['unit_id'];
        $res = db_connect()->query("SELECT `title` from `units` where `id`=$unit_id;");
        if (!$res->num_rows) {
            header("location:units.php?lev=$lev");
            exit;
        }
        $res = $res->fetch_assoc();
        $unit_title = $res['title'];
        $lessons = db_connect()->query("SELECT `id` from `lessons` where `unit_id`=$unit_id and `level`=$lev;");
        # setting the opacity of elements
        $manag_opac = 'none';
        $scor_opac = 'block';// block
        if ($role != 2) {
            $manag_opac = 'inline';
            $scor_opac = 'none';
        }

    ?>
        <header>
            <a href="units.php?lev=<?= $lev ?>"><i class="fas fa-arrow-left"></i></a>
            <h3><?= $unit_title ?></h3>
            <!-- <div class="main_score"  >
                 style="display: < ?= $scor_opac ?>;"
                <div class="score_container">
                    <div class="score"></div>
                </div>
                <span>100%</span>
            </div> -->
        </header>
        <div id="marg"></div>
        <main>
            <?php
            if ($lessons->num_rows) {
                $nb_less = 1;
                while ($row = $lessons->fetch_assoc()) {
                    $less_id = $row['id'];
                    $less_info = db_connect()->query("SELECT distinct `title` ,`description`,`video`,`pdf` 
                                                FROM `lessons` 
                                                WHERE `id`=$less_id
                                                ;");
                    if ($less_info) {
                        extract($less_info->fetch_assoc());
                        // $_SESSION['less_title'] = $title;
                        // $_SESSION['video'] = $video;
                        // $_SESSION['pdf'] = $pdf;
                        $nb_unit = $_GET['unit_id'];
                        // fa-tv | fa-lock
                        // if (
                        //     $nb_unit < $_SESSION['r_unit']
                        //     && $_SESSION['lev'] < $_SESSION['r_lev']
                        // )
                        //     $logo = "fa-tv";
                        // else
                        //     $logo = $_SESSION['r_less'] <= $nb_less ? "fa-tv" : "fa-lock";
                        $logo = "fa-tv";
            ?>
                        <div class="container">
                            <div class="d_contai">
                                <div class="d1 d">
                                    <div class="d2 d">
                                        <div class="d3 d"><i class="fa-solid <?= $logo ?>"></i></div>
                                    </div>
                                </div>
                            </div>
                            <a href="../BACK/drop_less.php?less_id=<?= $less_id ?>" id="delete" style="display: <?= $manag_opac ?>;"><i class="fas fa-trash"></i></a>
                            <a href="../BACK/edit_less.php?less_id=<?= $less_id ?>" id="edit" style="display: <?= $manag_opac ?>;"><i class="fas fa-edit"></i></a>
                            <h4><?= $title ?></h4>
                            <p><?= $description ?></p>
                            <a href="lesson.php?less_id=<?= $less_id ?>" id="start">Let's start</a>
                        </div>
        <?php
                    } // if less info
                } // while
            }else {
                echo "<h2 style='color:#fff;'>no lessons in this unit</h2>";
            }
        } else {
            header("location:home.php");
            exit;
        }
        // echo "<script>alert('display : $manag_opac');</script>";
        ?>
        <i class='fa-solid fa-square-plus' id="add" style="display:<?=$manag_opac?>"></i>

        <div class="main" id="main">
            <i class="fas fa-times" id="x"></i>
            <form id="mainForm" action="../BACK/add_lesson.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="video">Video:</label>
                    <input type="file" id="video" name="video" accept="video/*" required>
                </div>
                <div class="form-group">
                    <label for="photo">photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="pdf">PDF:</label>
                    <input type="file" id="pdf" name="pdf" accept="application/pdf" required>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div id="keyValuePairsContainer"></div>
                <div class="form-group">
                    <button type="button" onclick="addKeyValuePair()">Add Key-Value Pair</button>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>

        </main>
        <script src="../JS/add_word.js"></script>
        <script src="../JS/units.js"></script>        
</body>

</html>