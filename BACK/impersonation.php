<?php
require_once "../BACK/function.php";
// print_r($_GET);
// exit;
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
    && $_SESSION['role_id'] == 0
    && isset($_GET['teach_id']) && !empty($_GET['teach_id'])
) {
    #save admin data
    // echo "yess";
    // exit;
    $_SESSION['transition'] = $_SESSION['user_id'];
    $teach_id = $_GET['teach_id'];
    $_SESSION['user_id']=$teach_id;
    $_SESSION['role_id']=1;
    header("location:../FRONT/home.php");
    exit;

} else {
    // header("Location: ../FRONT/admin.php");
    print_r($_GET);
    print_r($_SESSION);
    exit;
}
