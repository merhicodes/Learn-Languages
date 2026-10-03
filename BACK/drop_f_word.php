<!-- to drop the words form users forlders -->
 <?php
 require_once "../BACK/function.php";
 if (
     isset($_SESSION['user_id'])
     && isset($_SESSION['role_id'])
     && isset($_GET['f_id'])
     && !empty($_GET['f_id'])
     && isset($_GET['w_id'])
     && !empty($_GET['w_id'])
 ) {
    extract($_GET);
    $con=db_connect();
    $sql="DELETE FROM folder_word where word_id=$w_id and folder_id=$f_id;";
    if(!$con->query($sql)){
        die($con->error);
    }
    header("location:../FRONT/folder.php?f_id=$f_id&w_id=$w_id");
    exit;
 }else{
    header("location:../FRONT/index.php");
    exit;
 }