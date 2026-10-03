<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/vocabulary.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>vocabulary</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['less_id'])
        && !empty($_SESSION['less_id'])
        && isset($_SESSION['lev'])
        // && isset($_SESSION['r_less'])
        // && isset($_SESSION['r_lev'])
        // && isset($_SESSION['r_unit'])
        && isset($_SESSION['unit_id'])
    ) {
        $nb_unit = $_SESSION['unit_id'];

        // if (
        //     $_SESSION['lev'] = $_SESSION['r_lev']
        //     && $nb_unit = $_SESSION['r_unit']
        //     && $_SESSION['r_less'] < $nb_less
        // ) {
        //     header("locaion:lesson.php?nb_less=$nb_less");
        // }
        $less_id = $_SESSION['less_id'];
    ?>
        <header>
            <div class="first_row">
                <a href="lesson.php?less_id=<?=$less_id?>"><i class="fas fa-arrow-left"></i></a>
                <h3>Vocabulary lesson</h3>
                <i class="fa-solid fa-square-plus"></i>
            </div>
            <p>it is a long established fact that a reader will be distracted by the readable </p>
        </header>
        <div id="marg"></div>
        <main>
            <div class="sup_cont">
                <div class="cont">
                    <form action="../BACK/add_to_folder.php" method="POST">
                        <!-- <div class="selected"><i class="fas fa-check-circle"></i></div> -->
                        <?php
                        $res = db_connect()->query("SELECT `id` as word_id,`key`,`value`from `words`where `less_id`=$less_id;");
                        if (!$res->num_rows) {
                            header('location:congratulation.php?win=1');
                            exit;
                        } else {
                            while ($row = $res->fetch_assoc()) {
                                extract($row);
                        ?>
                                <input type="checkbox" name="sel_words[]" value="<?= $word_id ?>" id="word_<?= $word_id ?>" class="x">
                                <label for="word_<?= $word_id ?>" class="word">
                                    <div class="inner_word"><span><?= $key ?></span><span><?= $value ?></span></div>
                                </label>
                            <?php
                            }
                            ?>
                </div>
                <a href="Quiz.php">Next</a>
            </div>
            <div class="main">
                <div class="on_adding">
                    <div class="head">
                        <i class="fas fa-times"></i>
                    </div>
                    <label for="folders">Select Folder</label>
                    <select name="folders[]" id="folder_input">
                        <?php
                            $user_id = $_SESSION['user_id'];
                            $folders = db_connect()->query("SELECT `id`,`name` from `folder`where `stu_id`=$user_id;");
                            if (!$folders->num_rows) {
                                echo "<option value='-1'>NO FOLDERS!</option>";
                            } else {
                                while ($row = $folders->fetch_assoc()) {
                                    extract($row);
                                    echo "<option value='$id'>$name</option>";
                                }
                            }
                        ?>
                    </select>
                    <input type="submit" value="Add to Deck" />
                    </form>
                </div>
            </div>
            <script src="../JS/vocabulary.js"></script>
        </main>
<?php
                        }
                    } else { // !log in
                        print_r($_SESSION);
                        // header("location:lesson.php?less_id=$less_id");
                    }
?>
<script src="../JS/vocabulary.js"></script>
</body>

</html>