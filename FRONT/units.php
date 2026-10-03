<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/units.css">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>units</title>
</head>

<body>
    <header>
        <div class="first_row">
            <a href="home.php"><i class="fas fa-arrow-left"></i></a>
            <h2>Units</h2>
        </div>
        <p>it is a long established fact that a reader will be distracted by the readable </p>
    </header>
    <div id="marg"></div>
    <main>
        <?php
        require_once "../BACK/function.php";
        if (
            isset($_SESSION['user_id'])
            && isset($_SESSION['role_id'])
            // && isset($_SESSION['r_lev'])
            // && !empty($_SESSION['r_lev'])
            // && isset($_SESSION['r_unit'])
            // && !empty($_SESSION['r_unit'])
            && isset($_SESSION['lang_id'])
            && isset($_GET['lev']) && $_GET['lev'] > 0
            // && $_GET['lev'] <= $_SESSION['r_lev']
        ) {
            $_SESSION['lev'] = $_GET['lev'];
            $lang_id = $_SESSION['lang_id'];
            $level = $_GET['lev'];
            // can't get into unit 1 ???
            $add = 'none';
            if ($_SESSION['role_id'] != 2) {
                $_SESSION['r_less'] = INF;
                $add = 'inline';
            }
            $opacity = 0;
            $scor = $add == 'inline' ? 0 : 1;
            $units = db_connect()->query("SELECT `title`,`description`,`id` from `units` where `lang_id`=$lang_id and `level`=$level;");
            if ($units->num_rows) {
                $nb_unit = 1;
                while ($unit = $units->fetch_assoc()) {
                    // if ($level < $_SESSION['r_lev'])
                    //     $opacity = 0;
                    // else
                    //     $opacity = $_SESSION['r_unit'] <= $nb_unit ? 0 : 1;
                    $unit_title = $unit['title'];
                    $unit_id = $unit['id'];
                    $unit_desc = $unit['description'];
        ?>
                    <a href="lessons.php?unit_id=<?= $unit_id ?>">
                        <div class="container">
                        <div class="pho_con"><div class="photo"></div></div>
                            <div class="txt_score">
                                <div class="txt_head">
                                    <i class="fas fa-lock" style="opacity:<?= $opacity ?>"></i>
                                    <h4><?= $unit_title ?></h4>
                                    <a href="../BACK/edit.php?unit_id=<?= $unit_id ?>" style="display:<?= $add ?>">
                                        <i class="fas fa-edit" id="edit"></i>
                                    </a>
                                    <a href="../BACK/drop.php?unit_id=<?= $unit_id ?>" style="display:<?= $add ?>">
                                        <i class="fas fa-trash" id="drop" onclick="back(this)"></i>
                                    </a>
                                </div>
                                <p><?= $unit_desc ?></p>
                                <div class="main_score" style="opacity:<?= $scor ?>">
                                    <div class="score_container">
                                        <div class="score"></div>
                                    </div>
                                    <span>100%</span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php
                    $nb_unit++;
                } // while
            } else { ?>
                <div id="no_units">
                    <h2 style='color:#fff'>no units in this level :D</h2>
                </div>
        <?php
                // exit;
            }
        } else {
            header("location:home.php");
            exit;
            // print_r($_SESSION);
            // echo "<br><br>";
            // print_r($_GET);
        }
        ?>
    </main>
    <i class='fa-solid fa-square-plus' style="display: <?= $add ?>" id="add"></i>
    <div class="main" id="main">
        <form action="../BACK/add_unit.php" method="post">
            <div class="unit">
                <i class="fas fa-times" id="x"></i>
                <label for="title">unit title:</label>
                <input type="text" name="title" id="title">
                <label for="desc">description:</label>
                <input type="text" name="desc" id="desc" maxlength="255">
                <input type="submit" value="submit">
            </div>
        </form>
    </div>
    <script src="../JS/units.js"></script>
    <!-- <script>
            const form=document.getElementById('main');
            const add =document.getElementById('add');
            const x =document.getElementById('x');
            add.addEventListener('click',function(){
                form.style.top='0vh';
            })
            x.addEventListener('click',function(){
                form.style.top='100vh';
            })
        </script>  -->
</body>

</html>