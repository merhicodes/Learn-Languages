<?php
require_once "../BACK/function.php";
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
    && isset($_SESSION['lang_id'])
    && isset($_SESSION['lev'])
    && isset($_POST['title'])
    && !empty($_POST['title'])
    && isset($_POST['desc'])
    && !empty($_POST['desc'])
) {
    $level = $_SESSION['lev'];
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $lang_id = $_SESSION['lang_id'];
    $state = $_GET['state'];
    $sql = "SELECT `ID`FROM `UNITS`WHERE `title`like '$title' and `description`like '$desc' and `lang_id`=$lang_id;";
    $con = db_connect();
    /**
     * msg:
     * 1:exist
     * 2:can't insert
     * 3:success
     */
    if ($con->query($sql)->num_rows > 0) {
        header("location:../FRONT/units.php?lev=$level&msg=1");
        exit;
    } else {
        $sql = "INSERT INTO `units`(`id`,`level`,`title`,`description`,`lang_id`) values('',$level,'$title','$desc',$lang_id);";
        if (!$con->query($sql)) {
            header("location:../FRONT/units.php?lev=$level&msg=2");
            exit;
        } else {
            header("location:../FRONT/units.php?lev=$level&msg=3");
            exit;
        }
    }
} else {
    if (isset($_SESSION['lev']) && !empty($_SESSION['lev'])) {
        $lev = $_SESSION['lev'];
        header("location:../FRONT/units.php?lev=$lev");
        exit;
    } else {
        header("location:../FRONT/index.php");
        exit;
    }
}
