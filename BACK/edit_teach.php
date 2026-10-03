<?php
require_once "../BACK/function.php";
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
    && isset($_GET['teach_id']) && !empty($_GET['teach_id'])
    && isset($_POST['teacher']) && !empty($_POST['teacher'])
) {
    $con = db_connect();
    $teach_id = valid_input($_GET['teach_id']);
    $admin_id = valid_input($_POST['teacher']);
    $sql = "UPDATE `teacher` set `admin_id`=$admin_id where `teach_id` = $teach_id;";
    if (!$con->query($sql)) {
        die($con->error);
    }
    header("location:../FRONT/admin.php");
    exit;
} elseif (isset($_GET['teach_id']) && !empty($_GET['teach_id'])) {
    //  send some error if we want
    header("location:../FRONT/edit_teach.php?teach_id=" . $_GET['teach_id']);
    exit;
} else {
    header("location:logout.php");
    exit;
}
