<?php
require_once "../BACK/function.php";
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
) {
    // if the admin try to logout a teach_acount we stop him
    if(isset($_SESSION['transition']) && $_SESSION['transition']=2){
        header("location:../FRONT/profile.php?l=1");
        exit;
    }
    session_destroy();
    header("location:../FRONT/index.php");
}
?>