<?php
require_once "function.php";
if (
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['pass']) && !empty($_POST['pass'])
) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $qer = "SELECT * from users as u where u.email='$email' and u.pass='$pass'; ";
    $res = db_connect()->query($qer);
    if ($res->num_rows == 0) {
        header('location:../FRONT/index.php');
    } else {
/**
 * admin   :    0
 * teacher :    1
 * student :    2
 */
        $res=$res->fetch_assoc();
        extract($res);
        $_SESSION['user_id'] =$id;
        $is_stu=db_connect()->query("SELECT * FROM `STUDENT` WHERE `STU_ID`=$id");
        if($is_stu->num_rows){
            $_SESSION['role_id']= 2;
        }else{
            $is_admin=db_connect()->query("SELECT * FROM `TEACHER` WHERE `TEACH_ID`=$id AND `ADMIN_ID` IS NULL;");
            if($is_admin->num_rows){
                $_SESSION['role_id']= 0;
                header('location:../FRONT/admin.php');
                exit;
            }else{
                $is_teach=db_connect()->query("SELECT * FROM `TEACHER` WHERE `TEACH_ID`=$id;");
                if($is_teach->num_rows)
                    $_SESSION['role_id']= 1;
            }
        }
        header('location:../FRONT/home.php');
    }
}
