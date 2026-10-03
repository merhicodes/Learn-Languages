<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Selection</title>
    <link rel="stylesheet" href="../CSS/select_lang.css">
</head>

<body>
    <div class="center-container">
        <form class="language-form" action="select_lang.php" method="POST">
            <h2>Select Language</h2>
            <?php
            require_once "../BACK/function.php";
            if (
                isset($_SESSION['user_id'])
                && isset($_SESSION['role_id'])
            ) {
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['role_id'];
                $con = db_connect();
                if (isset($_POST['language']) && !empty($_POST['language'])) {
                    // update selected lang
                    $lang = $_POST['language'];
                    if ($role == 2) {
                        #update user_lang
                        $sql = "UPDATE `user_lang` set `lang_id`=$lang where `stu_id`=$user_id;";
                        $res = $con->query($sql);
                        if (!$res) {
                            die("update error!");
                        }
                        // echo "updated rows:$res";
                        // exit;
                        header("location:home.php");
                        exit;
                    } elseif ($role == 1) {
                        # update when we add teach_lang table
                    }
                    header("location:home.php");
                    exit;
                }
                if ($role == 2) {
                    #display all languages
                    $sql = "SELECT `name`,`id` from `language`";
                    $res = $con->query($sql);
                    if (!$res) {
                        die("sql error: $con->error");
                    }
                } elseif ($role == 1) {
                    # display its languages
                    $sql = "SELECT `name`,`id` from `language` where `teach_id`=$user_id;";
                    $res = $con->query($sql);
                    if (!$res) {
                        die("sql error: $con->error");
                    }
                    if ($res->num_rows) {
                        # you have no lang yet 
            ?>
                        <div class="radio-group">
                            <label class="radio-label">
                                <span class="custom-radio"></span> No languages !
                            </label>
                        </div>
                    <?php
                    }
                }
                # getting current lang_id
                $checked="";
                $current_id=-1;
                $sql = "SELECT `lang_id` from `user_lang` where `stu_id`=$user_id;";
                $current_lang = $con->query($sql);
                if (!$current_lang) {
                    die("sql error: $con->error");
                }
                if($current_lang=$current_lang->fetch_assoc()){
                    $current_id=$current_lang['lang_id'];
                }else{
                    die("fetch error");
                }
                while ($langs = $res->fetch_assoc()) {
                    $lang_id = $langs['id'];
                    if($current_id==$lang_id){
                        $checked="checked";
                    }
                    $lang_name = $langs['name'];
                    ?>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="language" value="<?= $lang_id ?>" <?=$checked?>>
                            <span class="custom-radio"></span> <?= $lang_name ?>
                        </label>
                    </div>
                <?php
                } // end while
                ?>
                <button type="submit">Submit</button>
        </form>
    </div>
<?php
            } //not isset
?>
</body>

</html>

<!-- <select name="" id="">
    <option value="lanuage">language</option>
</select> -->