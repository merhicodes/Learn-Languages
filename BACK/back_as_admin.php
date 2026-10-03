<?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['transition']) 
        && $_SESSION['transition'] == 2
    ) {
        $_SESSION['role_id']=0;
        $_SESSION['user_id']=2;
        unset($_SESSION['transition']);
        header("location:../FRONT/admin.php");
        exit;
    }else{
        header("location:../FRONT/index.php");
        exit;
    }