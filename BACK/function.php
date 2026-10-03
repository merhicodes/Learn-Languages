<?php
session_start();
function db_connect()
{
    $con = new mysqli("localhost", 'root', '', 'taleek');
    if ($con->connect_error) {
        die("error: " . $con->connect_error);
    }
    return $con;
}
function valid_input($p)
{
    return htmlspecialchars(stripslashes(trim($p)));
}
function valid_array($arr)
{
    $arr = array_values(array_filter($arr, function ($v) {
        return !empty($v);
    }));
    return $arr;
}
function delete_word($word_id, $less_id, &$why)
{
    $con = db_connect();
    // deletting them from folders containning them
    $sql = "DELETE FROM `folder_word` where `word_id`=$word_id;";
    $res1 = $con->query($sql);
    if (!$res1) {
        $why = "delete deppending fold_words error";
        return false;
    }
    // deletting them;
    $sql = "DELETE FROM `words` where `id`=$word_id and `less_id`=$less_id;";
    $res2 = $con->query($sql);
    if (!$res2) {
        $why = "delete words error";
        return false;
    }
    return true;
}
function delete_less($less_id, $unit_id, $level, $lang_id, &$why): bool
{
    $con = db_connect();
    $sql = "SELECT `name` from `language` where `id`=$lang_id;";
    if ($res = db_connect()->query($sql)) {
        $lang_name = $res->fetch_assoc()['name'];
        $less_title = db_connect()->query("SELECT `title` from `lessons` where `id`=$less_id;");
        if (!$less_title->num_rows) {
            $why = "no title for this lesson !";
            return false;
        }
        $less_title = $less_title->fetch_assoc()['title'];
        $dir = "../lessons/$lang_name/$less_title-$unit_id-$level";
        if (is_dir($dir)) {
            $pdf = $dir . "/less_p.pdf";
            $vid = $dir . "/less_v.mp4";
            if (is_file($pdf) && is_file($vid)) { //!empty(glob($vid)): Checks if the glob function found any matching files.
                unlink($pdf);
                unlink($vid);
            } else {
                $why = "can't delete deppending files";
                return false;
            }
            if (!rmdir($dir)) {
                $why = "can't remove $dir";
                return false;
            }
        } else {
            $why = "$dir is not a dir";
            return false;
        }
    }
    // getting word id to delete them from folders and words
    $sql = "SELECT `id` from `words` where `less_id` = $less_id;";
    $res = $con->query($sql);
    if (!$res) {
        $why = "select id_word error";
        return false;
    }
    while ($row = $res->fetch_assoc()) {
        $word_id = $row['id'];
        if (!delete_word($word_id, $less_id, $why)) {
            return false;
        }
    }
    $sql = "DELETE FROM `lessons` where `id`=$less_id and `unit_id`=$unit_id and `level`=$level;";
    $res = $con->query($sql);
    if (!$res) {
        $why = "delete less error";
        return false;
    }
    return true;
}
function delete_unit($unit_id, $level, $lang_id, &$why): bool
{
    $con = db_connect();
    $sql = "SELECT `id` from `lessons` where `unit_id`=$unit_id and `level`=$level;";
    $lessons = $con->query($sql);
    if (!$lessons) {
        $why = "selecting deppending less error even empty :{\n$con->error\n}";
        return false;
    }
    while ($lesson = $lessons->fetch_assoc()) {
        if (!delete_less($lesson['id'], $unit_id, $level, $lang_id, $why)) {
            return false; // achtung !
        }
    }
    $sql = "DELETE FROM `units` where `id`=$unit_id and `level`=$level;";
    if (!$con->query($sql)) {
        $why = "delete unit error:{\n$con->error\n}";
        return false;
    }
    return true;
}
function is_supper_teacher($user_id)
{
    $con = db_connect();
    $sql = "SELECT `teach_id` from `teacher` where `admin_id`=$user_id;";
    $res = $con->query($sql);
    if ($res && $res->num_rows) {
        return true;
    }
    // echo $res->fetch_assoc()['teach_id'];
    return false;
}
function delete_lang($lang_id, &$why): bool
{
    $con = db_connect();
    // delete words
    for ($i = 1; $i <= 6; $i++) {
        $sql = "SELECT `id` from `units` where `level`=$i and `lang_id`=$lang_id;";
        $res1 = $con->query($sql);
        if (!$res1) {
            $why="select ids error";
            return false;
        }
        while ($units = $res1->fetch_assoc()) {
            $unit_id = $units['id'];
            if (!delete_unit($unit_id, $i, $lang_id, $why)) {
                return false;
            }
        }
    }
    # update user_lang
    $sql = "DELETE FROM `user_lang` where `lang_id` = $lang_id;";
    $res2 = $con->query($sql);
    if (!$res2) {
        $why = "update stu error: $con->error";
        return false;
    }
    $sql = "DELETE FROM `language` where `id` = $lang_id;";
    $res2 = $con->query($sql);
    if (!$res2) {
        $why = "lang_error: $con->error";
        return false;
    }
    return true;
}

function delete_student($user_id, &$why): bool
{
    // $con=db_connect();
    // forlders

    // form user_lang

    // from student

    // form users
    if(1){
        $why="<h1>no time</h1>";
        return false;
    }
    return true;
}
function delete_teach($user_id, &$why): bool
{
    // $con=db_connect();

    // set lang to null

    // set its sub_teach.admin_id to owner.id
    
    // from teacher

    // form users
    if(1){
        $why="<h1>no time</h1>";
        return false;
    }
    return true;
}
