<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/add_lesson.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <style>
        .main {
            top: 0vh;
            background-color: #056ce2;
        }

        form {
            width: 80%;
            height: 500px;
            min-height: 50vh;
            max-width: 500px;
            margin-bottom: 30px;
            border: 1px solid;
        }
    </style>
    <title>edit less</title>
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
        $con = db_connect();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($less_data = $con->query("SELECT `video` ,`description`,`title` from `lessons` where `id`=$less_id and `level`=$level and `unit_id`=$unit_id;")) {
                if (!$less_data->num_rows) {
                    die("lesson not found !");
                } else {
                    $less_data = $less_data->fetch_assoc();
                    $video = $less_data['video'];
                    $description = $less_data['description'];
                    // when we get the video path it include the title 
                    $title =  $less_data['title'];
                }
            } else {
                die($con->error);
            }

    ?>
            <div class="main">
                <a href="../FRONT/lessons.php?unit_id=<?= $unit_id ?>"><i class="fas fa-times"></i></a>
                <form id="mainForm" action="edit_less.php?less_id=<?= $less_id ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="video">Video:</label>
                        <input type="file" id="video" name="video" accept="video/*">
                    </div>
                    <div class="form-group">
                        <label for="photo">photo:</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="pdf">PDF:</label>
                        <input type="file" id="pdf" name="pdf" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?= $title ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"><?= $description ?></textarea>
                    </div>
                    <div id="keyValuePairsContainer">
                        <?php
                        $words = $con->query("SELECT  `key`,`value`,`id`as 'word_id' from `words` where `less_id`=$less_id;");
                        if (!$words) {
                            die($con->error);
                        }
                        if ($words->num_rows) {
                            while ($row = $words->fetch_assoc()) {
                                extract($row);
                        ?>
                                <div class="key-value-pair">
                                    <input type="text" name="db_key[]" required value="<?= $key ?>">
                                    <input type="text" name="db_value[]" required value="<?= $value ?>">
                                    <a href="drop_word.php?word_id=<?= $word_id ?>&less_id=<?= $less_id ?>"><i class="fas fa-trash"></i></a>
                                </div>
                        <?php
                            } // end while
                        } // else no row
                        ?>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="addKeyValuePair()">Add Key-Value Pair</button>
                    </div>
                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <script src="../JS/add_word.js"></script>
    <?php
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $less_i_info = $con->query("SELECT `video` , `pdf`, `title`,`description` from `lessons` where `id`=$less_id and `level`=$level and `unit_id`=$unit_id;");
            if (!$less_i_info) {
                die($con->error);
            }
            if ($less_i_info->num_rows) {
                if ($less_i_info = $less_i_info->fetch_assoc()) {
                    // extract($less_i_info);
                    $old_title = $less_i_info['title'];
                    $old_description = $less_i_info['description'];
                    $old_pdf = $less_i_info['pdf'];
                    $old_video = $less_i_info['video'];
                    if (
                        isset($_POST['title']) && !empty($_POST['title'])
                        && $_POST['title'] !== $old_title
                    ) {
                        // rename title and paths
                        $new_title = valid_input($_POST['title']);
                        // to remove the file name
                        $old_video = preg_replace('/\/[^\/]+$/', '', $old_video);
                        $old_pdf = preg_replace('/\/[^\/]+$/', '', $old_pdf);
                        $new_vid_path = str_replace($old_title, $new_title, $old_video);
                        $new_pdf_path = str_replace($old_title, $new_title, $old_pdf);
                        if (is_dir($old_pdf)) {
                            if (!rename($old_pdf, $new_pdf_path)) {
                                die("pdf rename error!");
                            }
                        } else {
                            die("\"$old_pdf\" isn't a dir pdf");
                        }
                        $old_title = $new_title;
                        // adding the file name before insertting in db
                        $old_pdf = $new_pdf_path . "/less_p.pdf";
                        $old_video = $new_vid_path . "/less_v.mp4";
                    }
                    if (
                        isset($_POST['description']) && !empty($_POST['description'])
                        && $_POST['description'] !== $old_description
                    ) {
                        $old_description = valid_input($_POST['description']);
                    }
                    // update db
                    $update = $con->query("UPDATE `lessons` set `video`='$old_video' , `pdf`='$old_pdf', `title`='$old_title',`description`='$old_description' where `id`=$less_id and `level`=$level and `unit_id`=$unit_id;");
                    # updatting pdf- video
                    if (isset($_FILES['video']) && !empty($_FILES['video']['tmp_name']) && !$_FILES['video']['error']) {
                        $video = $_FILES['video'];
                        if (!copy($video['tmp_name'], $old_video)) {
                            die("copy erro");
                        }
                    }
                    if (isset($_FILES['pdf']) && !empty($_FILES['pdf']['tmp_name']) && !$_FILES['pdf']['error']) {
                        $pdf = $_FILES['pdf'];
                        if (!copy($pdf['tmp_name'], $old_pdf)) {
                            die("copy erro");
                        }
                    }
                    # update db words
                    if (
                        isset($_POST['db_key']) && !empty($_POST['db_key'])
                        && isset($_POST['db_value']) && !empty($_POST['db_value'])
                    ) {
                        $db_key_form = $_POST['db_key'];
                        $db_value_form = $_POST['db_value'];
                        $words = $con->query("SELECT `key`,`value`,`id` from `words` where `less_id` = $less_id;");
                        for ($i = 0; $i < count($db_key_form); $i++) {
                            // we update key or value if they are both not empty
                            $k = $db_key_form[$i];
                            $v = $db_value_form[$i];
                            if (
                                $k && $v
                                && $word_i = $words->fetch_assoc()
                            ) {
                                if ( // we update them when one of them has updated
                                    $db_key_form[$i] != $word_i['key']
                                    || $db_value_form[$i] != $word_i['value']
                                ) {
                                    $w_id = $word_i['id'];
                                    if (!$con->query("UPDATE `words` set `key`='$k', `value`='$v' where `less_id`=$less_id and `id`=$w_id;")) {
                                        die($con->error);
                                    }
                                }
                            }
                        }
                    }else{
                        echo "no udate";
                    }
                    # add new words
                    if (
                        isset($_POST['key']) && !empty($_POST['key'])
                        && isset($_POST['value']) && !empty($_POST['value'])
                    ) {
                        $x=$_POST['key'];$y=$_POST['value'];
                        for($i=0;$i<count($x);$i++){                                
                            if(!empty($x[$i] )&& !empty($y[$i])){// they arn't null
                                $s1=$x[$i];$s2=$y[$i];
                                $sql="INSERT INTO `words` (`id`,`key`,`value`,`less_id`) values(null,'$s1','$s2',$less_id);";
                                if(!$con->query($sql)){
                                    die($con->error);
                                }
                            }else{
                                print_r($new_words);//echo "<br>";print_r($v);
                            }
                        }
                    }
                    header("location:../FRONT/lessons.php?unit_id=$unit_id");
                } else {
                    die("fetch error");
                }
            } else {
                die("no data found");
            }
        } else {
            die("can't accesse this page :D post");
        }
    } else {
        print_r($_SESSION);
        die("can't accesse this page :D 0");
    }
    ?>
</body>

</html>